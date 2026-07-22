<?php



require 'cookiesvariablesadmin.php';



$cookieSet = 0;

if(isset($_COOKIE[$cookieadminname]) && isset($_COOKIE[$cookieadminstatus]) && isset($_COOKIE[$cookieadminusername]) && isset($_COOKIE[$cookieadminpassword])) {
  $cookieSet = 1;
}else{
  header('Location: ' . $serverhost .'/logout.php');
  exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && $cookieSet == 1) {


  $productsIdForDeltion = "";

  if(isset($_POST["productsid"])){
    $productsIdForDeltion = $_POST["productsid"];

    require 'connectserver.php';

    $sql = 'DELETE FROM productsforsell WHERE productscode = '. $productsIdForDeltion .';';

    if (mysqli_query($conn, $sql)) {
        echo "Y";
    } else {
        echo "N";
    }




  }else{
    echo "N";
  }



}else{
  echo "N";
}


 ?>
