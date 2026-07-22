<!DOCTYPE html>
<html lang="en">
      <head>

          <title>Member Profile - BosheBoshe</title>


          <?php require_once("headermeta.php"); ?>


          <style>
            #member_order_tablediv{
              overflow-x: scroll;
            }

            .sslPaymentConfirmBtn{
              padding: 3px 9px;
              margin: 2px;
              font-size: 14px;
              border: none;
              background-color: #46b04b;
              color: white;
              cursor: pointer;
            }

            .sslPaymentConfirmBtn:hover{
              box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
            }

          </style>



      </head>
      <body>


<?php


  require 'cookiesvariables.php';




  $decryptedFullname = $decryptedEmail = $decryptedPhone = $decryptedDeliveryAddress = $decryptedCityName = $decryptedPassword = "";
  $finalFormEmail = "";


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




  if(isset($_COOKIE[$cookiefullname])  && isset($_COOKIE[$cookieemail])  && isset($_COOKIE[$cookiephone])  && isset($_COOKIE[$cookiedeliveryaddress]) && isset($_COOKIE[$cookiecity])  && isset($_COOKIE[$cookiepassword])) {

    $decryptedFullname = openssl_decrypt ($_COOKIE[$cookiefullname], $ciphering,
    $decryption_key, $options, $decryption_iv);
    $decryptedEmail = openssl_decrypt ($_COOKIE[$cookieemail], $ciphering,
    $decryption_key, $options, $decryption_iv);
    $decryptedPhone = openssl_decrypt ($_COOKIE[$cookiephone], $ciphering,
    $decryption_key, $options, $decryption_iv);
    $decryptedDeliveryAddress = openssl_decrypt ($_COOKIE[$cookiedeliveryaddress], $ciphering,
    $decryption_key, $options, $decryption_iv);
    $decryptedCityName = openssl_decrypt ($_COOKIE[$cookiecity], $ciphering,
    $decryption_key, $options, $decryption_iv);
    $decryptedPassword = openssl_decrypt ($_COOKIE[$cookiepassword], $ciphering,
    $decryption_key, $options, $decryption_iv);

    $finalFormEmail = $decryptedEmail;

    $userfound = 1;

  }else if(isset($_COOKIE[$cookiefullname]) && isset($_COOKIE[$cookiephone])  && isset($_COOKIE[$cookiedeliveryaddress]) && isset($_COOKIE[$cookiecity])  && isset($_COOKIE[$cookiepassword])) {

    $decryptedFullname = openssl_decrypt ($_COOKIE[$cookiefullname], $ciphering,
    $decryption_key, $options, $decryption_iv);
    $decryptedEmail = "Not Found";
    $decryptedPhone = openssl_decrypt ($_COOKIE[$cookiephone], $ciphering,
    $decryption_key, $options, $decryption_iv);
    $decryptedDeliveryAddress = openssl_decrypt ($_COOKIE[$cookiedeliveryaddress], $ciphering,
    $decryption_key, $options, $decryption_iv);
    $decryptedCityName = openssl_decrypt ($_COOKIE[$cookiecity], $ciphering,
    $decryption_key, $options, $decryption_iv);
    $decryptedPassword = openssl_decrypt ($_COOKIE[$cookiepassword], $ciphering,
    $decryption_key, $options, $decryption_iv);

    $userfound = 1;

  }else{

      session_start();

    setcookie($cookiefullname, "", time() - 3600, "/"); // 86400 = 1 day
    setcookie($cookieemail, "", time() - 3600, "/"); // 86400 = 1 day
    setcookie($cookiephone, "", time() - 3600, "/"); // 86400 = 1 day
    setcookie($cookiedeliveryaddress, "", time() - 3600, "/"); // 86400 = 1 day
    setcookie($cookiecity, "", time() - 3600, "/"); // 86400 = 1 day
    setcookie($cookiepassword, "", time() - 3600, "/"); // 86400 = 1 day

    // $_SESSION["errorsofregistration"] = "User Not Found";
    // header('Location: ' . $serverhost .'/member?do=login');
  }






//
// $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// // echo $actual_link;
//
// $actuallinklength = strlen($actual_link);
//
// $islogout = substr($actual_link, $actuallinklength-6, $actuallinklength-1);









include 'header.php';

// if(strcmp($islogout, "logout")==0){
//
//   echo '<div id="customer_profile_container">
//
//     <div id="not_found_container">
//
//       <img src="images/mirage-no-connection.png.png" alt="Successfully Logged Out">
//
//       <button></button>
//       <button></button>
//
//     </div>
//
//
//   </div>
//
// ';
//
// }else


if($userfound == 1){

  echo '
  <div id="customer_profile_container">




    <div id="personal_information_container">
      <div id="log_out_container">
        <button id="log_out" onclick="clearcookies()">Log Out</button><br>
      </div>
      <img id="image_profile" src="images/undraw_choice_9385.svg">
      <h2 id="myprofileh2">My Profile</h2>
      <div id="inside_personal_information">
        <p class="profile_single_informarion"><span>Name &nbsp;&nbsp;&nbsp;: <span><span>'. $decryptedFullname .'<span></p>
        <p class="profile_single_informarion"><span>Email &nbsp;&nbsp;&nbsp;&nbsp;: <span><span>'. $decryptedEmail .'<span></p>
        <p class="profile_single_informarion"><span>Phone &nbsp;&nbsp;&nbsp;: <span><span>'. $decryptedPhone .'<span></p>
        <p class="profile_single_informarion"><span>Address : <span><span>'. $decryptedDeliveryAddress .'<span></p>
        <p class="profile_single_informarion"><span>City &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span><span>'. $decryptedCityName .'<span></p>


        <br>
        <br>
        <br>
        <!--<button id="edit_profile_btn">Edit Profile</button>-->
      </div>

    </div>


    <div id="change_password_container">

    </div>';


    echo '<div id="order_tracking_container">
      <h2 id="odrderh2">My Orders</h2>

      <div id="member_order_tablediv">';

      require 'connectserver.php';

      $sql = 'SELECT orderno, orderedtime, customerproducts, deliverystatus, customercoupon, status FROM orderbook WHERE customerphone = "'. $decryptedPhone .'" ORDER BY orderno DESC;';
      $result = $conn->query($sql);


      //$image_location . $row["filename"]

      if ($result->num_rows > 0) {
        echo '<table id="order_status_table">
              <tr>
                <th>Date</th>
                <th>Order Code</th>
                <th>Amount</th>
                <th>Delivery</th>
                <th>Payment</th>
              </tr>';


              while($row = $result->fetch_assoc()) {


                if(strcmp($row["status"], "Pending")==0){
                  echo '<tr style="background-color:rgba(176, 70, 123, 0.2)">';
                }else{
                  echo '<tr>';
                }
                echo '<td>'. substr($row["orderedtime"], 0, 10) .'</td>
                  <td><a href="ordersummary?orderid='.  $row["orderno"] .'">'. $row["orderno"] .'</a></td>';


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

                    // echo '<div class="single_product_of_order clearfix">
                    //     <p>'. intval($x+1) .'. ('. $singleProductArray[0] .') '. $singleProductArray[1] .' ('. $singleProductArray[2] .' x '. $singleProductArray[4] .') </p>
                    //     <p>'. $singleProductArray[5] .' TK</p>
                    //   </div>';


                    unset($singleProductArray);
                    $singleProductArray = array();

                  }


            echo '<td>';


            $totalValueToPay = 0;


            if( strcmp( $row["customercoupon"], "besafe")==0 ){
              if($totalBesafeCounter>100){
                $totalValueToPay = intval($totalAmountCalculator-100+30);
                echo $totalValueToPay;
              }else{
                $totalValueToPay = intval($totalAmountCalculator-$totalBesafeCounter+30);
                echo $totalValueToPay;
              }

            }else{
              $totalValueToPay = $totalAmountCalculator + 30;
              echo $totalValueToPay;
            }

            if($totalValueToPay<30){
              $totalValueToPay = $totalAmountCalculator + 30;
            }


            echo '</td>
                  <td>'. ucfirst($row["deliverystatus"]) .'</td>';

                  if(strcmp($row["status"], "Cancelled")==0){
                    echo '<td>Not Paid</td>';
                  }else if(strcmp($row["status"], "Success")==0){
                    echo '<td>Paid</td>';
                  }else if(strcmp($row["status"], "Pending")==0){
                    echo '<td style="padding: 3px 0px;">
                      <p style="font-size: 12px;">Not Paid</p>




                      <div>
                      <form action="sslpayment" name="sslpayment"  method="post">
                      <input type="hidden" placeholder="Full Name" id="fname" name="fullname" autocomplete="off" maxlength="80" required value="'. $decryptedFullname .'" readonly >
                      <input type="hidden"  placeholder="Phone" id="fphone"  name="phonenumber" value="'. $decryptedPhone .'" readonly autocomplete="off" required maxlength="11">
                      <input type="hidden"  placeholder="Enter Email (To Get Recipt)" id="femail" name="useeremail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" autocomplete="off" maxlength="230"  value="'. $finalFormEmail .'" title="Enter Valid Email" readonly>
                      <input type="hidden" placeholder="Product Delivery Address" id="faddress" name="delivery_address" autocomplete="off" required maxlength="230" pattern=".{6,}" required title="Enter Valid Address" value="'. $decryptedDeliveryAddress .'" readonly>
                      <input type="hidden" placeholder="City" id="fcity" name="city_address" autocomplete="off" required maxlength="40" pattern=".{3,}" title="Enter Valid City" value="'. $decryptedCityName .'" readonly>
                      <input type="hidden" placeholder="Total Payment" id="ftotalpayment" name="user_total_payment" autocomplete="off" required value="'. $totalValueToPay .'" readonly>
                      <input type="hidden" placeholder="Order ID" id="fordercode" name="userodercode" autocomplete="off" required value="'. $row["orderno"] .'" readonly>

                      <input class="sslPaymentConfirmBtn" type="submit" value="Pay '. $totalValueToPay .' TK">
                      </form>
                      </div>



                    </td>';
                  }else{
                    echo '<td>Not Paid</td>';
                  }



          echo '</tr>';
              }


        echo '</table>';



      }else{
        echo '<h2 style="padding: 15px 0px;">No Order Found</h2>';
      }












    echo '</div>

    </div>


  </div>





  ';
}else{

  echo '<div id="profile_system_container">

    <div id="not_found_container">

      <h2>No Data Found</h2>

      <img src="images/undraw_no_data_qbuo.svg"><br>

      <a href="member.php?do=login"><button class="same_button_log">Log In</button></a>
      <a href="startshopping"><button class="same_button_log">Go Shopping</button></a>

    </div>


  </div>

';

}

















echo '<script>


  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    // location.reload();
  }

  function clearcookies(){
    setCookie("'. $cookiefullname .'", "", -1);
    setCookie("'. $cookieemail .'", "", -1);
    setCookie("'. $cookiephone .'", "", -1);
    setCookie("'. $cookiedeliveryaddress .'", "", -1);
    setCookie("'. $cookiecity .'", "", -1);
    setCookie("'. $cookiepassword .'", "", -1);

    location.reload();
  }


</script>';













        include 'footer.php';
?>


      </body>
<html>
