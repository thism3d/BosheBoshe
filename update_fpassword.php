<?php



if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if(isset($_POST["pswrd"])){



    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }


    $userpassword = test_input($_POST["pswrd"]);

    session_start();

    if(isset($_SESSION["phonenumber"])){




      require 'connectserver.php';


      $sql = 'UPDATE customers SET passofcustomer = "'. $userpassword .'" WHERE phonenumber = "'. $_SESSION["phonenumber"] .'";';

      if ($conn->query($sql) === TRUE) {
          echo "Y";
          $_SESSION["phonenumber"] = "";
      } else {
          echo "N";
      }

    }


  }

}


 ?>
