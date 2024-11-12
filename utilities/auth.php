<?php
// TODO: Add salts to the passwords.

// Encryption info: 
// https://medium.com/@mrityunjay.webmaster/how-to-secure-hash-and-salt-for-php-passwords-54f1c9d268a6
// https://stackoverflow.com/questions/34662684/setting-a-salt-for-password-hash

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
    // Make a 16 bit salt value that prevents rainbow table lookup.
    $password_salt = bin2hex(random_bytes(16));

    $password = $password;

    // Combine the salt with the hash.
    $combined_hash_and_salt = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO Users (
                email, hash, salt, first_name, last_name, auth_level
              )
              VALUES (
                :email, :combined_hash, :salt, :fn, :ln, :auth_level
                );";

    $statement = $database->prepare($query);

    $statement->bindValue(":email", $email);
    $statement->bindValue(":combined_hash", $combined_hash_and_salt);
    $statement->bindValue(":salt", $password_salt);
    $statement->bindValue(":fn", $first_name);
    $statement->bindValue(":ln", $last_name);
    $statement->bindValue(":auth_level", $auth_level, PDO::PARAM_INT);

    $statement->execute();
}

function login_user($database, $username, $password)
{
    $user_query = "SELECT email, hash, salt FROM users WHERE email = :username;";
    $statement = $database->prepare($user_query);
    $statement->bindValue(":username", $username);
    $statement->execute();

    $user_row = $statement->fetch();

    $stored_password_hash = $user_row["hash"];
    $stored_password_salt = $user_row["salt"];

    $password = $password;

    $isValidLogin = password_verify($password, $stored_password_hash);
    if ($isValidLogin) {
        set_user_logged_in();
    } else {
        header("../pages/login.php");
    }
}
