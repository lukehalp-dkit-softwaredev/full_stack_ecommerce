<?php

require_once "../../php/configuration.php";

/* Connect to the database */
$dbConnection = new PDO("mysql:host=$dbHost", $dbUsername, $dbPassword);
$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception



$minecraft_username = trim(filter_input(INPUT_POST, "nickname", FILTER_SANITIZE_STRING));
if (empty($minecraft_username)) {
    
}




# Our new data
$data = array(
    'cziuwatis',
    'nonExistingPlayer'
);
echo json_encode($data);
# Create a connection
$url = 'https://api.mojang.com/profiles/minecraft';
$ch = curl_init($url);
# Form data string
//$postString = http_build_query($data, '', '&');
//echo $postString;
# Setting our options
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
# Get the response
$response = curl_exec($ch);
curl_close($ch);
echo $response;



