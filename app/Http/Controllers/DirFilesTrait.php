<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Image;
use Exception;

trait DirFilesTrait {

    public function createDir($id, $odogram = false)
    {               
        $dir = "/$this->own_dir/$id";

        if ( ! Storage::exists($dir) )
            Storage::makeDirectory($dir,0770,true);

        if ($odogram) {
	        $odogdir = "$dir/$this->odog_dir";

	        if ( ! Storage::exists($odogdir) )
	            Storage::makeDirectory($odogdir,0770,true);

	        $odogram = "/$dir/$this->odog_dir/$this->odogram";
	        $img = "$this->img_folder/$this->odogram";
	          
	        if ( ! Storage::exists($odogram) )
	            Storage::copy($img,$odogram);
        }

        $profile_photo = "/$dir/$this->profile_photo_name";
        $foto = "$this->img_folder/profile_photo.jpg";
          
        if ( ! Storage::exists($profile_photo) )
            Storage::copy($foto,$profile_photo);   

        $thumbdir = "$dir/$this->thumb_dir";

        if ( ! Storage::exists($thumbdir) )
            Storage::makeDirectory($thumbdir,0770,true);
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
        $filedisk = "$dir/$this->thumb_dir/$file";         

        if ( file_exists($filedisk) ) {
            unlink($filedisk);
        }

        unlink("$dir/$file");    
          
        return redirect("$this->main_route/$id/file");
    }  

}