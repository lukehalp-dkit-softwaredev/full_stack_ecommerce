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
    'scope' => 'openid profile email',
        ]);

$auth0->login();

