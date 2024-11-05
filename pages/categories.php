<?php
require("../utilities/categories.php");
require("../utilities/connect.php");

$categories = get_all_categories($db);
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
    <ul>
        <?php for ($category = 0; $category < count($categories); $category++): ?>
            <li><a href="view_category.php?<?=?>"><?= $categories[$category] ?></a></li>
        <?php endfor ?>
    </ul>
    <?php require("../templates/footer.php") ?>
</body>

</html>