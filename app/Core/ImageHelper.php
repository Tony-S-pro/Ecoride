<?php
namespace App\Core;

use App\Models\User;

Class ImageHelper
{
    public static function uploadPhoto($user_id) 
    {
        if(isset($_FILES['file'])){
            $tmp_name = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $error = $_FILES['file']['error'];
        }else {
            return false;
        }

        // File extension + extentions allowed
        $arr = explode('.', $name);
        $extension = strtolower(end($arr));        
        $ext_allowed = ['jpg', 'png', 'jpeg', 'gif'];

        // Max file size
        $max_size = 100000000;

        if(in_array($extension, $ext_allowed) && $size <= $max_size && $error == 0){

            //find previous photo and erase if exists
            $user = new User(Database::getPDOInstance());
            $photo = $user->findPhotoById($user_id);
            if(!empty($photo)){
                $file = './../public/assets/uploads/'.$photo['photo']; 
                unlink($file);            
            }

            //give the file a unique name
            $unique = uniqid('pfp.', true);
            $file_name = $unique.".".$extension;

            move_uploaded_file($tmp_name, './../public/assets/uploads/'.$file_name);

            //upload path to db
            $user->uploadPhotoPath($file_name, $user_id);
            
            return true;
        }
        else{
            return false;
        }       
    }


}