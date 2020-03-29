<?php

  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Admin.php';

  $database = new Database();
  $db = $database->connect();

  $admin = new Admin($db);

  $data = json_decode(file_get_contents("php://input"));

  $admin->admin_phone = $data->admin_phone;
  $admin->admin_password = $data->admin_password;
  



  // Get post
  $result= $admin->login();

  print_r(json_encode($result));
  // Make JSON
