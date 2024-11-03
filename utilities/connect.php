<?php
// Credentials that are used for the dev database.
define('DB_DSN', 'mysql:host=localhost;port=3306;dbname=meshes_and_models;charset=utf8');
define('DB_USER', 'serveruser');
define('DB_PASS', 'gorgonzola7!');

$azure_db_name = getenv("AZURE_MYSQL_DBNAME");
$azure_db_host = getenv("AZURE_MYSQL_HOST");
$azure_db_password = getenv("AZURE_MYSQL_PASSWORD");
$azure_db_port = getenv("AZURE_MYSQL_PORT");
$azure_db_username = getenv("AZURE_MYSQL_USERNAME");

//  PDO is PHP Data Objects
//  mysqli <-- BAD. 
//  PDO <-- GOOD.
try {
    // Try creating new PDO connection to MySQL.

    // Checks if the azure are defined and should be used.
    if ($azure_db_name && $azure_db_host && $azure_db_password && $azure_db_port && $azure_db_username) {
        define('AZURE_DB_DSN', "mysql:host=$azure_db_host;port=$azure_db_port;dbname=$azure_db_name;charset=utf8");
        define('AZURE_DB_USER', $azure_db_username);
        define('AZURE_DB_PASS', $azure_db_password);
        $db = new PDO(AZURE_DB_DSN, AZURE_DB_USER, AZURE_DB_PASS);
    } else {
        $db = new PDO(DB_DSN, DB_USER, DB_PASS);
    }
    //,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
} catch (PDOException $e) {
    print "Error: " . $e->getMessage();
    header("Location: pages/db_error.php");
}
