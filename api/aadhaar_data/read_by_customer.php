<?php

// Headers
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');


include_once '../../config/Database.php';
include_once '../../models/Customer.php';
include_once '../../models/AadhaarData.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate blog service object
$aadhaarData = new AadhaarData($db);
$customer = new Customer($db);


$postdata = json_decode(file_get_contents("php://input"));

$aadhaarData->cust_id = $postdata->cust_id;
$aadhaarData->start_date = $postdata->start_date;
$aadhaarData->end_date = $postdata->end_date;


$result = $aadhaarData->read_by_customer();

// Get row count
$num = $result->rowCount();
if ($num > 0) {
  $resp = array();
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $customer->cust_id = $cust_id;
    $customerRes = $customer->read_single();
    $selectedCustomer = $customerRes['data'];
    $row['customer'] = $selectedCustomer;
    array_push($resp, $row);
  }

  $response = array(
    "success" => true,
    "startDate" => $postdata->start_date,
    "endDate" => $postdata->end_date,
    "data" => $resp
  );
  echo json_encode($response);
} else {
  $response = array(
    "success" => false,
    "startDate" => $postdata->start_date,
    "endDate" => $postdata->end_date,
    "data" => null,
    "error" => "No Data Found"
  );
  echo json_encode($response);
}
