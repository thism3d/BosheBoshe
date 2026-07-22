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



        $sql = 'SELECT orderno, orderedtime, customername, customerphone, customerdelivery, customercity, customerproducts, customercoupon, deliverystatus, deliverytime FROM orderbook WHERE deliverystatus != "Delivered" && deliverystatus != "Cancelled";';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo '
            <div class="all_orders">
              <h2><a href="order_summary?orderid='.  $row["orderno"] .'" target="_blank">Order No '. $row["orderno"] .'</a></h2>

              <p class="time_of_order clearfix">Order Time: ';
              // Code for Order Time
              // 2020-06-02 16:29:46
              $orderTimeOfCustomer = strtotime($row["orderedtime"]);
              $extraTimeToAdd = (6 * 60 * 60) + (60 * 0);
              $finalOrderTime = $orderTimeOfCustomer + $extraTimeToAdd;
              echo date("d-M-Y h:ia", $finalOrderTime), '<br>';
              echo '</p>
              <p class="time_of_delivery clearfix">Delivery Time: ';

              if($row["deliverytime"]==""){
                $finalOrderTime = $finalOrderTime + (60 * 60);
                echo date("d-M-Y h:ia", $finalOrderTime), '<br>';
              }else{
                $finalOrderTime = strtotime($row["deliverytime"]);
                echo date("d-M-Y h:ia", $finalOrderTime), '<br>';
              }


              echo '</p>
              <p>Name: '. $row["customername"] .'</p>
              <p>Phone: '. $row["customerphone"] .'</p>
              <p>Address: '. $row["customerdelivery"] .'</p>
              <p>'. $row["customercity"] .'</p>

              <br>
              <h3 class="items_ordered_h3">Items Ordered</h3>';

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

              echo '
                <hr>
                <div class="single_product_of_order clearfix">
                  <p><b>Item Name</b></p>
                  <p><b>Price</b></p>
                  <p><b>Qty</b></p>
                  <p><b>Subtotal</b></p>
                </div>';

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

                echo '
                  <hr>
                  <div class="single_product_of_order clearfix">
                    <p>'. intval($x+1) .'. ('. $singleProductArray[0] .') '. $singleProductArray[1] .'</p>
                    <!-- <p>('. $singleProductArray[2] .' x '. $singleProductArray[4] .')</p> -->
                    <p>'. $singleProductArray[2] .'</p>
                    <p>'. $singleProductArray[4] .'</p>
                    <p>'. $singleProductArray[5] .' TK</p>
                  </div>';


                unset($singleProductArray);
                $singleProductArray = array();

              }






              echo '

              <!--<div class="single_product_of_order clearfix">
                <p>1. () Fish 1kg (300 x 2) </p>
                <p>600TK</p>
              </div>-->

              <div class="single_product_of_order clearfix total_cost_p_of_ordered">
                <p>Total Cost </p>
                <p>-</p>
                <p style="color: transparent;">-</p>
                <p>'. $totalAmountCalculator .' TK</p>
              </div>';

              if(strcmp( $row["customercoupon"], "besafe")==0){
                echo '

                <div class="single_product_of_order clearfix">
                  <p style="color:transparent;">-</p>
                  <p>-</p>
                  <p style="color: transparent;">-</p>
                  <p> (besafe) - '. $totalBesafeCounter .' TK</p>
                </div>
                <div class="single_product_of_order clearfix total_cost_p_of_ordered">
                  <p>Final Cost </p>
                  <p>-</p>
                  <p style="color: transparent;">-</p>
                  <p>'. intval($totalAmountCalculator-$totalBesafeCounter) .' TK</p>
                </div>';
              }






              echo '

              <h3 class="update_status_h3">Update Status: '. $row["deliverystatus"] .'</h3><br>
              <form method="post" action="order_confirmer.php">
                Date: <input type="datetime-local" id="delivery_time_updateid" name="delivery_time_update" value="'. date("Y-m-d\TH:i", $finalOrderTime) .'"><br>
                <input type="text" id="orderidnumber" placeholder="Order Id" value="'. $row["orderno"] .'" name="ordercode" style="display: none";>
                <input type="radio" id="cancelorderid" name="orderstatus" value="Cancelled">
                <label for="cancelorderid">Cancelled</label>
                <input type="radio" id="cofirmorderid" name="orderstatus" value="Confirmed" checked="checked">
                <label for="cofirmorderid">Confirmed</label>
                <input type="radio" id="deliverorderid" name="orderstatus" value="Delivered">
                <label for="deliverorderid">Delivered</label>
                <input type="submit" name="submit" value="Update Order">
              </form>

            </div>
            ';
          }
        }

?>

























































<?php
        include 'admin_footer.php';
?>


      </body>
<html>
