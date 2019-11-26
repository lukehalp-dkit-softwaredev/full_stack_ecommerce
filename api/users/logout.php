<?php

require_once "../../php/configuration.php";

require '../../vendor/autoload.php';
\Firebase\JWT\JWT::$leeway = 60;
use Auth0\SDK\Auth0;

$auth0 = new Auth0([
    'domain' => 'dev-44t0mog0.eu.auth0.com',
    'client_id' => 'hzLwly8pSwfEEJPBcJXtd8HLLS6eO0ZC',
    'client_secret' => 'oUbeVZiuepsh92ldnjHHPAuEaI2WDEjDUM7aXAN-vcONJlRZ9T5SrB-SQUwiA8Rr',
    'redirect_uri' => $siteName . '/index.php',
    'persist_id_token' => true,
    'persist_access_token' => true,
    'persist_refresh_token' => true,
        ]);
$auth0->logout();
$return_to = $siteName . '/index.php';
$logout_url = sprintf('http://%s/v2/logout?client_id=%s&returnTo=%s', 'dev-44t0mog0.eu.auth0.com', 'hzLwly8pSwfEEJPBcJXtd8HLLS6eO0ZC', $return_to);
header('Location: ' . $logout_url);
die();
