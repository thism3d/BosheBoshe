<!DOCTYPE html>
<html lang="en">
      <head>

          <title>Resistration Message - BosheBoshe</title>


          <?php require_once("headernofollowmeta.php"); ?>


      </head>
      <body>








<?php

    require 'cookiesvariables.php';


    $formUsernameError = $formPasswordError = "";
    $errorString = "";
    $formName = $formEmail = $formPhone = $formDeliveryAddress = $formCity = $formPassword = "";
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








      if (empty($_POST["fullname"]) || empty($_POST["phonenumber"]) || empty($_POST["delivery_address"]) || empty($_POST["city_address"]) || empty($_POST["password"])) {
          $errorcount = $errorcount + 1;
          $_SESSION["errorsofregistration"] = "Fill All Starred Fields";
          header('Location: ' . $serverhost .'/member?do=registration');
      }else{        // If User Input Not Empty (Starts Here)



        //Code for name
        $formName = test_input($_POST["fullname"]);
        // if (!preg_match("/^[a-zA-Z ]*$/", $formName)) {
        //   $errorcount = $errorcount + 1;
        //   $_SESSION["errorsofregistration"] = "Place a valid name";
        //   header('Location: ' . $serverhost .'/member?do=registration');
        // }



        // Code for Email

        if(!empty($_POST["useeremail"])){
          $formEmail = test_input($_POST["useeremail"]);
          if (!filter_var($formEmail, FILTER_VALIDATE_EMAIL)) {
            $errorcount = $errorcount + 1;
            $_SESSION["errorsofregistration"] = "Invalid Email Format";
            header('Location: ' . $serverhost .'/member?do=registration');
          }
        }


        //Code for phonenumber
        $formPhone = test_input($_POST["phonenumber"]);
        if (strlen($formPhone)==10) {
          if(strcmp(substr($formPhone, 0, 1), "1")!=0){
            $errorcount = $errorcount + 1;
            $_SESSION["errorsofregistration"] = "Invalid Phone Number";
            header('Location: ' . $serverhost .'/member?do=registration');
          }

          $formPhone = '0' . $formPhone;
        }elseif (strlen($formPhone)==11) {
          if(strcmp(substr($formPhone, 0, 2), "01")!=0){
            $errorcount = $errorcount + 1;
            $_SESSION["errorsofregistration"] = "Invalid Phone Number";
            header('Location: ' . $serverhost .'/member?do=registration');
          }
        }else if(strlen($formPhone)<10 || strlen($formPhone)>11){
          $errorcount = $errorcount + 1;
          $_SESSION["errorsofregistration"] = "Invalid Phone Number";
          header('Location: ' . $serverhost .'/member?do=registration');
        }


        // Code for delivery address, city & password
        $formDeliveryAddress = test_input($_POST["delivery_address"]);
        $formCity = test_input($_POST["city_address"]);
        $formPassword = test_input($_POST["password"]);



        if($errorcount <= 0){ // If No Error Found, Connect To Server Starts

          $servername = "localhost";
          $username = "bosheboshe_udtxasd";
          $password = "@RGYhjfasdtU1245";
          $dbname = "bosheboshe_userdatabase";

          $conn = new mysqli($servername, $username, $password, $dbname);

          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);

              $_SESSION["errorsofregistration"] = "Server Connection Failed, Try Again!";
              header('Location: ' . $serverhost .'/member?do=registration');
          }else{


              $sql = 'SELECT phonenumber FROM customers WHERE phonenumber LIKE "%'. $formPhone .'%";';
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                  $conn->close();
                  $_SESSION["errorsofregistration"] = "You are already registered! Please Log In";
                  header('Location: ' . $serverhost .'/member?do=login');
              }


              $stmt = $conn->prepare("INSERT INTO customers(fullname, email, phonenumber, deliveryaddress, cityname, passofcustomer) VALUES(?, ?, ?, ?, ?, ?)");
              $stmt->bind_param("ssssss", $customerfullnamesql, $customeremailsql, $customerphonesql, $customerdeliveryaddresssql, $customercitynamesql, $customerpasswordsql);

              // set parameters and execute
              $customerfullnamesql = $formName;
              if(!empty($_POST["useeremail"])){
                $customeremailsql = $formEmail;
              }else{
                $formEmail = "";
                $customeremailsql = "";
              }
              $customerphonesql = $formPhone;
              $customerdeliveryaddresssql = $formDeliveryAddress;
              $customercitynamesql = $formCity;
              $customerpasswordsql = $formPassword;

              if ($stmt->execute()) {
                 // it worked


                 // Use openssl_encrypt() function to encrypt the data
                 $usernameencrypted = openssl_encrypt($formName, $ciphering,
                             $encryption_key, $options, $encryption_iv);
                 $useremailencrypted = openssl_encrypt($formEmail, $ciphering,
                             $encryption_key, $options, $encryption_iv);
                 $userphoneencrypted = openssl_encrypt($formPhone, $ciphering,
                             $encryption_key, $options, $encryption_iv);
                 $userdeliveryaddressencrypted = openssl_encrypt($formDeliveryAddress, $ciphering,
                             $encryption_key, $options, $encryption_iv);
                 $usercitynameencrypted = openssl_encrypt($formCity, $ciphering,
                             $encryption_key, $options, $encryption_iv);
                 $userpasswordEncrypted = openssl_encrypt($formPassword, $ciphering,
                             $encryption_key, $options, $encryption_iv);

//                                echo $usernameencrypted . "<br>";
//                                echo $userpasswordEncrypted . "<br>";


                 setcookie($cookiefullname, $usernameencrypted, time() + (86400 * 30), "/"); // 86400 = 1 day
                 setcookie($cookieemail, $useremailencrypted, time() + (86400 * 30), "/"); // 86400 = 1 day
                 setcookie($cookiephone, $userphoneencrypted, time() + (86400 * 30), "/"); // 86400 = 1 day
                 setcookie($cookiedeliveryaddress, $userdeliveryaddressencrypted, time() + (86400 * 30), "/"); // 86400 = 1 day
                 setcookie($cookiecity, $usercitynameencrypted, time() + (86400 * 30), "/"); // 86400 = 1 day
                 setcookie($cookiepassword, $userpasswordEncrypted, time() + (86400 * 30), "/"); // 86400 = 1 day



              } else {
                 // it didn't

                 $_SESSION["errorsofregistration"] = "Server Connection Error, Try Again!";
                 header('Location: ' . $serverhost .'/member?do=registration');
              }



              $stmt->close();
              $conn->close();

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
          echo '<img src="images/undraw_fill_forms_yltj.svg">
                <h2>Want To Register!</h2>
                <a href="member?do=login"><button id="go_to_profile_afterreg">Login</button></a>
                <a href="member?do=registration"><button id="start_shopping_afterreg">Registration</button></a>';
        }else{
          echo '<img src="images/undraw_done_a34v.svg">
                <h2>Successfully Registered</h2>
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
