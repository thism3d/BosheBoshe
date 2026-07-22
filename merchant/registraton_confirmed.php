<!DOCTYPE html>
<html lang="en">
  <head>

      <title>Merchant Registration | BosheBoshe</title>

      <meta name="robots" content="noindex, nofollow">
      <meta charset="utf-8">
      <meta name="google" content="notranslate" />
      <meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, initial-scale=1.0, user-scalable=no, shrink-to-fit=no" />


      <!-- CSS For this Page -->
      <link rel="manifest" href="https://bosheboshe.com/bosheboshe.manifest">

      <link rel="icon" href="../icon.png">

      <link rel="stylesheet" href="merchant_main.css">

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


      <link rel="stylesheet" href="form_material.css">




  </head>
  <body>

<?php


    require 'cookiesvariablesmerchant.php';


    $errorString = "";
    $merchantShop = $merchantPerson = $merchantPhone = $merchantPassword = $merchantCategory =  "";
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








      if (empty($_POST["merchantshop"]) || empty($_POST["merchantperson"]) || empty($_POST["merchantnumber"]) || empty($_POST["merchantpassword"]) || empty($_POST["merchantcategory"])) {
          $errorcount = $errorcount + 1;
          $_SESSION["errormerchantregistration"] = "Fill All Starred Fields";
          header('Location: ' . $serverhost .'/registration');
      }else{        // If User Input Not Empty (Starts Here)



        //Escape Character
        $merchantShop = test_input($_POST["merchantshop"]);
        $merchantPerson = test_input($_POST["merchantperson"]);
        $merchantCategory = test_input($_POST["merchantcategory"]);
        $merchantPhone = test_input($_POST["merchantnumber"]);
        $merchantPassword = test_input($_POST["merchantpassword"]);



        if($errorcount <= 0){ // If No Error Found, Connect To Server Starts

          require_once __DIR__ . '/../connectserver.php';

          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);

              $_SESSION["errormerchantregistration"] = "Server Connection Failed, Try Again!";
              header('Location: ' . $serverhost .'/registration');
          }else{


              $sql = 'SELECT ownerphone FROM registershop WHERE ownerphone LIKE "%'. $merchantPhone .'%";';
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                  $conn->close();
                  $_SESSION["errormerchantregistration"] = "Already Registered! Please Log In";
                  header('Location: ' . $serverhost .'/index');
              }


              $stmt = $conn->prepare('INSERT INTO registershop(shopname, shopownername, shopcategory, ownerphone, shoppass) VALUES(?, ?, ?, ?, ?);');
              $stmt->bind_param("sssss", $serverMerchantShop, $serverMerchantPerson, $serverMerchantCategory, $serverMerchantPhone, $serverMerchantPassword);

              $serverMerchantShop = $merchantShop;
              $serverMerchantPerson = $merchantPerson;
              $serverMerchantCategory = $merchantCategory;
              $serverMerchantPhone = $merchantPhone;
              $serverMerchantPassword = $merchantPassword;



              if ($stmt->execute()) {


                    // echo "Registered";

                    $_SESSION["errormerchantregistration"] = "Registered, Application Begin Reviewed"; 

                    echo '<div id="merchant_login_container">


                            <div id="merchant_login_main" style="background-color: rgba(245, 245, 245, 0.7)">

                                <img id="merchant_login_img" src="merchant_icon.png" alt="BosheBoshe Icon"><br><br>


                                <h2 id="merchant_login_h2"><i class="fa fa-check-circle"></i> Registration Successful</h2>

                                <div id="merchant_reg_success_container">
                                   <img src="undraw_team_goals_hrii.svg">
                                    <div id="merchant_reg_deatils">
                                        <h3>Merchant Details</h3>

                                        <div id="deatils_0f_mreg">
                                        <p>
                                            Merchant : '. $serverMerchantShop .'
                                        </p>
                                        <p>
                                            Owner : '. $serverMerchantPerson .'
                                        </p>
                                        <p>
                                            Phone : '. $serverMerchantPhone .'
                                        </p>
                                        </div>
                                        <div id="extra_info_merchant_reg">
                                            <p>Your application is being reviewed. Our customer service will call you soon for confirmation.</p><br>
                                            <p>If any delay happens, you may contact us at <a href="tel:+8801884084849">01884084849</a>.</p>

                                        </div>

                                    </div>


                                    <a href="index"><button id="registration_confirm_btn">Merchant Login</button></a>
                                </div>





                            </div>



                        </div>';


              } else {
                 // it didn't

                 $_SESSION["errormerchantregistration"] = "Server Connection Error, Try Again!";
                 header('Location: ' . $serverhost .'/registration');
              }



              $stmt->close();
              $conn->close();

          }





        }  // If No Error Found, Connect To Server Ends











      }   // If User Input Not Empty (Ends Here)





    }    // Server Method Post Ends Here






 ?>




  </body>
<html>
