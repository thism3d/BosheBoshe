<!DOCTYPE html>
<html lang="en">
      <head>

          <title> Help Center - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>


          <?php require_once("headermeta.php"); ?>



      </head>
      <body>


<?php
        include 'header.php';
?>




<div id="main_help_contianer">

    <div id="help_center_container">

      <!-- <img id="help_center_image" class="lazy" src="backgroundopacity.png" data-src="images/undraw_contact_us_15o2.svg"> -->
      <img id="help_center_image" src="images/undraw_contact_us_15o2.svg">

      <h1>Welcome to the help center of BosheBoshe!</h1>

      <div id="contact_phone_email_container" class="clearfix">

        <div class="contactemaildiv">
          <a href="tel:+8801884084849">
            <span class="email_phone_spanheader"><i class="fa fa-phone" aria-hidden="true"></i> Call</span>
            <hr>
            <span class="email_phonespan">01884084849</span>
            <p class="last_text_of_contact">10am - 10pm, 7 days</p>
          </a>
        </div>

        <div class="contactemaildiv">
          <a href="mailto:enquiries@bosheboshe.com?Subject=MemberHelp" target="_top">
            <span class="email_phone_spanheader"><i class="fa fa-envelope"></i> Mail</span>
            <hr>
            <span class="email_phonespan">enquiries@bosheboshe.com</span>
            <p class="last_text_of_contact">Replies within 24 hours</p>
          </a>
        </div>

      </div>







      <div id="send_us_messages">
        <br>
        <br>
        <br>
        <h2>Or leave us a message <i class="fa fa-bell-o"></i><h2>
        <div id="leave_message_form">
          <form method="post" action="messagesent" onsubmit="return validForm()">
            <label for="fname">Name</label>
            <input type="text" placeholder="Full Name" id="fname" name="fullname" autocomplete="off" required  maxlength="80" pattern=".{6,}" required title="Minimum 6 Characters">
            <label for="femail">Email</label>
            <input type="text"  placeholder="Email" id="femail" name="useeremail" autocomplete="off" maxlength="230" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="Enter Valid Email">
            <label for="fphone">Phone Number</label>
            <input type="text"  placeholder="Phone" id="fphone"  name="phonenumber" autocomplete="off" required maxlength="15">
            <label for="fmessage">Your Message</label>
            <textarea rows="5" id="fmessage" name="usermessage" autocomplete="off" placeholder="Type Message Here" required maxlength="4000" pattern=".{5,}" required title="Minimum 5 Characters"></textarea>
            <button id="send_message_button"><input type="submit" value="Send" style="display: none;">Send <i class="fa fa-paper-plane-o"></i></button>
          </form>
        </div>

      </div>

    </div>


  </div>



























<script>

  function validForm() {

    var sendernametxt = document.getElementById("fname").value;
    var senderemailtxt = document.getElementById("femail").value;
    var senderphonetxt = document.getElementById("fphone").value;
    var sendermessagetxt =  document.getElementById("fmessage").value;

    // console.log(sendernametxt + " " + senderemailtxt + " " + senderphonetxt + " " + sendermessagetxt);

    if(sendernametxt.length>6 && senderemailtxt.length>6 && senderphonetxt.length>9 && senderphonetxt.length<15 && sendermessagetxt.length>4 && sendermessagetxt.length<40001){
      // console.log("Yes");


      sendMail(sendernametxt, senderemailtxt, senderphonetxt, sendermessagetxt);

      return false;
    }else{
      // console.log("No");
      return false;
    }








  }


  var send_message_button = document.getElementById("send_message_button");

  var send_message_previous_html = '<input type="submit" value="Send" style="display: none;">Send Again <i class="fa fa-paper-plane-o"></i>';
  var send_button_loading_html = '<input type="submit" value="Send" style="display: none;">Sending <i class="fa fa-circle-o-notch fa-spin"></i>';



  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";

  }



      // Ajax For Sending Mail

    function sendMail(sendername, senderemail, senderphone, sendermessage) {

      send_message_button.disabled = true;
      send_message_button.innerHTML = send_button_loading_html;

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

          // document.getElementById("demo").innerHTML = this.responseText;




          var requestResponse = this.responseText;

          console.log(requestResponse);

          if(requestResponse.localeCompare("Error")==0){
            send_message_button.disabled = false;
            send_message_button.innerHTML = '<input type="submit" value="Send" style="display: none;">Error! Send Again <i class="fa fa-paper-plane-o"></i>';
          }else if (requestResponse.localeCompare("Sent")==0){
            setCookie("mail_sent", "z", 1);
            window.location.href = "https://bosheboshe.com/messagesent";
          }else if (requestResponse.localeCompare("No")==0){
            send_message_button.disabled = false;
            send_message_button.innerHTML = send_button_loading_html;
          }else{
            send_message_button.disabled = false;
            send_message_button.innerHTML = send_button_loading_html;
          }



        }
      };


      xhttp.open("POST", "mail_ajax.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("sendername="+ sendername +"&senderemail=" + senderemail + "&sendermobile=" + senderphone + "&sendermessage=" + sendermessage);
    }
</script>





















<?php
        include 'footer.php';
?>


      </body>
<html>
