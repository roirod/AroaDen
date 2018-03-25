<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Storage;
use Image;

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
	            Storage::copy($img,$odogram);
        }

        $profile_photo = "/$dir/$this->profile_photo_name";
        $photo = "$this->img_folder/profile_photo.jpg";
          
        if ( !Storage::exists($profile_photo) )
            Storage::copy($photo, $profile_photo);   

        $thumbdir = "$dir/$this->thumb_dir";

        if ( !Storage::exists($thumbdir) )
            Storage::makeDirectory($thumbdir, 0770, true);
    }

    public function upload(Request $request)
    {
        $id = $request->input('id');
        $files = $request->file('files');        
        $profile_photo = $request->input('profile_photo');

        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $dir = storage_path("$this->files_dir/$id");
        $thumbdir = "$dir/$this->thumb_dir";

        if ($profile_photo == 1) {
            $extension = $files->getClientOriginalExtension();

            if ($extension == 'jpg' || $extension == 'png') {
                Image::make($files)->encode('jpg', 60)
                    ->resize(150, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save("$dir/$this->profile_photo_name");

                return redirect("$this->main_route/$id");

            } else {

                $request->session()->flash($this->error_message_name, 'Formato no soportado, suba una imagen jpg o png.');
                return redirect("$this->main_route/$id/file");
            }

        } else {

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

                    if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg' || $extension == 'gif') {
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