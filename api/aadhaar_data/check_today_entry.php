<?php

// Headers
// Headers

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once '../../config/Database.php';
include_once '../../models/Customer.php';
include_once '../../models/AadhaarData.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate blog service object
$aadhaarData = new AadhaarData($db);
$customer = new Customer($db);

$result = $aadhaarData->check_last();
