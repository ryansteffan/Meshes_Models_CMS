<?php
require("../utilities/connect.php");
require("../utilities/auth.php");
require("../utilities/image_upload.php");
require("../vendor/autoload.php");

use \Gumlet\ImageResize;

function save_to_database($db, $title, $image_name, $written_content, $categories = null)
{
    if ($categories == null) {
        $query = "INSERT INTO posts (title, author, written_content, image_content)
                  VALUES (
                  	:title,
                    :author,
                    :written_content,
                    :image_content
                  );";

        $statement = $db->prepare($query);

        $statement->bindValue(":title", $title);
        $statement->bindValue(":author", $_SESSION["user_details"]["user_id"]);
        $statement->bindValue(":written_content", $written_content);
        $statement->bindValue(":image_content", "uploads" . DIRECTORY_SEPARATOR . $image_name);

        $statement->execute();
    }
}

$do_display_options = false;
$upload_error_detected = false;
$error = false;

if (is_logged_in()) {
    $do_display_options = true;
} else {
    header("Location: login.php");
}

$is_form_filled = isset($_POST["title"]) && isset($_POST["written_content"]);
$file_upload_path = ".." . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR;

if ($is_form_filled) {

    $image_upload_detected = isset($_FILES["image_upload"]) && ($_FILES["image_upload"]["error"] === 0);
    $upload_error_detected = isset($_FILES["image_upload"]) && ($_FILES["image_upload"]["error"] > 0);

    if ($image_upload_detected) {

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

            // Resize to 50px wide.
            $small_image_file_path = $file_upload_path . "small" . $image_filename;
            $small_image = new ImageResize($new_image_path);
            $small_image->resizeToWidth(250);
            $small_image->save($small_image_file_path);

            save_to_database($db, $_POST["title"], $image_filename, $_POST["written_content"]);

            header("Location: ./my_posts.php");
        } else {
            $error = "The file being uploaded must be an image.";
        }
    }
}

if ($upload_error_detected) {
    $error = "There was an error with the image upload. Please try again.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php require("../templates/header.php") ?>

    <?php if ($do_display_options): ?>
        <h1>Create a new post</h1>
        <form action="#" method="post" enctype="multipart/form-data">
            <label for="title">Enter a title:</label>
            <input type="text" name="title" id="title">
            <label for="image_upload">Upload an Image:</label>
            <input type="file" name="image_upload" id="image_upload">
            <label for="written_content">Add a description to the image:</label>
            <textarea name="written_content" id="written_content"></textarea>
            <button type="submit">Post</button>
        </form>
        <?php if ($error): ?>
            <?= $error ?>
        <?php endif ?>
    <?php endif ?>

    <?php require("../templates/footer.php") ?>

</body>

</html>