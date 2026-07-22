<!DOCTYPE html>
<html lang="en">
      <head>

          <title>Gas Refill Request - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>


          <?php

          session_start();
          require_once("headermeta.php");

          ?>



          <style>


            #gas_exchnage_picture_holder{
              width: 100%;
              box-sizing: border-box;
              padding: 18px;
            }


            #gas_exchange_picture{
              width: 100%;
              max-width: 400px;
              border: none;
            }

            #gas_exchange_system{
              text-align: center;
            }


            #gas_cylinder_request_header{
              padding: 20px 0px 20px 0px;
              background-color: #525252;
              color: #fafafa;
            }








            #gas_exchange_form_container{
                box-sizing: border-box;
                padding: 20px;
                max-width: 800px;
                margin: 0 auto;
                border: 0.8px solid #adadad;
                margin-top: 20px;
                margin-bottom: 20px;
                border-radius: 5px;
            }


            #gas_exchange_form_container form{
              font-family: 'Roboto', sans-serif;
              padding: 30px 0;
            }


            #gas_exchange_form_container label{
              color: #3b3b3b;
              font-size: 15px;
              float: left;
            }

            #gas_exchange_form_container input{
              box-sizing: border-box;
              background-color: inherit;
              width: 100%;
              border: none;
              border-bottom: 2px solid black;
              outline: none;
              padding: 4px;
              font-size: 16px;
              margin-bottom: 20px;
              -webkit-transition-duration: 0.4s; /* Safari */
              transition-duration: 0.4s;
            }

            #gas_exchange_form_container input:focus{
              border-bottom: 2px solid red;
            }


            #gas_exchange_form_container input[type=submit]{
              width: 100%;
              background-color: #3c72a3;
              color: white;
              padding: 10px 20px;
              margin: 8px 0;
              border: none;
              border-radius: 4px;
              cursor: pointer;
              font-size: 16px;
              font-weight: bold;
              outline: none;
            }

            #gas_exchange_form_container input[type=submit]:hover{
              background-color: #1c80d9;
            }








          </style>




      </head>
      <body>


<?php

    require 'cookiesvariables.php';


    // Decryption System Starts Here

    // Store the cipher method
    $ciphering = "AES-128-CTR";

    // Use OpenSSl Encryption method
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;

    // Non-NULL Initialization Vector for decryption
    $decryption_iv = '8675992784945782';

    // Store the decryption key
    $decryption_key = "MayeshaMeemMuzahidIslam";


    // Decryption System Starts Here



    // Intitializing Information
    $decryptedFullname = "";
    $decryptedAddress = "";
    $decryptedCity = "";
    $decryptedPhone = "";


    $foundPreviousIdData = 0;




    if(isset($_COOKIE[$cookiefullname]) && isset($_COOKIE[$cookiephone]) && isset($_COOKIE[$cookiedeliveryaddress]) && isset($_COOKIE[$cookiephone])) {

      $decryptedFullname = openssl_decrypt ($_COOKIE[$cookiefullname], $ciphering,
      $decryption_key, $options, $decryption_iv);
      $decryptedAddress = openssl_decrypt ($_COOKIE[$cookiedeliveryaddress], $ciphering,
      $decryption_key, $options, $decryption_iv);
      $decryptedCity = openssl_decrypt ($_COOKIE[$cookiecity], $ciphering,
      $decryption_key, $options, $decryption_iv);
      $decryptedPhone = openssl_decrypt ($_COOKIE[$cookiephone], $ciphering,
      $decryption_key, $options, $decryption_iv);

      $foundPreviousIdData = 1;

    }else{
      $decryptedFullname = "";
      $decryptedAddress = "";
      $decryptedCity = "";
      $decryptedPhone = "";
    }






    include 'header.php';


?>















<div id="gas_exchange_system">

  <div id="gas_exchnage_picture_holder">
    <img id="gas_exchange_picture" src="images/undraw_abstract_x68e.svg">
  </div>

  <h2 id="gas_cylinder_request_header">Request Gas Cylinder Refill</h2>


  <p id="notification_of_invalidation" style="text-align:center;padding: 30px 0px 0px 0px; color: red;">
    <?php

      if(isset($_SESSION["errorOfGasSubmission"])){
        echo $_SESSION["errorOfGasSubmission"];
        $_SESSION["errorOfGasSubmission"] = "";
      }

    ?>
  </p>



  <div id="gas_exchange_form_container">
    <form name="gas_exchange_form" method="post" action="gas_exchange" onsubmit="return validationGasExchangeForm()">

      <label for="cylindernameid">Cylinder Name<span class="requiredasterisk"> *</span></label>
      <input type="text" placeholder="e.g. Bashundhara" id="cylindernameid" name="cylindername" autocomplete="off" autofocus maxlength="20" required pattern=".{3,}" required title="Enter Valid Cylinder Name">

      <?php
        if($foundPreviousIdData==1){
          echo '<label for="fname">Customer Name<span class="requiredasterisk"> *</span></label>
          <input type="text" placeholder="Full Name" id="fname" value="'. $decryptedFullname .'" name="fullname" autocomplete="off" autofocus maxlength="80" required readonly="true">

          <label for="fphone">Phone Number<span class="requiredasterisk"> *</span></label>
          <input onchange="checkRegistrationFormPhoneNumber()" type="number" value="'. $decryptedPhone .'"  placeholder="Phone" id="fphone"  name="phonenumber" autocomplete="off" required maxlength="11" readonly="true">

          <label for="faddress">Product Delivery Address<span class="requiredasterisk"> *</span></label>
          <input type="text" placeholder="Product Delivery Address" id="faddress" name="delivery_address" value="'. $decryptedAddress .'" autocomplete="off" required maxlength="230" pattern=".{6,}" required title="Enter Valid Address">


          <label for="fcity">City<span class="requiredasterisk"> *</span></label>
          <input type="text" placeholder="City" id="fcity" name="city_address" autocomplete="off" value="'. $decryptedCity .'" required maxlength="40" pattern=".{3,}" required title="Enter Valid City">
          ';
        }else{
          echo '<label for="fname">Customer Name<span class="requiredasterisk"> *</span></label>
          <input type="text" placeholder="Full Name" id="fname" name="fullname" autocomplete="off" autofocus maxlength="80" required>

          <label for="fphone">Phone Number<span class="requiredasterisk"> *</span></label>
          <input onchange="checkRegistrationFormPhoneNumber()" type="number"  placeholder="Phone" id="fphone"  name="phonenumber" autocomplete="off" required maxlength="11">

          <label for="faddress">Product Delivery Address<span class="requiredasterisk"> *</span></label>
          <input type="text" placeholder="Product Delivery Address" id="faddress" name="delivery_address" autocomplete="off" required maxlength="230" pattern=".{6,}" required title="Enter Valid Address">


          <label for="fcity">City<span class="requiredasterisk"> *</span></label>
          <input type="text" placeholder="City" id="fcity" name="city_address" autocomplete="off" required maxlength="40" pattern=".{3,}" required title="Enter Valid City">
          ';
        }
       ?>


      <input type="submit" value="Request">
    </form>
  </div>







</div>




<!-- <script>


var notification_of_invalidation = document.getElementById("notification_of_invalidation");

var fphone;
var fphonevalue;

if(document.getElementById("fphone")){
  fphone = document.getElementById("fphone");
  fphonevalue = fphone.value;
}


function checkRegistrationFormPhoneNumber(){
  fphonevalue = fphone.value;
  // console.log(fphonevalue);
  if(fphonevalue<0 || fphonevalue>99999999999){
    fphone.value = 0;
  }
}









function validationGasExchangeForm() {


  var gascylindername = document.forms["gas_exchange_form"]["cylindername"].value;
  var gascylindernamelength = gascylindername.length;


  var deliveryaddresslength = document.forms["gas_exchange_form"]["cylindername"].value.length;
  var cityaddresslength = document.forms["gas_exchange_form"]["city_address"].value.length;



  if(gascylindernamelength<4 && deliveryaddresslength<4 && cityaddresslength<3){
    notification_of_invalidation.style.display = "block";
    notification_of_invalidation.innerHTML = "Place valid informations";
    return false;
  }


  var regex = /^[A-Za-z]+$/

  //Validate TextBox value against the Regex.
  var isValid = regex.test(gascylindername);
  if (!isValid) {
    notification_of_invalidation.style.display = "block";
    notification_of_invalidation.innerHTML = "Place valid cylinder name";
    return false;
  }



  var gasregistrationfullname = document.forms["gas_exchange_form"]["fullname"].value;
  var namelength = gasregistrationfullname.length;

  for(var i = 0; i< namelength; i++){
    if(!(gasregistrationfullname.charAt(i)>='a' && gasregistrationfullname.charAt(i)<='z') && !(gasregistrationfullname.charAt(i)>='A' && gasregistrationfullname.charAt(i)<='Z') && !(gasregistrationfullname.charAt(i)==' ') && !(gasregistrationfullname.charAt(i)=='.')){
      notification_of_invalidation.style.display = "block";
      notification_of_invalidation.innerHTML = "Must not contain number, or other special character";
      return false;
    }
  }


  if(namelength<8 || namelength>59){
    notification_of_invalidation.style.display = "block";
    notification_of_invalidation.innerHTML = "Name must be more between 8 to 60 character";
    return false;
  }


  if (gasregistrationfullname == "") {
    notification_of_invalidation.style.display = "block";
    notification_of_invalidation.innerHTML = "Fill name field";
    return false;
  }


  var gasformphone = document.forms["gas_exchange_form"]["phonenumber"].value;
  var gasformphonestring = gasformphone.toString();

  if (gasformphonestring.length==11) {
    if(!(gasformphonestring.charAt(0)=='0' && gasformphonestring.charAt(1)=='1')){
      notification_of_invalidation.style.display = "block";
      notification_of_invalidation.innerHTML = "Place Valid Phone Number";
      return false;
    }
  }else if (gasformphonestring.length==10) {
    if(gasformphonestring.charAt(0)!='1'){
      notification_of_invalidation.style.display = "block";
      notification_of_invalidation.innerHTML = "Place Valid Phone Number";
      return false;
    }
  }


  if(gasformphonestring.length<10){
    notification_of_invalidation.style.display = "block";
    notification_of_invalidation.innerHTML = "Place Valid Phone Number";
    return false;
  }




}

</script> -->









<!-- <script src="addtocartjavascript.js"></script> -->







<?php
        include 'footerforcart.php';
?>


      </body>
<html>
