<?php
require("../utilities/connect.php");
require("../utilities/categories.php");
require("../utilities/auth.php");
require("../utilities/image_upload.php");
require("../vendor/autoload.php");
require("../vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php");

// Used to sanitize WYSIWYG.
$purifier_config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($purifier_config);

$categories = get_full_category_data($db);

// function save_category_selection($db, $post_id)
// {
//     $select_categories = [];
//     $all_categories = get_full_category_data($db);


//     foreach ($_POST as $key => $value) {
//         for ($category = 0; $category < count($all_categories); $category++) {
//             // If the name of the category is on then add it to the list.
//             if ($key == $all_categories[$category]["name"]) {
//                 if ($value == "on") {
//                     // Add the category id to the list.
//                     array_push($select_categories, $all_categories[$category]["category_id"]);
//                 }
//             }
//         }
//     }

//     for ($id = 0; $id < count($select_categories); $id++) {
//         $insert_category_relationship = "INSERT INTO posts_categories
//                                          (post_id, category_id)
//                                          VALUES(:post_id, :cat_id);";

//         $statement = $db->prepare($insert_category_relationship);

//         $statement->bindValue(":post_id", $post_id);
//         $statement->bindValue(":cat_id", $select_categories[$id]);

//         $statement->execute();
//     }
// }

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
        $statement->bindValue(":image_content", $image_name);

        $statement->execute();

        // https://stackoverflow.com/questions/31681096/getting-the-next-primary-key-without-adding-a-new-record-is-impossible-isnt-it
        $post_id_query = "SELECT AUTO_INCREMENT - 1 AS 'current_id'
                          FROM information_schema.TABLES
                          WHERE TABLE_SCHEMA = 'meshes_and_models'
                          AND TABLE_NAME = 'posts';";

        $post_id_statement = $db->prepare($post_id_query);

        $post_id_statement->execute();

        return $post_id_statement->fetch()["current_id"];
    }

    return null;
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

        save_images($file_upload_path);

        $image_filename = $_FILES["image_upload"]["name"];

        $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $written_content = $purifier->purify($_POST["written_content"]);

        $new_post_id = save_to_database($db, $title, $image_filename, $written_content);

        // save_category_selection($db, $new_post_id);

        header("Location: ./my_posts.php");
    } else {
        $error = "The file being uploaded must be an image.";
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
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="./styles/tinymce_config.css">

    <!-- Config the WYSIWYG -->
    <script src="../vendor/tinymce/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="js/tinymce_config.js"></script>

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
            <textarea name="written_content" id="written_content" rows="10" cols="80"></textarea>
            <button type="submit">Post</button>
        </form>
        <?php if ($error): ?>
            <?= $error ?>
        <?php endif ?>
    <?php endif ?>

    <?php require("../templates/footer.php") ?>

</body>

</html>