<!DOCTYPE html>
<html lang="en">
      <head>

          <title>Gas Exchange Message - BosheBoshe</title>


          <?php require_once("headermeta.php"); ?>



      </head>
      <body>








<?php

    require 'cookiesvariables.php';


    $formCylinder = $formName = $formPhone = $formDeliveryAddress = $formCity = "";
    $errorcount = 0;


    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }



    session_start();

    $being_registered = 0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {       // Server Method Post Starts Here

      $being_registered = 1;




      //
      //
      // // Code For Encryption Strats Here
      //
      // // Store the cipher method
      // $ciphering = "AES-128-CTR";
      //
      // // Use OpenSSl Encryption method
      // $iv_length = openssl_cipher_iv_length($ciphering);
      // $options = 0;
      //
      // // Non-NULL Initialization Vector for encryption
      // $encryption_iv = '8675992784945782';
      //
      // // Store the encryption key
      // $encryption_key = "MayeshaMeemMuzahidIslam";
      //
      // // Non-NULL Initialization Vector for decryption
      // $decryption_iv = '8675992784945782';
      //
      // // Store the decryption key
      // $decryption_key = "MayeshaMeemMuzahidIslam";
      //
      //
      // // Code For Encryption Ends Here
      //
      //






      if (empty($_POST["cylindername"]) || empty($_POST["phonenumber"]) || empty($_POST["delivery_address"]) || empty($_POST["city_address"]) || empty($_POST["fullname"])) {
          $errorcount = $errorcount + 1;
          $_SESSION["errorOfGasSubmission"] = "Fill All Starred Fields";
          header('Location: ' . $serverhost .'/gas');
      }else{        // If User Input Not Empty (Starts Here)



        //Code for name
        $formName = test_input($_POST["fullname"]);
        // if (!preg_match("/^[a-zA-Z ]*$/", $formName)) {
        //   $errorcount = $errorcount + 1;
        //   $_SESSION["errorsofregistration"] = "Place a valid name";
        //   header('Location: ' . $serverhost .'/member?do=registration');
        // }



        // Code for Email




        //Code for phonenumber
        $formPhone = test_input($_POST["phonenumber"]);
        if (strlen($formPhone)==10) {
          if(strcmp(substr($formPhone, 0, 1), "1")!=0){
            $errorcount = $errorcount + 1;
            $_SESSION["errorOfGasSubmission"] = "Invalid Phone Number";
            header('Location: ' . $serverhost .'/gas');
          }

          $formPhone = '0' . $formPhone;
        }elseif (strlen($formPhone)==11) {
          if(strcmp(substr($formPhone, 0, 2), "01")!=0){
            $errorcount = $errorcount + 1;
            $_SESSION["errorOfGasSubmission"] = "Invalid Phone Number";
            header('Location: ' . $serverhost .'/gas');
          }
        }else if(strlen($formPhone)<10 || strlen($formPhone)>11){
          $errorcount = $errorcount + 1;
          $_SESSION["errorOfGasSubmission"] = "Invalid Phone Number";
          header('Location: ' . $serverhost .'/gas');
        }


        // Code for delivery address, city & password
        $formDeliveryAddress = test_input($_POST["delivery_address"]);
        $formCity = test_input($_POST["city_address"]);
        $formCylinder = test_input($_POST["cylindername"]);



        if($errorcount <= 0){ // If No Error Found, Connect To Server Starts

          require_once __DIR__ . '/connectserver.php';

          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);

              $_SESSION["errorOfGasSubmission"] = "Server Connection Failed, Try Again!";
              header('Location: ' . $serverhost .'/gas');
          }else{


              $sql = 'SELECT * FROM orderbook WHERE customerphone = "'. $formPhone .'" AND deliverystatus = "Ordered";';
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                  $conn->close();
                  $_SESSION["errorOfGasSubmission"] = "You have a pending request!";
                  header('Location: ' . $serverhost .'/gas');
              }



              $stmt = $conn->prepare('INSERT INTO orderbook(customername, customerphone, customerdelivery, customercity, customerproducts, customercoupon) VALUES(?, ?, ?, ?, ?, ?)');
              $stmt->bind_param("ssssss", $formName, $formPhone, $formDeliveryAddress, $formCity, $customerProducts, $customerCoupon);

              $customerProducts = 'p9999~'. $formCylinder .'~900~0~1~900~,';

              $customerCoupon = "no";


              if ($stmt->execute()) {
                // echo $stmt->insert_id;



                /* On Complete Order Sent Us a Mail */

                $to = "muzahid221@gmail.com";
                
                $subject = "Gas Refill Request";

                $message = '
                <html>
                    <head>
                        <style>

                            body{
                                text-align: center;
                            }

                            img{
                                max-width: 250px;
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
                        <img src="https://bosheboshe.com/bosheboshefinal.png" alt="BosheBoshe.com">
                        <hr>
                        <p>
                            <b>Name : </b>'. $formName .'<br>
                            <b>Phone: </b>'. $formPhone .'<br>
                            <b>Cylinder Requested: </b>'. $formCylinder .'<br>
                            <b>Delivery Address: </b>'. $formDeliveryAddress .'<br>
                            <b>City: </b>'. $formCity .'<br>
                        </p>

                        <hr>
                        <h3>Order Code '. $stmt->insert_id .'</h3>
                        <hr>
                        <p>
                            Gas Refill Request has been placed successfully.<br>
                            Use your admin panel to confirm this request.
                        </p>
                    </body>
                </html>
                ';



         
                require 'sendmail.php';
                $retval = sendmail($to, $subject, $message);
             
                
                
                
                $to = "atiyafahmida42@gmail.com";
                $retval = sendmail($to, $subject, $message);
                
                /* 
                if( $retval == true ) {
                  echo "Sent";
                }else {
                  echo "No";
                }
                */
                
                /* Mail Section Ends Here */
                
                  $to = "01714526039," . $formPhone;
                  $token = "5c2197ff8b4626b10524566425b51066";
                  $message = "Your " . $formCylinder . " gas refill request has been received by bosheboshe.com";
            
                  
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



              } else {
                $_SESSION["errorOfGasSubmission"] = "Database Error, Try Again!";
                header('Location: ' . $serverhost .'/gas');
              }




          }





        }  // If No Error Found, Connect To Server Ends











      }   // If User Input Not Empty (Ends Here)





    }    // Server Method Post Ends Here



?>




































<?php
        include 'header.php';
?>






    <div id="registration_confirmation">


      <?php
        if($being_registered==0){
          echo '<img src="images/undraw_heavy_box_agqi.svg">
                <h2>Request Gas Refill!</h2>
                <a href="gas"><button id="go_to_profile_afterreg">Gas Refill</button></a>
                <a href="profile"><button id="go_to_profile_afterreg">Go to profile</button></a>';
        }else{
          echo '<img src="images/undraw_confirmed_81ex.svg">
                <h2>Request Successful</h2>
                <a href="profile"><button id="go_to_profile_afterreg">Go to profile</button></a>
                <a href="startshopping"><button id="start_shopping_afterreg">Start Shopping</button></a>';
        }

       ?>


    </div>


















<?php
        include 'footer.php';
?>


      </body>
<html>
