<!DOCTYPE html>
<html>
      <head>

          <title>Order Summary - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>

          <meta name="robots" content="noindex">
          <meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, initial-scale=1.0, user-scalable=no, shrink-to-fit=no" />


          <!-- CSS For this Page -->
          <link rel="icon" href="../icon.png">
  	      <link rel="stylesheet" href="main_admin.css">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


          <style>


            *{
              background-color: white;
            }

            .all_orders{
              max-width: 600px;
              border: none;
            }


            #main_container{
              background-color: white;
            }

            #main_container div{
              font-family: 'Roboto', sans-serif;
            }

            .all_orders *{
              font-family: 'Roboto', sans-serif;
            }

            form{
              display: none;
            }

            .update_status_h3{
              display: none;
            }

            #imagefor_smaller_device{
              display: none;
            }

            #login_for_smaller_device{
              display: none;
            }

            #final_header{
              display: none;
            }

            #header_section_sticky{
              display: none;
            }

            #last_footer{
              display: none;
            }

            #last_fixed_footerprinter{
              text-align: center;
              position: fixed;
              bottom: 0;
              width: 100%;
            }


            #printer_footer{
              width: 100%;
              box-sizing: border-box;
              padding: 28px 0px;
            }


            #printer_footer div{
              float: left;
            }


            #left_printer_footer{
              width: 40%;
              font-size: 20px;
            }

            #left_printer_footer i{
              font-size: 28px;
            }

            #right_printer_footer{
              padding-top: 4px;
              width: 60%;
              font-size: 17px;
              font-weight: lighter;
            }


          .total_cost_p_of_ordered {
            border-top: 2px solid #adadad
          }


          #main_container{
              padding-bottom: 80px;
          }

          #last_fixed_footerprinter{
            display: none;
          }



          @media print {
            #last_fixed_footerprinter{
              display:block;
            }

            #office_recipt_print_section{
              display: none;
            }
          }



          </style>



      </head>
      <body onafterprint="afterPrintFunction()">


      <div id="office_recipt_print_section">
        <button onclick="printOfficeReciptCopy()">Print Office Recipt</button>
        <button onclick="printCustomerReciptCopy()">Print Customer Recipt</button>
      </div>


<?php



        $orderid = "";

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
          $orderid = $_GET["orderid"];

          if(!$orderid){
            $str = "Location: index.php";
            header($str);
          }
        }



        include 'admin_header.php';



        require 'connectserver.php';



        $sql = 'SELECT orderno, orderedtime, customername, customerphone, customerdelivery, customercity, customerproducts, customercoupon, deliverystatus, deliverytime FROM orderbook WHERE orderno="'. $orderid .'";';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {


          echo '<div style="text-align: center;">
            <br><br>
            <img style="max-width: 240px; width: 100%;" src="Final_Logo_For_Web.png">
            <br>
            <h3 style="padding: 10px;">Stay home, let us deliver. Shop with <span style="color:firebrick;">bosheboshe.com</span><h3>
          </div>';

          echo '<br><hr>';


          while($row = $result->fetch_assoc()) {
            echo '
            <div class="all_orders">
              <h2>Order No '. $row["orderno"] .'</h2>

              <p id="office_delivery_time">Delivery Time: ';

              if($row["deliverytime"]==""){

                $orderTimeOfCustomer = strtotime($row["orderedtime"]);
                $extraTimeToAdd = (7 * 60 * 60) + (60 * 0);
                $finalOrderTime = $orderTimeOfCustomer + $extraTimeToAdd;
                echo date("d-M-Y h:ia", $finalOrderTime), '<br>';

              }else{
                echo date("d-M-Y h:ia", strtotime($row["deliverytime"]));
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
                    <p>'. intval($x+1) .'. '. $singleProductArray[1] .'</p>
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

              <h3 class="update_status_h3">Update Status: '. $row["deliverystatus"] .'</h3>
              <form method="post" action="order_confirmer.php">
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




<div id="last_fixed_footerprinter">

<hr>
<div id="printer_footer" class="clearfix">


  <div id="left_printer_footer">
  <i class="fa fa-facebook-square"></i>
  <i class="fa fa-instagram"></i>
  <i class="fa fa-twitter-square"></i>
  bosheboshebd
</div>

  <div id="right_printer_footer">
    Hotline: 01884084849 | Email: enquiries@bosheboshe.com
  </div>
</div>
</div>


<div>
  <span id="office_copy_print">Office Copy</span>
</div>




<script>
  var office_copy_print = document.getElementById("office_copy_print");
  var office_delivery_time = document.getElementById("office_delivery_time");

  var officeClicked = 0;

  function printOfficeReciptCopy() {
    officeClicked = 1;

    office_copy_print.style.display = "inline";
    office_delivery_time.style.display = "block";
    window.print();
  }


  function printCustomerReciptCopy() {
    window.print();
    officeClicked = 0;
  }

  function afterPrintFunction() {

    if(officeClicked == 1){
      office_copy_print.style.display = "none";
      office_delivery_time.style.display = "none";
    }

    officeClicked = 0;
  }


</script>







      </body>
<html>
