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

$admin = new Admin($db);
$time = time();
// Get raw posted data
$time2 = date("Y-m-d H:i:s", $time);
$data = json_decode(file_get_contents("php://input"));


$admin->admin_name = $data->admin_name;
$admin->admin_email = $data->admin_email;
$admin->admin_phone = $data->admin_phone;
$admin->admin_password = $data->admin_password;
$admin->admin_type = $data->admin_type;
$admin->addedon = $time2;
$admin->updatedon = $time2;


$result = $admin->read_single_by_entity('admin_phone', $data->admin_phone);



if (!$result["success"]) {
  // Create Category
  if ($admin->create()) {
    // Get post

    echo json_encode(
      array(
        "success" => true,
      )
    );
    // Make JSON
  } else {
    echo json_encode(
      array(
        'error' => 'Server Connection Failed',
        "success" => false
      )
    );
  }
} else {
  echo json_encode(
    array(
      'error' => 'An account associated with this mobile number is already present',
      "success" => false
    )
  );
}
