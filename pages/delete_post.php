<?php
require("../utilities/auth.php");
require("../utilities/connect.php");

function delete_post($db, $post_id)
{
    $delete_query = "DELETE FROM posts 
                     WHERE post_id = :post_id;";

    $get_image_query = "SELECT p.image_content 
                       FROM posts p 
                       WHERE p.author = :post_id;";

    $get_image_statement = $db->prepare($get_image_query);
    $get_image_statement->bindValue(":post_id", $post_id);
    $get_image_statement->execute();

    $image_name = $get_image_statement->fetch();

    // Remove the images from the server.
    delete_images($image_name);

    $delete_post_statement = $db->prepare($delete_query);
    $delete_post_statement->bindValue(":post_id", $post_id);

    $delete_post_statement->execute();
}

function delete_images($image_name)
{
    unlink("../uploads/{$image_name}");
    unlink("../uploads/small{$image_name}");
    unlink("../uploads/medium{$image_name}");
}

if (is_logged_in() && isset($_GET["post_id"])) {
    $post_id = $_GET["post_id"];
    delete_post($db, $post_id);
} else {
    header("location: db_error.php");
}
