<?php

// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey($stripeSK);

// You can find your endpoint's secret in your webhook settings
$endpoint_secret = 'whsec_TGhoLNMGK6fNpzy6ORma8ZXhhKIm2NXC';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload, $sig_header, $endpoint_secret
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400);
  exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
  // Invalid signature
  http_response_code(400);
  exit();
}

// Handle the checkout.session.completed event
if ($event->type == 'checkout.session.completed') {
  $session = $event->data->object;

  // Fulfill the purchase...
  handle_checkout_session($session);
}

function handle_checkout_session($session) {
    $order_id = $session->client_reference_id;

    /* Connect to the database */
    $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

    /* Perform Query */
    $query = "SELECT order_id FROM orders WHERE order_id = :order_id";
    $statement = $dbConnection->prepare($query);
    $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT);
    $statement->execute();



    if($statement->rowCount() > 0) {
        $query = "SELECT product_id, quantity FROM order_lines WHERE order_id = :order_id";
        $statement = $dbConnection->prepare($query);
        $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT);
        $statement->execute();
        if($statement->rowCount() > 0) {
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


http_response_code(200);

?>