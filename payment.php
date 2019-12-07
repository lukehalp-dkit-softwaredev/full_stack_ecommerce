<?php
require_once "php/configuration.php";

session_start();

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
        $order_id = strval($result->order_id);
        $query = "SELECT order_lines.quantity, products.product_id, products.name, products.description, products.unit_price, products.stock FROM order_lines, products WHERE order_id = :order_id AND order_lines.product_id = products.product_id";
        $statement = $dbConnection->prepare($query);
        $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $user_email = $userInfo['email'];
            $session_object = [
                'payment_intent_data' => [
                    'setup_future_usage' => 'off_session',
                ],
//                'customer' => $userInfo["sub"],
                'client_reference_id' => $order_id,
                'customer_email' => $user_email,
                'payment_method_types' => ['card'],
                'line_items' => [],
                'success_url' => $siteName . '/confirmation.php?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $siteName . "/cart.php",
            ];
            $result = $statement->fetchAll(PDO::FETCH_OBJ);
            /**
              'name' => '60-minute massage',
              'description' => 'A 60-minute therapeutic massage',
              'images' => ['https://example.com/massage.png'],
              'amount' => 7000,
              'currency' => 'eur',
              'quantity' => 1, */
            foreach ($result as $row) {
                if($row->quantity > $row->stock) {
                    $error->code = 500;
                    $error->msg = "Order quantity ".$row->quantity."greater than stock ".$row->stock."for item ".$row->name;
                    $response->error = $error;
                    $response->apiVersion = "1.0";
                    $_SESSION['error'] = $response;
                    header("location: cart.php");
                }
                $item = [
                    "name" => $row->name,
                    "images" => [$siteName . "/img/products/" . $row->product_id . ".png"],
                    "description" => $row->description,
//                $session_object->line_items->images = $row["products.description"];
                    "amount" => $row->unit_price * 100,
                    "currency" => "eur",
                    "quantity" => $row->quantity
                ];
                $session_object['line_items'][] = $item;
            }
            $session = \Stripe\Checkout\Session::create($session_object);
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
        <title>Just Another Minecraft Store Payment</title>

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
            console.log(<?php json_encode($session_object) ?>);
            var stripe = Stripe('<?php echo $stripePK; ?>');
            stripe.redirectToCheckout({
                // Make the id field from the Checkout Session creation API response
                // available to this file, so you can provide it as parameter here
                // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
                sessionId: '<?php echo $session->id; ?>'
            }).then(function (result) {
                // If `redirectToCheckout` fails due to a browser or network
                // error, display the localized error message to your customer
                // using `result.error.message`.
            });
        </script>

    </head>
    <body>
        <div class="checkout-wrap">
            <ul class="checkout-bar">

                <li class="first visited">Choose payment method</li>

                <li class="active">Process Transaction</li>

                <li class="next">Payment Complete</li>

            </ul>
        </div>
        <div id="ag_user_message"></div>
    </body>
</html>

