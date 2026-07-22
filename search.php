<!DOCTYPE html>
<html lang="en">
      <head>

          <title>Search - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>


          <?php require_once("headermeta.php"); ?>



      </head>
      <body>


<?php
        include 'headerforsearch.php';






        require 'cookiesvariables.php';


        $foundProduct = 0;
        $sqlNoResultFound = 0;


        if ($_SERVER["REQUEST_METHOD"] == "GET") {
          $productsid = "";
          if(isset($_GET["s"])){
            $productsid = $_GET["s"];
            $productsid = urldecode($productsid);
            
          }


          if(!$productsid){
            $foundProduct = 0;
          }else{

            if($productsid){
            // if(ctype_alpha($productsid)){
              $foundProduct = 1;
              require 'connectserver.php';



              $sql = 'SELECT productscode, productpicture, productname, minprice, maxprice, offeredprice, besafeless, sellername FROM productsforsell WHERE  productmetadata LIKE "%'. $productsid .'%" OR productname LIKE "%'. $productsid .'%";';
              $result = $conn->query($sql);

              $image_location = 'productsimage/';

              //$image_location . $row["filename"]

              if ($result->num_rows > 0) {


                echo '
                  <div id="every_product_category" class="clearfix">   <!-- Every product container starts here -->





                          <div id="all_products_container" class="clearfix">    <!-- Products Section Starts Here -->

                              <p id="nagivator_on_products"><a href="index">Home</a> &gt; <a href="search">Search</a></p>
                              <h2 id="all_products_container_h2">Results Found</h2>

                              <br><br><br>

                              <div id="products_here" class="clearfix">         <!-- Products Goes Here -->';
                    // output data of each row
                    while($row = $result->fetch_assoc()) {

                      echo '

                        <div class="single_product">   <!-- Single Product Starts Here -->
                        <a href="product?p='. $row["productscode"] .'">
                          <img class="lazy" src="backgroundopacity.png" data-src="'. $image_location . $row["productpicture"] .'">
                          <p id="nameOfProduct'. $row["productscode"] .'">'. $row["productname"] .'</p>
                          <p class="price_tag"><b><span id="priceOf'. $row["productscode"] .'">'. $row["offeredprice"] .'</span> TK</b><span id="besafepriceof'. $row["productscode"] .'" style="display:none;">'. $row["besafeless"]  .'</span></p>
                        </a>
                          <button class="add_to_cart_btn_class" id="btn'. $row["productscode"] .'" onclick="addtocartfunction(this.id, '. $row["productscode"] .')"><i class="fa fa-shopping-cart"></i> Add to cart</button>
                        </div>                          <!-- Single Product Ends Here -->



                      ';


                    }

                    echo '








                                  </div>

                                  <!-- <button id="view_more_btn"><i class="fa fa-plus-square"></i> See More Products</button> -->
                              </div>                              <!-- Products Section Ends Here -->


                    </div>



';

                } else {
                  $foundProduct = 0;
                  $sqlNoResultFound = 1;
                }
            }else{
              $foundProduct = 0;
              // echo "Not a digit";
            }


          }

        }








        if($foundProduct==0 && $sqlNoResultFound==0){
          echo '<div id="search_result_goes_here_conatiner">
            <h2 id="waitingforsearchh2">Waiting for search . . .</h2>
            <img id="search_image" src="images/undraw_file_searching_duff.svg"><br>
            <br>
            <a href="startshopping"><button id="start_shopping_afterreg">Return Shopping</button></a>
          </div>';
        }else if($sqlNoResultFound==1){
          echo '
          <div id="search_result_goes_here_conatiner">
          <h2 id="waitingforsearchh2">No Results Found . . .</h2>
          <img id="search_image" src="images/undraw_empty_xct9.svg""><br>
          <br>
          <a href="startshopping"><button id="start_shopping_afterreg">Return Shopping</button></a>
          </div>';
        }

?>











































<script src="search_javascript_1.1.js"></script>
<!-- <script src="addtocartjavascript.js"></script> -->








<?php
        include 'footer.php';
?>


      </body>
<html>
