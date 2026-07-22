<!DOCTYPE html>
<html>
      <head>

          <title>Forget Passowrd - BosheBoshe</title>


          <?php require_once("headermeta.php"); ?>

          <style>

            #forget_password_main_container{
              width: 100%;
              margin: 0px auto;
              max-width: 450px;
              text-align: center;
              margin-bottom: 60px;
              box-sizing: border-box;
              padding: 15px;
              font-family: 'Roboto', sans-serif;
            }

            #forget_password_img{
              width: 100%;
              max-width: 250px;
              box-sizing: border-box;
              padding: 15px;
              padding-bottom: 0px;
            }

            #forget_shower_id{
              display: inline-table;
              font-family: 'Roboto', sans-serif;
            }

            #forget_password_header{
              padding: 8px;
              background-color: #4f4f4f;
              text-align: center;
              font-family: 'Roboto', sans-serif;
              color: #f2f2f2;
              max-width: 300px;
              margin: 0 auto;
            }

            #message_shower_forget_p{
              text-align: left;
              font-size: 16px;
              padding: 5px;
              font-family: 'Roboto', sans-serif;
            }

            #error_viewer_p{
              text-align: left;
              padding-left: 10px;
              color: red;
              font-size: 16px;
            }

            #number_inputbox, #otpinputbox, #passwordinputbox{
              outline: none;
              border: 3px solid gray;
              border-radius: 4px;
              padding: 5px;
              padding-left: 10px;
              width: 100%;
              max-width: 300px;
              min-width: 280px;
              font-size: 18px;
              -webkit-transition: 0.4s;
              transition: 0.4s;
              font-family: 'Roboto', sans-serif;
            }


            input::-webkit-input-placeholder{
              color: #ccc;
              text-shadow: none;
              -webkit-text-fill-color: initial;
            }

            #number_inputbox:focus, #otpinputbox:focus, #passwordinputbox:focus {
              border-color: black;
            }

            #forget_request_button, #otp_check_button, #changepasswordbtn{
              margin-top: 10px;
              min-width: 150px;
              border: none;
              background-color: #3c72a3;
              padding: 9px 10px;
              border-radius: 4px;
              font-family: 'Roboto', sans-serif;
              font-size: 14px;
              -webkit-transition: 0.4s;
              transition: 0.4s;
              color: white;
              cursor: pointer;
              outline: none;
            }

            #forget_request_button:hover, #otp_check_button:hover, #changepasswordbtn:hover{
              background-color: #1c80d9;
            }

            #forget_request_button i, #otp_check_button i, #changepasswordbtn i{
              font-size: 18px;
              font-weight: bold;
            }

            #go_to_login{
              display: inline-block;
              padding: 8px 15px;
              width: 100%;
              min-width: 150px;
              background-color: #3c72a3;
              border-radius: 6px;
              box-shadow: 1px 1px 6px 0px #5a5a5a;
              color: white;
              font-size: 16px;
              font-family: 'Roboto', sans-serif;
            }



          </style>


      </head>
      <body>


<?php
  include 'header.php';

?>



  <div style="text-align:center;">
    <img id="forget_password_img" src="images/undraw_forgot_password_gi2d.svg">
  </div>
  <h2 id="forget_password_header">Forget Password</h2>

<div id="forget_password_main_container">
  <div id="forget_shower_id">
    <p id="error_viewer_p"></p>
    <div id="final_forget_box">
      <p id="message_shower_forget_p">Enter Phone Number</p>
      <input id="number_inputbox" onchange="checkRegistrationFormPhoneNumber()" type="number" placeholder="Phone Number" required autofocus><br>
      <button id="forget_request_button" onclick="validateForget()">Request Forget <i class="fa fa-angle-double-right"></i></button>
    </div>
  </div>
</div>




<script>
  var forget_request_button = document.getElementById("forget_request_button");

  var number_inputbox = document.getElementById("number_inputbox");

  var error_viewer_p = document.getElementById("error_viewer_p");

  var final_forget_box = document.getElementById("final_forget_box");


  var fphone;
  var fphonevalue;

  if(document.getElementById("number_inputbox")){
    fphone = document.getElementById("number_inputbox");
    fphonevalue = fphone.value;
  }




  function checkRegistrationFormPhoneNumber(){
    fphonevalue = fphone.value;
    // console.log(fphonevalue);
    if(isNaN(fphonevalue) || fphonevalue<0 || fphonevalue>99999999999){
      fphone.value = "";
    }
  }




  function validateForget() {

     var errorFound = 0;

     var registrationformphone = number_inputbox.value;
     var registrationformphonestring = registrationformphone.toString();

      if (registrationformphonestring.length==11) {
        if(!(registrationformphonestring.charAt(0)=='0' && registrationformphonestring.charAt(1)=='1')){
           error_viewer_p.innerHTML = "Place a valid Number";
           errorFound = 1;
        }
      }else if (registrationformphonestring.length==10) {
        if(registrationformphonestring.charAt(0)!='1'){
           error_viewer_p.innerHTML = "Place a valid Number";
           errorFound = 1;
        }
      }


      if(registrationformphonestring.length<10 || registrationformphonestring.length>11){
         error_viewer_p.innerHTML = "Place a valid Number";
         errorFound = 1;
      }


      if(errorFound==0){
        error_viewer_p.innerHTML = "";
        forget_request_button.disabled = true;
        number_inputbox.disabled = true;
        forget_request_button.innerHTML = 'Requesting <i class="fa fa-circle-o-notch fa-spin"></i>';
        final_forget_box.children[0].style.opacity = "0.2";
        final_forget_box.children[1].style.opacity = "0.2";
        requestDataFromServer(registrationformphonestring);
      }

  }


  function requestDataFromServer(str){

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {

        var requestResponse = this.responseText;
        
        console.log(requestResponse);


        if(requestResponse.localeCompare("Found")==0){
          final_forget_box.children[0].style.opacity = "1";
          final_forget_box.children[1].style.opacity = "1";


          var otpHTML = '<p id="message_shower_forget_p">Enter Your OTP</p>' +
          '<input id="otpinputbox" onchange="checkOTPinput()" type="number" placeholder="One Time Password" required><br>' +
          '<button id="otp_check_button" onclick="validateOTP()">Check OTP <i class="fa fa-angle-double-right"></i></button>';
          final_forget_box.innerHTML = otpHTML;


        }else if (requestResponse.localeCompare("Not Found")==0) {
          final_forget_box.children[0].style.opacity = "1";
          final_forget_box.children[1].style.opacity = "1";

          error_viewer_p.innerHTML = "No User Found";
          forget_request_button.disabled = false;
          number_inputbox.disabled = false;
          forget_request_button.innerHTML = 'Request Forget <i class="fa fa-angle-double-right"></i>';
        }else{
          final_forget_box.children[0].style.opacity = "1";
          final_forget_box.children[1].style.opacity = "1";

          error_viewer_p.innerHTML = "Network Error";
          forget_request_button.disabled = false;
          number_inputbox.disabled = false;
          forget_request_button.innerHTML = 'Request Forget <i class="fa fa-angle-double-right"></i>';
        }

      }else{

      }
    };
    xhttp.open("POST", "forget_user_found.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("userphone="+ str);


  }


  function checkOTPinput() {
    var otpinputbox = document.getElementById("otpinputbox");
    var otpinputboxvalue = parseInt(otpinputbox.value);
    // console.log(otpinputboxvalue);

    if(isNaN(otpinputboxvalue) || otpinputboxvalue<0 || otpinputboxvalue>999999){
      otpinputbox.value = "";
    }
  }

  function validateOTP() {
    var otpvaluelength = document.getElementById("otpinputbox").value.length;
    if(otpvaluelength!=6){
      error_viewer_p.innerHTML = "Enter 6 digit OTP";
    }else{
      error_viewer_p.innerHTML = "";
      var otp_check_button = document.getElementById("otp_check_button");
      var otpinputbox = document.getElementById("otpinputbox");
      otpinputbox.disabled = true;
      otp_check_button.disabled = true;
      otp_check_button.innerHTML = 'Validating <i class="fa fa-circle-o-notch fa-spin"></i>';
      // console.log(final_forget_box.children[3]);
      requestOTPValidation(document.getElementById("otpinputbox").value);
    }
  }


  function requestOTPValidation(otpCode) {


    final_forget_box.children[0].style.opacity = "0.2";
    final_forget_box.children[1].style.opacity = "0.2";



    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {


        var requestResponse = this.responseText;


        if(requestResponse.localeCompare("Matched")==0){
          final_forget_box.children[0].style.opacity = "1";
          final_forget_box.children[1].style.opacity = "1";

          var otpHTML = '<p id="message_shower_forget_p">Enter New Password</p>' +
          '<input id="passwordinputbox" type="password" placeholder="Enter Password" required><br>' +
          '<button id="changepasswordbtn" onclick="changePassword()">Change Password <i class="fa fa-angle-double-right"></i></button>';
          final_forget_box.innerHTML = otpHTML;

        }else if(requestResponse.localeCompare("Not Matched")==0){

          error_viewer_p.innerHTML = "OTP Not Matched";
          otp_check_button.disabled = false;
          var otpinputbox = document.getElementById("otpinputbox");
          otpinputbox.disabled = false;
          final_forget_box.children[0].style.opacity = "1";
          final_forget_box.children[1].style.opacity = "1";

          document.getElementById("otp_check_button").innerHTML = 'Check OTP <i class="fa fa-angle-double-right"></i>';
        }else{

          error_viewer_p.innerHTML = "Network Error";
          otp_check_button.disabled = false;
          var otpinputbox = document.getElementById("otpinputbox");
          otpinputbox.disabled = false;
          final_forget_box.children[0].style.opacity = "1";
          final_forget_box.children[1].style.opacity = "1";

          document.getElementById("otp_check_button").innerHTML = 'Check OTP <i class="fa fa-angle-double-right"></i>';
        }

      }else{

      }
    };
    xhttp.open("POST", "check_otp.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("userotp="+ otpCode);


  }

  var passwordinputbox;
  var changepasswordbtn;

  function changePassword() {

    passwordinputbox = document.getElementById("passwordinputbox");
    var passwordinputboxinput = passwordinputbox.value.trim();


    var passwordinputlength = passwordinputboxinput.length;


    if(passwordinputlength<6 || passwordinputlength>128){
      error_viewer_p.innerHTML = "Minimum 6 Digit";
    }else{
      error_viewer_p.innerHTML = "";
      changepasswordbtn = document.getElementById("changepasswordbtn");
      changepasswordbtn.innerHTML = 'Changing Password <i class="fa fa-circle-o-notch fa-spin"></i>';
      changepasswordbtn.disabled = true;
      passwordinputbox.disabled = true;
      final_forget_box.children[0].style.opacity = "0.2";
      final_forget_box.children[1].style.opacity = "0.2";
      changePasswordServer(passwordinputboxinput);
    }

  }


  function changePasswordServer(psstr) {

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

          var requestResponse = this.responseText;



          if(requestResponse.localeCompare("Y")==0){
            final_forget_box.children[0].style.opacity = "1";
            final_forget_box.children[1].style.opacity = "1";
            error_viewer_p.innerHTML = "Password Changed";

            successfullyChangedSection();
          }else{
            final_forget_box.children[0].style.opacity = "1";
            final_forget_box.children[1].style.opacity = "1";
            error_viewer_p.innerHTML = "Failed";
            passwordinputbox.disabled = false;
            changepasswordbtn.disabled = false;
            changepasswordbtn.innerHTML = 'Change Password <i class="fa fa-angle-double-right"></i>';
          }


        }else{

        }
      };
      xhttp.open("POST", "update_fpassword.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("pswrd="+ psstr);

  }



  function successfullyChangedSection() {
    error_viewer_p.innerHTML = "";
    var otpHTML = '<p style="font-size: 16px;">Password Changed Successfully</p><br>' +
    '<a id="go_to_login" href="member">Login</a>';
    final_forget_box.innerHTML = otpHTML;
  }






</script>






























<?php
        include 'footer.php';
?>


      </body>
<html>
