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
    <a href="./q2.php">Potential covid victims</a>
    <a href="./q3.php">Statistics</a>
    <a class="active" href="#q4">Covid victim</a>
    <a href="./v1.php">Service Sales</a>
    <a href="./v2.php">Customers Data</a>
  </div>
  <div>
    <div>
      <h2>Covid Victim</h2>
      <form action="./q4.php" method="get">
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
          $result = $conn->query("SELECT room_id,description,date_of_entrance,date_of_exit  
                                  FROM customers_location
                                  WHERE nfc_id = ".$_GET['nfc_id']." ");
          
          
          
          echo "<table><tr><th>Rooms used</th></tr>";
          echo "<table class='table table-bordered table-striped'>";
          echo "<thead>";
              echo "<tr>";
                  echo "<th>room id</th>";
                  echo "<th>description</th>";
                  echo "<th>date of entrance</th>";
                  echo "<th>date of exit</th>";
              echo "</tr>";
          echo "</thead>";
          echo "<tbody>";
          
          
          while(!empty($result) && $row = $result->fetch_array()) {
            echo "<tr>";
            echo "<td>".$row['room_id']."</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>".$row['date_of_entrance']."</td>";
            echo "<td>" . $row['date_of_exit'] . "</td>";
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