<?php
require("utilities/connect.php");
require("utilities/categories.php");

$date_format = "F j, Y, h:i a";

if (isset($_GET["search_text"])) {
    $keyword = '%' . $_GET["search_text"] . '%';
    $get_posts_query = "SELECT * 
                        FROM posts p
                        JOIN users u
                        ON p.author = u.user_id
                        WHERE 
                        p.categories LIKE :cat
                        OR 
                        u.first_name LIKE :fn
                        OR 
                        u.last_name LIKE :ln
                        LIMIT 20;";

    $get_posts_statement = $db->prepare($get_posts_query);

    // Bind the category.
    $get_posts_statement->bindValue(":cat", $keyword, PDO::PARAM_STR);

    // Bind the first name.
    $get_posts_statement->bindValue(":fn", $keyword, PDO::PARAM_STR);

    // Bind the last name.
    $get_posts_statement->bindValue(":ln", $keyword, PDO::PARAM_STR);
} else {
    $get_posts_query = "SELECT * FROM posts p JOIN users u ON p.author = u.user_id LIMIT 20;";
    $get_posts_statement = $db->prepare($get_posts_query);
}

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
        <form action="#" method="get">
            <label for="search_text">Search by User or Category:</label>
            <input type="text" name="search_text" id="search_text">
            <button type="submit">Search</button>
        </form>
    </div>
    <?php while ($row = $get_posts_statement->fetch()): ?>
        <div class="search_item">
            <h1><?= $row["first_name"] . " " . $row["last_name"] ?> </h1>
            <p><?= date($date_format, strtotime($row["post_date"])) ?></p>
            <img src=<?= $row["image_content"] ?> alt="">
            <ul>
                <!-- List out all of the categories individually -->
                <?php for ($category = 0; $category < count(split_categories($row["categories"])); $category++): ?>
                    <li><?= split_categories($row["categories"])[$category] ?></li>
                <?php endfor ?>
            </ul>
            <p><a href="pages/view_post.php?post_id=<?= $row["post_id"] ?>">Read More...</a></p>
        </div>
    <?php endwhile ?>
    <!-- <div id="pagination">
        <ul>
            <li><a href="">Next Page</a></li>
            <li><a href="">Previous Page</a></li>
        </ul>
    </div> -->
    <?php require("./templates/footer.php"); ?>
</body>

</html>