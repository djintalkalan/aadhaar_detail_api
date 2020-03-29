<?php
class Customer
{
  // DB Stuff
  private $conn;
  private $table = 'tbl_customer';

  // Properties
  public $cust_id;
  public $cust_name;
  public $cust_email;
  public $pec_address;
  public $cust_phone;
  public $cust_password;
  public $cust_newpassword;
  public $machine_id;
  public $machine_location;
  public $station_id;
  public $addedon;
  public $updatedon;


  // Constructor with DB
  public function __construct($db)
  {
    $this->conn = $db;
  }

  // Get categories
  public function read()
  {
    // Create query
    $query = 'SELECT *  FROM ' . $this->table;

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Execute query
    $stmt->execute();

    $num = $stmt->rowCount();
    if ($num > 0) {
      $customer_array = array();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $row['cust_password'] = null;
        array_push($customer_array, $row);
      }

      return $customer_array;
    }
  }


  public function login()
  {
    // Create query
    $query = 'SELECT cust_phone FROM ' . $this->table . ' 
       WHERE cust_phone = ? ';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->cust_phone);

    // Execute query
    $stmt->execute();


    // Get row count
    $num = $stmt->rowCount();

    // Check if any categories
    if ($num > 0) {

      $query = 'SELECT * FROM ' . $this->table . ' 
              WHERE cust_phone = ? and cust_password = ? ';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $this->cust_phone);
      $stmt->bindParam(2,  $this->cust_password);


      // Execute query
      $stmt->execute();

      // Get row count
      $num = $stmt->rowCount();

      if ($num > 0) {
        // Cat array
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $customer = array(
          'cust_id' => $cust_id,
          'cust_name' => $cust_name,
          'cust_email' => $cust_email,
          'pec_address' => $pec_address,
          'cust_phone' => $cust_phone,
          'machine_id' => $machine_id,
          'machine_location' => $machine_location,
          'station_id' => $station_id,
          'addedon' => $addedon,
          'updatedon' => $updatedon
        );
        // Push to "data"

        return array(
          "data" => $customer,
          "success" => true
        );
      } else {
        return array(
          "error" => "Wrong Password",
          "data" => null,
          "success" => false
        );
      }
    } else  return array(
      "error" => "User not Registered",
      "data" => null,
      "success" => false
    );
  }

  // Get Single Category
  public function read_single()
  {
    // Create query
    $query = 'SELECT * FROM ' . $this->table . ' 
       WHERE cust_id = ? LIMIT 0,1';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->cust_id);

    // Execute query
    $stmt->execute();


    $num = $stmt->rowCount();

    if ($num == 1) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $customer = array(
        'cust_id' => $cust_id,
        'cust_name' => $cust_name,
        'cust_email' => $cust_email,
        'pec_address' => $pec_address,
        'cust_phone' => $cust_phone,
        'machine_id' => $machine_id,
        'machine_location' => $machine_location,
        'station_id' => $station_id,
        'addedon' => $addedon,
        'updatedon' => $updatedon
      );
      // Push to "data"

      return array(
        "data" => $customer,
        "success" => true
      );
    } else
      return array(
        "data" => null,
        "success" => false,
        "error" => "No data"
      );
  }




  // Create Category
  public function create()
  {
    // Create query
    $query = 'INSERT INTO ' . $this->table . ' 
    SET cust_name = :cust_name, 
  cust_email= :cust_email,
  pec_address= :pec_address,
  cust_phone = :cust_phone, 
  cust_password = :cust_password, 
  machine_id= :machine_id,
  machine_location= :machine_location,
  station_id= :station_id,
  addedon = :addedon,
  updatedon = :updatedon ';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->cust_name = htmlspecialchars(strip_tags($this->cust_name));
    $this->pec_address = htmlspecialchars(strip_tags($this->pec_address));
    $this->cust_phone = htmlspecialchars(strip_tags($this->cust_phone));
    $this->machine_id = htmlspecialchars(strip_tags($this->machine_id));

    // Bind data
    $stmt->bindParam(':cust_name', $this->cust_name);
    $stmt->bindParam(':cust_email', $this->cust_email);
    $stmt->bindParam(':pec_address', $this->pec_address);
    $stmt->bindParam(':cust_phone', $this->cust_phone);
    $stmt->bindParam(':cust_password', $this->cust_password);
    $stmt->bindParam(':machine_id', $this->machine_id);
    $stmt->bindParam(':machine_location', $this->machine_location);
    $stmt->bindParam(':station_id', $this->station_id);
    $stmt->bindParam(':addedon', $this->addedon);
    $stmt->bindParam(':updatedon', $this->updatedon);

    // Execute query
    if ($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);

    return false;
  }

  // Update Category
  public function update_password()
  {
    // Create query
    $query = 'SELECT cust_phone FROM ' . $this->table . ' 
     WHERE cust_phone = ? and cust_password = ? ';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->cust_phone);
    $stmt->bindParam(2, $this->cust_password);

    // Execute query
    $stmt->execute();


    // Get row count
    $num = $stmt->rowCount();

    // Check if any categories
    if ($num > 0) {
      // Create query
      $query = 'UPDATE ' . $this->table . '
    SET cust_password = :cust_newpassword 
     WHERE cust_phone = :cust_phone';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind data
      $stmt->bindParam(':cust_newpassword', $this->cust_newpassword);
      $stmt->bindParam(':cust_phone', $this->cust_phone);

      // Execute query
      if ($stmt->execute()) {
        return array(
          "error" => "",
          "success" => true
        );
      }
      return array(
        "error" => $stmt->error,
        "data" => null,
        "success" => true
      );
    } else  return array(
      "error" => "Wrong old password",
      "data" => null,
      "success" => false
    );
  }

  // Delete Category
  public function delete()
  {
    // Create query
    $query = 'DELETE FROM ' . $this->table . ' WHERE cust_id = :cust_id';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // clean data
    $this->cust_id = htmlspecialchars(strip_tags($this->cust_id));

    // Bind Data
    $stmt->bindParam(':cust_id', $this->cust_id);

    // Execute query
    if ($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);

    return false;
  }

  public function read_single_by_phone()
  {
    // Create query
    $query = 'SELECT * FROM ' . $this->table . ' 
       WHERE cust_phone = ? LIMIT 0,1';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->cust_phone);

    // Execute query
    $stmt->execute();


    $num = $stmt->rowCount();

    if ($num == 1) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $customer = array(
        'cust_id' => $cust_id,
        'cust_name' => $cust_name,
        'cust_email' => $cust_email,
        'pec_address' => $pec_address,
        'cust_phone' => $cust_phone,
        'machine_id' => $machine_id,
        'machine_location' => $machine_location,
        'station_id' => $station_id,
        'addedon' => $addedon,
        'updatedon' => $updatedon
      );
      // Push to "data"

      return array(
        "data" => $customer,
        "success" => true
      );
    } else
      return array(
        "data" => null,
        "success" => false,
        "error" => "No data"
      );
  }
}
