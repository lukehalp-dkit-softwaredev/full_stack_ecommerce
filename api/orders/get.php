<?php
    require_once "../../php/configuration.php";

    $response = new stdClass();

    if (isset($_GET['order'])) {
        $order_id = filter_input(INPUT_GET, "order", FILTER_SANITIZE_NUMBER_INT);
        
        /* Connect to the database */
        $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

        /* Perform Query */
        $query = "SELECT order_id, user_id, date_created, date_ordered FROM orders WHERE order_id = :order_id";
        $statement = $dbConnection->prepare($query);
        $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT);
        $statement->execute();

        /* echo "<br>---------DEBUG--------<br>";
        echo $statement->rowCount();
        echo "<br>-------END DEBUG------<br><br><br>"; */

        if ($statement->rowCount() == 1) {
            $result = $statement->fetch(PDO::FETCH_OBJ);

            /* Get items in order */
            $query = "SELECT product_id, quantity FROM order_lines WHERE order_id = :order_id";
            $statement = $dbConnection->prepare($query);
            $statement->bindParam(":order_id", $result->order_id, PDO::PARAM_INT);
            $statement->execute();
            
            $result->items = $statement->fetchAll(PDO::FETCH_OBJ);

            $response->apiVersion = "1.0";
            $response->data = $result;
        } else {
            // Order not found
            $error = new stdClass();
            $error->code = 404;
            $error->msg = "Order not found, check order id and try again.";

            $response->apiVersion = "1.0";
            $response->error = $error;
        }

        
    } else {
        // Order id not in url
        $error = new stdClass();
        $error->code = 400;
        $error->msg = "Malformed URL, please check url parameters and try again.";

        $response->apiVersion = "1.0";
        $response->error = $error;
    }

    echo json_encode($response);
?>