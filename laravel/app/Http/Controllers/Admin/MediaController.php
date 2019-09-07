<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
// use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class MediaController extends Controller
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

	// Upload Functionality
    public function upload(Request $request) {
		$CKEditor = $request->input('CKEditor');
		$funcNum  = !empty($request->input('CKEditorFuncNum')) ? $request->input('CKEditorFuncNum') : 0;
		$message  = $url = '';
		$storage_path = storage_path('app/public').'/uploads/'.date('Y/m').'/';
		$public_url = '/storage/uploads/'.date('Y/m').'/';


		if (Input::hasFile('upload')) {
			$file = Input::file('upload');
			if ($file->isValid()) {
				$filename = $this->checkFile( $file->getClientOriginalName(), $storage_path );
				$file->move($storage_path, $filename);
	            $url = $public_url . $filename;
			} else {
				$message = 'An error occurred while uploading the file.';
			}
		} else {
			$message = 'No file uploaded.';
		}
		return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
	}

	// Scan return folders as menu tree
	private function directoryTree($dir){
		if(is_dir($dir)){
			$children = '';
			$files = glob( $dir . '*', GLOB_MARK );
			if ($files) {
				$children .= '<ul class="treeview-menu">';
				foreach( $files as $file ) {
					$children .= $this->directoryTree($file);
				}
				$children .= '</ul>';
			}
			return '<li'.($children ? ' class="active treeview menu-open"' : '').'><a href="#"><i class="fa fa-folder"></i><span>'.basename($dir).'</span>'.($children ? '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>' : '').'</a>'.$children.'</li>';
		}
	}

	private function human_filesize($bytes, $decimals = 2) {
	    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
	    $factor = floor((strlen($bytes) - 1) / 3);
	    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
	}

	// Scan directory and return files and folders in array
	private function scan($dir){

		$files = array();

		// Is there actually such a folder/file?

		if (substr($dir,0,1) !== '/') {
			$dir = '/'.$dir;
		}
		if (substr($dir,-1) !== '/') {
			$dir = $dir.'/';
		}

		if(file_exists($dir)){

			foreach(scandir($dir) as $f) {

				if(!$f || $f[0] == '.') {
					continue; // Ignore hidden files
				}

				if(is_dir($dir . '/' . $f)) {

					// The path is a folder

					$files[] = array(
						"name" => $f,
						"type" => "folder",
						"path" => str_replace( public_path('storage/uploads'), '', $dir . $f ) . '/',
						"size" => '–' //scan($dir . '/' . $f) // Recursively get the contents of the folder
					);
				} else {

					// It is a file

					if( app('request')->input('type') && app('request')->input('type') == 'Images' && !preg_match('/\.(gif|jpg|jpeg|tiff|png)$/i', $f) ){
						continue;
					}

					$files[] = array(
						"name" => $f,
						"type" => pathinfo($f, PATHINFO_EXTENSION),
						"path" => str_replace( public_path(), '', $dir . $f ),
						"size" => $this->human_filesize( filesize($dir . $f) ) // Gets the size of this file
					);
				}
			}

		}

		return $files;
	}

	public function browse() {
		$files = $this->scan( public_path('storage/uploads') );

		$tree = $this->directoryTree( storage_path('app/public/uploads/') );

		$data = [
			'menu_tree' => $tree,
			'files'		=> $files,
		];

		return view('admin.media.browser',$data);
	}

	public function ajax() {
		$dir = !isset($_GET['dir']) ? '' : str_replace('#!','',$_GET['dir']);

		$files = $this->scan( public_path('storage/uploads').$dir );

		return response($files, 200)->header('Content-Type', 'application/json');
	}
}
