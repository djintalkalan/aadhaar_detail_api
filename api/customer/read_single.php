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

// Get ID
$customer->cust_id = isset($_GET['cust_id']) ? $_GET['cust_id'] : die();

// Get post
$result = $customer->read_single();

print_r(json_encode($result));
  // Make JSON
