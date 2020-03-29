<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/AadhaarData.php';
include_once '../../models/Admin.php';
include_once '../../models/Customer.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate blog post object
$aadhaarData = new AadhaarData($db);
$admin = new Admin($db);
$time = time();

$time2 = date("Y-m-d H:i:s", $time);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

$aadhaarData->cust_id = $data->cust_id;
$aadhaarData->machine_id = $data->machine_id;
$aadhaarData->station_id = $data->station_id;
$aadhaarData->machine_location = $data->machine_location;
$aadhaarData->new_enrollment = $data->new_enrollment;
$aadhaarData->mandatory_update = $data->mandatory_update;
$aadhaarData->normal_update = $data->normal_update;
//echo json_encode($data);
if (array_key_exists('addedon', $data) && $data->addedon != null) {
    $aadhaarData->addedon = $data->addedon;
} else
    $aadhaarData->addedon = $time2;

$day = date('l', strtotime($aadhaarData->addedon));
if ($day == 'Saturday' || $day == 'Sunday') {
    echo json_encode(array(
        "success" => false,
        "error" => "Data Can not be submitted for Weekends",
    ));
}
$aadhaarData->updatedon = $time2;


// Create Category

$check = $aadhaarData->read_last_by_date();
if (!$check['success']) {
    $result = $aadhaarData->create();

    if ($result) {
        echo json_encode($result);
    }
} else {
    echo json_encode(array(
        "success" => false,
        "error" => array_key_exists('addedon', $data) && $data->addedon != null ? "Data already submitted for selected date" : "Data already submitted for today",
    ));
}
//
