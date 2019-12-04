<?php
    require_once "../../php/configuration.php";

    require '../../vendor/autoload.php';
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
    $userInfo = $auth0->getUser();

    $response = new stdClass();

    if (isset($_GET['order'])) {
        $order_id = filter_input(INPUT_GET, "order", FILTER_SANITIZE_NUMBER_INT);
        
        /* Connect to the database */
        $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

        /* Perform Query */
        $query = "SELECT o.order_id, o.user_id, u.mc_username, o.date_created, o.date_ordered FROM orders o, users u WHERE o.user_id = u.user_id AND o.order_id = :order_id";
        $statement = $dbConnection->prepare($query);
        $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT);
        $statement->execute();

        /* echo "<br>---------DEBUG--------<br>";
        echo $statement->rowCount();
        echo "<br>-------END DEBUG------<br><br><br>"; */

        if ($statement->rowCount() == 1) {
            $result = $statement->fetch(PDO::FETCH_OBJ);

            if($userInfo['sub'] == $result->user_id) {
                /* Get items in order */
                $query = "SELECT p.product_id, ol.quantity, p.name, p.unit_price, p.description FROM order_lines ol, products p WHERE ol.product_id = p.product_id AND order_id = :order_id";
                $statement = $dbConnection->prepare($query);
                $statement->bindParam(":order_id", $result->order_id, PDO::PARAM_INT);
                $statement->execute();
                
                $result->items = $statement->fetchAll(PDO::FETCH_OBJ);

                $response->apiVersion = "1.0";
                $response->data = $result;
            } else {
                $error = new stdClass();
                $error->code = 403;
                $error->msg = "You do not have permission to access this resource.";

                $response->apiVersion = "1.0";
                $response->error = $error;

                http_response_code(403);
            }

            
        } else {
            // Order not found
            $error = new stdClass();
            $error->code = 404;
            $error->msg = "Order not found, check order id and try again.";

            $response->apiVersion = "1.0";
            $response->error = $error;

            http_response_code(404);
        }

        
    } else {
        // Order id not in url
        $error = new stdClass();
        $error->code = 400;
        $error->msg = "Malformed URL, please check url parameters and try again.";

        $response->apiVersion = "1.0";
        $response->error = $error;

        http_response_code(400);
    }

    echo json_encode($response);
?>