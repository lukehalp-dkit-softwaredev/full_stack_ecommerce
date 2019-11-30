<?php

require_once "../../php/configuration.php";

/* Connect to the database */
$dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

require '../../vendor/autoload.php';
\Firebase\JWT\JWT::$leeway = 60;

use Auth0\SDK\Auth0;

$auth0 = new Auth0([
    'domain' => 'dev-44t0mog0.eu.auth0.com',
    'client_id' => 'hzLwly8pSwfEEJPBcJXtd8HLLS6eO0ZC',
    'client_secret' => 'oUbeVZiuepsh92ldnjHHPAuEaI2WDEjDUM7aXAN-vcONJlRZ9T5SrB-SQUwiA8Rr',
    'redirect_uri' => $siteName . '/category.php',
    'persist_id_token' => true,
    'persist_access_token' => true,
    'persist_refresh_token' => true,
        ]);
$userInfo = $auth0->getUser();

$whole_response = new stdClass();
$whole_response->apiVersion = "1.0";
$error = new stdClass();
if ($userInfo) {
    $minecraft_username = trim(filter_input(INPUT_GET, "nickname", FILTER_SANITIZE_STRING));
    if (empty($minecraft_username)) {
        $error->code = 400;
        $error->msg = "No minecraft username supplied";
        $whole_response->error = $error;
    } else {# Our new data
        $data = array(
            $minecraft_username,
            'nonExistingPlayer'
        );
# Create a connection
        $url = 'https://api.mojang.com/profiles/minecraft';
        $ch = curl_init($url);
# Setting our options
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
# Get the response
        $response = curl_exec($ch);
        $response = json_decode($response);
        curl_close($ch);
        $whole_response->test = $response;
        if (empty($response) || sizeOf($response) < 1) {
            //error username doesn't exist
            $error->code = 404;
            $error->msg = "Such minecraft username doesn't exist or unable to connect to mojang.";
            $whole_response->error = $error;
        } else {
            $query = "SELECT * FROM users WHERE mc_username = :mc_username";
            $statement = $dbConnection->prepare($query);
            $statement->bindParam(":mc_username", $response["name"], PDO::PARAM_STR);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                //error username taken by someone else
                $error->code = 403;
                $error->msg = "Another account already has this minecraft username set.";
                $whole_response->error = $error;
            } else {
                $data = new stdClass();
                $data->nickname = $minecraft_username;
                # Create a connection
                $url = 'https://dev-44t0mog0.eu.auth0.com/api/v2/users/' . $userInfo['sub'];
                $ch = curl_init($url);
                # Setting our options
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $management_api_token, "Content-Type: application/json"));
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH'); //this update request requires using patch
                # Get the response
                $response2 = curl_exec($ch);
                $response2 = json_decode($response2);
                curl_close($ch);
                if (empty($response2->created_at)) {
                    $error->code = 409;
                    $error->msg = "Failed to update minecraft username, possible problem connecting to the authentication provider";
                    $whole_response->error = $error;
                } else {
                    //username exists so check if the logged in user is in the db
                    $query = "SELECT mc_username FROM users WHERE user_id = :user_id";
                    $statement = $dbConnection->prepare($query);
                    $statement->bindParam(":user_id", $userInfo["sub"], PDO::PARAM_STR);
                    $statement->execute();
                    if ($statement->rowCount() > 0) {
                        //user exists so need to update
                        $results = $statement->fetch(PDO::FETCH_OBJ);
                        if (strcasecmp($results->mc_username, $response[0]->name)) {
                            $query = "UPDATE users SET mc_username = :mc_username, mc_uuid = :mc_uuid WHERE user_id = :user_id";
                            $statement = $dbConnection->prepare($query);
                            $statement->bindParam(":mc_username", $response[0]->name, PDO::PARAM_STR);
                            $statement->bindParam(":mc_uuid", $response[0]->id, PDO::PARAM_STR);
                            $statement->bindParam(":user_id", $userInfo["sub"], PDO::PARAM_STR);
                            $statement->execute();
//                            $_SESSION['nickname'] = $minecraft_username;
                        } else {
                            //user is trying to set the same mc username that they already have.
                            $error->code = 403;
                            $error->msg = "Trying to set the same minecraft username as already set.";
                            $whole_response->error = $error;
                        }
                    } else {
                        //user doesn't exist so need to add
                        $query = "INSERT INTO users VALUES(:user_id, :mc_username, :mc_uuid)";
                        $statement = $dbConnection->prepare($query);
                        $statement->bindParam(":mc_username", $response[0]->name, PDO::PARAM_STR);
                        $statement->bindParam(":mc_uuid", $response[0]->id, PDO::PARAM_STR);
                        $statement->bindParam(":user_id", $userInfo["sub"], PDO::PARAM_STR);
                        $statement->execute();
//                        $_SESSION['nickname'] = $minecraft_username;
                    }
                }
            }
        }
    }
} else {
    //error user not logged in
    $error->code = 401;
    $error->msg = "User is not logged in, please login.";
    $whole_response->error = $error;
}
//session here is session on that page but not on the category page (I don't think).
echo json_encode($_SESSION);
echo json_encode($whole_response);






