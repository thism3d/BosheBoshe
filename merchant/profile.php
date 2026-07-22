<!DOCTYPE html>
<html>
      <head>

          <meta name="robots" content="noindex">
          <meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, initial-scale=1.0, user-scalable=no, shrink-to-fit=no" />

          <title>Merchant Dashboard BosheBoshe</title>


          <!-- CSS For this Page -->
          <link rel="icon" href="../icon.png">
  	      <link rel="stylesheet" href="main_merchant.css">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



      </head>
      <body>


<?php

        include 'merchant_header.php';



        require 'cookiesvariablesmerchant.php';


        require 'connectadminserver.php';


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
        $decryptedMerchantName = "";
        $decryptedMerchantOwner = "";
        $decryptedMerchantPhone = "";
        $decryptedMerchantShortName = "";




        $decryptedMerchantName = openssl_decrypt ($_COOKIE[$cookiemerchantname], $ciphering,
        $decryption_key, $options, $decryption_iv);

        $decryptedMerchantOwner = openssl_decrypt ($_COOKIE[$cookiemerchantowner], $ciphering,
        $decryption_key, $options, $decryption_iv);

        $decryptedMerchantPhone = openssl_decrypt ($_COOKIE[$cookiemerchantphone], $ciphering,
        $decryption_key, $options, $decryption_iv);

        $decryptedMerchantShortName = openssl_decrypt ($_COOKIE[$cookiemerchantshortname], $ciphering,
        $decryption_key, $options, $decryption_iv);


        $shopAddress = $shopCategory = $ownerEmail = "";


        $sql = 'SELECT shopcategory, shopaddress, owneremail FROM registershop WHERE ownerphone = "'. $decryptedMerchantPhone .'" AND shortname="'. $decryptedMerchantShortName .'";';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            $shopAddress = $row["shopaddress"];
            $shopCategory = $row["shopcategory"];
            $ownerEmail = $row["owneremail"];
          }
        }







?>























  <div id="merhcant_profile_container">

    <div id="merchant_profile_inside" class="clearfix">
      <h2 id="merchant_profile_headertext">Basic Information</h2>
      <p id="change_merchant_information_p">To change or add information of the merchant panel, contact our customer care. Phone: <a href="tel:+8801884084849">+8801884084849</a> | Email: <a href="mailto:support@bosheboshe.com?Subject=Merchant">support@bosheboshe.com</a></p>


<?php

  echo '
      <p class="merchant_deatils_p clearfix">
        <span>Merchant </span>
        <span>'. $decryptedMerchantName .'</span>
      </p>

      <p class="merchant_deatils_p clearfix">
        <span>Live Id </span>
        <span>'. $decryptedMerchantShortName .'</span>
      </p>

      <p class="merchant_deatils_p clearfix">
        <span>Owner </span>
        <span>'. $decryptedMerchantOwner .'</span>
      </p>

      <p class="merchant_deatils_p clearfix">
        <span>Phone </span>
        <span>'. $decryptedMerchantPhone .'</span>
      </p>

      <p class="merchant_deatils_p clearfix">
        <span>Email </span>
        <span>'. $ownerEmail .'</span>
      </p>

      <p class="merchant_deatils_p clearfix">
        <span>Shop Address </span>
        <span>'. $shopAddress .'</span>
      </p>

      <p class="merchant_deatils_p clearfix">
        <span>Products Category </span>
        <span>'. $shopCategory .'</span>
      </p>';

?>


    </div>


  </div>








































<?php
        include 'merchant_footer.php';
?>


      </body>
<html>
