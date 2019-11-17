<?php

$default_page = 0;
$default_pageLimit = 10; //items per page
$page = filter_input(INPUT_GET, "pageNumber", FILTER_SANITIZE_NUMBER_INT);
if ($page < 0) {
    $page = $default_page;
}
if (empty($page))
{
    $page = $default_page;
}
$pageLimit = filter_input(INPUT_GET, "pageLimit", FILTER_SANITIZE_NUMBER_INT);
if ($pageLimit === $default_pageLimit || $pageLimit === 25 || $pageLimit === 50) {
    $pageLimit = $default_pageLimit;
}
if (empty($pageLimit))
{
    $pageLimit = $default_pageLimit;
}
//pagestart is exclusive
$pageStart = $page * $pageLimit;
require_once "configuration.php";
$dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = "SELECT product_id, name, unit_price, image_url, stock FROM products LIMIT :pageStart,:pageLimit";
$statement = $dbConnection->prepare($query);
$statement->bindParam(":pageStart", $pageStart, PDO::PARAM_INT);
$statement->bindParam(":pageLimit", $pageLimit, PDO::PARAM_INT);
$statement->execute();
$product_response = array(array());
if ($statement->rowCount() > 0) {
    $result = $statement->fetchAll(PDO::FETCH_OBJ);
    foreach ($result as $row) {
        $product_response[0][] = $row;
    }
}
$query = "SELECT COUNT(*) AS product_count FROM products";
$statement = $dbConnection->prepare($query);
$statement->execute();
$product_response[1] = $statement->fetch(PDO::FETCH_OBJ);
echo json_encode($product_response);
