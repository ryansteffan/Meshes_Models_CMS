<?php
require("../utilities/auth.php");
require("../utilities/connect.php");


if (isset($_POST["create_account"])) {
    $is_email_password_set = $_POST["email"] != "" && $_POST["password"] != "" && $_POST["repeat_password"] != "";
    $is_names_set = $_POST["first_name"] != "" && $_POST["last_name"] != "";

    if ($is_email_password_set && $is_names_set) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $repeated_password = $_POST["repeat_password"];
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $auth_level = 5;

        // Ensure that a user does not make a password they do not know.
        if ($password != $repeated_password) {
            header("Location: account_creation_error.php");
        }

        try {
            create_user($db, $email, $password, $first_name, $last_name, $auth_level);
            header("Location: login.php");
        } catch (Exception $e) {
            header("Location: account_creation_error.php");
        }
    } else {
        header("Location: account_creation_error.php");
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
            <label for="repeat_password">Repeat Password:</label>
            <input type="password" name="repeat_password" id="repeat_password">
            <input type="hidden" name="create_account" value="true">
            <button type="submit">Create Account</button>
        </form>
    </div>
</body>

</html>