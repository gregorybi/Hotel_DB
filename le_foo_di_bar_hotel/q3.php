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
    <a class="active" href="#q3">Statistics</a>
    <a href="./q4.php">Covid victim</a>
    <a href="./v1.php">Service Sales</a>
    <a href="./v2.php">Customers Data</a>
  </div>
  <div>
    <div>
      <form action="./q3.php", method="get">
      <p>Statistics</p>
      <br>
        <p>Choose a search target:</p>
        <select name="type" id="type">
          <option value="1">Most visited rooms</option>
          <option value="2">Most used services</option>
          <option value="3">Services with most customers</option>
        </select>
        <p>Select an age range:</p>
        <input type="radio" name="age" id="age" value="1" required>
        <label for="age">20-40</label><br>
        <input type="radio" name="age" id="age" value="2" required>
        <label for="age">41-60</label><br>
        <input type="radio" name="age" id="age" value="3" required>
        <label for="age">61+</label>
        <br>
        <p>Results for last:</p>
        <input type="radio" name="time" id="time" value="1" required>
        <label for="time">Month</label><br>
        <input type="radio" name="time" id="time" value="2" required>
        <label for="time">Year</label>
        <br><br>
        <input type="submit" value="submit">
      </form>
    </div>
    <div>
      <?php
        if(isset($_GET['type']) && isset($_GET['age']) && isset($_GET['time'])) {
          $conn = new mysqli($servername, $username, $password, $database);
          if($conn->connect_error) {
            die("Connection to Database failed".$conn->connect_error);
          }
          $sql = "SELECT l.description AS name ";
          switch ($_GET['type']) {
            case '1':
              $sql = $sql . ", COUNT(l.description) AS uses " ;
              $sql = $sql . "FROM customers_location l, customers c ";
              $tmp = " AND YEAR(l.date_of_entrance)=";
              $mon = " AND MONTH(l.date_of_entrance)=";
              $c1 = "Room";
              $c2 = "Uses";
              $sig = " GROUP BY l.description ORDER BY uses DESC LIMIT 2";
              break;
            case '2':
              $sql = $sql . ", COUNT(l.description) AS uses " ;
              $sql = $sql . "FROM reception_and_charge_of_service l, customers c ";
              $tmp = " AND YEAR(l.date_of_charge)=";
              $mon = " AND MONTH(l.date_of_charge)=";
              $c1 = "Service";
              $c2 = "Uses";
              $sig = " GROUP BY l.description ORDER BY uses DESC LIMIT 2";
              break;
            case '3':
              $sql = $sql . ", COUNT(DISTINCT (l.nfc_id)) AS uses " ;
              $sql = $sql . "FROM reception_and_charge_of_service l, customers c ";
              $tmp = " AND YEAR(l.date_of_charge)=";
              $mon = " AND MONTH(l.date_of_charge)=";
              $c1 = "Service";
              $c2 = "Customers";
              $sig =  " GROUP BY service_id ORDER BY uses DESC LIMIT 2";
              break;
          }
          $sql = $sql . "WHERE l.nfc_id = c.nfc_id AND YEAR(CURDATE())-YEAR(c.date_of_birth) ";
          switch ($_GET['age']) {
            case '1':
              $sql = $sql . "BETWEEN 20 AND 40";
              break;
            case '2':
              $sql = $sql . "BETWEEN 41 AND 60";
              break;
            case '3':
              $sql = $sql . "> 61";
              break;
          }
          //$sql = $sql . $tmp;
          switch ($_GET['time']) {
            case '1':
              $sql = $sql . $mon;
              $sql = $sql . "MONTH(CURDATE())";
              break;
            case '2':
              $sql = $sql . $tmp;
              $sql = $sql . "YEAR(CURDATE())";
              break;
          }

            $sql = $sql . $sig;

          $result = $conn->query($sql);
          echo "<table><tr><th>".$c1."</th><th>".$c2."</th></tr>";
          while(!empty($result) && $row = $result->fetch_array()) {
            echo "<tr><th>".$row['name']."</th><th>".$row['uses']."</th></tr>";
          }
          echo "</table>";

          $conn->close();
        }
      ?>
    </div>
  </div>
</body>




</html>