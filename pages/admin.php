<?php
require("../utilities/connect.php");
require("../utilities/auth.php");
require("../utilities/categories.php");

$display_options = false;
$error = false;
$is_admin = is_logged_in() && $_SESSION["user_details"]["auth_level"] == 0;

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

if ($is_admin) {
    $display_options = true;

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
    <?php endif ?>
    <?php require("../templates/footer.php") ?>
</body>

</html>