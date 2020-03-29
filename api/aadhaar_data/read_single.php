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


// Get ID
$aadhaarData->id = isset($_GET['id']) ? $_GET['id'] : die();


$result = $aadhaarData->read_single();

// Get row count
$num = $result->rowCount();
if ($num > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    extract($row);
    $customer->cust_id = $cust_id;
    $customerRes = $customer->read_single();
    $selectedCustomer = $customerRes['data'];
    $row['customer'] = $selectedCustomer;


    $response = array(
        "success" => true,
        "data" => $row
    );
    echo json_encode($response);
} else {
    $response = array(
        "success" => false,
        "data" => null,
        "error" => "No Data Found"
    );
    echo json_encode($response);
}
