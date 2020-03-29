<?php
date_default_timezone_set('Asia/Calcutta');

include_once('../models/CallApi.php');
include_once('../utils/utils.php');

if (isTheseParametersAvailable(array('cust_id', 'start_date', 'end_date'))) {


    // $cust_id = $_POST['cust_id'];
    // $start_date = $_POST['start_date'];
    // $end_date = $_POST['end_date'];
    $cust_id = 1;
    $start_date = "2020-02-26";
    $end_date = "2020-03-29";

    $total1 = 1;
    $total2 = 1;

    $data_array = array(
        "cust_id" => $cust_id,
        "start_date" => $start_date,
        "end_date" => $end_date
    );

    $make_call = callAPI('POST', 'http://localhost/aadhaar_project_api/api/aadhaar_data/read_by_customer.php', json_encode($data_array));
    $response = json_decode($make_call, true);
    $success     = $response['success'];
    $start_date = $response['startDate'];
    $end_ate = $response['endDate'];
    if ($success) {
        $unfilteredData = add_total_field($response['data']);
        $totalMend = $unfilteredData['totalMend'];
        $totalNew = $unfilteredData['totalNew'];
        $totalNormal = $unfilteredData['totalNormal'];
        $grossTotal = $unfilteredData['grossTotal'];
        $data     = add_missing_dates($unfilteredData['data']);

        // $data     = $response['data'];
    } else $error   = $response['error'];
} else die("Bye");

function isTheseParametersAvailable($params)
{

    foreach ($params as $param) {
        if (!isset($_POST[$param]) && !isset($_GET[$param])) {
            return false;
        }
    }
    return true;
}
?>
<!DOCTYPE html>
<html>

<head>
    <style>
        #dataTable {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #dataTable td,
        #dataTable th {
            border: 1px solid #ddd;
            padding: 8px;
            min-width: 150px;
        }

        #dataTable tr:nth-child(odd) {
            background-color: #Fe104d30;
        }

        #dataTable tr:hover {
            background-color: #ddd;
        }

        #dataTable th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #Fe104d;
            color: white;
        }
    </style>
</head>

<body>

    <?php
    if (!$success) {
        echo ($error);
        die();
    }
    ?>
    <div style="margin-right:20px">

        <table id="dataTable">
            <tr style="border-top: #0074d9 solid 1px;border-left: #0074d9 solid 1px;border-right: #0074d9 solid 1px">
                <th colspan="8" style="text-align: center;border: 0px;background-color:#fff;color:#0074d9 ">DITS Aadhaar Data</th>
            </tr>
            <tr style="border-left: #0074d9 solid 1px;border-right: #0074d9 solid 1px">
                <th colspan="2" style="text-align: center; border:0px;border-bottom:2px solid #ddd;background-color:#fff;color:#0074d9 ">Operator:- <?php echo $data[0]['customer']['cust_name'] ?> </th>
                <th colspan="4" style=" text-align: center;border: 0px ;border-bottom:2px solid #ddd;background-color:#fff;color:#0074d9 ">Mobile:- &nbsp;<?php echo $data[0]['customer']['cust_phone'] ?></th>
                <th colspan="2" style="text-align: center ;border: 0px ;border-bottom:2px solid #ddd;background-color:#fff;color:#0074d9 ">From <?php echo date_format(date_create($start_date), 'd M Y') . " to " . date_format(date_create($end_date), 'd M Y') ?></th>
            </tr>



            <tr>
                <th style=" border-left: 0px">Date</th>
                <!-- <th>Customer Name</th>
            <th>Mobile</th> -->
                <th>Mandatory Update</th>
                <th>New Enrollment</th>
                <th>Normal Update</th>
                <th>Total</th>
                <th>Machine Id</th>
                <th>Station Id</th>
                <th style="border-right: 0px;min-width:250px">Machine Location</th>
            </tr>
            <?php
            $i = 0;
            while ($i < sizeof($data)) {

                $date = date_create($data[$i]['addedon']);
                $day = date('l', strtotime($data[$i]['addedon']));
                // echo $day;

                if ($day == 'Saturday' || $day == 'Sunday') {
                    // echo "<tr>
                    //   <td>" . date_format($date, 'd M Y') . "</td>

                    //   <td colspan='6' style=\"text-align:center\">" . $day . " </td>
                    //   </tr>";
                } else
                    echo "<tr>
                  <td>" . date_format($date, 'd M Y') . "</td>
                
                  <td>" . $data[$i]['mandatory_update'] . " </td>
                  <td>" . $data[$i]['new_enrollment'] . "</td>
                  <td>" . $data[$i]['normal_update'] . "</td>
                  <td>" . $data[$i]['total'] . "</td>
                  <td>" . $data[$i]['machine_id'] . "</td>
                  <td>" . $data[$i]['station_id'] . "</td>
                  <td>" . $data[$i]['machine_location'] . "</td>
                  
                  </tr>";
                $i++;
            }
            echo " <tr style='background-color:white;border-top: 5px solid #fff'>
                  <td style='font-weight: 900'>Gross Total</td>
                  <td style='font-weight: 900'>" . $totalMend . " </td>
                  <td style='font-weight: 900'>" . $totalNew . "</td>
                  <td style='font-weight: 900'>" . $totalNormal . "</td>
                  <td style='font-weight: 900'>" . $grossTotal . " </td>
                  <td style='font-weight: 900'> </td> <td></td><td> </td>";
            ?>
        </table>


        <?php echo "<table id=\"dataTable\"> <tr>
        
                
                  </tr></table>"; ?>
    </div>
</body>

</html>