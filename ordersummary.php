<!DOCTYPE html>
<html>
      <head>

          <title>Order Summary - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>

          <?php require_once("headernofollowmeta.php"); ?>


          <style>





          /* Admin Orders */
          .all_orders{
            max-width: 460px;
            margin: 0px auto;
            width: 100%;
            border: 0.8px solid #adadad;
            border-radius: 5px;
            box-sizing: border-box;
            padding: 10px;
            text-align: left;
            margin-bottom: 30px;
            background-color: white;
            margin-top: 50px;

          }

          .all_orders h2{
            text-align: center;
            padding: 10px 0px;
          }

          .single_product_of_order{

          }


          .single_product_of_order p:first-child{
            width: 70%;
            float: left;
          }


          .single_product_of_order p:last-child{
            width: 30%;
            float: left;
            text-align: right;
          }

          .total_cost_p_of_ordered p:first-child{
            text-align: center;
          }

          .total_cost_p_of_ordered{
            border-top: 0.7px solid #adadad;
          }


          .all_orders input[type="submit"]{
            border: 1px solid gray;
            padding: 4px 8px;
            cursor: pointer;
          }

          .items_ordered_h3{
            padding-top: 20px;
            text-align: center;
          }

          .update_status_h3{
            padding-top: 20px;
            text-align: center;
          }

          .all_orders form{
            text-align: center;
          }










          </style>



      </head>
      <body>


<?php










  require 'cookiesvariables.php';




  $decryptedPhone = "";


  // Code For Encryption Strats Here

  // Store the cipher method
  $ciphering = "AES-128-CTR";

  // Use OpenSSl Encryption method
  $iv_length = openssl_cipher_iv_length($ciphering);
  $options = 0;

  // Non-NULL Initialization Vector for encryption
  $encryption_iv = '8675992784945782';

  // Store the encryption key
  $encryption_key = "MayeshaMeemMuzahidIslam";

  // Non-NULL Initialization Vector for decryption
  $decryption_iv = '8675992784945782';

  // Store the decryption key
  $decryption_key = "MayeshaMeemMuzahidIslam";


  // Code For Encryption Ends Here

  $userfound = 0;



if(isset($_COOKIE[$cookiefullname]) && isset($_COOKIE[$cookiephone])  && isset($_COOKIE[$cookiedeliveryaddress]) && isset($_COOKIE[$cookiecity])  && isset($_COOKIE[$cookiepassword])) {

    $decryptedPhone = openssl_decrypt ($_COOKIE[$cookiephone], $ciphering,
    $decryption_key, $options, $decryption_iv);

    $userfound = 1;

  }






        $productFromServerFound = 0;




        $foundorderid = 0;

        $orderid = "";

        if ($_SERVER["REQUEST_METHOD"] == "GET") {

          if(isset($_GET["orderid"])){
            $orderid = $_GET["orderid"];
          }


          if(!$orderid){
            $foundorderid = 0;
            // $str = "Location: profile.php";
            // header($str);
          }else{


            if(is_numeric($orderid)){

              if($userfound == 1){
                $foundorderid = 1;
              }



              // echo "Numeric";
            }else{
              $foundorderid = 0;
              // echo "Not Numeric";
            }


          }
        }





        //
        include 'header.php';


        if($foundorderid == 1){
          // echo "Found";





          require 'connectserver.php';



          $sql = 'SELECT orderno, customername, customerphone, customerdelivery, customercity, customerproducts, customercoupon, deliverystatus FROM orderbook WHERE orderno="'. $orderid .'";';
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {

            $productFromServerFound = 1;


            while($row = $result->fetch_assoc()) {
              echo '
              <div class="all_orders">
                <h2>Order No '. $row["orderno"] .'</h2>


                <br>
                <h3 class="items_ordered_h3">Items Ordered</h3>';

                // echo $row["customercoupon"];

                // echo $row["customerproducts"];

                $allproducts=array();


                $string = $row["customerproducts"];
                $token = strtok($string, ",");

                while ($token !== false){
                  array_push($allproducts,$token);
                   $token = strtok(",");
                }



                $totalAmountCalculator = 0;

                $totalBesafeCounter = 0;




                $singleProductArray=array();

                $lengthAllProduct = count($allproducts);
                for($x = 0; $x < $lengthAllProduct; $x++){
                  // echo $allproducts[$x] . "<br>";

                  $singleProductArrayString = $allproducts[$x];

                  $token = strtok($singleProductArrayString, "~");

                  while ($token !== false){
                    array_push($singleProductArray,$token);
                     $token = strtok("~");
                  }

                  // echo count($singleProductArray) . "<br>";

                  $totalAmountCalculator = $totalAmountCalculator + intval( $singleProductArray[5]);
                  $totalBesafeCounter = $totalBesafeCounter + intval($singleProductArray[3] * $singleProductArray[4]);

                  echo '<div class="single_product_of_order clearfix">
                      <p>'. intval($x+1) .'. '. $singleProductArray[1] .' ('. $singleProductArray[2] .' x '. $singleProductArray[4] .') </p>
                      <p>'. $singleProductArray[5] .' TK</p>
                    </div>';


                  unset($singleProductArray);
                  $singleProductArray = array();

                }






                echo '

                <!--<div class="single_product_of_order clearfix">
                  <p>1. () Fish 1kg (300 x 2) </p>
                  <p>600TK</p>
                </div>-->

                <div class="single_product_of_order clearfix total_cost_p_of_ordered">
                  <p>Total Cost </p>
                  <p>'. intval($totalAmountCalculator) .' TK</p>
                </div>

                <div class="single_product_of_order clearfix">
                  <p style="text-align:center">Delivery Charge</p>
                  <p> 30 TK</p>
                </div>';

                if(strcmp( $row["customercoupon"], "besafe")==0){

                  if($totalBesafeCounter > 99){
                    $totalBesafeCounter = 100;
                  }

                  echo '

                  <div class="single_product_of_order clearfix">
                    <p style="color:transparent;">-</p>
                    <p> (besafe) - '. $totalBesafeCounter .' TK</p>
                  </div>
                  <div class="single_product_of_order clearfix total_cost_p_of_ordered">
                    <p>Final Cost </p>
                    <p>'. intval($totalAmountCalculator-$totalBesafeCounter+30) .' TK</p>
                  </div>';
                }else{
                  echo '<div class="single_product_of_order clearfix total_cost_p_of_ordered">
                    <p>Final Cost </p>
                    <p>'. intval($totalAmountCalculator+30) .' TK</p>
                  </div>';
                }






                echo '

              </div>
              ';
            }
          }


          if($productFromServerFound == 0){
            echo '<div id="search_result_goes_here_conatiner"><h2 id="waitingforsearchh2">Order Not Found</h2>
            <img id="search_image" src="images/undraw_empty_xct9.svg" "=""><br>
            <br>
            <a href="profile"><button id="start_shopping_afterreg">Return To Profile</button></a></div>';
          }





        }else{
          // echo "Not Found";

          if($userfound == 0){
            echo '<div id="search_result_goes_here_conatiner"><h2 id="waitingforsearchh2">User Not Found</h2>
            <img id="search_image" src="images/undraw_sign_in_e6hj.svg" "=""><br>
            <br>
            <a href="member.php?do=login"><button id="start_shopping_afterreg">Login First</button></a></div>';
          }else{
            echo '<div id="search_result_goes_here_conatiner"><h2 id="waitingforsearchh2">Order Not Found</h2>
            <img id="search_image" src="images/undraw_empty_xct9.svg" "=""><br>
            <br>
            <a href="profile"><button id="start_shopping_afterreg">Return To Profile</button></a></div>';
          }


        }



?>















































<?php
        include 'footer.php';
?>


      </body>
<html>
