<?php
require("../utilities/auth.php");
require("../utilities/connect.php");

$is_email_password_set = isset($_POST["email"]) && isset($_POST["password"]);
$is_names_set = isset($_POST["first_name"]) && isset($_POST["last_name"]);
$incorrect_form = false;

if (isset($_POST["create_account"])) {
    if ($is_email_password_set && $is_names_set) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $auth_level = 5;

        create_user($db, $email, $password, $first_name, $last_name, $auth_level);
    } else {
        header("Location: #");
    }
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
    <div id="signup_island">
        <form action="#" method="post">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name">
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name">
            <label for="email">Email:</label>
            <input type="text" name="email" id="email">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <input type="hidden" name="create_account" value="true">
            <button type="submit">Create Account</button>
        </form>
    </div>
</body>

</html>