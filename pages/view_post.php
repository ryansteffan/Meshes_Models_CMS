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
} else {
    // Redirect the user if they do not make a get request.
    header("Location: ../index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./styles/view_post.css">
    <link rel="stylesheet" href="../index.css">

    <title>Document</title>
</head>

<body>
    <?php require("../templates/header.php") ?>
    </div>
    <?php while ($row = $prepared_statement->fetch()): ?>
        <h1><?= $row["title"] ?></h1>
        <h2><?= $row["first_name"] . " " . $row["last_name"] ?> </h2>
        <?php if (isset($_SESSION["user_details"]) && $row["author"] == $_SESSION["user_details"]["user_id"]): ?>
            <ul>
                <li><a href="./modify_post.php?post_id=<?= $post_id ?>">Edit Post</a></li>
                <li><a href="./delete_post.php?post_id=<?= $post_id ?>">Delete Post</a></li>
            </ul>
        <?php endif ?>
        <p>Post Date: <?= date($date_format, strtotime($row["post_date"])) ?></p>
        <p>Last Edited: <?= date($date_format, strtotime($row["modified_date"])) ?></p>

        <?php $categories = get_post_categories($db, $row["post_id"]); ?>
        <ul>
            <?php for ($category_index = 0; $category_index < count($categories); $category_index++): ?>
                <li><?= $categories[$category_index]["name"] ?></li>
            <?php endfor ?>
        </ul>

        <img src=../uploads/medium<?= $row["image_content"] ?> alt="">
        <p id="written_content"><?= $row["written_content"] ?></p>
    <?php endwhile ?>
    <?php require("../templates/footer.php") ?>
</body>

</html>