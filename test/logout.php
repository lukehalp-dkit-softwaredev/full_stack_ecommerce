<?php

require '../vendor/autoload.php';

use Auth0\SDK\Auth0;

$auth0 = new Auth0([
    'domain' => 'dev-44t0mog0.eu.auth0.com',
    'client_id' => 'hzLwly8pSwfEEJPBcJXtd8HLLS6eO0ZC',
    'client_secret' => 'oUbeVZiuepsh92ldnjHHPAuEaI2WDEjDUM7aXAN-vcONJlRZ9T5SrB-SQUwiA8Rr',
    'redirect_uri' => 'http://localhost/FullStackWebDevY2S1_project2/test/login.php',
    'persist_id_token' => true,
    'persist_access_token' => true,
    'persist_refresh_token' => true,
        ]);
$auth0->logout();
$return_to = 'http://localhost/FullStackWebDevY2S1_project2/test/login.php';
$logout_url = sprintf('http://%s/v2/logout?client_id=%s&returnTo=%s', 'dev-44t0mog0.eu.auth0.com', 'hzLwly8pSwfEEJPBcJXtd8HLLS6eO0ZC', $return_to);
header('Location: ' . $logout_url);
die();
