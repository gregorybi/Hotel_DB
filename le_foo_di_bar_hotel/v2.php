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
    <a href="./q4.php">Covid victim</a>
    <a href="./v1.php">Service Sales</a>
    <a class="active" href="#v2">Customers Data</a>
  </div>
  <div>
    <div>
      <h2>Customers Data</h2>
 
    </div>
    <div>
      <?php
        if(true) {
          $conn = new mysqli($servername, $username, $password, $database);
          if($conn->connect_error) {
            die("Connection to Database failed".$conn->connect_error);
          }
          $result = $conn->query("SELECT *  
                                  FROM customers_data
                                  ");
          
          
          
          echo "<table><tr><th>Customers Data</th></tr>";
          echo "<table class='table table-bordered table-striped'>";
          echo "<thead>";
              echo "<tr>";
                  echo "<th>first name</th>";
                  echo "<th>last name</th>";
                  echo "<th>date of birth</th>";
                  echo "<th>id document number</th>";
                  echo "<th>department of id document</th>";
              echo "</tr>";
          echo "</thead>";
          echo "<tbody>";
          
          
          while(!empty($result) && $row = $result->fetch_array()) {
            echo "<tr>";
            echo "<td>".$row['first_name']."</td>";
            echo "<td>" . $row['last_name'] . "</td>";
            echo "<td>" . $row['date_of_birth'] . "</td>";
            echo "<td>" . $row['id_doc_nr'] . "</td>";
            echo "<td>" . $row['dep_of_id_doc'] . "</td>";
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