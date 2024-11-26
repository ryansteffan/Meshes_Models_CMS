<?php
require("../utilities/auth.php");
require("../utilities/connect.php");

function delete_post($db, $post_id)
{
    $delete_query = "DELETE FROM posts 
                     WHERE post_id = :post_id;";

    $get_image_query = "SELECT p.image_content 
                       FROM posts p 
                       WHERE p.post_id = :post_id;";

    $get_image_statement = $db->prepare($get_image_query);
    $get_image_statement->bindValue(":post_id", $post_id);
    $get_image_statement->execute();

    $image_name = $get_image_statement->fetch();

    try {
        // Remove the images from the server.
        delete_images($image_name);
        $delete_post_statement = $db->prepare($delete_query);
        $delete_post_statement->bindValue(":post_id", $post_id);
        $delete_post_statement->execute();
        header("location: my_posts.php");
    } catch (Exception $ex) {
        header("location: db_error.php");
    }
}

// Deletes all images in the uploads folder that match the name provided.
function delete_images($image_name)
{
    unlink("../uploads/{$image_name[0]}");
    unlink("../uploads/small{$image_name[0]}");
    unlink("../uploads/medium{$image_name[0]}");
}

if (is_logged_in() && isset($_GET["post_id"])) {
    $post_id = $_GET["post_id"];
    delete_post($db, $post_id);
} else {
    header("location: db_error.php");
}
