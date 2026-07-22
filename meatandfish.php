<!DOCTYPE html>
<html lang="en">
      <head>

          <title>Meat and Fish - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>


          <?php

              session_start();
              require_once("headermeta.php");




          ?>





      </head>
      <body>


<?php
        include 'header.php';


        // Finding IOS User Agent Starts Here
        $iosFound = 0;
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
        $webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
        if( $iPod || $iPhone || $iPad ){
            $iosFound = 1;
        }

        $findLastSessionData = "";

        $eofFoundOrNot = 0;


          $_SESSION["extraparamsforproduct"] = "productcategories LIKE '%MeatAndFish%' AND";
          $extraParametersForSql = "";

          if(isset($_SESSION["extraparamsforproduct"])){
            $extraParametersForSql = $_SESSION["extraparamsforproduct"];
          }else{
            $extraParametersForSql = "";
          }



        // include 'cookieforcart.php';
?>



















  <div id="every_product_category" class="clearfix">   <!-- Every product container starts here -->





          <div id="all_products_container" class="clearfix">    <!-- Products Section Starts Here -->

              <p id="nagivator_on_products"><a href="index">Home</a> &gt; <a href="bakery">Bakery</a></p>
              <h2 id="all_products_container_h2">Bakery</h2>

              <br><br><br>

              <div id="products_here" class="clearfix">         <!-- Products Goes Here -->




                <?php
                  require 'connectserver.php';



                  $sql = 'SELECT productscode, productpicture, productname, minprice, maxprice, offeredprice, besafeless, sellername FROM productsforsell WHERE '. $extraParametersForSql .' productscode > 0 LIMIT 80;';
                  $result = $conn->query($sql);

                  $image_location = 'productsimage/';

                  //$image_location . $row["filename"]

                  if ($result->num_rows > 0) {
                      // output data of each row
                      while($row = $result->fetch_assoc()) {

                        if($iosFound==1){       // IOS PLUS MINUS SYMBOL
                          $row["productname"] = str_replace("&plusmn;","+-",$row["productname"]);
                        }

                        echo '

                          <div class="single_product">   <!-- Single Product Starts Here -->
                          <a href="product?p='. $row["productscode"] .'">
                            <img class="lazy" src="backgroundopacity.png" data-src="'. $image_location . $row["productpicture"] .'" alt="'. $row["productname"] .'">
                            <p id="nameOfProduct'. $row["productscode"] .'">'. $row["productname"] .'</p>
                            <p class="price_tag"><b><span id="priceOf'. $row["productscode"] .'">'. $row["offeredprice"] .'</span> TK</b><span id="besafepriceof'. $row["productscode"] .'" style="display:none;">'. $row["besafeless"]  .'</span></p>
                          </a>
                            <button class="add_to_cart_btn_class" id="btn'. $row["productscode"] .'" onclick="addtocartfunction(this.id, '. $row["productscode"] .')"><i class="fa fa-shopping-cart"></i> Add to cart</button>
                          </div>                          <!-- Single Product Ends Here -->



                        ';

                        $findLastSessionData = $row["productscode"];
                      }

                      $_SESSION["nextProduct"] = $findLastSessionData;

                      if($result->num_rows<80){
                        $eofFoundOrNot = 1;
                      }
                  } else {
                      echo "0 results";
                  }

                ?>










              </div>
              <?php

                if($eofFoundOrNot == 1){
                  echo '<div style="text-align:center; padding: 10px 0px; font-size: 18px; margin-top: 10px;">-- Ends Here --</div><br>';
                }else{
                  echo '<p id="again_loading_information" style="text-align:center; margin: 20px 0px 70px 0px; padding: 10px; font-size: 18px;"><i class="fa fa-circle-o-notch fa-spin"></i> Loading More Products</p>';
                }

               ?>

              <!-- <button id="view_more_btn" onclick="loadDoc()"><i class="fa fa-plus-square"></i> See More Products</button> -->
          </div>                              <!-- Products Section Ends Here -->


</div>














<!-- <script src="addtocartjavascript.js"></script> -->







<?php
        include 'footer.php';
?>











      </body>
<html>
