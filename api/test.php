<?php

require '../vendor/autoload.php';

$session = new stdClass();
$session->client_reference_id = "42";

handle_checkout_session($session);

function handle_checkout_session($session) {

    require_once "../php/configuration.php";

    require '../vendor/autoload.php';

    $order_id = intval($session->client_reference_id);

    /* Connect to the database */
    $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

    /* Perform Query */
    $query = "SELECT order_id FROM orders WHERE order_id = :order_id";
    $statement = $dbConnection->prepare($query);
    $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT);
    $statement->execute();

    if($statement->rowCount() > 0) {
        echo "got order";
        $query = "SELECT product_id, quantity FROM order_lines WHERE order_id = :order_id";
        $statement = $dbConnection->prepare($query);
        $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT);
        $statement->execute();
        if($statement->rowCount() > 0) {
            echo "got order items";
            $results = $statement->fetchAll(PDO::FETCH_OBJ);
            foreach ($result as $row) {
                $quantity = $result->quantity;
                $query = "SELECT product_id, stock FROM products WHERE product_id = :product_id";
                $statement = $dbConnection->prepare($query);
                $statement->bindParam(":product_id", $result->product_id, PDO::PARAM_INT);
                $statement->execute();

                if($statement->rowCount() > 0) {
                    $result = $statement->fetch(PDO::FETCH_OBJ);
                    $newstock = $result->stock;
                    if($result->stock != -1) {
                        $newstock = $newstock - $quantity;
                    }
                    $query = "UPDATE products SET stock = :stock WHERE product_id = :product_id";
                    $statement = $dbConnection->prepare($query);
                    $statement->bindParam(":product_id", $result->product_id, PDO::PARAM_INT);
                    $statement->bindParam(":stock", $newstock, PDO::PARAM_INT);
                    $statement->execute();

                    $order_id = $snowflake->id();
                    $query = "INSERT INTO orders(order_id, user_id) VALUES (:order_id, :user_id);";
                    $statement = $dbConnection->prepare($query);
                    $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT);
                    $statement->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                    $statement->execute();
                } else {
                    //Invalid item
                    http_response_code(400);
                    exit();
                }
            }
        } else {
            //No items in order
            http_response_code(400);
            exit();
        }
    } else {
        //Invalid order
        http_response_code(400);
        exit();
    }
}

?>