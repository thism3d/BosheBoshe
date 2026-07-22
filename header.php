<?php


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






if(isset($_COOKIE[$cookiefullname])) {

  $decryptedFullname = openssl_decrypt ($_COOKIE[$cookiefullname], $ciphering,
  $decryption_key, $options, $decryption_iv);

}else{
  $decryptedFullname = "";
}


   echo "
        <!-- Google tag (gtag.js) -->
        <script async src='https://www.googletagmanager.com/gtag/js?id=G-0DKWWN6MJL'></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'G-0DKWWN6MJL');
        </script>
    ";
    

/*
echo '<div id="bosheboshedelayheader">
  <p>BosheBoshe\'s Response to COVID-19 and Delayed Order Shipping >></p>
</div>';
*/

echo '<div id="cart_div_container" class="clearfix">
        <div id="mobile_screen_home_btn">
          <a href="index"><i class="fa fa-home"></i></a>
        </div>
        <a href="proceedtocart">
          <div id="left_side_of_cart">
            <p><i class="fa fa-shopping-cart"></i> Proceed To Cart</p>
          </div>
          <div id="right_side_of_cart">
            <p id="items_selected">0 ITEM</p>
            <p id="total_price">0 TK</p>
          </div>
          </a>
      </div>';



      echo '
        <div id="myNav" class="overlay">
          <a id="closebtnmobile" href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
          <div class="overlay-content">
            <div id="mobile_menu_design">
              <a href="index"><img id="bosheboshe_small_screen_menu" src="bosheboshefinal.png" alt="Boshe Boshe Logo"></a>

              <a href="startshopping">For You</a>
              <a href="bakery">Bakery</a>
              <a href="food">Food</a>
              <a href="vegetables">Vegetable</a>
              <a href="fruits">Fruit</a>
              <a href="stationary">Stationary</a>
              <a href="groceries">Groceries</a>
            </div>
          </div>
        </div>



        <div id="main_container">     <!-- Main Container Starts Here -->


          <div id="imagefor_smaller_device">
            <a href="index"><img src="bosheboshefinal.png" alt="Logo of BosheBoshe"></a>
          </div>



          <div id="login_for_smaller_device">';

          if (strcmp($decryptedFullname, "")!=0) {

            $token = strtok($decryptedFullname, " ");


            echo '
            <a href="profile">Hi! '. $token .'</a>';
          }else{
            echo '
            <a href="member?do=login">Login</a>
            <a href="member?do=registration">Registration</a>';
          }

          echo '
            <a href=""><i class="fa fa-language"></i> Language: <span>বাংলা</span></a>
          </div>




          <div  id="final_header">   <!-- Final Header Starts Here -->

            <div id="upper_header" class="clearfix">
              <div id="inside_upper_header">
                <a href="howtoorder">How to Order</a>
                <a href="contactus">Help Center</a>
                <a href="trackmyorder">Delivery Track</a>';

                if (strcmp($decryptedFullname, "")!=0) {

                  $token = strtok($decryptedFullname, " ");


                  echo '
                  <a href="profile">Hi! '. $token .'</a>';
                }else{
                  echo '
                  <a href="member?do=login">Login</a>
                  <a href="member?do=registration">Registration</a>';
                }



                echo '
              </div>
            </div>
          </div>  <!-- Final Header Ends Here -->














          <div id="header_section_sticky">  <!-- Sticky Section Starts Here -->






          <div id="first_section" class="clearfix">    <!-- First Section Starts Here -->

            <div id="left_first_section">
              <a href="index"><img src="bosheboshefinal.png" alt="BosheBoshe Logo"></a>
              <span id="menu_option_tablet" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</span>
              <span id="menu_option_tablet_small_device" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>


              <!-- <h1 id="name_of_company">BosheBoshe</h1> -->

              <!-- <h3>Stay Home and Do Shopping</h3> -->
            </div>

            <div id="right_first_section">
              <div class="clearfix">
                <a href="search">
                  <div id="inside_hidden_search">
                    <p class="clearfix"><span>Search</span><span><i class="fa fa-search"></i></span></p>
                  </div>
                </a>
              </div>
            </div>



            <div id="third_first_section">
              <p id="non_hidden_thirdp1"><i class="fa fa-language"></i> Language</p>
              <p id="non_hidden_thirdp2"><span id="third_first_span">বাংলা</span> | <span id="third_second_span">English</span></p>';




              if (strcmp($decryptedFullname, "")!=0) {
                echo '<a id="non_hidden_thirda" href="profile"><p id="non_hidden_thirdp3"><span><i class="fa fa-user"></i></span></p></a>';
              }else{
                echo '<a id="non_hidden_thirda" href="member"><p id="non_hidden_thirdp3"><span><i class="fa fa-user"></i></span></p></a>';
              }

            echo '</div>

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
              <a href="startshopping">For You</a>
              <a href="bakery">Bakery</a>
              <a href="food">Food</a>
              <a href="vegetables">Vegetable</a>
              <a href="fruits">Fruit</a>
              <a href="stationary">Stationary</a>
              <a href="groceries">Groceries</a>
            </span>
          </div>



        </div>    <!-- Sticky Section Ends Here -->';
?>
