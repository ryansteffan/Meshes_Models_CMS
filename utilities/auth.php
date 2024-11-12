<?php
// Encryption info: 
// https://medium.com/@mrityunjay.webmaster/how-to-secure-hash-and-salt-for-php-passwords-54f1c9d268a6

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

function create_user($database, $email, $password, $first_name, $last_name, $auth_level)
{
    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Make a 16 bit salt value that prevents rainbow table lookup.
    $password_salt = bin2hex(random_bytes(16));

    // Combine the salt with the hash.
    $combined_hash_and_salt = password_hash($password_hash . $password_salt, PASSWORD_BCRYPT);

    $query = "INSERT INTO Users (
                email, hash, salt, first_name, last_name, auth_level
              )
              VALUES (
                ':email', ':combined_hash', ':salt', ':fn', ':ln', :auth_level
                );";

    $database->prepare($query);

    $database->bindValue(":email", $email);
    $database->bindValue(":combined_hash", $combined_hash_and_salt);
    $database->bindValue(":salt", $password_salt);
    $database->bindValue(":fn", $first_name);
    $database->bindValue(":ln", $last_name);
    $database->bindValue(":auth_level", $auth_level, PDO::PARAM_INT);

    $database->execute();
}

function login_user($database, $username, $password, $required_auth_level)
{
    $user_query = "SELECT email, password, auth_level FROM users WHERE email = :username;";
    $database->prepare($user_query);
    $database->bindValue(":username", $username);
    $database->execute();

    $user_row = $database->fetch();

    $stored_email = $user_row["email"];
    $stored_password_hash = $user_row["hash"];
    $stored_password_salt = $user_row["salt"];
    $stored_auth_level = $user_row["auth_level"];

    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $password_salt = bin2hex(random_bytes(16));
    $combined_salt_and_hash = password_hash($password_hash . $password_salt, PASSWORD_BCRYPT);
}
