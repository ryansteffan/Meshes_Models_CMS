<?php
require("../utilities/connect.php");
require("../utilities/categories.php");

if (isset($_GET["category"])) {

    $date_format = "F j, Y, h:i a";

    $category = $_GET["category"];

    $posts = get_category_posts($db, $category);

    print_r($posts);
} else {
    // Redirect the user if they do not make a proper get request.
    header("Location: categories.php");
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
    <?php for ($index = 0; $index < count($posts); $index++): ?>
        <div class="search_item">
            <h1><?= $posts[$index]["first_name"] . " " . $posts[$index]["last_name"] ?> </h1>
            <p><?= date($date_format, strtotime($posts[$index]["post_date"])) ?></p>
            <img src=../<?= $posts[$index]["image_content"] ?> alt="">
            <p><a href="pages/view_post.php?post_id=<?= $row["post_id"] ?>">Read More...</a></p>
        </div>
    <?php endfor ?>
    <?php require("../templates/footer.php") ?>
</body>

</html>