<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Customer.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();


$customer = new Customer($db);

$data = json_decode(file_get_contents("php://input"));

$customer->cust_phone = $data->phone;
$customer->cust_password = $data->password;
$customer->cust_newpassword = $data->new_password;

// Update post
$response = $customer->update_password();

echo json_encode($response);
