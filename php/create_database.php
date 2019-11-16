<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PHP Create Database</title>
    </head>
    <body>

        <?php
        /* Include "configuration.php" file */
        require_once "configuration.php";


        /* Connect to the database */
        $dbConnection = new PDO("mysql:host=$dbHost", $dbUsername, $dbPassword);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception



        /* Create the database */
        $query = "CREATE DATABASE IF NOT EXISTS $dbName;";
        $statement = $dbConnection->prepare($query);
        $statement->execute();

        $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

        /* Create table */
        $query = "CREATE TABLE products(product_id INT NOT NULL PRIMARY KEY,
    name VARCHAR(88) NOT NULL,
    description TEXT NOT NULL,
    unit_price FLOAT NOT NULL,
    image_url VARCHAR(255) NULL,
    stock INT NOT NULL);"
                . "CREATE TABLE users(
    user_id INT NOT NULL PRIMARY KEY,
    email VARCHAR(320) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    billing_address VARCHAR(255) NOT NULL,
    mc_username VARCHAR(16) NOT NULL)";
        $statement = $dbConnection->prepare($query);
        $statement->execute();

        /* Provide feedback to the user */
        echo "Database '$dbName' created.";
        ?>
    </body>
</html>