<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Customer.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();
// Instantiate blog admin object
$customer = new Customer($db);


// Get post
$result = $customer->read();

print_r(json_encode($result));
  // Make JSON
