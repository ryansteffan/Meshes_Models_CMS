<?php
require("../utilities/connect.php");
require("../utilities/auth.php");
require("../utilities/categories.php");

$display_options = false;
$error = false;
$is_admin = is_logged_in() && $_SESSION["user_details"]["auth_level"] == 0;

function get_current_post($db)
{
    $query = "SELECT * 
              FROM posts p
              JOIN users u
              on p.author = u.user_id";

    $statement = $db->prepare($query);

    $statement->execute();

    return $statement->fetchAll();
}

if (isset($_POST["new_category_name"]) && $_POST["new_category_name"] != "" && $is_admin) {
    $category_name = filter_input(INPUT_POST, "new_category_name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $query = "INSERT INTO categories
              (name)
              VALUES(:category_name);";

    $statement = $db->prepare($query);
    $statement->bindValue(":category_name", $category_name);

    try {
        $statement->execute();
    } catch (Exception $ex) {
        $error = "Sorry, there was an error. Please make sure the category does not already exist.";
    }
}

$do_post_association = isset($_POST["page_list"]) && isset($_POST["categories_list"]);
if ($do_post_association && $is_admin) {
    $page_selection = filter_input(INPUT_POST, "page_list", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_selection = filter_input(INPUT_POST, "categories_list", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($page_selection != "" && $category_selection != "") {
        $insert_category_relationship = "INSERT INTO posts_categories
                                                 (post_id, category_id)
                                                 VALUES(:post_id, :cat_id);";

        $statement = $db->prepare($insert_category_relationship);

        $statement->bindValue(":post_id", $page_selection);
        $statement->bindValue(":cat_id", $category_selection);

        try {
            $statement->execute();
        } catch (Exception $ex) {
            $error = "There was an error adding the post to the category. Make sure it is not already added.";
        }
    }
}

if ($is_admin) {
    $display_options = true;

    $current_pages = get_current_post($db);
    $current_categories = get_full_category_data($db);
} else {
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../index.css">

    <title>Admin</title>
</head>

<body>
    <?php require("../templates/header.php") ?>
    <?php if ($display_options): ?>
        <h2>Welcome Admin</h2>
        <h2>Add a category:</h2>
        <?php if ($error): ?>
            <p><?= $error ?></p>
        <?php endif ?>
        <h3>The current categories are:</h3>
        <?php if (count($current_categories) != 0): ?>
            <ul>
                <?php for ($category = 0; $category < count($current_categories); $category++): ?>
                    <li><?= $current_categories[$category]["name"] ?></li>
                <?php endfor ?>
            </ul>
        <?php else: ?>
            <p>There are currently no categories in the system.</p>
        <?php endif ?>
        <form action="#" method="post">
            <label for="new_category_name">Category Name:</label>
            <input type="text" name="new_category_name" id="new_category_name">
            <button type="submit">Create</button>
        </form>
        <h2>Add a Page to a category:</h2>
        <form action="#" method="post">
            <label for="page_list">
                <h3>Select a Page:</h3>
            </label>
            <select name="page_list" id="page_list">
                <?php for ($page = 0; $page < count($current_pages); $page++): ?>
                    <option value=<?= $current_pages[$page]["post_id"] ?>>
                        <?= $current_pages[$page]["first_name"] ?> <?= $current_pages[$page]["last_name"] ?>: <?= $current_pages[$page]["title"] ?>
                    </option>
                <?php endfor ?>
            </select>

            <label for="categories_list">
                <h3>Select a Category</h3>
            </label>
            <select name="categories_list" id="categories_list">
                <?php for ($category = 0; $category < count($current_categories); $category++): ?>
                    <option value=<?= $current_categories[$category]["category_id"] ?>> <?= $current_categories[$category]["name"] ?></option>
                <?php endfor ?>
            </select>
            <button type="submit">Add to Category</button>
        </form>
    <?php endif ?>
    <?php require("../templates/footer.php") ?>
</body>

</html>