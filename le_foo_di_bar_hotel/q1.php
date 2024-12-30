<!doctype html>

<html lang="en">

<style>
table, th, td {
  border: 1px solid black;
  width: 1000px;
}

</style>



<head>
  <meta charset="utf-8">
  <link rel='stylesheet' type='text/css' href='./styles.css'>
  <?php include 'funcs.php';?>
  <title>Hotel Big Brother</title>
</head>

<body>
  <div class="topnav">
    <a href="./index.php">Home</a>
    <a class="active" href="#q1">Service Visits</a>
    <a href="./q2.php">Potential covid victims</a>
    <a href="./q3.php">Statistics</a>
    <a href="./q4.php">Covid victim</a>
    <a href="./v1.php">Service Sales</a>
    <a href="./v2.php">Customers Data</a>
  </div>
  <div>
    <div>
      <?php
        $conn = new mysqli($servername, $username, $password, $database);
        if($conn->connect_error) {
          die("Connection to Database failed");
          echo "Connection to Database failed";
        }
        $result = $conn->query("SELECT service_id as ID, description as Service
                                FROM service
                                ORDER BY service_id");
        ?>
        
        <form action="./q1.php" method="get">
        <p>Select a service:</p>
        <?php
          while($row = $result->fetch_array()) {
            echo "<input type=\"radio\" name=\"service\""."id=\"".$row['Service']."\" value=\"".$row['ID']."\"/>";
            echo "<label for=\"".$row['Service']."\">".$row['Service']."</label>";
            echo "<br>";
          }
        ?>
        <label for="start">Start date:</label>
        <input type="date" id="start" name="start">
        <br>
        <label for="end">End date:</label>
        <input type="date" id="end" name="end">
        <br>
        <label for="low">Price low:</label><br>
        <input type="number" id="low" name="low" min="0">
        <br>
        <label for="high">Price high:</label><br>
        <input type="number" id="high" name="high" min="0">
        <br>
        <input type="submit" value="Submit">
        </form>
      </div>
      <div>
        <?php
          $any_param_set = false;
          $sql = "SELECT v.nfc_id, v.room_id, description, date_of_charge, cost
                  FROM visit v, reception_and_charge_of_service s, provided_into p
                  WHERE v.nfc_id = s.nfc_id and p.room_id = v.room_id and p.service_id = s.service_id AND s.date_of_charge=v.date_of_entrance ";
          foreach ($_GET as $key => $value) {
            if(!empty($value)) {
              $any_param_set = true;
              switch ($key) {
                case 'service':
                  $sql = $sql . " AND s.service_id=" . $value;
                 // $sql = $sql . " GROUP BY (v.nfc_id)" ;
                  break;
                case 'start':
                 //$value = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $value);
                  $value = date("Y-m-d",strtotime(str_replace('/','-',$value)));
                  $sql = $sql . " AND DATE(s.date_of_charge)>= '$value' ";
                  break;
                case 'end':
                  $sql = $sql . " AND DATE(s.date_of_charge)<= '$value' ";
                  //$sql = $sql . " GROUP BY (v.nfc_id)";
                  break;
                case 'low':
                  $sql = $sql . " AND s.cost>" . $value;
                  break;
                case 'high':
                  $sql = $sql . " AND s.cost<" . $value;
                  break;
              }
            }
          }
         $sql = $sql . " GROUP BY v.nfc_id,  s.date_of_charge, s.cost" ;

          if ($any_param_set) {
            $result = $conn->query($sql);
            //echo "<table><tr><th>nfc_id</th><th>room_id</th><th>service</th><th>date</th><th>cost</th></tr>";
            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>nfc_id</th>";
                                        echo "<th>room_id</th>";
                                        echo "<th>service</th>";
                                        echo "<th>date of visit</th>";
                                        echo "<th>cost</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
            while ($row = $result->fetch_array()) {
              echo "<tr>";
              echo "<td>" . $row['nfc_id'] . "</td>";
              echo "<td>" . $row['room_id'] . "</td>";
              echo "<td>" . $row['description'] . "</td>";
              echo "<td>" . $row['date_of_charge'] . "</td>";
              echo "<td>" . $row['cost'] . "</td>";
              //echo "<td>";
              echo "</td>";
          echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
          } else {
            echo "Please fill and submit form.";
          }
          
        ?>
      </div>
      <?php
        $conn->close();
      ?>
  </div>
</body>




</html>