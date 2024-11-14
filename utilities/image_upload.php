<?php

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
