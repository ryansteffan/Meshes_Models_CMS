<?php

use Gumlet\ImageResize;

// Checks if the image actually an image file.
function file_is_image($temp_path, $new_path)
{
    $allowed_mime_types = ["image/gif", "image/jpeg", "image/png"];
    $allowed_file_extensions = ["gif", "jpg", "jpeg", "png"];

    $actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);
    $actual_mime_type = getimagesize($temp_path)["mime"];

    $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
    $file_mime_is_valid = in_array($actual_mime_type, $allowed_mime_types);

    return $file_extension_is_valid && $file_mime_is_valid;
}

// Save an image to a specified path in small, medium and full res.
function save_images($file_upload_path)
{
    $image_filename = $_FILES["image_upload"]["name"];
    $temp_image_path = $_FILES["image_upload"]["tmp_name"];
    $new_image_path = $file_upload_path . $image_filename;

    if (file_is_image($temp_image_path, $new_image_path)) {
        // Save the original
        move_uploaded_file($temp_image_path, $new_image_path);

        // Resize to 400px wide.
        $medium_image_file_path = $file_upload_path . "medium" . $image_filename;
        $medium_image = new ImageResize($new_image_path);
        $medium_image->resizeToWidth(400);
        $medium_image->save($medium_image_file_path);

        // Resize to 250px wide.
        $small_image_file_path = $file_upload_path . "small" . $image_filename;
        $small_image = new ImageResize($new_image_path);
        $small_image->resizeToWidth(250);
        $small_image->save($small_image_file_path);
    }
}

// Deletes all images in the uploads folder that match the name provided.
function delete_images($image_name)
{
    unlink("../uploads/{$image_name[0]}");
    unlink("../uploads/small{$image_name[0]}");
    unlink("../uploads/medium{$image_name[0]}");
}
