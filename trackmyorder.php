 <!DOCTYPE html>
<html lang="en">
      <head>

          <title>Track My Order - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>
          
          <?php require_once("headermeta.php"); ?>

      </head>
      <body>


<?php





        include 'header.php';
?>






<?php


    require 'cookiesvariables.php';


    $foundProduct = 0;

    $deliverystatus = "";

    $anotherProductCode = "";


    if ($_SERVER["REQUEST_METHOD"] == "GET") {
      $productsid = "";
      if(isset($_GET["trackordercode"])){
        $productsid = $_GET["trackordercode"];
      }


      if(!$productsid){
        $foundProduct = 0;
      }else{

        if(ctype_digit($productsid) && strlen($productsid) < 7){
          $anotherProductCode = $productsid;
          $foundProduct = 1;
          require 'connectserver.php';



          $sql = 'SELECT deliverystatus FROM orderbook WHERE orderno = "'. $productsid .'";';
          $result = $conn->query($sql);



          //$image_location . $row["filename"]

          if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  $deliverystatus = $row["deliverystatus"];
                }
          }else{
            $foundProduct = 0;
            $deliverystatus = "No";
          }



        }else{
          $foundProduct = 0;
        }

      }

    }


    // echo $deliverystatus . "<br>";

?>




  <div id="track_my_order_container">

    <img src="images/undraw_drone_delivery_5vrm.svg">

    <?php

      if(strcmp($deliverystatus, "No")==0){
        echo '<h2>No Order Found On Code '. $anotherProductCode .'</h2>';
      }else if(strcmp($deliverystatus, "")!=0){
        echo '<h2>Order Code '. $anotherProductCode .': '. $deliverystatus .'</h2>';
      }else{
        echo '<h2>Track Your Order By Order Code</h2>';
      }
    ?>


    <div id="inside_track_form">
      <form id="track_order_form" class="clearfix">
        <input id="trackcodeinpur" type="number" name="trackordercode" placeholder="Enter Order No EX:109091">
        <input type="submit" value="Track">
      </form>
    </div>


    <a href="index"><button id="return_shoppingtrackmyorder">Return Shopping</button></a>

  </div>














































<?php
        include 'footer.php';
?>


      </body>
<html>
