<!DOCTYPE html>
<html lang="en">
      <head>



        <!-- Carousel slider -->

        <link rel="stylesheet" href="owlcarousel/owl.carousel.min.css">
        <link rel="stylesheet" href="owlcarousel/owl.theme.default.min.css">
        <script src="jquery.min.js"></script>
        <script src="owlcarousel/owl.carousel.min.js"></script>


<?php








    require 'cookiesvariables.php';


    $foundProduct = 0;


    if ($_SERVER["REQUEST_METHOD"] == "GET") {
      $productsid = "";
      if(isset($_GET["p"])){
        $productsid = $_GET["p"];
      }


      if(!$productsid){
        $foundProduct = 0;
      }else{

        if(ctype_digit($productsid)){
          $foundProduct = 1;
          require 'connectserver.php';



          $sql = 'SELECT productscode, productshortdetails, productpicture, productname, minprice, maxprice, offeredprice, besafeless, sellername, extraimages FROM productsforsell WHERE  productscode = '. $productsid .' LIMIT 30;';
          $result = $conn->query($sql);

          $image_location = 'productsimage/';
          $extra_image_location = 'extradetails/';

          //$image_location . $row["filename"]

          if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {

                  echo '

                  <title>'. $row["productname"] .' - Product Bosheboshe</title>';

                  require_once("headermeta.php");

                  echo '<style>
                      .single_product{
                          height: auto;
                      }

                  </style>

              </head>
              <body>';


                  include 'header.php';

                  $extraImagesOfProduct = trim($row["extraimages"]);

                  if(strcmp($extraImagesOfProduct, "")==0){
                    echo '



                        <div id="view_single_product" class="clearfix">



                          <div class="single_product">   <!-- Single Product Starts Here -->
                            <img class="lazy" src="backgroundopacity.png" data-src="'. $image_location . $row["productpicture"] .'" alt="'. $row["productname"] .'">
                            <p id="nameOfProduct'. $row["productscode"] .'">'. $row["productname"] .'</p>
                            <p class="price_tag"><b><span id="priceOf'. $row["productscode"] .'">'. $row["offeredprice"] .'</span> TK</b><span id="besafepriceof'. $row["productscode"] .'" style="display:none;">'. $row["besafeless"]  .'</span></p>
                            <button class="add_to_cart_btn_class" id="btn'. $row["productscode"] .'" onclick="addtocartfunction(this.id, '. $row["productscode"] .')"><i class="fa fa-shopping-cart"></i> Add to cart</button>
                          </div>                          <!-- Single Product Ends Here -->';

                          if(strcmp(trim($row["productshortdetails"]), "")!=0){
                            echo '<h2 id="details_text">Deatils</h2>
                                  <hr>
                                <div id="description_of_singe_products">
                                  <div id="description_product_inside">
                                    '. $row["productshortdetails"] .'
                                  </div>
                                </div>';
                          }


                      echo '<a href="startshopping"><button id="start_shopping_afterviewProduct">Return Shopping</button></a>

                          </div>


                      ';
                  }else{
                    echo '<div id="view_single_product" class="clearfix">



                      <div class="single_product">   <!-- Single Product Starts Here -->


                            <!-- Owl Carousel Slider -->
                             <div class="owl-carousel owl-theme">
                                <div class="item"><img src="'. $image_location . $row["productpicture"] .'" alt="'. $row["productname"] .'"></div>';
                                $extraImagesString = $extraImagesOfProduct;

                                $exraAltCounter = 0;
                                $token = strtok($extraImagesString, "~");

                                while ($token !== false){
                                  $exraAltCounter = $exraAltCounter + 1;

                                  echo '<div class="item"><img src="'. $extra_image_location . $token .'" alt="'. $row["productname"] . " " . $exraAltCounter .'"></div>';

                                  $token = strtok("~");
                                }


                          echo '</div>


                              <script>
                              $(\'.owl-carousel\').owlCarousel({
                                loop:true,
                                nav:true,
                                navText:["<div class=\'nav-btn prev-slide\'></div>","<div class=\'nav-btn next-slide\'></div>"],
                                rewind:true,
                                lazyLoad: true,
                                autoplay: true,
                                autoplayTimeout: 4000,
                                stagePadding: 24,
                                responsive:{
                                    0:{
                                        items:1
                                    },
                                    600:{
                                        items:1
                                    },
                                    1000:{
                                        items:1
                                    }
                                }
                              })
                            </script>



                        <!-- <img class="lazy" src="backgroundopacity.png" data-src="productsimage/1587370251.kitkat_chocolate.jpg" alt="KitKat Chocolate 1 Piece"> -->
                        <p id="nameOfProduct'. $row["productscode"] .'">'. $row["productname"] .'</p>
                        <p class="price_tag"><b><span id="priceOf'. $row["productscode"] .'">'. $row["offeredprice"] .'</span> TK</b><span id="besafepriceof'. $row["productscode"] .'" style="display:none;">'. $row["besafeless"]  .'</span></p>
                        <button class="add_to_cart_btn_class" id="btn'. $row["productscode"] .'" onclick="addtocartfunction(this.id, '. $row["productscode"] .')"><i class="fa fa-shopping-cart"></i> Add to cart</button>
                      </div>                          <!-- Single Product Ends Here -->';

                      if(strcmp(trim($row["productshortdetails"]), "")!=0){
                        echo '<h2 id="details_text">Deatils</h2>
                              <hr>
                            <div id="description_of_singe_products">
                              <div id="description_product_inside">
                                '. $row["productshortdetails"] .'
                              </div>
                            </div>';
                      }




                  echo '<a href="startshopping"><button id="start_shopping_afterviewProduct">Return Shopping</button></a>

                      </div>';
                  }





                }
            } else {
              $foundProduct = 0;
            }
        }else{
          $foundProduct = 0;
          // echo "Not a digit";
        }


      }

    }

    if($foundProduct==0){

      echo '
      <title>Product Not Found - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>';

      require_once("headermeta.php");


      echo '<style>
          .single_product{
              height: auto;
          }

      </style>


  </head>
  <body>';


      include 'header.php';
    }



?>





































    <div id="registration_confirmation">


      <?php
        if($foundProduct==0){
          echo '<img src="images/undraw_empty_xct9.svg">
                <h2>Product Not Found!</h2>
                <a href="startshopping"><button id="start_shopping_afterreg">Return Shopping</button></a>';
        }else{

        }

       ?>


    </div>









<!-- <script src="addtocartjavascript.js"></script> -->








<?php
        include 'footer.php';
?>


      </body>
<html>
