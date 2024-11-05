<?php
require("../utilities/connect.php");
require("../utilities/categories.php");

if (isset($_GET["category"])) {

    $date_format = "F j, Y, h:i a";

    $category = '%' . $_GET["category"] . '%';

    $query = "SELECT * 
              FROM posts p
              JOIN users u
              ON p.author = u.user_id
              WHERE 
              p.categories LIKE :cat
              LIMIT 20;";

    $statement = $db->prepare($query);

    $statement->bindValue(":cat", $category);

    $statement->execute();
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
    <?php while ($row = $statement->fetch()): ?>
        <div class="search_item">
            <h1><?= $row["first_name"] . " " . $row["last_name"] ?> </h1>
            <p><?= date($date_format, strtotime($row["post_date"])) ?></p>
            <img src=../<?= $row["image_content"] ?> alt="">
            <ul>
                <!-- List out all of the categories individually -->
                <?php for ($category = 0; $category < count(split_categories($row["categories"])); $category++): ?>
                    <li><?= split_categories($row["categories"])[$category] ?></li>
                <?php endfor ?>
            </ul>
            <p><a href="pages/view_post.php?post_id=<?= $row["post_id"] ?>">Read More...</a></p>
        </div>
    <?php endwhile ?>
</body>

</html>