<!DOCTYPE html>
<html>
      <head>

          <title>Orders Processing - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>
          <meta name="robots" content="noindex">
          <meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, initial-scale=1.0, user-scalable=no, shrink-to-fit=no" />


          <!-- CSS For this Page -->
          <link rel="icon" href="../icon.png">
  	      <link rel="stylesheet" href="main_admin.css">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



      </head>
      <body>



<?php
        include 'admin_header.php';



        require 'connectserver.php';

        echo '<div id="order_status_container">';



        $sql = 'SELECT orderno, orderedtime, customername, customerphone, customerdelivery, customercity, customerproducts, customercoupon, deliverystatus FROM orderbook';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {

          echo '<table id="customers">
            <tr>
              <th>Order Code</th>
              <th>OrderDate</th>
              <th>Name</th>
              <th>Number</th>
              <th>Address</th>
              <th>Sell (TK)</th>
              <th>Delivery Status</th>
            </tr>';
          while($row = $result->fetch_assoc()) {
            // echo '
            // <div class="all_orders">
            //   ';

              // echo $row["customercoupon"];

              // echo $row["customerproducts"];

              $allproducts=array();


              $string = $row["customerproducts"];
              $token = strtok($string, ",");

              while ($token !== false){
                array_push($allproducts,$token);
                 $token = strtok(",");
              }



              $totalAmountCalculator = 0;

              $totalBesafeCounter = 0;




              $singleProductArray=array();

              $lengthAllProduct = count($allproducts);
              for($x = 0; $x < $lengthAllProduct; $x++){
                // echo $allproducts[$x] . "<br>";

                $singleProductArrayString = $allproducts[$x];

                $token = strtok($singleProductArrayString, "~");

                while ($token !== false){
                  array_push($singleProductArray,$token);
                   $token = strtok("~");
                }

                // echo count($singleProductArray) . "<br>";

                $totalAmountCalculator = $totalAmountCalculator + intval( $singleProductArray[5]);
                $totalBesafeCounter = $totalBesafeCounter + intval($singleProductArray[3] * $singleProductArray[4]);


                // echo '<div class="single_product_of_order clearfix">
                //     <p>'. intval($x+1) .'. ('. $singleProductArray[0] .') '. $singleProductArray[1] .' ('. $singleProductArray[2] .' x '. $singleProductArray[4] .') </p>
                //     <p>'. $singleProductArray[5] .' TK</p>
                //   </div>';


                unset($singleProductArray);
                $singleProductArray = array();

              }






              // echo '
              //
              // <!--<div class="single_product_of_order clearfix">
              //   <p>1. () Fish 1kg (300 x 2) </p>
              //   <p>600TK</p>
              // </div>-->
              //
              // <div class="single_product_of_order clearfix total_cost_p_of_ordered">
              //   <p>Total Cost </p>
              //   <p>'. $totalAmountCalculator .' TK</p>
              // </div>';

              $finalTotalPrice = 0;

              if(strcmp( $row["customercoupon"], "besafe")==0){




                if($totalBesafeCounter<100){
                  $finalTotalPrice = intval($totalAmountCalculator-$totalBesafeCounter);
                }else{
                  $finalTotalPrice = intval($totalAmountCalculator - 100);
                }

                // echo '
                //
                // <div class="single_product_of_order clearfix">
                //   <p style="color:transparent;">-</p>
                //   <p> (besafe) - '. $totalBesafeCounter .' TK</p>
                // </div>
                // <div class="single_product_of_order clearfix total_cost_p_of_ordered">
                //   <p>Final Cost </p>
                //   <p>'. intval($totalAmountCalculator-$totalBesafeCounter) .' TK</p>
                // </div>';
              }else{
                $finalTotalPrice = $totalAmountCalculator;
              }


              echo '

                <tr>
                  <td><a href="order_summary?orderid='. $row["orderno"] .'"  target="_blank">'. $row["orderno"] .'</a></td>
                  <td>'. substr($row["orderedtime"], 0, 10) .'</td>
                  <td>'. $row["customername"] .'</td>
                  <td>'. $row["customerphone"] .'</td>
                  <td>'. $row["customerdelivery"] . ", " . $row["customercity"] .'</td>
                  <td style="text-align:right;">'. $finalTotalPrice .'</td>
                  <td>'. $row["deliverystatus"] .'</td>
                </tr>';






            //   echo '
            //
            // </div>
            // ';
          }

          echo '</table>';
        }

        echo '</div>';

?>






















































<?php
        include 'admin_footer.php';
?>


      </body>
<html>
