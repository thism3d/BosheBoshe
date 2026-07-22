<?php


  require 'cookiesvariables.php';




  $decryptedFullname = $decryptedEmail = $decryptedPhone = $decryptedDeliveryAddress = $decryptedCityName = $decryptedPassword = "";





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


      $formUsernameError = $formPasswordError = "";
      $errorString = "";
      $formUsername = $formPassword = "";
      $errorcount = 0;


      function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }



      session_start();

      if ($_SERVER["REQUEST_METHOD"] == "POST") {       // Server Method Post Starts Here















        if (empty($_POST["username"]) || empty($_POST["custompassword"])) {
            $errorcount = $errorcount + 1;
            $errorString =  'E808';            // Fill All Starred Fields
        }else{        // If User Input Not Empty (Starts Here)



          //Code for name
          $formUsername = test_input($_POST["username"]);



          $formPassword = test_input($_POST["custompassword"]);


          // Code for Email










          $str = $formUsername;


          $x = 0;

          $isEmail = 0;
          for(; $x<strlen($str); $x++){
            if(!($str[$x]>='0' && $str[$x]<='9')){
              $isEmail = 1;
              break;
            }
          }

          if($isEmail==1){

            if (!filter_var($str, FILTER_VALIDATE_EMAIL)) {
              $errorcount = $errorcount + 1;
              $errorString = 'E909';        // Invalid Email Format
            }

          }else{
            if(strlen($str)==10){
              if($str[0] != '1'){
                $errorcount = $errorcount + 1;
                $errorString = 'E606';        // Place a valid number
              }
              $formUsername = '0' . $formUsername;
            }else if(strlen($str)==11){
              if(!($str[0] == '0' && $str[1] == '1')){
                $errorcount = $errorcount + 1;
                $errorString = 'E606';        // Place a valid number
              }
            }else{
              $errorcount = $errorcount + 1;
              $errorString = 'E606';        // Place a valid number
            }
          }





          echo $errorString;



          if($errorcount <= 0){ // If No Error Found, Connect To Server Starts

            require_once __DIR__ . '/connectserver.php';

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
                $errorString = 'E206';///"Server Connection Failed, Try Again!";
                echo $errorString;
            }else{

              $sql = "";

              if($isEmail==1){
                $sql = 'SELECT fullname, email, phonenumber, deliveryaddress, cityname, passofcustomer FROM customers WHERE email = "'. $formUsername .'" AND passofcustomer="'. $formPassword .'";';
              }else{
                $sql = 'SELECT fullname, email, phonenumber, deliveryaddress, cityname, passofcustomer FROM customers WHERE phonenumber = "'. $formUsername .'" AND passofcustomer="'. $formPassword .'";';
              }

              $result = $conn->query($sql);



                if ($result->num_rows > 0) {

                  while($row = $result->fetch_assoc()) {

                     $decryptedFullname = $row["fullname"];
                     $decryptedEmail = $row["email"];
                     $decryptedPhone = $row["phonenumber"];
                     $decryptedDeliveryAddress = $row["deliveryaddress"];
                     $decryptedCityName = $row["cityname"];
                     $decryptedPassword = $row["passofcustomer"];

                     // Use openssl_encrypt() function to encrypt the data
                     $usernameencrypted = openssl_encrypt($row["fullname"], $ciphering,
                                 $encryption_key, $options, $encryption_iv);
                     $useremailencrypted = openssl_encrypt($row["email"], $ciphering,
                                 $encryption_key, $options, $encryption_iv);
                     $userphoneencrypted = openssl_encrypt($row["phonenumber"], $ciphering,
                                 $encryption_key, $options, $encryption_iv);
                     $userdeliveryaddressencrypted = openssl_encrypt($row["deliveryaddress"], $ciphering,
                                 $encryption_key, $options, $encryption_iv);
                     $usercitynameencrypted = openssl_encrypt($row["cityname"], $ciphering,
                                 $encryption_key, $options, $encryption_iv);
                     $userpasswordEncrypted = openssl_encrypt($row["passofcustomer"], $ciphering,
                                 $encryption_key, $options, $encryption_iv);


        //                                echo $usernameencrypted . "<br>";
        //                                echo $userpasswordEncrypted . "<br>";


                     setcookie($cookiefullname, $usernameencrypted, time() + (86400 * 30), "/"); // 86400 = 1 day
                     setcookie($cookieemail, $useremailencrypted, time() + (86400 * 30), "/"); // 86400 = 1 day
                     setcookie($cookiephone, $userphoneencrypted, time() + (86400 * 30), "/"); // 86400 = 1 day
                     setcookie($cookiedeliveryaddress, $userdeliveryaddressencrypted, time() + (86400 * 30), "/"); // 86400 = 1 day
                     setcookie($cookiecity, $usercitynameencrypted, time() + (86400 * 30), "/"); // 86400 = 1 day
                     setcookie($cookiepassword, $userpasswordEncrypted, time() + (86400 * 30), "/"); // 86400 = 1 day

                   }

                 $errorString = 'S100';      // User Found
                 echo $errorString;


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
        setcookie($cookiefullname, "", time() - 3600, "/"); // 86400 = 1 day
        setcookie($cookieemail, "", time() - 3600, "/"); // 86400 = 1 day
        setcookie($cookiephone, "", time() - 3600, "/"); // 86400 = 1 day
        setcookie($cookiedeliveryaddress, "", time() - 3600, "/"); // 86400 = 1 day
        setcookie($cookiecity, "", time() - 3600, "/"); // 86400 = 1 day
        setcookie($cookiepassword, "", time() - 3600, "/"); // 86400 = 1 day
      }

 ?>
