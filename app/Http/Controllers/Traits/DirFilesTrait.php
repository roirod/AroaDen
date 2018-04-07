<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Storage;
use Image;
use Lang;

trait DirFilesTrait {

    public function loadFileView($request, $id)
    {   
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $this->createDir($id, $this->has_odogram);

        $dir = "/$this->own_dir/$id";
        $files = Storage::files($dir);
        $url = url("$this->main_route/$id");
        $user_dir = "/$this->files_dir/$id";
        $thumb_dir = "/$this->files_dir/$id/$this->thumb_dir";

        $object = $this->model::FirstById($id);
        $this->setPageTitle($object->surname.', '.$object->name); 

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $id;
        $this->view_data['files'] = $files;
        $this->view_data['url'] = $url;
        $this->view_data['user_dir'] = $user_dir;        
        $this->view_data['thumb_dir'] = $thumb_dir;        
        $this->view_data['profile_photo_name'] = $this->profile_photo_name;

        $this->view_name = 'file';
        $this->view_response = true;
        
        return $this->loadView();
    }

    public function createDir($id, $odogram = false)
    {               
        $dir = "/$this->own_dir/$id";

        if ( !Storage::exists($dir) )
            Storage::makeDirectory($dir, 0770, true);

        if ($odogram) {
	        $odogdir = "$dir/$this->odog_dir";

	        if ( !Storage::exists($odogdir) )
	            Storage::makeDirectory($odogdir, 0770, true);

	        $odogram = "/$dir/$this->odog_dir/$this->odogram";
	        $img = "$this->img_folder/$this->odogram";
	          
	        if ( !Storage::exists($odogram) )
	            Storage::copy($img, $odogram);
        }

        $user_profile_photo = "$dir/$this->profile_photo_dir/$this->profile_photo_name".'_'.uniqid().'.jpg';
        $default_profile_photo = "$this->img_folder/$this->profile_photo_name.jpg";
        $profile_photo_dir = "$this->files_dir/$id/$this->profile_photo_dir";
        $getProfilePhoto = $this->getProfilePhoto($profile_photo_dir);

        if ($getProfilePhoto === false) {
            Storage::copy($default_profile_photo, $user_profile_photo);
        }

        $thumb_dir = "$dir/$this->thumb_dir";

        if ( !Storage::exists($thumb_dir) )
            Storage::makeDirectory($thumb_dir, 0770, true);
    }

    public function uploadProfilePhoto(Request $request)
    {
        $id = $request->input('id');
        $files = $request->file('files');        

        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $dir = storage_path("$this->files_dir/$id");
        $profile_photo_dir = "$this->files_dir/$id/$this->profile_photo_dir";

        $extension = $files->getClientOriginalExtension();

        $output = [];

        if ( in_array($extension, $this->img_extensions) ) {
            $this->deleteAllFilesOnDir(storage_path($profile_photo_dir));

            $save_dir = storage_path("$profile_photo_dir/$this->profile_photo_name".'_'.uniqid().'.jpg');

            Image::make($files)->encode('jpg', 60)
                ->resize(150, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($save_dir);

            $output['profile_photo'] = url($this->getProfilePhoto($profile_photo_dir));

        } else {

            $output['error'] = true;
            $output['msg'] = Lang::get('aroaden.img_type_not_allow');
            
        }

        $this->echoJsonOuptut($output);
    }

    private function getProfilePhoto($dir)
    {
        $files = glob($dir . "/*.jpg");

        if ( isset($files[0]) )
            return $files[0];

        return false;        
    }

    private function deleteAllFilesOnDir($dir)
    {
        $files = glob($dir.'/*');

        foreach($files as $file) {
          if(is_file($file))
            unlink($file);
        }
    }

    public function upload(Request $request)
    {
        $id = $request->input('id');
        $files = $request->file('files');        

        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $dir = storage_path("$this->files_dir/$id");
        $thumbdir = "$dir/$this->thumb_dir";

        $ficount = count($files);
        $upcount = 0;

        foreach ($files as $file) {                       
            $filename = $file->getClientOriginalName();
            $size = $file->getClientSize();
            $extension = $file->getClientOriginalExtension();

            $filedisk = storage_path("$this->files_dir/$id/$filename");

            if ( $size > $this->file_max_size ) {
                $mess = "El archivo: $filename - es superior a $this->file_max_size MB";
                $request->session()->flash($this->error_message_name, $mess);
                return redirect("$this->main_route/$id/file");
            }                

            if ( file_exists($filedisk) ) {
                $mess = "El archivo: $filename -- existe ya en su carpeta";
                $request->session()->flash($this->error_message_name, $mess);
                return redirect("$this->main_route/$id/file");

            } else {

                if ( in_array($extension, $this->img_extensions) ) {
                    $file_name = pathinfo($filename, PATHINFO_FILENAME);
                    
                    Image::make($file)->encode('jpg', 50)
                        ->resize(34, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save("$thumbdir/$file_name.jpg");
                }

                $file->move($dir, $filename);
                $upcount ++;
            }
        }            
         
        if($upcount == $ficount){
          return redirect("$this->main_route/$id/file");

        } else {

          $request->session()->flash($this->error_message_name, 'error!!!');
          return redirect("$this->main_route/$id/file");
        }
    }

    public function download(Request $request, $id, $file)
    {   
        $id = $this->sanitizeData($id);
        $this->redirectIfIdIsNull($id, $this->main_route);
        $this->redirectIfIdIsNull($file, $this->main_route);
        
        $filedown = storage_path("$this->files_dir/$id").'/'.$file;

        return response()->download($filedown);
    } 

    public function filerem(Request $request)
    {     
        $id = $request->input('id');
        $file = $request->input('filerem');

        $this->redirectIfIdIsNull($id, $this->main_route);
        $this->redirectIfIdIsNull($file, $this->main_route);
        $id = $this->sanitizeData($id);

        $dir = storage_path("$this->files_dir/$id");

        $path_parts = pathinfo($file);

        if ( isset($path_parts['extension']) ) {

            $extension = $path_parts['extension'];
            $thumb_filename = $path_parts['filename'];
            $thumb = "$dir/$this->thumb_dir/$thumb_filename.jpg";

        } else {

            $extension = '';

        }

        if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg' || $extension == 'gif') {
            if ( file_exists($thumb) )
                unlink($thumb);
        }

        $file_path = "$dir/$file";

        if ( file_exists($file_path) )
            unlink($file_path); 
          
        return redirect("$this->main_route/$id/file");
    }  

}