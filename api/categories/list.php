<?php
    require_once "../../php/configuration.php";

    $response = new stdClass();

    $query = "SELECT category_id, name FROM categories;";

    /* Connect to the database */
    $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

    /* Perform Query */
    
    $statement = $dbConnection->prepare($query);
    $statement->execute();

    /* echo "<br>---------DEBUG--------<br>";
    echo $statement->rowCount();
    echo "<br>-------END DEBUG------<br><br><br>"; */

    if ($statement->rowCount() > 0) {
        $results = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->apiVersion = "1.0";
        $response->data = $results;
    } else {
        // Categories not found
        $error = new stdClass();
        $error->code = 404;
        $error->msg = "No categories found.";

        $response->apiVersion = "1.0";
        $response->error = $error;

        http_response_code(404);
    }

    echo json_encode($response);
?>