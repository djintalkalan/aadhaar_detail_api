<?php
class Admin
{
  // DB Stuff
  private $conn;
  private $table = 'tbl_admin';

  // Properties
  public $admin_id;
  public $admin_email;
  public $admin_name;
  public $admin_phone;
  public $admin_password;
  public $admin_newpassword;
  public $fcm_key;
  public $admin_type;
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

    return $stmt;
  }


  public function login()
  {
    // Create query
    $query = 'SELECT admin_phone FROM ' . $this->table . ' 
       WHERE admin_phone = ? ';


    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->admin_phone);

    // Execute query
    $stmt->execute();


    // Get row count
    $num = $stmt->rowCount();

    // Check if any categories
    if ($num > 0) {

      $query = 'SELECT * FROM ' . $this->table . ' 
              WHERE admin_phone = ? and admin_password = ? ';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $this->admin_phone);
      $stmt->bindParam(2,  $this->admin_password);


      // Execute query
      $stmt->execute();

      // Get row count
      $num = $stmt->rowCount();

      if ($num > 0) {
        // Cat array
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);

        $admin = array(
          'admin_id' => $admin_id,
          'admin_name' => $admin_name,
          'admin_email' => $admin_email,
          'admin_phone' => $admin_phone,
          'admin_type' => $admin_type,
          'addedon' => $addedon,
          'updatedon' => $updatedon
        );
        return array(
          "data" => $admin,
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
  public function read_fcmToken()
  {
    $query = 'SELECT fcm_key  FROM ' . $this->table;

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Execute query
    $stmt->execute();


    return $stmt;
  }
  public function read_single()
  {
    // Create query
    $query = 'SELECT * FROM ' . $this->table . ' 
       WHERE admin_id = ? LIMIT 0,1';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->admin_id);

    // Execute query
    $stmt->execute();


    $num = $stmt->rowCount();

    if ($num == 1) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $admin = array(
        'admin_id' => $admin_id,
        'admin_name' => $admin_name,
        'admin_email' => $admin_email,
        'admin_phone' => $admin_phone,
        'admin_type' => $admin_type,
        'addedon' => $addedon,
        'updatedon' => $updatedon
      );
      // Push to "data"

      return array(
        "data" => $admin,
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
    SET admin_name = :admin_name, 
   admin_email=:admin_email,
  admin_phone = :admin_phone, 
  admin_password = :admin_password,
  admin_type = :admin_type,
  addedon = :addedon,
  updatedon = :updatedon ';
    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->admin_name = htmlspecialchars(strip_tags($this->admin_name));
    $this->admin_phone = htmlspecialchars(strip_tags($this->admin_phone));
    $this->admin_type = htmlspecialchars(strip_tags($this->admin_type));

    // Bind data
    $stmt->bindParam(':admin_name', $this->admin_name);
    $stmt->bindParam(':admin_email', $this->admin_email);
    $stmt->bindParam(':admin_phone', $this->admin_phone);
    $stmt->bindParam(':admin_password', $this->admin_password);
    $stmt->bindParam(':admin_type', $this->admin_type);
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
    $query = 'SELECT admin_phone FROM ' . $this->table . ' 
     WHERE admin_phone = ? and admin_password = ? ';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->admin_phone);
    $stmt->bindParam(2, $this->admin_password);

    // Execute query
    $stmt->execute();


    // Get row count
    $num = $stmt->rowCount();

    // Check if any categories
    if ($num > 0) {
      // Create query
      $query = 'UPDATE ' . $this->table . '
    SET admin_password = :admin_newpassword 
     WHERE admin_phone = :admin_phone';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind data
      $stmt->bindParam(':admin_newpassword', $this->admin_newpassword);
      $stmt->bindParam(':admin_phone', $this->admin_phone);

      // Execute query
      if ($stmt->execute()) {
        return array(
          "error" => null,
          "data" => "Successfully updated",
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
    $query = 'DELETE FROM ' . $this->table . ' WHERE admin_id = :admin_id';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // clean data
    $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));

    // Bind Data
    $stmt->bindParam(':admin_id', $this->admin_id);

    // Execute query
    if ($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);

    return false;
  }

  public function read_single_by_entity($entity, $value)
  {
    // Create query
    $query = 'SELECT * FROM ' . $this->table . ' 
       WHERE ' . $entity . ' = ? LIMIT 0,1';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind

    $stmt->bindParam(1, $value);

    // Execute query
    $stmt->execute();


    $num = $stmt->rowCount();

    if ($num == 1) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $admin = array(
        'admin_id' => $admin_id,
        'admin_name' => $admin_name,
        'admin_email' => $admin_email,
        'admin_phone' => $admin_phone,
        'admin_type' => $admin_type,
        'addedon' => $addedon,
        'updatedon' => $updatedon
      );

      // Push to "data"

      return array(
        "data" => $admin,
        "success" => true
      );
    } else
      return array(
        "data" => null,
        "success" => false,
        "error" => "No data"
      );
  }

  public function setFCM()
  {

    // Create query
    $query = 'UPDATE ' . $this->table . '
    SET fcm_key = :fcm_key 
     WHERE admin_id = :admin_id';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind data
    $stmt->bindParam(':fcm_key', $this->fcm_key);
    $stmt->bindParam(':admin_id', $this->admin_id);

    // Execute query
    if ($stmt->execute()) {
      return array(
        "error" => "",
        "data" => "Successfully updated",
        "success" => true
      );
    }
  }
}
