<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  session_start();


  if(isset($_POST["validation"])){

      require 'connectserver.php';

      $nextProductPointer = "";
      $findLastSessionData = "";
      $extraParametersForSql = "";

      if (isset($_SESSION["nextProduct"])) {
        $nextProductPointer = $_SESSION["nextProduct"];
      }

      if(isset($_SESSION["extraparamsforproduct"])){
        $extraParametersForSql = $_SESSION["extraparamsforproduct"];
      }else{
        $extraParametersForSql = "";
      }
      $sql = 'SELECT productscode, productpicture, productname, minprice, maxprice, offeredprice, besafeless, sellername FROM productsforsell WHERE '. $extraParametersForSql .' productscode > '. $nextProductPointer .' LIMIT 80;';
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

            $findLastSessionData = $row["productscode"];
          }
          $_SESSION["nextProduct"] = $findLastSessionData;
          if($result->num_rows!=80){
            // echo $nextProductPointer;
            echo "EOF";
            // echo '<div style="padding: 10px 0px; font-size: 18px; margin-top: 10px;">-- Ends Here --</div>';
          }
      } else {
        // echo $nextProductPointer;
        echo "EOF";
        // echo '<div style="padding: 10px 0px; font-size: 18px; margin-top: 10px;">-- Ends Here --</div>';
      }

    }

}



 ?>
