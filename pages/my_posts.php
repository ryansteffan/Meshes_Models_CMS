<?php
require("../utilities/connect.php");
require("../utilities/auth.php");

$date_format = "F j, Y, h:i a";
$user_id = $_SESSION["user_details"]["user_id"];

$do_display_options = false;

function get_users_posts($db, $user_id, $order_type = "updated_date", $order_direction = "descending")
{
    switch ($order_type) {
        case "title":
            $users_posts_query = "SELECT
                                      post_id,
                                      title,
                                      written_content,
                                      image_content,
                                      post_date,
                                      modified_date
                                  FROM
                                      posts p
                                  JOIN users u 
                                  ON
                                      p.author = u.user_id
                                  WHERE
                                      p.author = :user_id
                                  ORDER BY title";
            break;
        case "creation_date":
            $users_posts_query = "SELECT
                                      post_id,
                                      title,
                                      written_content,
                                      image_content,
                                      post_date,
                                      modified_date
                                  FROM
                                      posts p
                                  JOIN users u 
                                  ON
                                      p.author = u.user_id
                                  WHERE
                                      p.author = :user_id
                                  ORDER BY post_date";
            break;
        case "updated_date":
            $users_posts_query = "SELECT
                                      post_id,
                                      title,
                                      written_content,
                                      image_content,
                                      post_date,
                                      modified_date
                                  FROM
                                      posts p
                                  JOIN users u 
                                  ON
                                      p.author = u.user_id
                                  WHERE
                                      p.author = :user_id
                                  ORDER BY modified_date";
            break;
    }

    switch ($order_direction) {
        case "ascending":
            $sort_direction = " ASC;";
            break;
        case "descending":
            $sort_direction = " DESC;";
            break;
    }

    $users_posts_query = $users_posts_query . $sort_direction;

    $statement = $db->prepare($users_posts_query);

    $statement->bindValue("user_id", $user_id);

    $statement->execute();

    return $statement->fetchAll();
}


if (is_logged_in()) {
    $do_display_options = true;
    if (isset($_GET["sort_selection"])) {
        $sort_type = $_GET["sort_selection"];
        $sort_direction = $_GET["sort_direction"];

        print_r($sort_type);
        echo "<br>";
        print_r($sort_direction);

        $result = get_users_posts($db, $user_id, $sort_type, $sort_direction);
    } else {
        $result = get_users_posts($db, $user_id);
    }
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
        <form action="#" method="get">
            <select name="sort_selection" id="sort_selection">
                <option value="creation_date">Date Created</option>
                <option value="title">Title</option>
                <option value="updated_date">Date Updated</option>
            </select>
            <select name="sort_direction" id="sort_direction">
                <option value="ascending">Ascending</option>
                <option value="descending">Descending</option>
            </select>
            <button type="submit">Sort</button>
        </form>
        <?php for ($post = 0; $post < count($result); $post++): ?>
            <div class="edit_list_item">
                <h2><?= $result[$post]["title"] ?></h2>
                <p>Post Date: <?= date($date_format, strtotime($result[$post]["post_date"])) ?></p>
                <p>Last Edited: <?= date($date_format, strtotime($result[$post]["modified_date"])) ?></p>
                <img src="../uploads/small<?= $result[$post]["image_content"] ?>" alt="Image for <?= $result[$post]["title"] ?> post.">
                <p><a href="./modify_post.php?post_id=<?= $result[$post]["post_id"] ?>">Edit</a></p>
                <p><a href="./delete_post.php?post_id=<?= $result[$post]["post_id"] ?>">Delete</a></p>
            </div>
        <?php endfor ?>

    <?php endif ?>

    <?php require("../templates/footer.php") ?>
</body>

</html>