<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Order.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();


$order = new Order($db);

$data = json_decode(file_get_contents("php://input"));

$order->id = $data->id;
$order->status = $data->status;
$order->updatedon = time();

// Update post
$response = $order->updateStatus();


echo json_encode(
    array(
    "success" => $response
  )
);
