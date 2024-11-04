<?php
require("utilities/connect.php");

$date_format = "F j, Y, h:i a";

$get_posts_query = "SELECT * FROM posts p JOIN users u ON p.author = u.user_id;";

$get_posts_statement = $db->prepare($get_posts_query);

$get_posts_statement->execute();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Home Page</title>
</head>

<body>
    <?php require("./templates/header.php"); ?>
    <div id="search">
    </div>
    <?php while ($row = $get_posts_statement->fetch()): ?>
        <h1><?= $row["first_name"] . " " . $row["last_name"] ?> </h1>
        <h2><?= date($date_format, strtotime($row["post_date"])) ?></h2>
        <img src=<?= $row["image_content"] ?> alt="">
        <p><?= $row["witten_content"] ?></p>
    <?php endwhile ?>
    <?php require("./templates/footer.php"); ?>
</body>

</html>