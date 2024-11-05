<?php
require("../utilities/connect.php");
require("../utilities/categories.php");
require("../utilities/auth.php");

$date_format = "F j, Y, h:i a";

if (isset($_GET["post_id"])) {
    $post_id = $_GET["post_id"];

    $get_post_query = "SELECT * 
                        FROM posts p
                        JOIN users u
                        ON p.author = u.user_id
                        WHERE p.post_id = :post_id";

    $prepared_statement = $db->prepare($get_post_query);

    $prepared_statement->bindValue(":post_id", $post_id);

    $prepared_statement->execute();
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
    <?php while ($row = $prepared_statement->fetch()): ?>
        <h1><?= $row["first_name"] . " " . $row["last_name"] ?> </h1>
        <p><?= date($date_format, strtotime($row["post_date"])) ?></p>
        <ul>
            <!-- List out all of the categories individually -->
            <?php for ($category = 0; $category < count(split_categories($row["categories"])); $category++): ?>
                <li><?= split_categories($row["categories"])[$category] ?></li>
            <?php endfor ?>
        </ul>
        <img src=../<?= $row["image_content"] ?> alt="">
        <p><?= $row["written_content"] ?></p>
    <?php endwhile ?>
    <?php require("../templates/footer.php") ?>
</body>

</html>