<?php
$current_dir = explode(DIRECTORY_SEPARATOR, getcwd());

// Ensure that them template works in multiple parts of the app.
if (end($current_dir) == "Meshes_Models_CMS") {
    $styles_location = "templates/styles/footer.css";
    $home_link = "index.php";
    $categories_link = "pages/categories.php";
    $login_link = "pages/login.php";
    $my_posts_link = "pages/my_posts.php";
    $admin_page_link = "pages/admin.php";
} else {
    $styles_location = "../templates/styles/footer.css";
    $home_link = "../index.php";
    $categories_link = "categories.php";
    $login_link = "login.php";
    $my_posts_link = "my_posts.php";
    $admin_page_link = "admin.php";
}
?>

<html>
<link rel="stylesheet" href=<?= $styles_location ?>>
<footer>
    <p id="copyright_message">
        Website copyright of Ryan Steffan.
        <br />
        Contact: ryan.steffanbiz@gmail.com
    </p>
    <ul>
        <li><a href=<?= $home_link ?>>Home</a></li>
        <li><a href=<?= $categories_link ?>>Categories</a></li>
        <li><a href=<?= $my_posts_link ?>>My Posts</a></li>
        <li><a href="<?= $login_link ?>">Login</a></li>
        <li><a href=<?= $admin_page_link ?>>Administration</a></li>
    </ul>
</footer>

</html>