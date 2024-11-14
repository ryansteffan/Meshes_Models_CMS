<?php
require("utilities/connect.php");
require("utilities/categories.php");
require("utilities/auth.php");

$date_format = "F j, Y, h:i a";

if (isset($_GET["search_text"])) {
    $is_search = true;
    $keyword = $_GET["search_text"];

    $search_results = post_search($db, $keyword);
    print_r($search_results);
} else {
    $is_search = false;
    $get_posts_query = "SELECT * FROM posts p JOIN users u ON p.author = u.user_id LIMIT 20;";
    $get_posts_statement = $db->prepare($get_posts_query);
    $get_posts_statement->execute();
}

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
    <?php if ($is_search): ?>
        <?php for ($post = 0; $post < count($search_results); $post++): ?>
            <div class="search_item">
                <h1><?= $search_results[$post]["first_name"] . " " . $search_results[$post]["last_name"] ?> </h1>
                <p><?= date($date_format, strtotime($search_results[$post]["post_date"])) ?></p>
                <img src=<?= $search_results[$post]["image_content"] ?> alt="">
                <?php $categories = get_post_categories($db, $search_results[$post]["post_id"]); ?>
                <ul>
                    <?php for ($category_index = 0; $category_index < count($categories); $category_index++): ?>
                        <li><?= $categories[$category_index]["name"] ?></li>
                    <?php endfor ?>
                </ul>
                <p><a href="pages/view_post.php?post_id=<?= $search_results[$post]["post_id"] ?>">Read More...</a></p>
            </div>
        <?php endfor ?>
    <?php else: ?>
        <?php while ($row = $get_posts_statement->fetch()): ?>
            <div class="search_item">
                <h1><?= $row["first_name"] . " " . $row["last_name"] ?> </h1>
                <p><?= date($date_format, strtotime($row["post_date"])) ?></p>
                <img src=<?= $row["image_content"] ?> alt="">
                <?php $categories = get_post_categories($db, $row["post_id"]); ?>
                <ul>
                    <?php for ($category_index = 0; $category_index < count($categories); $category_index++): ?>
                        <li><?= $categories[$category_index]["name"] ?></li>
                    <?php endfor ?>
                </ul>
                <p><a href="pages/view_post.php?post_id=<?= $row["post_id"] ?>">Read More...</a></p>
            </div>
        <?php endwhile ?>
    <?php endif ?>
    <?php require("./templates/footer.php"); ?>
</body>

</html>