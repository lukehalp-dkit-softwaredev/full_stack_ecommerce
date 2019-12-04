<?php

require_once "../../php/configuration.php";

require '../../vendor/autoload.php';
\Firebase\JWT\JWT::$leeway = 60;

use Auth0\SDK\Auth0;

$auth0 = new Auth0([
    'domain' => $auth0_domain,
    'client_id' => $auth0_client_id,
    'client_secret' => $auth0_client_secret,
    'redirect_uri' => $siteName . '/php/logged_in.php',
    'scope' => 'openid email',
        ]);

$userInfo = $auth0->getUser();
$error = new stdClass();
if ($userInfo) {

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://" . $auth0_domain . "/dbconnections/change_password",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"client_id\": \"" . $auth0_client_id . "\",\"email\": \"" . $userInfo["email"] . "\",\"connection\": \"Username-Password-Authentication\"}",
        CURLOPT_HTTPHEADER => array(
            "content-type: application/json"
        ),
    ));

    $response = new stdClass();
    $response->msg = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        $error->code = 404;
        $error->msg = $err;
        echo json_encode($error);
    } else {
        echo json_encode($response);
    }
} else {
    $error->code = 401;
    $error->msg = "User is not logged in, please login.";
    echo json_encode($error);
}