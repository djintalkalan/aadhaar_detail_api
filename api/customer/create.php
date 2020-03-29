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
$time = time();
// Get raw posted data
$time2 = date("Y-m-d H:i:s", $time);

$data = json_decode(file_get_contents("php://input"));

$customer->cust_name = $data->cust_name;
$customer->cust_email = $data->cust_email;
$customer->cust_password = $data->cust_password;
$customer->pec_address = $data->pec_address;
$customer->machine_id = $data->machine_id;
$customer->machine_location = $data->machine_location;
$customer->station_id = $data->station_id;
$customer->cust_phone = $data->cust_phone;
$customer->addedon = $time2;
$customer->updatedon = $time2;


$result = $customer->read_single_by_phone();



if (!$result["success"]) {
  // Create Category
  if ($customer->create()) {
    // Get post
    echo json_encode(
      array(
        "success" => true
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
