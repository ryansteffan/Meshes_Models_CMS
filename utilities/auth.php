<?php
require("connect.php");

if (session_id() == '') {
    session_start();
}

function is_logged_in()
{
    return $_SESSION["logged_in"];
}

function set_user_logged_in()
{
    $_SESSION["logged_in"] = true;
}

function set_user_logged_out()
{
    $_SESSION["logged_in"] = false;
}

function login_user($database, $username, $password, $required_auth_level)
{
    $password_hash = $password;

    $user_query = "SELECT email, password, auth_level FROM users WHERE email = :username;";
    $database->prepare($user_query);
    $database->bindValue(":username", $username);
    $database->execute();

    $user_row = $database->fetch();

    $email = $user_row["email"];
}
