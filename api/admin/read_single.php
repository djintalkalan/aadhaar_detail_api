<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Admin.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();
// Instantiate blog admin object
$admin = new Admin($db);

// Get ID
$admin->admin_id = isset($_GET['admin_id']) ? $_GET['admin_id'] : die();

// Get post
$result = $admin->read_single();

print_r(json_encode($result));
  // Make JSON
