
<?php

//echo "Connected successfully";



/*
$address = $day = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $address = $_POST["placeteam"];
  $day = $_POST["daysel"];


  $sql = 'INSERT INTO teamtable(address, day) VALUES ("' . $address . '", "'. $day .'");';

  if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
  } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }

  mysqli_close($conn);


  header("Location: full_system.php");
  exit;



}

*/

$q = intval($_GET['q']);


require 'connectserver.php';


$sql = 'SELECT groupcount FROM teamtable WHERE serial = '. $q .';';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {



      $new_group = $row["groupcount"]+1;


      $update_group = 'UPDATE teamtable SET groupcount=' . $new_group . ' WHERE serial=' . $q . ';';
      mysqli_query($conn, $update_group);


      $add_memberquery = 'INSERT INTO grouptable(teamserial, groupid) VALUES('.  $q .', ' . $new_group . ');';
      mysqli_query($conn, $add_memberquery);


      mysqli_close($conn);


      echo chr($new_group + 64);




    }
} else {
    //echo "Error";
}



//mysqli_close($conn);


exit;




 ?>
