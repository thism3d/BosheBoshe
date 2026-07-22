<!DOCTYPE html>
<html lang="en">
      <head>

          <title>Ramadan - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>


          <?php require_once("headermeta.php"); ?>





      </head>
      <body>


<?php
        include 'header.php';

        // include 'cookieforcart.php';
?>

















  <div id="every_product_category" class="clearfix">   <!-- Every product container starts here -->





          <div id="all_products_container" class="clearfix">    <!-- Products Section Starts Here -->

              <p id="nagivator_on_products"><a href="index">Home</a> &gt; <a href="eid">Eid</a></p>
              <h2 id="all_products_container_h2">Eid</h2>

              <br><br><br>

              <div id="products_here" class="clearfix">         <!-- Products Goes Here -->




                <?php
                  require 'connectserver.php';

                  $sql = "SELECT productscode, productpicture, productname, minprice, maxprice, offeredprice, besafeless, sellername FROM productsforsell WHERE productcategories LIKE '%Ramadan%' ORDER BY productscode DESC;";
                  $result = $conn->query($sql);

                  $image_location = 'productsimage/';

                  //$image_location . $row["filename"]

                  if ($result->num_rows > 0) {
                      // output data of each row
                      while($row = $result->fetch_assoc()) {

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


                      }
                  } else {
                      echo "0 results";
                  }

                ?>










              </div>

              <br>
              <br>
              <br>
              <br>
              <!-- <button id="view_more_btn"><i class="fa fa-plus-square"></i> See More Products</button> -->
          </div>                              <!-- Products Section Ends Here -->


</div>
















<!-- <script src="addtocartjavascript.js"></script> -->







<?php
        include 'footer.php';
?>


      </body>
<html>
