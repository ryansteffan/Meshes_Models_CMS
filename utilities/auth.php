<?php
// Encryption info:
// https://stackoverflow.com/questions/34662684/setting-a-salt-for-password-hash
// https://www.php.net/manual/en/function.password-hash.php

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

function create_user($database, $email, $password, $first_name, $last_name, $auth_level)
{
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO Users (
                email, hash, first_name, last_name, auth_level
              )
              VALUES (
                :email, :combined_hash, :fn, :ln, :auth_level
                );";

    $statement = $database->prepare($query);

    $statement->bindValue(":email", $email);
    $statement->bindValue(":combined_hash", $hashed_password);
    $statement->bindValue(":fn", $first_name);
    $statement->bindValue(":ln", $last_name);
    $statement->bindValue(":auth_level", $auth_level, PDO::PARAM_INT);

    $statement->execute();
}

function login_user($database, $username, $password)
{
    $user_query = "SELECT email, hash FROM users WHERE email = :username;";
    $statement = $database->prepare($user_query);
    $statement->bindValue(":username", $username);
    $statement->execute();

    $user_row = $statement->fetch();

    try {
        $stored_password_hash = $user_row["hash"];
    } catch (Exception $e) {
        header("Location: ../pages/login.php");
    }

    $isValidLogin = password_verify($password, $stored_password_hash);
    if ($isValidLogin) {
        set_user_logged_in();
        header("Location: ../index.php");
    } else {
        header("../pages/login.php");
    }
}
