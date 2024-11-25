<?php
require("../utilities/connect.php");
require("../utilities/auth.php");

$do_display_options = false;

if (is_logged_in()) {
    $do_display_options = true;

    $users_posts_query = "SELECT
	post_id,
	title,
	written_content,
	image_content,
	post_date
    FROM
	    posts p
    JOIN users u 
    ON
    	p.author = u.user_id
    WHERE
    	p.author = :user_id;";

    $statement = $db->prepare($users_posts_query);

    $statement->bindValue(":user_id", $_SESSION["user_details"]["user_id"]);

    $statement->execute();
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
        <div>
            <a href="./create_post.php">Create Post</a>
        </div>
        <h1>Your Posts:</h1>
        <?php while ($row = $statement->fetch()): ?>
            <h2><?= $row["title"] ?></h2>
            <p><?= $row["post_date"] ?></p>
            <img src="../uploads/small<?= $row["image_content"] ?>" alt="Image for <?= $row["title"] ?> post.">
            <p><a href="./modify_post.php?post_id=<?= $row["post_id"] ?>">Edit</a></p>
            <p><a href=./delete_post.php?post_id=<?= $row["post_id"] ?>">Delete</a></p>
        <?php endwhile ?>

    <?php endif ?>

    <?php require("../templates/footer.php") ?>
</body>

</html>