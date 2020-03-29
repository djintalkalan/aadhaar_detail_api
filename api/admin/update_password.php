<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Admin.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();


$admin = new admin($db);

$data = json_decode(file_get_contents("php://input"));

$admin->admin_phone = $data->phone;
$admin->admin_password = $data->password;
$admin->admin_newpassword = $data->new_password;

// Update post
$response = $admin->update_password();

echo json_encode($response);
