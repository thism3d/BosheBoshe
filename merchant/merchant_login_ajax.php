<?php

    require 'cookiesvariablesmerchant.php';




    $decryptedShopName = $decryptedShopOwner = $decryptedShopPhone = $decryptedShopShortName = $decryptedShopPassword = $decryptedShopApproval = "";




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


      $errorString = "";
      $formUsername = $formPassword = "";
      $errorcount = 0;


      function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }



      // session_start();

      if ($_SERVER["REQUEST_METHOD"] == "POST") {       // Server Method Post Starts Here





        if (empty($_POST["merchantusername"]) || empty($_POST["merchantpassword"])) {
            $errorcount = $errorcount + 1;
            $errorString =  'E808';            // Fill All Starred Fields
            echo $errorString;
        }else{        // If User Input Not Empty (Starts Here)



          //Code for name
          $formUsername = test_input($_POST["merchantusername"]);



          $formPassword = test_input($_POST["merchantpassword"]);






          if($errorcount <= 0){ // If No Error Found, Connect To Server Starts

            require_once __DIR__ . '/../connectserver.php';

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
                $errorString = 'E206';///"Server Connection Failed, Try Again!";
                echo $errorString;

            }else{

              $sql = 'SELECT shopname, shopownername, shopcategory, shopdetails, ownerphone, owneremail, shopaddress, registratonnumber, totalproducts, shortname, shoppass, approval FROM registershop WHERE (ownerphone = "'. $formUsername .'" AND shoppass = "'. $formPassword .'") OR (shortname="'. $formUsername .'" AND shoppass = "'. $formPassword .'")';

              $result = $conn->query($sql);



              if ($result->num_rows > 0) {

                while($row = $result->fetch_assoc()) {

                     $decryptedShopName = $row["shopname"];
                     $decryptedShopOwner = $row["shopownername"];
                     $decryptedShopPhone = $row["ownerphone"];
                     $decryptedShopShortName = $row["shortname"];
                     $decryptedShopPassword = $row["shoppass"];
                     $decryptedShopApproval = $row["approval"];


                     // Use openssl_encrypt() function to encrypt the data
                     $shopNameEncrypted = openssl_encrypt($decryptedShopName, $ciphering,
                                 $encryption_key, $options, $encryption_iv);
                     $shopOwnerEncrypted = openssl_encrypt($decryptedShopOwner, $ciphering,
                                 $encryption_key, $options, $encryption_iv);
                     $shopPhoneEncrypted = openssl_encrypt($decryptedShopPhone, $ciphering,
                                 $encryption_key, $options, $encryption_iv);
                     $shopShortNamencrypted = openssl_encrypt($decryptedShopShortName, $ciphering,
                                 $encryption_key, $options, $encryption_iv);
                     $shopPasswordEncrypted = openssl_encrypt($decryptedShopPassword, $ciphering,
                                 $encryption_key, $options, $encryption_iv);


        //                                echo $usernameencrypted . "<br>";
        //                                echo $userpasswordEncrypted . "<br>";


                     setcookie($cookiemerchantname, $shopNameEncrypted, time() + (86400 * 15), "/"); // 86400 = 1 day
                     setcookie($cookiemerchantowner, $shopOwnerEncrypted, time() + (86400 * 15), "/"); // 86400 = 1 day
                     setcookie($cookiemerchantphone, $shopPhoneEncrypted, time() + (86400 * 15), "/"); // 86400 = 1 day
                     setcookie($cookiemerchantshortname, $shopShortNamencrypted, time() + (86400 * 15), "/"); // 86400 = 1 day
                     // setcookie($cookiemerchantpassword, $shopPasswordEncrypted, time() + (86400 * 15), "/"); // 86400 = 1 day

                   }

                   if(strcmp($decryptedShopApproval, "N")==0){
                     $errorString = 'EX100100';      // User Found and Not Approved
                     echo 'EX100100';
                   }else{
                     $errorString = 'S100';      // User Found and Approved
                     echo $errorString;
                   }


                }else{

                  $errorString = 'E208';      // No User Found
                  echo $errorString;
                }


              $conn->close();

            }






          }  // If No Error Found, Connect To Server Ends











        }   // If User Input Not Empty (Ends Here)





      }    // Server Method Post Ends Here

      if(strcmp($errorString, 'S100')!=0){
        setcookie($cookiemerchantname, "", time() - 3600, "/"); // 86400 = 1 day
        setcookie($cookiemerchantowner, "", time() - 3600, "/"); // 86400 = 1 day
        setcookie($cookiemerchantphone, "", time() - 3600, "/"); // 86400 = 1 day
        setcookie($cookiemerchantshortname, "", time() - 3600, "/"); // 86400 = 1 day
        // setcookie($cookiemerchantpassword, "", time() - 3600, "/"); // 86400 = 1 day
      }

 ?>
