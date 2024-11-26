<?php
require("../utilities/connect.php");
require("../utilities/auth.php");

$logged_in = false;

if (isset($_GET["post_id"])) {
    if (is_logged_in()) {
        $logged_in = true;
        $post_id = $_GET["post_id"];
    } else {
        header("location: login.php");
    }
} else {
    header("location: my_posts.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify A Post</title>
</head>

<body>

    <?php if ($logged_in): ?>

    <?php endif ?>

</body>

</html>