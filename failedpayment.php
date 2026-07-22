<?php
// Aggregator bridge: if this callback is for a partner-brokered payment,
// hand off to the API handler (which redirects the partner) and stop here.
// Native store payments fall straight through, untouched.
require_once __DIR__ . '/api/lib/native_callback_hook.php';
aggregator_maybe_handle('fail');
?>
<!DOCTYPE html>
<html>
      <head>

          <title>Payment Failed</title>


          <?php require_once("headernofollowmeta.php"); ?>


          <style>
            #left_text_notification{
              display: inline-table;
              text-align: left;
            }
          </style>




      </head>
      <body>


<?php
  include 'header.php';



  echo'
    <div id="user_sent_message_container">
      <img src="images/undraw_breakfast_psiw.svg">
      <br>
      <br>
      <br>
      <p id="message_query_number_p">
        <b style="font-size:21px;">Payment Failed!</b><br><br>
        <b>No worries. Pay again using your profile section.</b>
      </p><br>


      <a href="profile"><button id="return_shopping">Profile &amp; Pay</button></a>
    </div>';







?>



































<?php
        include 'footer.php';
?>


      </body>
<html>
