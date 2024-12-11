<?php
require("../utilities/connect.php");
require("../utilities/categories.php");

if (isset($_GET["category"])) {

    $date_format = "F j, Y, h:i a";

    $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($category != false) {
        $posts = get_category_posts($db, $category);
    } else {
        $posts = "No posts could be found.";
    }
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

    <link rel="stylesheet" href="../index.css">

    <title>Document</title>
</head>

<body>
    <?php require("../templates/header.php") ?>
    <?php for ($index = 0; $index < count($posts); $index++): ?>
        <div class="search_item">

            <h1><?= $posts[$index]["first_name"] . " " . $posts[$index]["last_name"] ?> </h1>
            <p>Post Date: <?= date($date_format, strtotime($posts[$index]["post_date"])) ?></p>
            <p>Last Edited: <?= date($date_format, strtotime($posts[$index]["modified_date"])) ?></p>
            <img src=../uploads/medium<?= $posts[$index]["image_content"] ?> alt="">

            <?php $categories = get_post_categories($db, $posts[$index]["post_id"]); ?>
            <ul>
                <?php for ($category_index = 0; $category_index < count($categories); $category_index++): ?>
                    <li><?= $categories[$category_index]["name"] ?></li>
                <?php endfor ?>
            </ul>

            <p><a href="view_post.php?post_id=<?= $posts[$index]["post_id"] ?>">Read More...</a></p>

        </div>
    <?php endfor ?>
    <?php require("../templates/footer.php") ?>
</body>

</html>