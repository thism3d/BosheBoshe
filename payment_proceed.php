<!DOCTYPE html>
<html lang="en">
      <head>

          <title>Payment Proceed - BosheBoshe</title>




          <style>

              /* Admin Orders */
              .all_orders{
                max-width: 700px;
                margin: 0px auto;
                width: 100%;
                border: 0.8px solid #adadad;
                border-radius: 5px;
                box-sizing: border-box;
                padding: 10px;
                text-align: left;
                margin-bottom: 30px;
                background-color: white;
                margin-top: 20px;

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
                /* border-top: 0.7px solid #adadad; */
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

              #sslPaymentConfirmBtn{
                min-width: 290px;
                width: 65%;
                background-color: #3c72a3;
                color: white;
                padding: 10px 20px;
                margin: 8px 0;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                font-weight: bold;
              }

              #sslPaymentConfirmBtn:hover{
                background-color: #1c80d9;
              }

              #overview_div_image_container{
                text-align: center;
              }

              #overview_cart_image{
                width: 100%;
                max-width: 320px;
                box-sizing: border-box;
                padding: 10px 20px;
              }




          </style>



      </head>
      <body>


<?php

$ordercodefromsql = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_COOKIE["meAtQsrzAmKla"])) {


    setcookie("meAtQsrzAmKla", "", time() - 3600, "/");
    setcookie("tMeWAMem", "", time() - 3600, "/");


     require_once("headernofollowmeta.php");




    require 'cookiesvariables.php';

    $toalFinalPrice = 0;



    $formName = $formEmail = $formPhone = $formDeliveryAddress = $formCity = $fulldetailedcart = $isofferoncart = "";
    $errorcount = 0;



    if(isset($_COOKIE["meAtQsrzAmKla"])){
      $fulldetailedcart = $_COOKIE["meAtQsrzAmKla"];
    }else{
      $errorcount = $errorcount + 1;
    }

    $isofferoncart = "no";
    if(isset($_COOKIE["tMeWAMem"])){
      if(strcmp($_COOKIE["tMeWAMem"], "t")==0){
        $isofferoncart = "besafe";
      }
    }



    include 'header.php';


    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }




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









      // Code For Empty Check
      if (empty($_POST["fullname"]) || empty($_POST["phonenumber"]) || empty($_POST["delivery_address"]) || empty($_POST["city_address"])) {
          $errorcount = $errorcount + 1;
      }else{
        $formName = test_input($_POST["fullname"]);
        $formDeliveryAddress = test_input($_POST["delivery_address"]);
        $formCity = test_input($_POST["city_address"]);
        $formPhone = test_input($_POST["phonenumber"]);
        $formEmail = test_input($_POST["useeremail"]);
      }


      // Code for Email
      if(isset($_POST["useeremail"])){

        if(strlen($formEmail)>0){
          if (!filter_var($formEmail, FILTER_VALIDATE_EMAIL)) {
            $errorcount = $errorcount + 1;
          }
        }
      }





      // Code For Phone Number

      if (strlen($formPhone)==10) {
        if(strcmp(substr($formPhone, 0, 1), "1")!=0){
          $errorcount = $errorcount + 1;
        }

        $formPhone = '0' . $formPhone;
      }elseif (strlen($formPhone)==11) {
        if(strcmp(substr($formPhone, 0, 2), "01")!=0){
          $errorcount = $errorcount + 1;
        }
      }else if(strlen($formPhone)<10 || strlen($formPhone)>11){
        $errorcount = $errorcount + 1;
      }


      if(strlen($formName)<6 || strlen($formDeliveryAddress)<6 || strlen($formCity)<3){
        $errorcount = $errorcount + 1;
      }


      if($errorcount==0){
        // echo $formName . "<br>" . $formEmail . "<br>" . $formPhone . "<br>" . $formDeliveryAddress . "<br>" . $formCity . "<br>Cart: " . $fulldetailedcart . "<br>Offer: " . $isofferoncart;



        // Overview Data Starts Here

        $orderCodeId = "";








                    echo '
                    <div class="all_orders">
                      <div id="overview_div_image_container">
                        <img id="overview_cart_image" src="images/undraw_add_to_cart_vkjp.svg">
                      </div>
                      <h2>Order Received: 102913 <span style="font-size: 14px; font-weight: normal;">(Waiting For Payment)</span></h2>
                      <hr>
                      <br>

                      <p>Name: '. $formName .'</p>
                      <p>Email: '. $formEmail .'</p>
                      <p>Phone: '. $formPhone .'</p>
                      <p>Address: '. $formDeliveryAddress .'</p>
                      <p>City: '. $formCity .'</p>

                      <br>
                      <h3 class="items_ordered_h3">Items Checklist</h3>';

                      // echo $row["customercoupon"];

                      // echo $row["customerproducts"];

                      $allproducts=array();


                      $string = $fulldetailedcart;
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

                      <div style="border-top: 0.7px solid #adadad;" class="single_product_of_order clearfix total_cost_p_of_ordered">
                        <p>Total Cost </p>
                        <p>'. $totalAmountCalculator .' TK</p>
                      </div>

                      <div class="single_product_of_order clearfix total_cost_p_of_ordered">
                        <p>Delivery Charge </p>
                        <p>30 TK</p>
                      </div>';

                      if(strcmp( $isofferoncart, "besafe")==0){

                        if($totalBesafeCounter > 99){
                          $totalBesafeCounter = 100;
                        }

                        $toalFinalPrice = intval($totalAmountCalculator-$totalBesafeCounter + 30);

                        echo '

                        <div class="single_product_of_order clearfix">
                          <p style="color:transparent;">-</p>
                          <p> (besafe) - '. $totalBesafeCounter .' TK</p>
                        </div>
                        <div  style="border-top: 0.7px solid #adadad;"  class="single_product_of_order clearfix total_cost_p_of_ordered">
                          <p>Final Cost </p>
                          <p>'. $toalFinalPrice .' TK</p>
                        </div>';
                      }else{
                        $toalFinalPrice = $totalAmountCalculator + 30;
                        echo '
                        <div style="border-top: 0.7px solid #adadad;" class="single_product_of_order clearfix total_cost_p_of_ordered">
                          <p>Final Cost </p>
                          <p>'. $toalFinalPrice .' TK</p>
                        </div>';
                      }



                    // Muzahid Server Coding Starts Here

                    require_once __DIR__ . '/connectserver.php';


                    $stmt = $conn->prepare('INSERT INTO orderbook(customername, customerphone, customeremail, customerdelivery, customercity, customerproducts, totalamount, customercoupon) VALUES(?, ?, ?, ?, ?, ?, ?, ?)');
                    $stmt->bind_param("ssssssis", $formName, $formPhone, $formEmail, $formDeliveryAddress, $formCity, $fulldetailedcart, $toalFinalPrice, $isofferoncart);


                    if ($stmt->execute()) {

                      // echo $stmt->insert_id;
                      $orderCodeId = $stmt->insert_id;
                      $ordercodefromsql = $stmt->insert_id;
                  //
                  //
                  //
                  //     /* On Complete Order Sent Us a Mail */
                  //
                      $to = "muzahid221@gmail.com, atiyafahmida42@gmail.com";
                      $subject = "Paid Order Confirmation";

                      $message = '
                      <html>
                          <head>
                              <style>

                                  body{
                                      text-align: center;
                                  }

                                  img{
                                      max-width: 300px;
                                      width: 100%;
                                      padding: 30px;
                                      box-sizing: border-box;
                                  }

                                  p{
                                      text-align: left;
                                      padding:
                                  }
                              </style>
                          </head>
                          <body>
                              <img src="https://bosheboshe.com/bosheboshefinalblack.png" alt="BosheBoshe.com">
                              <hr>
                              <p>
                                  <b>Name : </b>'. $formName .'<br>
                                  <b>Phone: </b>'. $formPhone .'<br>
                                  <b>Email: </b>'. $formEmail .'<br>
                                  <b>Delivery Address: </b>'. $formDeliveryAddress .'<br>
                                  <b>City: </b>'. $formCity .'<br>
                                  <b>Total Amount: '. $toalFinalPrice .' TK</b>
                              </p>

                              <hr>
                              <h3>Order Code '. $stmt->insert_id .'</h3>
                              <hr>
                              <p>
                                  Order has been placed successfully with payment option.<br>
                                  Use your admin panel to confirm this order.
                              </p>
                          </body>
                      </html>
                      ';



                      $headers = "MIME-Version: 1.0" . "\r\n";
                      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                      $headers .= 'From: Bosheboshe Paid Orders <orders@bosheboshe.com>' . "\r\n";

                      $retval = mail($to,$subject,$message,$headers);













                      $to = "01714526039," . $formPhone;
                      $token = "5c2197ff8b4626b10524566425b51066";
                      $message = "Your order " . $stmt->insert_id . " has been received by bosheboshe.com (Waiting For Payment)";

                      $url = "http://bulksmsbd.net/api/smsapi";
                      $api_key = "OKfopQjVVNmaMjM1O98E";
                      $senderid = "8809617626388";
                      $number = $to;
                      $message = $message;

                      $data = [
                          "api_key" => $api_key,
                          "senderid" => $senderid,
                          "number" => $number,
                          "message" => $message
                      ];
                      $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $url);
                      curl_setopt($ch, CURLOPT_POST, 1);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                      $response = curl_exec($ch);
                      curl_close($ch);
                      $smsresult = $response;










                  }

                  /* Muzahid Server Coding Ends Here */




// echo $formName . "<br>" . $formEmail . "<br>" . $formPhone . "<br>" . $formDeliveryAddress . "<br>" . $formCity . "<br>Cart: " . $fulldetailedcart . "<br>Offer: " . $isofferoncart;
                      echo '
                      <div>
                      <br><br>
                      <form action="sslpayment" name="sslpayment"  method="post">
                      <input type="hidden" placeholder="Full Name" id="fname" name="fullname" autocomplete="off" maxlength="80" required value="'. $formName .'" readonly >
                      <input type="hidden"  placeholder="Phone" id="fphone"  name="phonenumber" value="'. $formPhone .'" readonly autocomplete="off" required maxlength="11">
                      <input type="hidden"  placeholder="Enter Email (To Get Recipt)" id="femail" name="useeremail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" autocomplete="off" maxlength="230"  value="'. $formEmail .'" title="Enter Valid Email" readonly>
                      <input type="hidden" placeholder="Product Delivery Address" id="faddress" name="delivery_address" autocomplete="off" required maxlength="230" pattern=".{6,}" required title="Enter Valid Address" value="'. $formDeliveryAddress .'" readonly>
                      <input type="hidden" placeholder="City" id="fcity" name="city_address" autocomplete="off" required maxlength="40" pattern=".{3,}" title="Enter Valid City" value="'. $formCity .'" readonly>
                      <input type="hidden" placeholder="Total Payment" id="ftotalpayment" name="user_total_payment" autocomplete="off" required value="'. $toalFinalPrice .'" readonly>
                      <input type="hidden" placeholder="Order ID" id="fordercode" name="userodercode" autocomplete="off" required value="'. $ordercodefromsql .'" readonly>

                      <input id="sslPaymentConfirmBtn" type="submit" value="Pay '. $toalFinalPrice .' TK">
                      </form>
                      </div>

                    </div>
                  ';

        //Overview Data Ends Here








      }else{
        echo'
          <div id="user_sent_message_container">
            <img src="images/undraw_empty_cart_co35.svg">
            <br>
            <br>
            <br>
            <p id="message_query_number_p">
              <b style="font-size:21px;">Payment Error !</b>
            </p><br>



            <a href="proceedtocart"><button id="return_shopping">Go Back To Cart</button></a>
          </div>';
      }



}else{


  include "headernofollowmeta.php";
  include 'header.php';


  echo'
    <div id="user_sent_message_container">
      <img src="images/undraw_at_home_octe.svg">
      <br>
      <br>
      <br>
      <p id="message_query_number_p">
        <b style="font-size:21px;">Start Shopping with BosheBoshe!</b>
      </p><br>



      <a href="index"><button id="return_shopping">BosheBoshe Home</button></a>
    </div>';
}


?>
































<?php
        include 'footerforcart.php';
?>


      </body>
<html>
