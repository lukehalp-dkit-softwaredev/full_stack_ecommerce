<?php
require_once "php/configuration.php";
/* Connect to the database */
$dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey($stripeSK);
\Firebase\JWT\JWT::$leeway = 60;

use Auth0\SDK\Auth0;

$auth0 = new Auth0([
    'domain' => $auth0_domain,
    'client_id' => $auth0_client_id,
    'client_secret' => $auth0_client_secret,
    'redirect_uri' => $siteName . '/category.php',
    'persist_id_token' => true,
    'persist_access_token' => true,
    'persist_refresh_token' => true,
        ]);
//get user's basket
//get order id 
//get lines from basket from order id
//insert to line items
$error = new stdClass();
$response = new stdClass();
$userInfo = $auth0->getUser();
if ($userInfo) {
    $query = "SELECT order_id FROM orders WHERE user_id = :user_id AND date_ordered IS NULL";
    $statement = $dbConnection->prepare($query);
    $statement->bindParam(":user_id", $userInfo["sub"], PDO::PARAM_STR);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $result = $statement->fetch(PDO::FETCH_OBJ);
        $query = "SELECT order_lines.quantity, products.product_id, products.name, products.description, products.unit_price FROM order_lines, products WHERE order_id = :order_id AND order_lines.product_id = products.product_id";
        $statement = $dbConnection->prepare($query);
        $statement->bindParam(":order_id", $result->order_id, PDO::PARAM_INT);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            
        } else {
            //order line empty???
            $error->code = 404;
            $error->msg = "Order empty";
            $response->error = $error;
        }
    } else {
        //order_id not found
        $error->code = 404;
        $error->msg = "Order not found";
        $response->error = $error;
    }
} else {
    header("location: api/login.php");
}
?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">

    <head>
        <!-- Mobile Specific Meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Favicon-->
        <link rel="shortcut icon" href="img/fav.png">
        <!-- Author Meta -->
        <meta name="author" content="CodePixar">
        <!-- Meta Description -->
        <meta name="description" content="">
        <!-- Meta Keyword -->
        <meta name="keywords" content="">
        <!-- meta character set -->
        <meta charset="UTF-8">
        <!-- Site Title -->
        <title>Just Another Minecraft Store Choose Payment Method</title>

        <!--
            CSS
            ============================================= -->
        <link rel="stylesheet" href="css/linearicons.css">
        <link rel="stylesheet" href="css/owl.carousel.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/themify-icons.css">
        <link rel="stylesheet" href="css/nice-select.css">
        <link rel="stylesheet" href="css/nouislider.min.css">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="https://js.stripe.com/v3/"></script>
        <script>
        </script>

    </head>
    <body>
        <div class="container ag_payment_method_container">
            <div class="row">
                <div class="checkout-wrap">
                    <ul class="checkout-bar">

                        <li class="first active">
                            <a href="#">Calculate cost</a>
                        </li>

                        <li class="next">Confirm Payment</li>

                        <li class="">Payment Complete</li>

                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row ag_payment_method_header">
                        <h2>Choose payment method</h2>
                    </div>
                    <div class="row">
                        <div class="col-6 ag_payment_method">
                            <button class="primary-btn">Paypal</button>
                        </div>
                        <div class="col-6 ag_payment_method">
                            <button class="primary-btn">Card</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>

