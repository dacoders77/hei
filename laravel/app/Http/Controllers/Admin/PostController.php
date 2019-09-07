<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Post\Campaign;
use App\Model\User\User;
use App\Model\Admin\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Validator;


class PostController extends Controller
{

    // Check filename on server and append incremented number if needs be
    private function checkFile($name,$path){
        $actual_name = pathinfo($name,PATHINFO_FILENAME);
        $original_name = $actual_name;
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        $i = 1;
        while ( file_exists($path.$actual_name.".".$extension) ) {
            $actual_name = (string)$original_name.$i;
            $name = $actual_name.".".$extension;
            $i++;
        }

        return $name;
    }

    // @see https://stackoverflow.com/a/35223390
    private function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'title' => 'Campaigns',
            'subtitle' => 'All',
            'posts' => DB::table('posts')->where('status','!=',2)->get()
        ];
        return view('admin.posts',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Add New',
            'subtitle' => 'Campaign',
        ];
        return view('admin.posts-new',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$post_id = false)
    {
        $GLOBALS['post_id'] = $post_id;

        $request->merge([
            'slug' => $this->slugify($request->slug),
            'form_content' => json_decode( $request->form_content ) ? $request->form_content : null,
        ]);

        $storage_path = storage_path('app/public').'/uploads/'.date('Y/m').'/';
        $public_url = '/storage/uploads/'.date('Y/m').'/';

        if( Input::hasFile('image_file') ) {
            $file = Input::file('image_file');

            if ($file->isValid()) {
                $filename = $this->checkFile( $file->getClientOriginalName(), $storage_path );
                $file->move($storage_path, $filename);

                $request->merge([
                    'image' => $public_url . $filename,
                    'image_file' => null,
                ]);
            }
        }
        if( Input::hasFile('image_responsive_file') ) {
            $file = Input::file('image_responsive_file');

            if ($file->isValid()) {
                $filename = $this->checkFile( $file->getClientOriginalName(), $storage_path );
                $file->move($storage_path, $filename);

                $request->merge([
                    'image_responsive' => $public_url . $filename,
                    'image_responsive_file' => null,
                ]);
            }
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'domain' => 'required',
            'image' => 'required',
            // 'form_content' => 'required',
            'status' => 'required',
        ], [
            'title.required' => 'Campaign title is required.',
            'domain.required' => 'Please select a domain.',
            'image.required' => 'Desktop image is required.',
            // 'form_content.required' => 'Form fields are required.',
            'status.required' => 'Status is required.',
        ]);

        $validator->after(function ($validator) {
            global $request;
            global $post_id;

            $slug_exists = DB::table('posts')->where([
                ['id', '!=', $post_id ? $post_id : ''],
                ['domain', $request->domain],
                ['slug', $request->slug],
                ['status','!=', 2]
            ])->value('id');

            if ($slug_exists) {
                $validator->errors()->add('slug', 'Slug "'.$request->slug.'" already exists for the selected domain.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if(!$post_id) {
            $post = new Campaign;
        } else {
            $post = Campaign::find($post_id);
        }

        $post->title = $request->title;
        $post->domain = $request->domain;
        $post->slug = $request->slug;
        $post->image = $request->image ? $request->image : null;
        $post->image_responsive = $request->image_responsive ? $request->image_responsive : null;
        $post->form_prefix = $request->form_prefix;
        $post->form_content = json_encode(json_decode($request->form_content));
        $post->form_suffix = $request->form_suffix ;
        $post->footer = $request->footer;
        $post->status = $request->status;
        $post->posted_by = 1;

        $post->save();

        if( Input::hasFile('consumers_file') ) {
            $file = Input::file('consumers_file');

            if ($file->isValid()) {
                $consumers = $this->csvToArray( $file->getRealPath() );
                foreach ($consumers as $consumer) {
                    $user = DB::table('users')->where([
                        ['email',$consumer[$request->uel_email]],
                        ['campaign',$post->id]
                    ])->first();
                    if(!$user) {
                        $user = new User;
                        $user->first_name = $consumer[$request->uel_first_name];
                        $user->last_name = $consumer[$request->uel_last_name];
                        $user->email = $consumer[$request->uel_email];
                        $user->status = 1;
                        $user->campaign = $post->id;
                        $user->password = str_random(12);
                        $user->save();

                        $user = User::find($user->id);
                        $user->uuid = userUUID($user->id,$post->id);
                        $user->save();
                    }
                }
            }
        }

        if( Input::hasFile('validation_file') ) {
            $file = Input::file('validation_file');

            if ($file->isValid()) {
                $consumers = $this->csvToArray( $file->getRealPath() );
                foreach ($consumers as $consumer) {
                    $user = DB::table('users')->where([
                        ['email',$consumer[$request->ual_email]],
                        ['campaign',$post->id]
                    ])->first();
                    if($user) {
                        $user = User::find($user->id);
                        $user->status = 2;
                        $user->save();
                    } else {
                        $user = new User;
                        $user->first_name = $consumer[$request->ual_first_name];
                        $user->last_name = $consumer[$request->ual_last_name];
                        $user->email = $consumer[$request->ual_email];
                        $user->status = 3;
                        $user->campaign = $post->id;
                        $user->password = str_random(12);
                        $user->save();

                        $user = User::find($user->id);
                        $user->uuid = userUUID($user->id,$post->id);
                        $user->save();
                    }
                }
            }
        }

        return redirect()->route('campaigns.index')->with('success','Campaign successfully updated.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = DB::table('posts')->where('id', $id)->first();
        return redirect()->away( 'https://' . Domain::find($post->domain)->value('domain') . $post->slug );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [
            'title' => 'Edit',
            'subtitle' => 'Campaign',
            'post' => DB::table('posts')->where('id', $id)->first(),
        ];
        return view('admin.posts-edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->store($request,$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('posts')->where('id',$id)->update(array('status' => '2'));
        return redirect()->route('campaigns.index')->with('success','Campaign successfully removed.');
    }



    private function slugify($text) {
      // replace non letter or digits by -
      $text = preg_replace('~[^\pL\d]+~u', '-', $text);
      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);
      // trim
      $text = trim($text, '-');
      // remove duplicate -
      $text = preg_replace('~-+~', '-', $text);
      // lowercase
      $text = strtolower($text);
      if (empty($text)) {
        return '/';
      }
      $text = preg_replace('/^([^\/])/', '/$1', $text);

      return $text;
    }


    /**
     * Display a listing of the form submissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function submissions($id = false)
    {
        if(!$id) return redirect()->route('campaigns.index');

        if($id == 'all') {

          $data = [
              'title' => 'Submissions',
              'subtitle' => 'All',
              'submissions' => DB::table('submissions')->get()
          ];

        } else {

          $status = DB::table('posts')->where('id', $id)->value('status');

          $data = [
              'title' => 'Submissions',
              'subtitle' => DB::table('posts')->where('id', $id)->value('title'),
              'submissions' => DB::table('submissions')->where('cid', $id)->get()
          ];

        }

        return view('admin.posts-submissions',$data);
    }

    /**
     * Display the specified form submission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function submissions_show($cid,$id)
    {
        $data = DB::table('submissions')->where('id', $id)->value('data');
        return Crypt::decryptString( $data );
    }

    /**
     * Remove a specified form submission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function submissions_destroy($cid,$id)
    {
        DB::table('submissions')->where('id',$id)->delete();
        return redirect()->back()->with('success','Submission successfully removed.');
    }


    // public function __construct()
    // {
    //     $this->middleware('auth:admin');
    // }
}
