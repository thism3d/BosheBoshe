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


  $productsIdForDeltion = $minPrice = $maxPrice = $offeredPrice = $besafeLess = "";

  if(isset($_POST["productsid"]) && isset($_POST["minPrice"]) && isset($_POST["maxPrice"]) && isset($_POST["offeredPrice"]) && isset($_POST["besafeLess"]) && isset($_POST["productName"])){
    $productsIdForDeltion = $_POST["productsid"];
    $minPrice = $_POST["minPrice"];
    $maxPrice = $_POST["maxPrice"];
    $offeredPrice = $_POST["offeredPrice"];
    $besafeLess = $_POST["besafeLess"];
    $productName = $_POST["productName"];

    // echo $productsIdForDeltion . " " . $minPrice . " " . $maxPrice . " " . $offeredPrice . " " . $besafeLess;

    require 'connectserver.php';

    $sql = 'UPDATE productsforsell SET productname="'. $productName .'", minprice = '. $minPrice .', maxprice = '. $maxPrice .',  offeredprice = '. $offeredPrice .', besafeless = '. $besafeLess .' WHERE productscode = '. $productsIdForDeltion .';';

    if ($conn->query($sql) === TRUE) {
        echo "Y";
    } else {
        echo "N";
    }

    // echo "N";



  }else{
    echo "N";
  }



}else{
  echo "N";
}


 ?>
