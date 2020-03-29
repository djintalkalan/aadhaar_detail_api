<?php
class AadhaarData
{
  // DB stuff
  private $conn;
  private $table = 'tbl_aadhaar_data';

  // Service Properties
  public $id;
  public $cust_id;
  public $machine_id;
  public $station_id;
  public $machine_location;
  public $new_enrollment;
  public $mandatory_update;
  public $normal_update;
  public $addedon;
  public $updatedon;
  public $start_date;
  public $end_date;

  // Constructor with DB
  public function __construct($db)
  {
    $this->conn = $db;
  }

  // Get Services
  public function read()
  {
    $time1 = $this->start_date . " 00:00:00";
    $time2 = $this->end_date . " 23:59:59";

    $query = 'SELECT * FROM ' . $this->table . ' WHERE addedon >= :time1 AND addedon <= :time2 ORDER BY addedon';

    $stmt = $this->conn->prepare($query);
    // Bind ID
    $stmt->bindParam(':time1', $time1);
    $stmt->bindParam(':time2', $time2);

    $stmt->execute();

    return $stmt;
    // Create query
  }

  // Get Single Service
  public function read_single()
  {

    // Create query
    $query = 'SELECT * FROM ' . $this->table . ' 
           WHERE id = ? LIMIT 0,1';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->id);

    // Execute query
    $stmt->execute();

    return $stmt;
  }

  public function read_by_customer()
  {

    $time1 = $this->start_date . " 00:00:00";
    $time2 = $this->end_date . " 23:59:59";

    $timeAdd = strtotime("+1 day", strtotime($time2));
    $timeAdd = date("Y-m-d", $timeAdd);

    $query = 'SELECT * FROM ' . $this->table . ' WHERE cust_id = :cust_id AND addedon >= :time1 AND addedon <= :time2 ORDER BY addedon';

    $stmt = $this->conn->prepare($query);
    // Bind ID
    $stmt->bindParam(':cust_id', $this->cust_id);
    $stmt->bindParam(':time1', $time1);
    $stmt->bindParam(':time2', $timeAdd);

    $stmt->execute();

    return $stmt;
    // $num = $stmt->rowCount();

    // if ($num == 1) {
    //   $data_array = array();
    //   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //     extract($row);
    //     array_push($data_array, $row);
    //   }
    //   return $data_array;
    // }
  }

  function check_last()
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE cust_id='.$cust_id.' ORDER BY addedon DESC LIMIT 0,1';

    $stmt = $this->conn->prepare($query);

    $stmt->execute();


    // Get row count
    $num = $stmt->rowCount();
    if ($num > 0) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $currentDate = date("Y-m-d", time());
      $lastDate =  date_format(date_create($addedon), 'Y-m-d');

      if ($currentDate == $lastDate) {
        $response = array(
          "success" => true,
          "data_submitted" => true
        );
      } else $response = array(
        "success" => true,
        "data_submitted" => false
      );
    } else {
      $response = array(
        "success" => false,
        "data" => null,
        "error" => "No Data Found"
      );
    }
    echo json_encode($response);
  }

  public function read_last_by_date()
  {
    $time1 = date_format(date_create($this->addedon), 'Y-m-d') . " 00:00:00";
    $time2 = date_format(date_create($this->addedon), 'Y-m-d') . " 23:59:59";

    $query = 'SELECT * FROM ' . $this->table . ' WHERE cust_id = :cust_id AND addedon >= :time1 AND addedon <= :time2 LIMIT 0,1';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(':cust_id', $cust_id);
    $stmt->bindParam(':time1', $time1);
    $stmt->bindParam(':time2', $time2);

    // Execute query
    $stmt->execute();


    $num = $stmt->rowCount();

    if ($num == 1) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      extract($row);
      // Push to "data"

      return array(
        "data" => $row,
        "success" => true
      );
    } else
      return array(
        "data" => null,
        "success" => false,
        "error" => "No data"
      );
  }
  // Create Service
  public function create()
  {
    // Create query
    $query = 'INSERT INTO ' . $this->table . ' 
      SET
      cust_id = :cust_id, 
      station_id = :station_id, 
      machine_id = :machine_id,
      machine_location = :machine_location,
      new_enrollment = :new_enrollment, 
      mandatory_update = :mandatory_update,
      normal_update = :normal_update,
      addedon = :addedon,
      updatedon = :updatedon ';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->mandatory_update = htmlspecialchars(strip_tags($this->mandatory_update));
    $this->normal_update = htmlspecialchars(strip_tags($this->normal_update));
    $this->addedon = htmlspecialchars(strip_tags($this->addedon));
    $this->updatedon = htmlspecialchars(strip_tags($this->updatedon));

    // Bind data
    $stmt->bindParam(':cust_id', $this->cust_id);
    $stmt->bindParam(':station_id', $this->station_id);
    $stmt->bindParam(':machine_id', $this->machine_id);
    $stmt->bindParam(':machine_location', $this->machine_location);
    $stmt->bindParam(':new_enrollment', $this->new_enrollment);
    $stmt->bindParam(':mandatory_update', $this->mandatory_update);
    $stmt->bindParam(':normal_update', $this->normal_update);
    $stmt->bindParam(':addedon', $this->addedon);
    $stmt->bindParam(':updatedon', $this->updatedon);

    // Execute query
    if ($stmt->execute()) {
      return array(
        "error" => null,
        "success" => true
      );
    }

    return array(
      "error" => $stmt->error,
      "data" => null,
      "success" => false
    );
  }
}
