<?php
class cimagen { 
    function cwUpload($target_folder = '', $file_name = '', $thumb = FALSE, $thumb_folder = '', $thumb_width = '', $thumb_height = '') {
        
        // folder path setup
        $target_path = $target_folder;
        $thumb_path = $thumb_folder;
        
        // Check if file exists and is a valid image extension
        $filename_err = explode(".", $file_name);
        $file_ext = strtolower(end($filename_err)); // Convert to lowercase for consistency
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($file_ext, $allowed_extensions)) {
            return false; // Invalid file extension
        }

        // upload image path
        $upload_image = $target_path . basename($file_name);
        
        // Rename the file if it already exists
        if (file_exists($upload_image)) {
            $file_name = time() . '_' . $file_name;
            $upload_image = $target_path . basename($file_name);
        }

        // Move the uploaded image
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_image)) {
            // thumbnail creation
            if ($thumb == TRUE) {
                $thumbnail = $thumb_path . $file_name;
                list($width, $height) = getimagesize($upload_image);
                $thumb_create = imagecreatetruecolor($thumb_width, $thumb_height);

                switch ($file_ext) {
                    case 'jpg':
                        $source = imagecreatefromjpeg($upload_image);
                        break;
                    case 'jpeg':
                        $source = imagecreatefromjpeg($upload_image);
                        break;
                    case 'png':
                        $source = imagecreatefrompng($upload_image);
                        break;
                    case 'gif':
                        $source = imagecreatefromgif($upload_image);
                        break;
                    default:
                        $source = imagecreatefromjpeg($upload_image);
                }

                imagecopyresized($thumb_create, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
                switch ($file_ext) {
                    case 'jpg':
                        imagejpeg($thumb_create, $thumbnail, 10);
                        break;
                    case 'jpeg':
                        imagejpeg($thumb_create, $thumbnail, 10);
                        break;
                    case 'png':
                        imagepng($thumb_create, $thumbnail, 10);
                        break;
                    case 'gif':
                        imagegif($thumb_create, $thumbnail, 10);
                        break;
                    default:
                        imagejpeg($thumb_create, $thumbnail, 10);
                }
            }

            return $file_name;
        } else {
            return false; // Error uploading file
        }
    }
}


?>