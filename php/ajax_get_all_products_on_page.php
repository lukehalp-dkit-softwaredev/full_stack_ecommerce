<?php

$response = new stdClass();
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
$category = filter_input(INPUT_GET, "category_id", FILTER_SANITIZE_NUMBER_INT);
if (!empty($category)) {
    $query .= "AND category_id = :category ";
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
if (!empty($category)) {
    $statement->bindParam(":category", $category, PDO::PARAM_INT);
}
$statement->bindParam(":pageStart", $pageStart, PDO::PARAM_INT);
$statement->bindParam(":pageLimit", $pageLimit, PDO::PARAM_INT);
$statement->execute();
$response->apiVersion = "1.0";
if ($statement->rowCount() > 0) {
    $result = $statement->fetchAll(PDO::FETCH_OBJ);
    foreach ($result as $row) {
        $response->data->products[] = $row;
    }
    //    $product_response->data->products = $result;
    $query = "SELECT COUNT(*) AS count FROM products";
    $statement = $dbConnection->prepare($query);
    $statement->execute();
    $response->data->prod_count = $statement->fetch(PDO::FETCH_OBJ);
} else {
    $response->data->products[] = $statement->fetch(PDO::FETCH_OBJ);
    $error = new stdClass();
    $error->code = 404;
    $error->msg = "No products found.";
    $response->error = $error;
}
//category id move to the product search
$query = "SELECT category_id, name AS category_name FROM categories";
$statement = $dbConnection->prepare($query);
$statement->execute();
if ($statement->rowCount() > 0) {
    $results = $statement->fetchAll(PDO::FETCH_OBJ);
    $response->data->categories = $results;
    $query = "SELECT COUNT(*) AS product_count FROM products GROUP BY category_id";
    $statement = $dbConnection->prepare($query);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $results = $statement->fetchAll(PDO::FETCH_OBJ);
        $response->data->categories_numbers = $result;
    } else {
        // Categories not found
        $error = new stdClass();
        $error->code = 404;
        $error->msg = "Something went wrong? Oops.";
        $response->error = $error;
        http_response_code(404);
    }
} else {
    // Categories not found
    $error = new stdClass();
    $error->code = 404;
    $error->msg = "No categories found.";
    $response->error = $error;
    http_response_code(404);
}
echo json_encode($response);
