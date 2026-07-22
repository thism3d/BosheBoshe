<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>

      <title>Merchant Registration | BosheBoshe</title>

      <meta name="robots" content="noindex, nofollow">
      <meta charset="utf-8">
      <meta name="google" content="notranslate" />
      <meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, initial-scale=1.0, user-scalable=no, shrink-to-fit=no" />


      <!-- CSS For this Page -->
      <link rel="manifest" href="https://bosheboshe.com/bosheboshe.manifest">

      <link rel="icon" href="../icon.png">

      <link rel="stylesheet" href="merchant_main.css">

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


      <link rel="stylesheet" href="form_material.css">




  </head>
  <body>




    <div class="container">
        <div id="merchant_registration_container">
            <img id="merchant_registration_img" src="merchant_icon.png">
            <h2 id="merchant_new_registration_h2"><i class="fa fa-institution"></i> Register New Merchant</h2>
        </div>
        <p id="php_notification_of_invalidation" style="text-align:center; color: red;">
          <?php
            if(isset($_SESSION["errormerchantregistration"])){
              echo $_SESSION["errormerchantregistration"];
              $_SESSION["errormerchantregistration"] = "";
            }
          ?>
        </p>
        <div id="merchant_registration_form">
          <form action="registraton_confirmed.php" method="post" autocomplete="off">

            <div class="form-group">
              <input name="merchantshop" type="text" required="required" autofocus maxlength="200"/>
              <label for="input" class="control-label">Merchant/Shop/Company Name <span style="color: red;">*</span></label><i class="bar"></i>
            </div>


            <div class="form-group">
              <input name="merchantperson" type="text" required="required" maxlength="255"/>
              <label for="input" class="control-label">Person Name <span style="color: red;">*</span></label><i class="bar"></i>
            </div>


            <div class="form-group">
              <input name="merchantcategory" type="text" required="required" maxlength="200"/>
              <label for="input" class="control-label">Products Category <span style="color: red;">*</span></label><i class="bar"></i>
            </div>

            <div class="form-group">
              <input name="merchantnumber" type="number" required="required" maxlength="30"/>
              <label for="input" class="control-label">Phone Number <span style="color: red;">*</span></label><i class="bar"></i>
            </div>

            <div class="form-group">
              <input name="merchantpassword" type="password" required="required" maxlength="100"/>
              <label for="input" class="control-label">Password <span style="color: red;">*</span></label><i class="bar"></i>
            </div>
    <!--
            <div class="form-group">
              <textarea required="required"></textarea>
              <label for="textarea" class="control-label">Textarea</label><i class="bar"></i>
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" checked="checked"/><i class="helper"></i>I'm the label from a checkbox
              </label>
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox"/><i class="helper"></i>I'm the label from a checkbox
              </label>
            </div>
            <div class="form-radio">
              <div class="radio">
                <label>
                  <input type="radio" name="radio" checked="checked"/><i class="helper"></i>I'm the label from a radio button
                </label>
              </div>
              <div class="radio">
                <label>
                  <input type="radio" name="radio"/><i class="helper"></i>I'm the label from a radio button
                </label>
              </div>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox"/><i class="helper"></i>I'm the label from a checkbox
              </label>
            </div> -->

              <p id="agree_tc_paragraph">By clicking Register, you're agreeing with the <a href="https://bosheboshe.com/termsofuse">Terms and Condition</a> of BosheBoshe.com</p>

              <div style="text-align:center;">
                <input id="merchant_submitinput" type="submit" value="Register">
              </div>



          </form>
        </div>

<!--
        <div class="button-container">
            <button type="button" class="button"><span><i class="fa fa-paper-plane-o"></i> Register</span></button>
          </div>
-->

    </div>




  </body>
<html>
