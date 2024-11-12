<?php
require("../utilities/connect.php");
require("../utilities/auth.php");

$password_and_email_set = isset($_POST["email"]) && isset($_POST["password"]);

if ($password_and_email_set) {
    $username = $_POST["email"];
    $password = $_POST["password"];

    if ($username != "" && $password != "") {
        login_user($db, $username, $password);
    } else {
        header("Location: login_error.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>

    <div id="login_island">
        <form action="#" method="post">
            <label for="email">Username/Email:</label>
            <input type="text" name="email" id="email">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <button type="submit">Login</button>
        </form>
        <p>Not already a user? Sign Up Here: <a href="./create_account.php">Sign Up</a></p>
    </div>

</body>

</html>