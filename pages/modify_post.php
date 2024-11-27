<?php
require("../utilities/connect.php");
require("../utilities/auth.php");
require("../utilities/image_upload.php");


$do_display_options = false;
$error = false;
$default_error_message = "There was an error trying to update the page, please try again later.";

function get_current_post($db, $post_id)
{
    $query = "SELECT * FROM posts WHERE post_id = :post_id;";

    $statement = $db->prepare($query);
    $statement->bindValue(":post_id", $post_id);
    $statement->execute();
    $post = $statement->fetch();

    return $post;
}

function update_post($db, $post_id, $title, $image_name, $written_content, $categories = null)
{
    $query = "UPDATE
              	posts
              SET 
              	title = :title,
              	written_content = :written_content,
              	image_content = :image_content,
              	modified_date = current_timestamp()
              WHERE
              	post_id = :post_id;";

    $statement = $db->prepare($query);
    $statement->bindValue(":post_id", $post_id);
    $statement->bindValue(":title", $title);
    $statement->bindValue(":image_content", $image_name);
    $statement->bindValue(":written_content", $written_content);
    try {
        $statement->execute();
    } catch (Exception $ex) {
        header("location: db_error.php");
    }
}

if (isset($_GET["post_id"])) {
    if (is_logged_in()) {
        $do_display_options = true;
        $post_id = $_GET["post_id"];
        $current_post = get_current_post($db, $post_id);
    } else {
        header("location: login.php");
    }
}

if (isset($_POST["update_post"])) {
    $post_id = $_POST["post_id"];
    $title = $_POST["title"];
    $old_image = $_POST["current_image"];
    $written_content = $_POST["written_content"];
    if ($_FILES["image_upload"]["error"] == 0) {
        $filename = $_FILES["image_upload"]["name"];
        update_post($db, $post_id, $title, $filename, $written_content);
        delete_images($old_image);
        save_images("../uploads/");
    } else {
        update_post($db, $post_id, $title, $old_image, $written_content);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../index.css">

    <!-- Config the WYSIWYG -->
    <script src="../vendor/tinymce/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="js/tinymce_config.js"></script>

    <title>Modify A Post</title>
</head>

<body>
    <?php require("../templates/header.php") ?>
    <?php if ($do_display_options): ?>
        <h1>Modify Your Post:</h1>
        <form action="#" method="post" enctype="multipart/form-data">
            <label for="title">Edit the title:</label>
            <input type="text" name="title" id="title" value="<?= $current_post["title"] ?>">
            <h3>Current Image:</h3>
            <img src="../uploads/small<?= $current_post["image_content"] ?>" alt="Image for the <?= $current_post["title"] ?> post.">
            <label for="image_upload">Choose a new image:</label>
            <input type="file" name="image_upload" id="image_upload">
            <label for="written_content">Modify the description:</label>
            <textarea name="written_content" id="written_content"><?= $current_post["written_content"] ?></textarea>
            <input type="hidden" name="update_post">
            <input type="hidden" name="post_id" value="<?= $current_post["post_id"] ?>">
            <input type="hidden" name="current_image" value="<?= $current_post["image_content"] ?>">
            <button type="submit">Update</button>
        </form>
        <?php if ($error): ?>
            <?= $error ?>
        <?php endif ?>
    <?php endif ?>
    <?php require("../templates/footer.php") ?>
</body>

</html>