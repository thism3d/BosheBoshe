<?php


require 'cookiesvariablesadmin.php';


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
$decryptedAdminName = "";
$decryptedAdminStatus = "";
$decryptedAdminUsername = "";
$decryptedAdminPassword = "";



if(isset($_COOKIE[$cookieadminname]) && isset($_COOKIE[$cookieadminstatus]) && isset($_COOKIE[$cookieadminusername]) && isset($_COOKIE[$cookieadminpassword])) {

  $decryptedAdminName = openssl_decrypt ($_COOKIE[$cookieadminname], $ciphering,
  $decryption_key, $options, $decryption_iv);
  $decryptedAdminStatus = openssl_decrypt ($_COOKIE[$cookieadminstatus], $ciphering,
  $decryption_key, $options, $decryption_iv);
  $decryptedAdminUsername = openssl_decrypt ($_COOKIE[$cookieadminusername], $ciphering,
  $decryption_key, $options, $decryption_iv);
  $decryptedAdminPassword = openssl_decrypt ($_COOKIE[$cookieadminpassword], $ciphering,
  $decryption_key, $options, $decryption_iv);

}else{
  header('Location: ' . $serverhost .'/logout.php');
  exit();
}



      echo '
        <div id="myNav" class="overlay">
          <a id="closebtnmobile" href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
          <div class="overlay-content">
            <div id="mobile_menu_design">
              <a><img id="bosheboshe_small_screen_menu" src="bosheboshefinal.png" alt="Boshe Boshe Logo"></a>

              <a href="uploadproducts">Upload Products</a>
              <a href="newshop">New Shop</a>
              <a href="merchant">Merchant</a>
              <a href="orders">Order Processing</a>
              <a href="order_status">Order Status</a>
              <a href="productsupdate">Products Update</a>
            </div>
          </div>
        </div>



        <div id="main_container">     <!-- Main Container Starts Here -->


          <div id="imagefor_smaller_device">
            <a href="../index"><img src="bosheboshefinal.png" alt="Logo of BosheBoshe"></a>
          </div>



          <div id="login_for_smaller_device">

            <a href="profile">Hi! '. $decryptedAdminName .'</a>

            <a href="member.php?do=login">Login</a>
            <a href="member.php?do=registration">Registration</a>

            <a href=""><i class="fa fa-language"></i> Language: <span>বাংলা</span></a>
          </div>




          <div  id="final_header">   <!-- Final Header Starts Here -->

            <div id="upper_header" class="clearfix">
              <div id="inside_upper_header">
                <h2 style="display:inline; color: #b22222;">Admin Panel</h2>
                <a href="logout.php" style="margin-right: 10px; margin-left: 10px; "><h3 style="display:inline;">Logout</h3></a>

              </div>
            </div>
          </div>  <!-- Final Header Ends Here -->














          <div id="header_section_sticky">  <!-- Sticky Section Starts Here -->






          <div id="first_section" class="clearfix">    <!-- First Section Starts Here -->

            <div id="left_first_section">
              <a href="../index"><img src="bosheboshefinal.png" alt="BosheBoshe Logo"></a>
              <span id="menu_option_tablet" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</span>
              <span id="menu_option_tablet_small_device" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>


              <!-- <h1 id="name_of_company">BosheBoshe</h1> -->

              <!-- <h3>Stay Home and Do Shopping</h3> -->
            </div>

            <div id="right_first_section">
              <div>
                <a><p id="my_hidden_search_box"  style="border:none; text-align:center;">'. $decryptedAdminName .'</p></a>
              </div>
            </div>



            <div id="third_first_section">
              <p id="non_hidden_thirdp1"><i class="fa fa-language"></i> Language</p>
              <p id="non_hidden_thirdp2"><span id="third_first_span">বাংলা</span> | <span id="third_second_span">English</span></p>
              <a id="non_hidden_thirda" href="logout.php"><p id="non_hidden_thirdp3"><span><i class="fa fa-sign-out"></i></span></p></a>
            </div>

          </div>                      <!-- First Section Ends Here -->



          <script>




            function openNav() {
              document.getElementById("myNav").style.width = "100%";
              document.getElementById("closebtnmobile").style.position = "fixed";
              document.getElementById("closebtnmobile").style.display = "block";
            }

            function closeNav() {
              document.getElementById("myNav").style.width = "0%";
              document.getElementById("closebtnmobile").style.position = "static";
              document.getElementById("closebtnmobile").style.display = "none";
            }
          </script>



          <!-- Menu Items -->
          <div id="top_menu_container">
            <span>
              <a href="uploadproducts">Upload Products</a>
              <a href="newshop">New Shop</a>
              <a href="merchant">Merchant</a>
              <a href="orders">Order Processing</a>
              <a href="order_status">Order Status</a>
              <a href="productsupdate">Products Update</a>
            </span>
          </div>



        </div>    <!-- Sticky Section Ends Here -->';




?>
