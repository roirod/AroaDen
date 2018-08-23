<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use App\Models\Files;
use Exception;
use Storage;
use Image;
use Lang;

trait DirFilesTrait {

    public function loadFileView($request, $id)
    {
        $id = $this->sanitizeData($id);

        $this->createDir($id);

        $dir = "/$this->own_dir/$id";
        $files = Files::GetFilesByUserId($id);
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
        $this->view_data['object'] = $object;
        $this->view_data['img_extensions'] = $this->img_extensions;
        $this->view_data['default_img_type'] = $this->default_img_type;

        $this->view_name = 'file';
        
        return $this->loadView();
    }

    public function createDir($id)
    {               
        $dir = "/$this->own_dir/$id";

        if ( !Storage::exists($dir) )
            Storage::makeDirectory($dir, 0770, true);

        if ($this->has_odontogram) {
	        $odontogram_dir = "$dir/$this->odontogram_dir";

	        if ( !Storage::exists($odontogram_dir) )
	            Storage::makeDirectory($odontogram_dir, 0770, true);

            $odontogram = "/$dir/$this->odontogram_dir/$this->odontogram".'_'.uniqid().'.'.$this->default_img_type;
            $default_odontogram = "$this->img_folder/$this->odontogram".'.'.$this->default_img_type;
            $odontogram_dir = "$this->files_dir/$id/$this->odontogram_dir";
            $getFirstJpgOnDir = $this->getFirstJpgOnDir($odontogram_dir);

            if ($getFirstJpgOnDir === false)
                Storage::copy($default_odontogram, $odontogram);
        }

        $user_profile_photo = "$dir/$this->profile_photo_dir/$this->profile_photo_name".'_'.uniqid().'.'.$this->default_img_type;
        $default_profile_photo = "$this->img_folder/$this->profile_photo_name".'.'.$this->default_img_type;
        $profile_photo_dir = "$this->files_dir/$id/$this->profile_photo_dir";
        $getFirstJpgOnDir = $this->getFirstJpgOnDir($profile_photo_dir);

        if ($getFirstJpgOnDir === false)
            Storage::copy($default_profile_photo, $user_profile_photo);

        $thumb_dir = "$dir/$this->thumb_dir";

        if ( !Storage::exists($thumb_dir) )
            Storage::makeDirectory($thumb_dir, 0770, true);
    }

    public function uploadProfilePhoto(Request $request, $id)
    {
        $id = $request->input('id');
        $file = $request->file('files');
        $output = [];

        try {
             
            $this->checkIfFileIsValid($file);

            $id = $this->sanitizeData($id);
            $file_path = "$this->files_dir/$id/$this->profile_photo_dir";
            $file_name = $this->profile_photo_name.'_'.uniqid();

            $name = $file->getClientOriginalName();
            $size = $file->getClientSize();
            $extension = $file->getClientOriginalExtension();

            if (in_array($extension, $this->img_extensions)) {

                $this->deleteAllFilesOnDir(storage_path($file_path));          

                $this->misc_array['file'] = $file;
                $this->misc_array['file_path'] = $file_path.'/'.$file_name.'.'.$this->default_img_type;

                $this->processImage();

            } else {

                throw new Exception(Lang::get('aroaden.img_type_not_allow'));

            }

            $output['profile_photo'] = url($this->getFirstJpgOnDir($file_path));

        } catch (Exception $e) {
         
            $output['error'] = true;
            $output['msg'] = $e->getMessage();

        } 

        $this->echoJsonOuptut($output);
    }

    public function uploadFiles(Request $request, $id)
    {
        $files = $request->file('files');
        $id = $this->sanitizeData($id);
        $dir = storage_path("$this->files_dir/$id");

        foreach ($files as $file) {

            try {                 

                $this->checkIfFileIsValid($file);

                $originalName = $file->getClientOriginalName();
                $size = $file->getClientSize();
                $extension = $file->getClientOriginalExtension();
                $fsFilenameNoExt = pathinfo($originalName, PATHINFO_FILENAME). '_' .uniqid();
                $fsFilename = $fsFilenameNoExt. '.' .$extension;

                if ($extension == '')
                    $fsFilename = $fsFilenameNoExt;

                $file_exists = Files::CheckIfFileExist($id, $originalName);

                if ($file_exists) {
                    throw new Exception("El archivo: - $originalName - existe ya en su carpeta");
                }

                if ( $size > $this->file_max_size )
                    throw new Exception("El archivo: - $originalName - es superior a $this->file_max_size MB");
    
                $this->misc_array['file'] = $file;

                if ( in_array($extension, $this->img_extensions) ) {
                    $thumb_dir = "$this->files_dir/$id/$this->thumb_dir";

                    $this->misc_array['img_px'] = 34;
                    $this->misc_array['img_quality'] = 40;
                    $this->misc_array['file_path'] = $thumb_dir.'/'.$fsFilenameNoExt.'.'.$this->default_img_type;

                    $this->processImage();
                }

                $this->misc_array['save_path'] = $dir;
                $this->misc_array['fsFilename'] = $fsFilename;

                $this->saveFileOnDisk();

                $info_arr = [
                    'originalName' => $originalName,
                    'fsFilename' => $fsFilename,
                    'fsFilenameNoExt' => $fsFilenameNoExt,
                    'size' => $size,
                    'extension' => $extension
                ];

                $info_arr = json_encode($info_arr);

                Files::create([
                    'iduser' => $id,
                    'originalName' => $originalName,                    
                    'info' => $info_arr
                ]);

            } catch (Exception $e) {

                $request->session()->flash($this->error_message_name, $e->getMessage());
                return redirect("/$this->main_route/$id/file");      

            }
        }

        return redirect("/$this->main_route/$id/file");
    }

    private function saveFileOnDisk()
    {
        $file = $this->misc_array['file'];        
        $save_path = $this->misc_array['save_path'];
        $fsFilename = $this->misc_array['fsFilename'];

        $file->move($save_path, $fsFilename);
    }

    private function processImage()
    {
        $file = $this->misc_array['file'];
        $img_px = (empty($this->misc_array['img_px'])) ? 150 : $this->misc_array['img_px'];
        $img_quality = (empty($this->misc_array['img_quality'])) ? 60 : $this->misc_array['img_quality'];
        $img_type = (empty($this->misc_array['img_type'])) ? $this->default_img_type : $this->misc_array['img_type'];
        $file_path = $this->misc_array['file_path'];

        Image::make($file)->encode($img_type, $img_quality)
            ->resize($img_px, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($file_path);
    }

    private function checkIfFileIsValid($file)
    {
        if (!$file->isValid())
            throw new Exception(Lang::get('aroaden.file_not_valid'));
    }

    private function getFirstJpgOnDir($dir)
    {
        $jpg = glob($dir . "/*.jpg");

        if ( isset($jpg[0]) )
            return $jpg[0];

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

    public function download(Request $request, $id, $idfiles)
    {   
        $id = $this->sanitizeData($id);
        $idfiles = $this->sanitizeData($idfiles);
        $getFile = Files::GetFileByUserId($id, $idfiles);
        $info = json_decode($getFile->info);
        $fsFilename = $info->fsFilename;

        $filedown = storage_path("$this->files_dir/$id").'/'.$fsFilename;

        return response()->download($filedown);
    }

    public function destroy(Request $request, $id)
    {               
        $this->redirectIfIdIsNull($id, $this->main_route); 
        $id = $this->sanitizeData($id);
        
        $this->model::destroy($id);
      
        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        
        return redirect($this->main_route);
    }

    public function deleteFile(Request $request, $idfiles)
    {
        try {

            $id = $request->input('id');
            $id = $this->sanitizeData($id);
            $idfiles = $this->sanitizeData($idfiles);

            $getFile = Files::GetFileByUserId($id, $idfiles);
            $info = json_decode($getFile->info);

            $fsFilename = $info->fsFilename;
            $fsFilenameNoExt = $info->fsFilenameNoExt;        
            $extension = $info->extension;

            $dir = storage_path("$this->files_dir/$id");
            $thumb_dir = "$dir/$this->thumb_dir";
            $thumb_file = $thumb_dir.'/'.$fsFilenameNoExt.'.'.$this->default_img_type;

            if ( in_array($extension, $this->img_extensions) ) {
                if ( file_exists($thumb_file) )
                    unlink($thumb_file);
            }

            $object = Files::find($idfiles);
            $object->delete();

            $file_path = "$dir/$fsFilename";

            if ( file_exists($file_path) )
                unlink($file_path); 

        } catch (Exception $e) {

            $request->session()->flash($this->error_message_name, $e->getMessage());
            return redirect("/$this->main_route/$id/file");      

        }

        return redirect("$this->main_route/$id/file");
    }  

}