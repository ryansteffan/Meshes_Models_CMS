<?php
// require("utilities/connect.php");
require("utilities/auth.php");

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
    <h1>My Content</h1>
    <p>This is some content that comes after the header and before the footer.</p>
    <?php require("./templates/footer.php"); ?>
</body>

</html>