<?php

$default_page = 1;
$default_pageLimit = 10; //items per page
$page = filter_input(INPUT_POST, "pageNumber", FILTER_SANITIZE_NUMBER_INT);
if (empty($page || $page < 0)) {
    $page = $default_page;
}
$pageLimit = filter_input(INPUT_POST, "pageLimit", FILTER_SANITIZE_NUMBER_INT);
//have check if one of the allowed values e.g 10,25,50
if (empty($pageLimit || !($pageLimit == $default_pageLimit || $pageLimit == 25 || $pageLimit == 50))) {
    $pageLimit = $default_pageLimit;
}

require_once "configuration.php";

$dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//pagestart is exclusive
$query = "SELECT id, name, unit_price, image, stock FROM products LIMIT :pageStart,:pageLimit;";
$statement = $dbConnection->prepare($query);
$statement->bindParam(":pageStart", ($page * $pageLimit), PDO::PARAM_INT);
$statement->bindParam(":pageLimit", $pageLimit, PDO::PARAM_INT);
$statement->execute();

$products = array();
if ($statement->rowCount() > 0) {
    $result = $statement->fetchAll(PDO::FETCH_OBJ);
    foreach ($result as $row) {
        $products[] = $row;
    }
    echo json . encode($products);
}