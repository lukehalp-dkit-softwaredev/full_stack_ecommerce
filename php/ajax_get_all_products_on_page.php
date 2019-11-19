<?php

    $product_response = new stdClass();
    $default_page = 0;
    $default_pageLimit = 12; //items per page
    $query = "SELECT product_id, name, unit_price, image_url, stock FROM products WHERE 1=1 ";
    $page = filter_input(INPUT_GET, "pagenumber", FILTER_SANITIZE_NUMBER_INT);
    if ($page < 0) {
        $page = $default_page;
    }
    if (empty($page)) {
        $page = $default_page;
    }
    $pageLimit = filter_input(INPUT_GET, "pagelimit", FILTER_SANITIZE_NUMBER_INT);
    if ($pageLimit != $default_pageLimit && $pageLimit != 27 && $pageLimit != 57) {
        $pageLimit = $default_pageLimit;
    }
    if (empty($pageLimit)) {
        $pageLimit = $default_pageLimit;
    }
    $name = trim(filter_input(INPUT_GET, "name", FILTER_SANITIZE_STRING));
    if ($name != null and $name != False) {
        $query .= "AND name LIKE :name ";
    }
    $minprice = filter_input(INPUT_GET, "minprice", FILTER_SANITIZE_NUMBER_FLOAT);
    if ($minprice != null and $minprice != False) {
        $query .= "AND unit_price > :minprice ";
    }
    $maxprice = filter_input(INPUT_GET, "maxprice", FILTER_SANITIZE_NUMBER_FLOAT);
    if ($maxprice != null and $maxprice != False) {
        $query .= "AND unit_price < :maxprice ";
    }
    $sorting = trim(filter_input(INPUT_GET, "sorting", FILTER_SANITIZE_STRING));
    strtolower($sorting);
    if (!empty($sorting)) {
        if ($sorting == "priceasc") {
            $query .= "ORDER BY unit_price ASC ";
        } else if ($sorting == "pricedesc") {
            $query .= "ORDER BY unit_price DESC ";
        }
    }
    //pagestart is exclusive
    $pageStart = $page * $pageLimit;
    require_once "configuration.php";
    $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query .= " LIMIT :pageStart,:pageLimit";
    $statement = $dbConnection->prepare($query);
    if ($name != null and $name != False) {
        $name = '%' . $name . '%';
        $statement->bindParam(":name", $name, PDO::PARAM_STR);
    }
    if ($minprice != null and $minprice != False) {
        $statement->bindParam(":minprice", strval($minprice), PDO::PARAM_STR);
    }
    if ($maxprice != null and $maxprice != False) {
        $statement->bindParam(":maxprice", strval($maxprice), PDO::PARAM_STR);
    }
    $statement->bindParam(":pageStart", $pageStart, PDO::PARAM_INT);
    $statement->bindParam(":pageLimit", $pageLimit, PDO::PARAM_INT);
    $statement->execute();
    $product_response->apiVersion = "1.0";
    if ($statement->rowCount() > 0) {
        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        foreach ($result as $row) {
            $product_response->data->products[] = $row;
        }
    //    $product_response->data->products = $result;
        $query = "SELECT COUNT(*) AS count FROM products";
        $statement = $dbConnection->prepare($query);
        $statement->execute();
        $product_response->data->prod_count = $statement->fetch(PDO::FETCH_OBJ);
    } else {
        $product_response->data->products[] = $statement->fetch(PDO::FETCH_OBJ);
    }
    echo json_encode($product_response);
