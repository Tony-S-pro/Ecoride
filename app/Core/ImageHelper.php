<?php
namespace App\Core;

use App\Models\User;

Class ImageHelper
{
    /**
     * Upload an image to the server an save the filename in the db
     * 
     * @param mixed $user_id user's id
     * @return bool
     */
    public static function uploadPhoto($user_id): bool 
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
        $ext_allowed = ['jpg', 'jpeg', 'png', 'webp'];

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

    /**
     * Resize/crop a JPEG image
     * 
     * With crop, its width will be the same as entered in the param. 
     * If the width after resizing is greater than the param, the remaining width will be cropped.
     * 
     * Without crop, if height > width, the height will be the basis, and if width > height, the width will be the basis. 
     * The image will be resized based on it.
     * 
     * @param string $source_file
     * @param string $destination_file
     * @param int $width new width
     * @param int $height new height
     * @param int $quality 0 to 100. 100 to resize/crop without quality loss
     * @param bool $crop TRUE to crop the image to the params given
     * @return void
     */
    public static function resize_image_jpeg(string $source_file, string $destination_file, int $width, int $height, int $quality, bool $crop=FALSE): void 
    {
    list($current_width, $current_height) = getimagesize($source_file);
    $rate = $current_width / $current_height;
    if ($crop) {
        if ($current_width > $current_height) {
            $current_width = ceil($current_width-($current_width*abs($rate-$width/$height)));
        } else {
            $current_height = ceil($current_height-($current_height*abs($rate-$width/$height)));
        }
        $newwidth = $width;
        $newheight = $height;
    } else {
        if ($width/$height > $rate) {
            $newwidth = $height*$rate;
            $newheight = $height;
        } else {
            $newheight = $width/$rate;
            $newwidth = $width;
        }
    }
    $src_file = imagecreatefromjpeg($source_file);
    $dst_file = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst_file, $src_file, 0, 0, 0, 0, $newwidth, $newheight, $current_width, $current_height);

    imagejpeg($dst_file, $destination_file, $quality);
    }

    /**
     * Resize/crop a PNG image
     * 
     * With crop, its width will be the same as entered in the param. 
     * If the width after resizing is greater than the param, the remaining width will be cropped.
     * 
     * Without crop, if height > width, the height will be the basis, and if width > height, the width will be the basis. 
     * The image will be resized based on it.
     * 
     * @param string $source_file
     * @param string $destination_file
     * @param int $width new width
     * @param int $height new height
     * @param int $quality 0 to 9. 9 to resize/crop without quality loss
     * @param bool $crop TRUE to crop the image to the params given
     * @return void
     */
    public static function resize_image_png(string $source_file, string $destination_file, int $width, int $height, int $quality, bool $crop=FALSE): void 
    {
    list($current_width, $current_height) = getimagesize($source_file);
    $rate = $current_width / $current_height;
    if ($crop) {
        if ($current_width > $current_height) {
            $current_width = ceil($current_width-($current_width*abs($rate-$width/$height)));
        } else {
            $current_height = ceil($current_height-($current_height*abs($rate-$width/$height)));
        }
        $newwidth = $width;
        $newheight = $height;
    } else {
        if ($width/$height > $rate) {
            $newwidth = $height*$rate;
            $newheight = $height;
        } else {
            $newheight = $width/$rate;
            $newwidth = $width;
        }
    }
    $src_file = imagecreatefrompng($source_file);
    imagepalettetotruecolor($src_file);
    imagealphablending($src_file, true);
    imagesavealpha($src_file, true);
    $dst_file = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst_file, $src_file, 0, 0, 0, 0, $newwidth, $newheight, $current_width, $current_height);

    imagepng($dst_file, $destination_file, $quality);
    }

    /**
     * Resize/crop a WEBP image
     * 
     * With crop, its width will be the same as entered in the param. 
     * If the width after resizing is greater than the param, the remaining width will be cropped.
     * 
     * Without crop, if height > width, the height will be the basis, and if width > height, the width will be the basis. 
     * The image will be resized based on it.
     * 
     * @param string $source_file
     * @param string $destination_file
     * @param int $width new width
     * @param int $height new height
     * @param int $quality 0 to 100. 100 to resize/crop without quality loss
     * @param bool $crop TRUE to crop the image to the params given
     * @return void
     */
    public static function resize_image_webp(string $source_file, string $destination_file, int $width, int $height, int $quality, bool $crop=FALSE): void 
    {
        list($current_width, $current_height) = getimagesize($source_file);
        $rate = $current_width / $current_height;
        if ($crop) {
            if ($current_width > $current_height) {
                $current_width = ceil($current_width-($current_width*abs($rate-$width/$height)));
            } else {
                $current_height = ceil($current_height-($current_height*abs($rate-$width/$height)));
            }
            $newwidth = $width;
            $newheight = $height;
        } else {
            if ($width/$height > $rate) {
                $newwidth = $height*$rate;
                $newheight = $height;
            } else {
                $newheight = $width/$rate;
                $newwidth = $width;
            }
        }
        $src_file = imagecreatefromwebp($source_file);
        $dst_file = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst_file, $src_file, 0, 0, 0, 0, $newwidth, $newheight, $current_width, $current_height);

        imagewebp($dst_file, $destination_file, $quality);
    }




}