<?php



if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if(isset($_POST["userotp"])){

    $userotp = $_POST["userotp"];



    if(ctype_digit($userotp)){


      session_start();
      if(isset($_SESSION["userotp"])){
        if(strcmp($_SESSION["userotp"], $_POST["userotp"])==0){
          echo "Matched";
          $_SESSION["userotp"] = "";
        }else{
          echo "Not Matched";
        }
      }else{
        echo "Not Matched";
      }


    }else{
      echo "Not Matched";
    }

  }else{
    echo "Not Matched";
  }

}


 ?>
