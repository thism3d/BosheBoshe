<!DOCTYPE html>
<html>
      <head>

          <title>Sent Message - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>


          <?php require_once("headernofollowmeta.php"); ?>



      </head>
      <body>


<?php


$mail_sent = 0;

if(isset($_COOKIE["mail_sent"])) {
  setcookie("mail_sent", "", time() - 3600, "/");
  $mail_sent = 1;
}





        include 'header.php';









if($mail_sent==1){
  echo'
    <div id="user_sent_message_container">
      <img src="images/undraw_message_sent_1030.svg">
      <br>
      <br>
      <br>
      <p id="message_query_number_p">
        <b style="font-size:21px;">Thank you for letting us know.</b>
        <br><br>
        <b>Your message has been sent.</b><br>
        <b style="display:none">Your Enquery Number: #1238175</b>
        <br>
        <br>
      </p>

      <p>Someone from us will reach you soon. Happy shopping. 😊</p>



      <a href="index"><button id="return_shopping">Return Shopping</button></a>
    </div>';

}else{
  echo'
    <div id="user_sent_message_container">
      <img src="images/undraw_warning_cyit.svg">
      <br>
      <br>
      <br>
      <p id="message_query_number_p">
        <b style="font-size:21px;">Do you want to send us a message!</b>
      </p>
      <br>



      <a href="contactus"><button id="return_shopping">Contact Us</button></a>
    </div>';
}







?>



































<?php
        include 'footer.php';
?>


      </body>
<html>
