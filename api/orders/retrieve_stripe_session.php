<?php

require_once "../../php/configuration.php";

require '../../vendor/autoload.php';
\Firebase\JWT\JWT::$leeway = 60;

use Auth0\SDK\Auth0;

$auth0 = new Auth0([
    'domain' => $auth0_domain,
    'client_id' => $auth0_client_id,
    'client_secret' => $auth0_client_secret,
    'redirect_uri' => $siteName . '/index.php',
    'persist_id_token' => true,
    'persist_access_token' => true,
    'persist_refresh_token' => true,
        ]);
$userInfo = $auth0->getUser();
if ($userInfo) {
    $session_id = trim(filter_input(INPUT_GET, "session_id", FILTER_SANITIZE_STRING));
    if ($session_id) {
        \Stripe\Stripe::setApiKey($stripeSK);
        $response = \Stripe\Checkout\Session::retrieve(
                        $session_id
        );

        $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

        $order_id = trim(filter_var($response->client_reference_id, FILTER_SANITIZE_NUMBER_INT));
        $query = "SELECT user_id FROM orders WHERE order_id = :order_id";
        $statement = $dbConnection->prepare($query);
        $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $results = $statement->fetch(PDO::FETCH_OBJ);
            if ($results->user_id == $userInfo['sub']) {
                echo json_encode($results);
                exit();
            }
        }
    }
}
http_response_code(401);
$response = new stdClass();
$error = new stdClass();
$error->code = 401;
$error->msg = "Something went wrong!";
$response->apiVersion = "1.0";
$response->error = $error;
echo json_encode($response);

