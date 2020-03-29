<?php

  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Customer.php';

  $database = new Database();
  $db = $database->connect();

  $customer = new Customer($db);

  $data = json_decode(file_get_contents("php://input"));

  $customer->cust_phone = $data->cust_phone;
  $customer->cust_password = $data->cust_password;



  // Get post
  $result= $customer->login();

  print_r(json_encode($result));
  // Make JSON

  ?>

