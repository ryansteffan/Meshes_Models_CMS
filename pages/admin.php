<?php
require("../utilities/connect.php");
require("../utilities/auth.php");

$display_options = false;

if (is_logged_in() && $_SESSION["user_details"]["auth_level"] == 0) {
    $display_options = true;
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
        <h3>Add a category:</h3>
        <form action="#" method="post">
            <label for="new_category_name">Category Name:</label>
            <input type="text" name="new_category_name" id="new_category_name">
            <button type="submit">Create</button>
        </form>
    <?php endif ?>
    <?php require("../templates/footer.php") ?>
</body>

</html>