<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>

      <title>Merchant Login | BosheBoshe</title>

      <meta name="robots" content="noindex, nofollow">
      <meta charset="utf-8">
      <meta name="google" content="notranslate" />
      <meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, initial-scale=1.0, user-scalable=no, shrink-to-fit=no" />


      <!-- CSS For this Page -->
      <link rel="manifest" href="https://bosheboshe.com/bosheboshe.manifest">

      <link rel="icon" href="../icon.png">

      <link rel="stylesheet" href="merchant_main.css">

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">




  </head>
  <body>




    <div id="merchant_login_container">


        <div id="merchant_login_main">

            <img id="merchant_login_img" src="merchant_icon.png" alt="BosheBoshe Icon">

            <p id="new_merchant_p">
            <?php
              if(isset($_SESSION["errormerchantregistration"])){
                echo '<span id="already_registered_merchant_span">* '. $_SESSION["errormerchantregistration"] .'</span>';
                $_SESSION["errormerchantregistration"] = "";
                unset($_SESSION["errormerchantregistration"]);
              }else{
                echo '<a href="registration"><button><i class="fa fa-institution"></i> Register New Merchant Store</button></a>';
              }
            ?>
            </p>


            <h2 id="merchant_login_h2"><i class="fa fa-key"></i> Merchant Login</h2>

            <div id="mlogin_form_holder">
                <form name="merchant_login_form" onsubmit="return validateMerchantLogin()">
                    <input name="merchantusername" type="text" placeholder="Merchant Username">
                    <input name="merchantpassword" type="password" placeholder="Merchant Password">
                    <div id="mlogin_form_inside_separator" class="clearfix">
                        <div id="left_of_mlogins">
                            <p><a href="#">I forget my password</a></p>
                        </div>
                        <div id="right_of_mlogins">
                            <button id="merchant_login_btn">Login</button>
                        </div>
                    </div>


                </form>
            </div>





        </div>





    </div>

    <div id="loading_system"></div>

    <div id="snackbar"><i class="fa fa-check" style="color: #84ff00;"></i> Loading Complete</div>


    <script>

      var new_merchant_p = document.getElementById("new_merchant_p");

      var merchant_login_container = document.getElementById("merchant_login_container");
      var loading_system = document.getElementById("loading_system");

      var snackbar = document.getElementById("snackbar");

      var merchant_login_btn = document.getElementById("merchant_login_btn");


      function validateMerchantLogin() {

        var loginformuserdatainput = document.forms["merchant_login_form"]["merchantusername"].value;
        loginformuserdatainput.trim();


        var loginformpassword = document.forms["merchant_login_form"]["merchantpassword"].value;

        if(loginformuserdatainput == "" || loginformpassword == ""){
          new_merchant_p.innerHTML = '<span id="already_registered_merchant_span">* Fill all the fields</span>';
          return false;
        }

        loadFinalDoc(loginformuserdatainput, loginformpassword);
        return false;
      }


      function retryAgain(){
        merchant_login_container.style.opacity = "1";
        loading_system.style.display = "none";
        merchant_login_btn.disabled = false;

      }



      function loadFinalDoc(merchatUser, merchantPass) {

        merchant_login_container.style.opacity = "0.2";
        loading_system.style.display = "block";
        new_merchant_p.innerHTML = '<span id="already_registered_merchant_span">Loggin In ...</span>';
        merchant_login_btn.disabled = true;
        // console.log(merchatUser, merchantPass);

        var errorResponse = new_merchant_p;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {



            // document.getElementById("demo").innerHTML = this.responseText;

            var requestResponse = this.responseText;
            console.log(requestResponse);

            if(requestResponse.localeCompare("S100")==0){
              snackbar.innerHTML = '<i class="fa fa-check" style="color: #84ff00;"></i> Loading Complete';
              myScankFunction();
              // window.location.href = "https://bosheboshe.com/merchant/dashboard";
              window.location.href = "http://localhost/bosheboshe/merchant/dashboard"; // For Localhost
            }else{
              snackbar.innerHTML = '<i class="fa fa-close" style="color: #ff3b3b;"></i> Failed! Try Again';

              if(requestResponse.localeCompare("EX100100")==0){
                  snackbar.innerHTML = '<i class="fa fa-check" style="color: #84ff00;"></i> Registered! Awaiting Approval';
                  errorResponse.innerHTML = '<span id="already_registered_merchant_span" style="color: green">* Registered, Waiting for Approval!</span>';
              }else if(requestResponse.includes("E208")==true){
                snackbar.innerHTML = '<i class="fa fa-close" style="color: #ff3b3b;"></i> Not a registered merchant';
                new_merchant_p.innerHTML  = '<a href="registration"><button><i class="fa fa-institution"></i> Register New Merchant Store</button></a>';
              }else if (requestResponse.includes("E206")==true){
                errorResponse.innerHTML = '<span id="already_registered_merchant_span">* Server Connection Failed, Try Again!</span>';
              }else if (requestResponse.includes("E606")==true){
                errorResponse.innerHTML = '<span id="already_registered_merchant_span">* Place a valid number</span>';
              }else if (requestResponse.includes("E808")==true){
                errorResponse.innerHTML = '<span id="already_registered_merchant_span">* Fill All Starred Fields</span>';
              }else{
                errorResponse.innerHTML = '<span id="already_registered_merchant_span">* Network Error, Try Again!</span>';
              }
              myScankFunction();
              retryAgain();
            }







          }else{

            // userloginformsubmitbutton.disabled = false;
            // errorResponse.innerHTML = "Try Again, Network Error!";
          }
        };
        xhttp.open("POST", "merchant_login_ajax.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("merchantusername="+ merchatUser +"&merchantpassword=" + merchantPass);

      }




      //Snack Bar
      function myScankFunction() {
        var x = document.getElementById("snackbar");
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
      }

    </script>






  </body>
<html>
