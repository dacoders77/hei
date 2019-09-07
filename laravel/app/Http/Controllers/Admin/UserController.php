<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Post\Campaign;
use App\Model\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->role >= 3) {
            $data = [
                'title' => 'Restricted',
                'subtitle' => 'This area is off limits',
            ];
            return view('admin.restricted',$data);
        }
        $data = [
            'title' => 'Users',
            'subtitle' => 'All',
            'users' => DB::table('admins')->get()
        ];
        return view('admin.users',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->role >= 3) {
            $data = [
                'title' => 'Restricted',
                'subtitle' => 'This area is off limits',
            ];
            return view('admin.restricted',$data);
        }
        $data = [
            'title' => 'Add New',
            'subtitle' => 'User',
        ];
        return view('admin.users-new',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$post = false)
    {
        $this->validate($request,[
            'first_name'     => 'required',
            'last_name'     => 'required',
            'email'     => 'required',
        ]);

        return redirect( route('users.index') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if( ($id == 1 && intval(Auth::user()->id) !== 1) || (Auth::user()->role >= 3 && Auth::id() !== intval($id)) ) return redirect()->route('admin.users.index');
        //
        $data = [
            'title' => 'User',
            'subtitle' => 'Profile'
        ];
        return view('admin.auth.profile',$data);
    }

    public function redirectProfile()
    {
        return redirect()->route( 'admin.users.show', \Illuminate\Support\Facades\Auth::user()->id );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if($id == 1) return redirect()->route('admin.users.index');

        $data = [
            'title' => 'Edit',
            'subtitle' => 'User',
            'post' => DB::table('users')->where('id', $id)->first(),
        ];
        return view('admin.users-edit',$data);
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

        $validator = Validator::make($request->all(), [
            'first_name'     => 'required',
            'last_name'     => 'required',
            'email'     => 'required|email'
        ]);

        $validator->after(function ($validator) use ($request) {
            if(isset($request->password) && !empty($request->password) && strlen($request->password) < 6) {
                $validator->errors()->add('password','Password must be min 6 characters');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $admin = Admin::find($id);

        if( $request->first_name ) $admin->first_name = $request->first_name;
        if( $request->last_name ) $admin->last_name = $request->last_name;
        if( $request->email ) $admin->email = $request->email;

        if( $request->password ) $admin->password = Hash::make( $request->password );
        if( $request->role ) $admin->role = $request->role;

        $admin->save();

        return redirect( route('admin.users.index') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id == 1) return redirect()->route('admin.users.index');
        DB::table('admins')->where('id',$id)->delete();
        return redirect()->route('admin.users.index')->with('success','User successfully removed.');
    }

    public function destroy_confirm($id)
    {
        $data = [
            'title' => 'Confirm Delete',
            'subtitle' => 'User',
            'post' => DB::table('users')->where('id', $id)->first(),
        ];
        return view('admin.users-delete',$data);
    }
}
