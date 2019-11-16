<?php

/* Include "configuration.php" file */
require_once "configuration.php";

$dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

/* Create table */
$query = "INSERT INTO products VALUES "
        . "(0, \"KNOCKBACK DIAMOND SWORD\", \"A super awesome sword crafted from the best of the best sourcerer's stones called a diamond. Yes, a diamond is a sourcerer stone. Just for now. Yes, awesome description. Buy it now for 99.59$!!\", 99.59,\"img/product/p3.jpg\", 10),"
        . "(1, \"KNOCKBACK DIAMOND SWORD\", \"A super awesome sword crafted from the best of the best sourcerer's stones called a diamond. Yes, a diamond is a sourcerer stone. Just for now. Yes, awesome description. Buy it now for 99.59$!!\", 99.59,\"img/product/p3.jpg\", 10),"
        . "(2, \"KNOCKBACK DIAMOND SWORD\", \"A super awesome sword crafted from the best of the best sourcerer's stones called a diamond. Yes, a diamond is a sourcerer stone. Just for now. Yes, awesome description. Buy it now for 99.59$!!\", 99.59,\"img/product/p3.jpg\", 10),"
        . "(3, \"KNOCKBACK DIAMOND SWORD\", \"A super awesome sword crafted from the best of the best sourcerer's stones called a diamond. Yes, a diamond is a sourcerer stone. Just for now. Yes, awesome description. Buy it now for 99.59$!!\", 99.59,\"img/product/p3.jpg\", 10),"
        . "(4, \"KNOCKBACK DIAMOND SWORD\", \"A super awesome sword crafted from the best of the best sourcerer's stones called a diamond. Yes, a diamond is a sourcerer stone. Just for now. Yes, awesome description. Buy it now for 99.59$!!\", 99.59,\"img/product/p3.jpg\", 10),"
        . "(5, \"KNOCKBACK DIAMOND SWORD\", \"A super awesome sword crafted from the best of the best sourcerer's stones called a diamond. Yes, a diamond is a sourcerer stone. Just for now. Yes, awesome description. Buy it now for 99.59$!!\", 99.59,\"img/product/p3.jpg\", 10),"
        . "(6, \"KNOCKBACK DIAMOND SWORD\", \"A super awesome sword crafted from the best of the best sourcerer's stones called a diamond. Yes, a diamond is a sourcerer stone. Just for now. Yes, awesome description. Buy it now for 99.59$!!\", 99.59,\"img/product/p3.jpg\", 10)";
$statement = $dbConnection->prepare($query);
$statement->execute();

