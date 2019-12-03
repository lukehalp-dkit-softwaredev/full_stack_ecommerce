<?php

require_once "../../php/configuration.php";

require '../../vendor/autoload.php';

use Auth0\SDK\Auth0;

\Firebase\JWT\JWT::$leeway = 60;
$snowflake = new \Godruoyi\Snowflake\Snowflake;
$snowflake->setStartTimeStamp(strtotime('2019-11-11')*1000);


$auth0 = new Auth0([
    'domain' => $auth0_domain,
    'client_id' => $auth0_client_id,
    'client_secret' => $auth0_client_secret,
    'redirect_uri' => $siteName . '/category.php',
    'persist_id_token' => true,
    'persist_access_token' => true,
    'persist_refresh_token' => true,
        ]);
$userInfo = $auth0->getUser();

$response = new stdClass();

if ($userInfo) {
    if (isset($_GET['product']) && isset($_GET['quantity'])) {
        $product_id = filter_input(INPUT_GET, "product", FILTER_SANITIZE_NUMBER_INT);
        $quantity = filter_input(INPUT_GET, "quantity", FILTER_SANITIZE_NUMBER_INT);
        /* Connect to the database */
        $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

        /* Perform Query */
        $query = "SELECT name FROM products WHERE product_id = :product_id";
        $statement = $dbConnection->prepare($query);
        $statement->bindParam(":product_id", $product_id, PDO::PARAM_INT);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            $result = $statement->fetch(PDO::FETCH_OBJ);
            $product_name = $result->name;
            if ($quantity > 0) {
                $user_id = $userInfo['sub'];
                

                /* Perform Query */
                $query = "SELECT order_id FROM orders WHERE user_id = :user_id AND date_ordered IS NULL";
                $statement = $dbConnection->prepare($query);
                $statement->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                $statement->execute();

                if ($statement->rowCount() > 0) {
                    $result = $statement->fetch(PDO::FETCH_OBJ);

                    /* Perform Query */
                    $query = "INSERT INTO order_lines(order_id,product_id,quantity) VALUES (:order_id, :product_id, :quantity);";
                    $statement = $dbConnection->prepare($query);
                    $statement->bindParam(":order_id", $result->order_id, PDO::PARAM_INT);
                    $statement->bindParam(":product_id", $product_id, PDO::PARAM_INT);
                    $statement->bindParam(":quantity", $quantity, PDO::PARAM_INT);
                    $statement->execute();

                    if ($statement->rowCount() > 0) {
                        $data = new stdClass();
                        $data->product_id = $product_id;
                        $data->quantity = $quantity;
                        $data->name = $product_name;

                        $response->apiVersion = "1.0";
                        $response->data = $data;
                    } else {
                        //Couldnt add product to basket
                        $error = new stdClass();
                        $error->code = 500;
                        $error->msg = "Couldnt add item.";

                        $response->apiVersion = "1.0";
                        $response->error = $error;

                        http_response_code(500);
                    }
                } else {
                    // No basket found
                    $order_id = $snowflake->id();
                    $query = "INSERT INTO orders(order_id, user_id) VALUES (:order_id, :user_id);";
                    $statement = $dbConnection->prepare($query);
                    $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT);
                    $statement->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                    $statement->execute();

                    /* Perform Query */
                    $query = "INSERT INTO order_lines(order_id,product_id,quantity) VALUES (:order_id, :product_id, :quantity);";
                    $statement = $dbConnection->prepare($query);
                    $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT);
                    $statement->bindParam(":product_id", $product_id, PDO::PARAM_INT);
                    $statement->bindParam(":quantity", $quantity, PDO::PARAM_INT);
                    $statement->execute();

                    if ($statement->rowCount() > 0) {
                        $data = new stdClass();
                        $data->status = "OK";
                        $data->product_id = $product_id;
                        $data->quantity = $quantity;

                        $response->apiVersion = "1.0";
                        $response->data = $data;
                    } else {
                        //Couldnt add product to basket
                        $error = new stdClass();
                        $error->code = 500;
                        $error->msg = "Couldnt add item.";

                        $response->apiVersion = "1.0";
                        $response->error = $error;

                        http_response_code(500);
                    }
                }
            } else {
                // Invalid quantity
                $error = new stdClass();
                $error->code = 400;
                $error->msg = "Malformed URL, please check url parameters and try again.";

                $response->apiVersion = "1.0";
                $response->error = $error;

                http_response_code(400);
            }
        } else {

        }
    } else {
        // Product id not in url
        $error = new stdClass();
        $error->code = 400;
        $error->msg = "Malformed URL, please check url parameters and try again.";

        $response->apiVersion = "1.0";
        $response->error = $error;

        http_response_code(400);
    }
} else { // Not logged in
    $error = new stdClass();
    $error->code = 403;
    $error->msg = "Please log in.";

    $response->apiVersion = "1.0";
    $response->error = $error;

    http_response_code(403);
}
echo json_encode($response);
?>