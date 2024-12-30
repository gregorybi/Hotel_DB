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
    <a href="./q1.php">Service Visits</a>
    <a class="active" href="#q2">Potential covid victims</a>
    <a href="./q3.php">Statistics</a>
    <a href="./q4.php">Covid victim</a>
    <a href="./v1.php">Service Sales</a>
    <a href="./v2.php">Customers Data</a>
  </div>
  <div>
    <div>
      <h2>Potential covid victims</h2>
      <form action="./q2.php" method="get">
        <p>Enter the desired nfc bracelet id:</p><br>
        <input type="number" name="nfc_id" required>
        <input type="submit" value="submit">
      </form>
    </div>
    <div>
      <?php
        if(isset($_GET['nfc_id'])) {
          $conn = new mysqli($servername, $username, $password, $database);
          if($conn->connect_error) {
            die("Connection to Database failed".$conn->connect_error);
          }
          $result = $conn->query("SELECT DISTINCT v2.nfc_id as potential, first_name, last_name FROM visit  v1, visit  v2, customers 
                                  WHERE v1.nfc_id = ".$_GET['nfc_id']." AND v2.nfc_id = customers.nfc_id 
                                  AND
                                  (
                                    (v2.date_of_entrance BETWEEN v1.date_of_entrance AND DATE_ADD(v1.date_of_exit, INTERVAL 1 HOUR))
                                    OR
                                    (v2.date_of_exit BETWEEN v1.date_of_entrance AND DATE_ADD(v1.date_of_exit, INTERVAL 1 HOUR))
                                    OR
                                    (v1.date_of_entrance BETWEEN v2.date_of_entrance AND v2.date_of_exit)
                                  )
                                  AND v2.nfc_id!=v1.nfc_id");
          
          echo "<table><tr><th>Potential Victims</th></tr>";

          echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>nfc id</th>";
                                        echo "<th>first name</th>";
                                        echo "<th>last name</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";          
          

          while(!empty($result) && $row = $result->fetch_array()) {
            echo "<tr>";
              echo "<td>" . $row['potential'] . "</td>";
              echo "<td>" . $row['first_name'] . "</td>";
              echo "<td>" . $row['last_name'] . "</td>";
              echo "</td>";
          echo "</tr>";
          }
          echo "</table>";
          $conn->close();
        }
      ?>
    </div>
  </div>
</body>




</html>