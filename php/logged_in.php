<?php

    require_once "configuration.php";

    require '../vendor/autoload.php';
    \Firebase\JWT\JWT::$leeway = 60;
    use Auth0\SDK\Auth0;

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

    /* Connect to the database */
    $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

    $user_id = $userInfo['sub'];

    /* Perform Query */
    $query = "SELECT user_id FROM users WHERE user_id = :user_id";
    $statement = $dbConnection->prepare($query);
    $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $statement->execute();
    if ($statement->rowCount() == 0) {
        $query = "INSERT INTO users(user_id) VALUES (:user_id)";
        $statement = $dbConnection->prepare($query);
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_STR);
        $statement->execute();
        
    header('location: ' . $siteName . '/profile_settings.php');
    die();
//        $order_id = $snowflake->id();
//        echo $order_id;
//        $query = "INSERT INTO orders(order_id, user_id) VALUES (:order_id, :user_id);";
//        $statement = $dbConnection->prepare($query);
//        $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT);
//        $statement->bindParam(":user_id", $user_id, PDO::PARAM_STR);
//        $statement->execute();
    }

    header('Location: ' . $siteName . '/index.php');

?>