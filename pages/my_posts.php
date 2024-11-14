<?php
require("../utilities/connect.php");
require("../utilities/auth.php");

$do_display_options = false;

if (is_logged_in()) {
    $do_display_options = true;
} else {
    header("Location: login.php");
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
        <ul>
            <li>
                <div>
                    <a href="./create_post.php">Create Post</a>
                </div>
            </li>
            <li>
                <div>
                    <a href="./modify_post.php">Modify A Post</a>
                </div>
            </li>
        </ul>
    <?php endif ?>

    <?php require("../templates/footer.php") ?>
</body>

</html>