<!DOCTYPE html>
<html lang="en">
      <head>

          <title> Member Login Registration - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>


          <?php require_once("headermeta.php"); ?>


          <style>
            #forget_password_anchor{
              display: inline-block;
              padding: 10px 0px 0px 14px;
              font-size: 16px;
            }
          </style>



      </head>
      <body>





<?php



$reponse = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["do"])) {
    $reponse = test_input($_GET["do"]);
  }else{
    $reponse = "login";
  }
}


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}





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




$decryptedFullname = "";


if(isset($_COOKIE[$cookiefullname])  && isset($_COOKIE[$cookieemail])  && isset($_COOKIE[$cookiephone])  && isset($_COOKIE[$cookiedeliveryaddress]) && isset($_COOKIE[$cookiecity])  && isset($_COOKIE[$cookiepassword])) {

  $decryptedFullname = openssl_decrypt ($_COOKIE[$cookiefullname], $ciphering,
  $decryption_key, $options, $decryption_iv);

}else{
  $decryptedFullname = "";


    setcookie($cookiefullname, "", time() - 3600, "/"); // 86400 = 1 day
    setcookie($cookieemail, "", time() - 3600, "/"); // 86400 = 1 day
    setcookie($cookiephone, "", time() - 3600, "/"); // 86400 = 1 day
    setcookie($cookiedeliveryaddress, "", time() - 3600, "/"); // 86400 = 1 day
    setcookie($cookiecity, "", time() - 3600, "/"); // 86400 = 1 day
    setcookie($cookiepassword, "", time() - 3600, "/"); // 86400 = 1 day
}







session_start();





include 'header.php';






if (strcmp($decryptedFullname, "")!=0) {

  $token = strtok($decryptedFullname, " ");



  echo '<div id="registration_confirmation">


      <img src="images/undraw_sign_in_e6hj.svg">
                <h2>Hello '. $token .'!</h2>
                <a href="profile"><button id="go_to_profile_afterreg">My Profile</button></a>
                <a href="startshopping"><button id="start_shopping_afterreg">Start Shopping</button></a>

    </div>';
}else{











      echo '<div id="image_div_container">
        <img src="images/undraw_sign_in_e6hj.svg">
      </div>';





        echo '<div id="final_containeer">
          <div id="button_system" class="clearfix">
            <div id="left_button_div">
              <button id="login_btn" onclick="login_btn_clicked()">Login</button>
            </div>
            <div id="right_button_div" onclick="registration_btn_clicked()">
              <button id="registration_btn">Registration</button>
            </div>
          </div>

            <p id="php_notification_of_invalidation" style="text-align:center;padding: 30px 0px 0px 0px; color: red;">';

                if(isset($_SESSION["errorsofregistration"])){
                  echo $_SESSION["errorsofregistration"];
                  $_SESSION["errorsofregistration"] = "";
                }

            echo '</p>


            <h2 id="loadingshowh2" style="text-align:center; padding: 0px 0px 10px 0px; display: none;">
              <i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i> Please Wait
            </h2>


          <div id="login_form">
            <form  name="customer_login_form" method="post"  onsubmit="return validateLoginForm()">
              <label for="fuser">Email/Phone Number</label>
              <input type="text"  placeholder="Enter Number or Email" id="fuser" name="username" autocomplete="off" autofocus pattern=".{8,}" required title="Place Valid Email or Phone">
              <label for="fpass">Password</label>
              <input type="password"  placeholder="Enter Password" name="custompassword" id="fpass" autocomplete="off" pattern=".{6,}" required title="Enter Correct Password">
              <input type="submit" value="Login" name="userloginformsubmit">
            </form>

            <a id="forget_password_anchor" href="forget_password">Forget Password</a>
          </div>

          <div id="registration_form" style="display: none;">
            <form name="customer_registration_form" method="post" action="registration" onsubmit="return validateRegistrationForm()">
              <label for="fname">Full Name<span class="requiredasterisk"> *</span></label>
              <input type="text" placeholder="Full Name" id="fname" name="fullname" autocomplete="off" autofocus maxlength="80" required>
              <label for="femail">Email</label>
              <input type="text"  placeholder="Email" id="femail" name="useeremail" autocomplete="off" maxlength="230" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="Enter Valid Email">
              <label for="fphone">Phone Number<span class="requiredasterisk"> *</span></label>
              <input onchange="checkRegistrationFormPhoneNumber()" type="number"  placeholder="Phone" id="fphone"  name="phonenumber" autocomplete="off" required maxlength="11">
              <label for="faddress">Product Delivery Address<span class="requiredasterisk"> *</span></label>
              <input type="text" placeholder="Product Delivery Address" id="faddress" name="delivery_address" autocomplete="off" required maxlength="230" pattern=".{6,}" required title="Enter Valid Address">
              <label for="fcity">City<span class="requiredasterisk"> *</span></label>
              <input type="text" placeholder="City" id="fcity" name="city_address" autocomplete="off" required maxlength="40" pattern=".{3,}" required title="Enter Valid City">
              <label for="frpass">Password<span class="requiredasterisk"> *</span></label>
              <input type="password"  placeholder="Enter Password" id="frpass" name="password" autocomplete="off" required maxlength="128" pattern=".{6,}" required title="Minimum 6 Characters">
              <input type="submit" value="Registration">
            </form>
          </div>



          <p id="notification_of_invalidation" style="text-align:center;padding: 30px 0px 0px 0px; color: red; display: none;">Error: </p>

        </div>

        <br>
        <br>
        <br>
        <br>';












}







?>






























          <script>

            var login_btn = document.getElementById("login_btn");
            var registration_btn = document.getElementById("registration_btn");


            var login_form = document.getElementById("login_form");
            var registration_form = document.getElementById("registration_form");



            var css = '#registration_btn:hover, #login_btn:hover{ box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19); background-color: #1c80d9; }';
            var style = document.createElement('style');





            function  login_btn_clicked(){
              registration_form.style.display = "none";
              login_form.style.display = "block";
              login_btn.style.backgroundColor = "#1c80d9";
              login_btn.style.boxShadow = "0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19)";
              registration_btn.style.backgroundColor = "#3c72a3";
              registration_btn.style.boxShadow = "none";
            }

            function  registration_btn_clicked(){
              registration_form.style.display = "block";
              login_form.style.display = "none";
              registration_btn.style.backgroundColor = "#1c80d9";
              registration_btn.style.boxShadow = "0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19)";
              login_btn.style.backgroundColor = "#3c72a3";
              login_btn.style.boxShadow = "none";
            }


          </script>














          <!-- Form Validation -->

          <script>



                var notification_of_invalidation = document.getElementById("php_notification_of_invalidation");

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









                function validateRegistrationForm() {
                  var registrationformname = document.forms["customer_registration_form"]["fullname"].value;
                  var namelength = registrationformname.length;

                  for(var i = 0; i< namelength; i++){
                    if(!(registrationformname.charAt(i)>='a' && registrationformname.charAt(i)<='z') && !(registrationformname.charAt(i)>='A' && registrationformname.charAt(i)<='Z') && !(registrationformname.charAt(i)==' ') && !(registrationformname.charAt(i)=='.') && !(registrationformname.charAt(i)=='-')){
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


                  if (registrationformname == "") {
                    notification_of_invalidation.style.display = "block";
                    notification_of_invalidation.innerHTML = "Fill name field";
                    return false;
                  }


                  var registrationformphone = document.forms["customer_registration_form"]["phonenumber"].value;
                  var registrationformphonestring = registrationformphone.toString();

                  if (registrationformphonestring.length==11) {
                    if(!(registrationformphonestring.charAt(0)=='0' && registrationformphonestring.charAt(1)=='1')){
                      notification_of_invalidation.style.display = "block";
                      notification_of_invalidation.innerHTML = "Place Valid Phone Number";
                      return false;
                    }
                  }else if (registrationformphonestring.length==10) {
                    if(registrationformphonestring.charAt(0)!='1'){
                      notification_of_invalidation.style.display = "block";
                      notification_of_invalidation.innerHTML = "Place Valid Phone Number";
                      return false;
                    }
                  }


                  if(registrationformphonestring.length<10){
                    notification_of_invalidation.style.display = "block";
                    notification_of_invalidation.innerHTML = "Place Valid Phone Number";
                    return false;
                  }

                }





                var php_notification_of_invalidation;
                var loadingshowh2;
                if(document.getElementById("php_notification_of_invalidation") && document.getElementById("loadingshowh2")){
                  php_notification_of_invalidation = document.getElementById("php_notification_of_invalidation");
                  loadingshowh2 = document.getElementById("loadingshowh2");
                }





                function validateLoginForm() {
                  php_notification_of_invalidation.innerHTML = "";
                  var loginformuserdatainput = document.forms["customer_login_form"]["username"].value;
                  loginformuserdatainput.trim();


                  var loginformpassword = document.forms["customer_login_form"]["custompassword"].value;

                  if(loginformuserdatainput == "" || loginformpassword == ""){
                    php_notification_of_invalidation.innerHTML = "Fill the fields";
                    return false;
                  }

                  if(loginformuserdatainput.length < 8 || loginformpassword.length < 6 ){
                    php_notification_of_invalidation.innerHTML = "Place data correctly";
                    return false;
                  }


                  var loginformuserdatainputstring = loginformuserdatainput.toString();
                  var loginformuserdatainputinteger = parseInt(loginformuserdatainput);

                  if(!isNaN(loginformuserdatainputinteger)){

                      if(loginformuserdatainputstring.length==10){
                        if(loginformuserdatainputstring.charAt(0)!='1'){
                          php_notification_of_invalidation.innerHTML = "Place a valid number";
                        }
                      }else if(loginformuserdatainputstring.length==11) {
                        if(!(loginformuserdatainputstring.charAt(0)=='0' && loginformuserdatainputstring.charAt(1)=='1')){
                          php_notification_of_invalidation.innerHTML = "Place a valid number";
                        }
                      }else{
                        php_notification_of_invalidation.innerHTML = "Place a valid number";
                      }

                  }else{
                    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                    if(!loginformuserdatainputstring.match(mailformat)){
                      php_notification_of_invalidation.innerHTML = "Place a valid email";
                      return false;
                    }
                  }


                  loadDoc(loginformuserdatainput, loginformpassword);
                  return false;



                }





                var errorResponse;

                var userloginformsubmitbutton;

                if(document.getElementById("php_notification_of_invalidation") && document.forms["customer_login_form"]["userloginformsubmit"]){
                  errorResponse = document.getElementById("php_notification_of_invalidation");

                  userloginformsubmitbutton = document.forms["customer_login_form"]["userloginformsubmit"];

                }









                // Ajax For Login Purpose

                function loadDoc(usrnm, usrps) {

                  loadingshowh2.style.display = "block";
                  // console.log("yes");
                  userloginformsubmitbutton.disabled = true;

                  var xhttp = new XMLHttpRequest();
                  xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                      // document.getElementById("demo").innerHTML = this.responseText;


                      userloginformsubmitbutton.disabled = true;


                      loadingshowh2.style.display = "none";

                      var requestResponse = this.responseText;
                      if(requestResponse.localeCompare("E208")==0){
                        errorResponse.innerHTML = "No User found";
                        userloginformsubmitbutton.disabled = false;
                      }else if (requestResponse.localeCompare("E206")==0){
                        errorResponse.innerHTML = "Server Connection Failed, Try Again!";
                        userloginformsubmitbutton.disabled = false;
                      }else if (requestResponse.localeCompare("E606")==0){
                        errorResponse.innerHTML = "Place a valid number";
                        userloginformsubmitbutton.disabled = false;
                      }else if (requestResponse.localeCompare("E909")==0){
                        errorResponse.innerHTML = "Invalid Email Format";
                        userloginformsubmitbutton.disabled = false;
                      }else if (requestResponse.localeCompare("E808")==0){
                        errorResponse.innerHTML = "Fill All Starred Fields";
                        userloginformsubmitbutton.disabled = false;
                      }else if (requestResponse.localeCompare("S100")==0){
                        errorResponse.innerHTML = "User Found";
                        window.location.href = "https://bosheboshe.com/profile";
                      }else{
                        errorResponse.innerHTML = "Network Error!";
                        userloginformsubmitbutton.disabled = false;
                      }



                    }else{

                      // userloginformsubmitbutton.disabled = false;
                      // errorResponse.innerHTML = "Try Again, Network Error!";
                    }
                  };
                  xhttp.open("POST", "login_ajax.php", true);
                  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                  xhttp.send("username="+ usrnm +"&custompassword=" + usrps);
                }



          </script>






<?php


if (strcmp($decryptedFullname, "")==0) {
  if(strcmp($reponse, "registration")==0){
    echo '<script>
      registration_btn_clicked();
    </script>';
  }else{
    echo '<script>
      login_btn_clicked();
    </script>';
  }
}

 ?>






<?php
        include 'footer.php';
?>


      </body>
<html>
