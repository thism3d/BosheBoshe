<?php

$foundProduct = 0;


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if(isset($_POST["searchquery"])){
    $productsid = $_POST["searchquery"];


    if(ctype_alpha($productsid)){
      require 'connectserver.php';






      $sql = 'SELECT productscode, productname FROM productsforsell WHERE  productmetadata LIKE "%'. $productsid .'%";';
      $result = $conn->query($sql);

      //$image_location . $row["filename"]

      if ($result->num_rows > 0) {
            // output data of each row

            echo '<div id="search_space_container">';

            while($row = $result->fetch_assoc()) {


              // echo '<a href="product?p='. $row["productscode"]  .'">'. $row["productname"] .'</a>';
              echo '<a href="search?s='. $productsid  .'">'. $row["productname"] .'</a>';



            }

            echo '</div>';

        }else{
          echo '<h2 id="waitingforsearchh2">No Results Found . . .</h2>
          <img id="search_image" src="images/undraw_empty_xct9.svg""><br>
          <br>
          <a href="startshopping"><button id="start_shopping_afterreg">Return Shopping</button></a>';
        }
    }



  }

}


 ?>
