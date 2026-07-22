<!DOCTYPE html>
<html lang="en">
      <head>

          <title>Proceed Cart</title>


          <?php require_once("headernofollowmeta.php"); ?>


          <style>
            #choose_payment_option_container{
              border-top: 0.8px solid #adadad;
              padding: 10px 0;
            }


            #left_choose_payment{
              width: 45%;
              text-align: right;
              padding: 8px 10px;
              float: left;
            }

            #right_choose_payment{
              width: 45%;
              text-align: left;
              float: left;
            }


            #right_choose_payment button{
              padding: 8px 15px;
              font-family: 'Roboto', sans-serif;
              outline: none;
              cursor: pointer;
            }


            .disabledbtn{
              background-color: #999999;
              color: #ffffff;
              border: 2px solid #999999;
              opacity: 0.9;
            }

            .disabledbtn:hover{
              background-color: #858585;
            }


            .selectedbtn{
              background-color: #4CAF50;
              color: white;
              border: 2px solid #4CAF50;
              box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
            }

            .selectedbtn:hover{
              background-color: #469e49;
            }

            #cash_delivery_button{

            }

            #online_payment_button{

            }

            #sslczPayBtn, #validation_of_form, #login_first_btn{
              width: 90%;
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


            #sslczPayBtn:hover, #validation_of_form:hover, #login_first_btn:hover{
              background-color: #1c80d9;
            }

            #login_first_btn{
              box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
            }


            #hidden_payment_text{
              display: none;
            }

            #visible_payment_text{
              display: inline;
            }



            #form_of_cart input[type=submit] {
              width: 90%;
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

            #form_of_cart input[type=submit]:hover {
              background-color: #1c80d9;
            }



            @media only screen and (max-width: 550px) {
              #left_choose_payment{
                width: 40%;
              }

              #right_choose_payment{
                width: 60%;
              }
            }

            @media only screen and (max-width: 470px) {

              #left_choose_payment{
                width: 30%;
                text-align: center;
              }

              #right_choose_payment{
                width: 70%;
              }

              #hidden_payment_text{
                display: inline;
              }
              #visible_payment_text{
                display: none;
              }

            }

            @media only screen and (max-width: 380px) {
              #left_choose_payment{
                width: 25%;
                text-align: center;
              }

              #right_choose_payment{
                width: 75%;
              }

              #right_choose_payment button{
                padding: 8px 10px;
              }
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
    $decryptedEmail = "";


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

      if(isset($_COOKIE[$cookieemail])){
        $decryptedEmail = openssl_decrypt ($_COOKIE[$cookieemail], $ciphering,
        $decryption_key, $options, $decryption_iv);
      }else{
        $decryptedEmail = "";
      }


      $foundPreviousIdData = 1;

    }else{
      $decryptedFullname = "";
      $decryptedAddress = "";
      $decryptedCity = "";
      $decryptedPhone = "";
      $decryptedEmail = "";
    }





    include 'header.php';
?>














<div id="order_cart_container">

  <p style="color: red;text-align: left;box-sizing: border-box;padding: 10px;padding-bottom: 20px;">*Important Notice: Outside Dinajpur Sadar, fruits and vegetables won't be delivered now.<br><br>Vegetables, Fruits, Fish and Meat price may vary. Final price depends on weight and other factors.</p>


  <h2 id="my_cart_order_h2"><i class="fa fa-check-square-o"></i> Your Cart ( <span id="mycartitemcountspan">0</span> Items )</h2>
  <div id="main_order_form">




    <!-- <div class="singleproduct clearfix">
      <div class="left_side_single1">Garlic 90g</div>
      <div class="right_side_single1">1 piece</div>

      <div class="left_side_single2"><img src="tk_wikipedia_icon.svg.png"> 900</div>
      <div class="right_side_single2">

        <div class="right_side_single2_inside clearfix">
          <button class="left_button_single_cart"><i class="fa fa-minus"></i></button>
          <button class="middle_button_single_cart">1</button>
          <button class="right_button_single_cart"><i class="fa fa-plus"></i></button>
        </div>

      </div>
    </div>





    <div class="singleproduct clearfix">
      <div class="left_side_single1">Mirch Cookies 90g</div>
      <div class="right_side_single1">1 piece</div>

      <div class="left_side_single2"><img src="tk_wikipedia_icon.svg.png"> 900</div>
      <div class="right_side_single2">

        <div class="right_side_single2_inside clearfix">
          <button class="left_button_single_cart"><i class="fa fa-minus"></i></button>
          <button class="middle_button_single_cart">1</button>
          <button class="right_button_single_cart"><i class="fa fa-plus"></i></button>
        </div>

      </div>
    </div>



    <div class="singleproduct clearfix">
      <div class="left_side_single1">Chilli Mirch 100g</div>
      <div class="right_side_single1">1 piece</div>

      <div class="left_side_single2"><img src="tk_wikipedia_icon.svg.png"> 900</div>
      <div class="right_side_single2">

        <div class="right_side_single2_inside clearfix">
          <button class="left_button_single_cart"><i class="fa fa-minus"></i></button>
          <button class="middle_button_single_cart">1</button>
          <button class="right_button_single_cart"><i class="fa fa-plus"></i></button>
        </div>

      </div>
    </div> -->




  </div>

  <div id="coupon_withdrawl_container">
    <div class="clearfix" id="coupon_input_system_div">
      <input id="coupon_input_system" type="text" maxlength="6" onchange="" placeholder="Coupon Code"><button onclick="applycoupon()" id="apply_coupon_btn">Apply</button>
    </div>
  </div>


  <div id="total_price_calculator">
    <div class="total_price_inside">
      <p>Subtotal Price: </p>
      <p>Offer Less: </p>
      <p>Delivery Charge: </p>
      <p>Total Price: </p>
    </div>
    <div class="total_price_inside">
      <p id="subtotal_price_of_cart">0 TK</p>
      <p id="total_offer_of_cart">0 TK</p>
      <p id="delivery_charge_of_cart">30 TK</p>
      <p id="total_price_of_cart">0 TK</p>
    </div>
  </div>

  <div id="choose_payment_option_container" class="clearfix">
    <div id="left_choose_payment"><span id="visible_payment_text">CHOOSE PAYMENT OPTION</span><span id="hidden_payment_text">PAYMENT</span></div>
    <div id="right_choose_payment">
      <button id="cash_delivery_button" onclick="cashBtnClicked()" class="disabledbtn"> Cash On Delivery</button>
      <button id="online_payment_button" onclick="onlinePaymentClicked()" class="selectedbtn"><i class="fa fa-check" aria-hidden="true"></i> Online Payment</button>
    </div>
  </div>



  <h2 id="my_cart_order_submit_form_h2">Check Your Information</h2>





  <?php
    if($foundPreviousIdData==1){

      echo '<div id="form_of_cart">
        <p style="color: red; padding: 10px 0px; font-size: 16px;" id="error_found_p"></p>
        <label  for="fname">Full Name: <span id="nameOfRegisteredUser">'. $decryptedFullname .'</span><span class="requiredasterisk"> *</span></label>
        <!--<input type="text" placeholder="Full Name" id="fname" name="fullname" autocomplete="off" maxlength="80" required disabled value="'. $decryptedFullname .'">-->
        <label for="fphone">Phone Number: <span id="phoneOfRegisteredUser">'. $decryptedPhone .'</span><span class="requiredasterisk"> *</span></label>
        <br>
        <!--<input onchange="checkRegistrationFormPhoneNumber()" type="number"  placeholder="Phone" id="fphone"  name="phonenumber" autocomplete="off" required maxlength="11" disabled value="'. $decryptedPhone .'">-->
        <label for="faddress">Product Delivery Address<span class="requiredasterisk"> *</span></label>
        <input type="text" placeholder="Product Delivery Address" id="faddress" name="delivery_address" autocomplete="off" required maxlength="230" pattern=".{6,}" required title="Enter Valid Address" value="'. $decryptedAddress .'">
        <label for="fcity">City<span class="requiredasterisk"> *</span></label>
        <input type="text" placeholder="City" id="fcity" name="city_address" autocomplete="off" required maxlength="40" pattern=".{3,}" required title="Enter Valid City" value="'. $decryptedCity .'">

      </div>';
    }else{
      echo '<div id="form_of_cart">
        <p style="color: red; padding: 10px 0px; font-size: 16px;" id="error_found_p"></p>
        <label for="fname">Full Name<span class="requiredasterisk"> *</span></label>
        <input type="text" placeholder="Full Name" id="fname" name="fullname" autocomplete="off" maxlength="80" required>
        <label for="fphone">Phone Number<span class="requiredasterisk"> *</span></label>
        <input onchange="checkRegistrationFormPhoneNumber()" type="number"  placeholder="Phone" id="fphone"  name="phonenumber" autocomplete="off" required maxlength="11">
        <label for="faddress">Product Delivery Address<span class="requiredasterisk"> *</span></label>
        <input type="text" placeholder="Product Delivery Address" id="faddress" name="delivery_address" autocomplete="off" required maxlength="230" pattern=".{6,}" required title="Enter Valid Address">
        <label for="fcity">City<span class="requiredasterisk"> *</span></label>
        <input type="text" placeholder="City" id="fcity" name="city_address" autocomplete="off" required maxlength="40" pattern=".{3,}" required title="Enter Valid City">



      </div>';
    }

   ?>




  <div id="button_for_cash_delivery">

  </div>





</div>



  <script src="proceedtocartjavascript_1.1.js"></script>







  <!-- JS For Oline Payment -->
  <script>



  <?php
    if($foundPreviousIdData==1){


      echo 'var presentUser = 1;';
      echo 'var registeredUserName = "'. $decryptedFullname .'";';
      echo 'var registeredUserPhone = "'. $decryptedPhone .'";';
      echo 'var registeredUserEmail = "'. $decryptedEmail .'";';
      echo 'var registeredUserAddress = "'. $decryptedAddress .'";';
      echo 'var registeredUserCity = "'. $decryptedCity .'";';
    }else{
      echo 'var presentUser = 0;';
    }


   ?>

   var form_of_cart = document.getElementById("form_of_cart");

   var form_of_cart_previous_html = form_of_cart.innerHTML;






    var total_price_of_cart_for_price = document.getElementById("total_price_of_cart");
    var exactValueOfPrice;

    var exactTotalInteger;


    var sslczPayBtnForServer = document.getElementById("sslczPayBtn");

    function validateAmount() {
      exactValueOfPrice = total_price_of_cart_for_price.innerHTML.split(' ');
      exactTotalInteger = parseInt(exactValueOfPrice[0]);

    }




    var button_for_cash_delivery = document.getElementById("button_for_cash_delivery");

    var cashButtonConfirm = '<button id="confirm_cart_order" onclick="processTheAjaxHere()">Confirm Order</button>';
    var onlinePayConfirm = '<button id="validation_of_form" onclick="validateData()">GO FOR CHECKOUT</button>';
    var onlinePayConfirmFinal = '<button id="sslczPayBtn" onclick="validateAmount()" order="120123" postdata="" endpoint="checkout_ajax.php" actionurl="checkout_ajax.php">Confirm &amp; Pay</button>';


    var right_choose_payment = document.getElementById("right_choose_payment");
    var cash_button = document.getElementById("cash_delivery_button");
    var online_button = document.getElementById("online_payment_button");

    var cashSelectedString = '<button id="cash_delivery_button" onclick="cashBtnClicked()" class="selectedbtn"><i class="fa fa-check" aria-hidden="true"></i> Cash On Delivery</button>'+
    '<button id="online_payment_button" onclick="onlinePaymentClicked()" class="disabledbtn">Online Payment</button>';

    var onlinSelectedString = '<button id="cash_delivery_button" onclick="cashBtnClicked()" class="disabledbtn"> Cash On Delivery</button>' +
    '<button id="online_payment_button" onclick="onlinePaymentClicked()" class="selectedbtn"><i class="fa fa-check" aria-hidden="true"></i> Online Payment</button>';


    function cashBtnClicked() {
      right_choose_payment.innerHTML = cashSelectedString;
      button_for_cash_delivery.innerHTML = cashButtonConfirm;

      form_of_cart.innerHTML = form_of_cart_previous_html;
    }



    function onlinePaymentClicked() {
      right_choose_payment.innerHTML = onlinSelectedString;
      button_for_cash_delivery.innerHTML = "";




       if(presentUser==1){              //User Found
        var userFoundHTML = '<p style="color: red; padding: 10px 0px; font-size: 16px;" id="error_found_p"></p>' +
          '<form name="confirm_payment_cart_form" method="post" action="payment_proceed" onsubmit="return validatepaymentproceed()">' +
          '<label for="fname">Full Name<span class="requiredasterisk"> *</span></label>' +
          '<input type="text" placeholder="Full Name" id="fname" name="fullname" autocomplete="off" maxlength="80" required value="'+ registeredUserName +'" readonly>' +
          '<label for="fphone">Phone Number<span class="requiredasterisk"> *</span></label>' +
          '<input onchange="checkRegistrationFormPhoneNumber()" type="number"  placeholder="Phone" id="fphone"  name="phonenumber" value="'+ registeredUserPhone +'" readonly autocomplete="off" required maxlength="11">' +
          '<label for="femail">Email</label>' +
          '<input type="text"  placeholder="Enter Email (To Get Recipt)" id="femail" name="useeremail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" autocomplete="off" maxlength="230"  value="'+ registeredUserEmail +'" title="Enter Valid Email">' +
          '<label for="faddress">Product Delivery Address<span class="requiredasterisk"> *</span></label>' +
          '<input type="text" placeholder="Product Delivery Address" id="faddress" name="delivery_address" autocomplete="off" required maxlength="230" pattern=".{6,}" required title="Enter Valid Address" value="'+ registeredUserAddress +'">' +
          '<label for="fcity">City<span class="requiredasterisk"> *</span></label>' +
          '<input type="text" placeholder="City" id="fcity" name="city_address" autocomplete="off" required maxlength="40" pattern=".{3,}" required title="Enter Valid City" value="'+ registeredUserCity +'">' +
          '<input id="sslFormSubmit" type="submit" value="Confirm &amp; Pay">' +
          '</form>'
          ;

        form_of_cart.innerHTML = userFoundHTML;
       }else{                           // User Not Found
         var userNotFoundHTML = '<a style="display:inline-block;" id="login_first_btn" href="member">Login First</a>';
         form_of_cart.innerHTML = userNotFoundHTML;
       }


    }





    cashBtnClicked();


    function validatepaymentproceed() {
      var error_found_p = document.getElementById("error_found_p");

      var paymentProceedEmail = document.forms["confirm_payment_cart_form"]["useeremail"].value;
      paymentProceedEmail.trim();

      if(paymentProceedEmail.length>0){
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(!paymentProceedEmail.match(mailformat)){
          error_found_p.innerHTML = "Place a valid email";
          return false;
        }
      }

      var paymentProceedAddress = document.forms["confirm_payment_cart_form"]["delivery_address"].value;
      paymentProceedAddress.trim();

      var paymentProceedCity =  document.forms["confirm_payment_cart_form"]["city_address"].value;
      paymentProceedCity.trim();

      if(paymentProceedAddress.length<6 || paymentProceedCity.length<3){
        error_found_p.innerHTML = "Place a valid address";
        return false;
      }

      if(parseInt(document.getElementById("total_price_of_cart").innerHTML)<30){
        error_found_p.innerHTML = "Minimum Order 30 TK";
        return false;
      }


    }




  </script>







  <script>


    var fphone;
    var fphonevalue;

    function checkRegistrationFormPhoneNumber(){
      fphone  = document.getElementById("fphone");
      fphonevalue = fphone.value;
      // console.log(fphonevalue);
      if(fphonevalue<0 || fphonevalue>99999999999){
        fphone.value = 0;
      }
    }

  </script>







<?php
        include 'footerforcart.php';
?>



      </body>
<html>
