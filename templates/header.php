<?php
$current_dir = explode(DIRECTORY_SEPARATOR, getcwd());

$login_message = "";

if (isset($_SESSION["logged_in"])) {
    if (is_logged_in()) {
        $login_message = "Welcome {$_SESSION["user_details"]["first_name"]}. You are logged in!";
    }
} else {
    $login_message = "You are not currently logged in. Login below to make a new post.";
}


// Ensure that them template works in multiple parts of the app and on the web.
if (end($current_dir) == "Meshes_Models_CMS" || end($current_dir) == "wwwroot") {
    $styles_location = "templates/styles/header.css";
    $banner_image = "images/dice_ryan_downscaled.png";
    $home_link = "index.php";
    $categories_link = "pages/categories.php";
    $login_link = "pages/login.php";
    $my_posts_link = "pages/my_posts.php";
} else {
    $styles_location = "../templates/styles/header.css";
    $banner_image = "../images/dice_ryan_downscaled.png";
    $home_link = "../index.php";
    $categories_link = "categories.php";
    $login_link = "login.php";
    $my_posts_link = "my_posts.php";
}
?>

<html>
<link rel="stylesheet" href=<?= $styles_location ?>>
<header>
    <!-- Bring in the styles for the template. -->
    <h1 id="title">Meshes and Models</h1>
    <form action=<?= $home_link ?> method="get">
        <label for="search_text">Search by User or Category:</label>
        <input type="text" name="search_text" id="search_text">
        <button type="submit">Search</button>
    </form>
    <p><?= $login_message ?></p>
    <div id="banner">
    </div>
    <ul id="nav_items">
        <li><a href=<?= $home_link ?>>Home</a></li>
        <li><a href=<?= $categories_link ?>>Categories</a></li>
        <li><a href=<?= $my_posts_link ?>>My Posts</a></li>
        <li><a href=<?= $login_link ?>>Login</a></li>
    </ul>
</header>

</html>