<!DOCTYPE html>
<html>
      <head>

          <title>Shop Summary - BosheBoshe | Bosheboshe Admin Panel</title>

          <meta name="robots" content="noindex">
          <meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, initial-scale=1.0, user-scalable=no, shrink-to-fit=no" />


          <!-- CSS For this Page -->
          <link rel="icon" href="../icon.png">
  	      <link rel="stylesheet" href="main_admin.css">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



      </head>
      <body>


<?php

      $merchantid = "";

      if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $merchantid = $_GET["merchantid"];



        $str = "Location: merchant.php";

        if(!is_numeric($merchantid) || strlen($merchantid)>10){
          header($str);
        }

        if(!$merchantid){
          header($str);
        }
      }




        require 'connectserver.php';

        $isFound = 0;
        $shopname = $ownername = $productscategory = $phonenumber = "";

        $sql = 'SELECT shopid, shopname, shopownername, shopcategory, ownerphone FROM registershop WHERE approval = "N" AND shopid='. $merchantid .';';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          $isFound = 1;
          while($row = $result->fetch_assoc()) {
            $shopname = $row["shopname"];
            $ownername = $row["shopownername"];
            $productscategory = $row["shopcategory"];
            $phonenumber = $row["ownerphone"];
          }
        }

        if($isFound!=1){
          header($str);
        }


        include 'admin_header.php';
?>




<div>

  <h1 id="approve_new_merchant"><i class="fa fa-child"></i> Pending Merchant</h1>



  <div id="merchant_approval_form" class="clearfix">

    <form id="not_approved_form" method="post" action="uploadpropermerchant.php">
      <?php

      echo '<div id="left_side_of_merchant_approval">
        <b style="color: #323ecc;">Merchant Given Information</b>

        <input type="hidden" name="merchant_expected_id" value="'. $merchantid .'">

        <label for="form_merchant_name_id">Merchant Name</label>
        <input type="text" id="form_merchant_name_id" name="merchant_name" placeholder="Merchant Name" value="'. $shopname .'">

        <label for="form_merchant_owner">Owner Name</label>
        <input type="text" id="form_merchant_owner" name="person_name" placeholder="Owner Name" value="'. $ownername .'">

        <label for="form_merchant_category">Products Category</label>
        <input type="text" id="form_merchant_category" name="merchant_category" placeholder="Products Category" value="'. $productscategory .'">

        <label for="form_phone_number">Phone Number</label>
        <input type="number" id="form_phone_number" name="person_phone" placeholder="Phone Number" value="'. $phonenumber .'">';

      ?>

      </div>
      <div id="right_side_of_merchant_approval">
        <b style="color: white; text-shadow: 1px 1px 2px black;">Additional Information</b>

        <label for="form_merchant_shortname">Merchant Live ID</label>
        <input type="text" id="form_merchant_shortname" name="merchant_liveid" placeholder="Merchant Live ID">

        <label for="form_merchant_details">Merchant Details</label>
        <input type="text" id="form_merchant_details" name="merchant_details" placeholder="Merchant Details">

        <label for="form_owner_email">Owner Email</label>
        <input type="text" id="form_owner_email" name="person_email" placeholder="Owner Email">

        <label for="form_merchant_address">Merchant Address</label>
        <input type="text" id="form_merchant_address" name="merchant_address" placeholder="Merchant Address">

        <label for="form_merchant_regnum">Registration Number</label>
        <input type="text" id="form_merchant_regnum" name="merchant_reg_num" placeholder="Registration Number">

        <div style="text-align: right;">
          <button id="approve_merchant_btn"><i class="fa fa-check-circle"></i> Approve</button>
        </div>
      </div>
    </form>

  </div>

  <br><br>






</div>









<?php
        include 'admin_footer.php';
?>


      </body>
<html>
