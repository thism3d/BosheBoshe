<?php


if(isset($_POST["submit"])) {



    require 'connectserver.php';


    $ordercode = $_POST["ordercode"];
    $orderstatus = $_POST["orderstatus"];
    $deliverytime = $_POST["delivery_time_update"];


    echo $deliverytime . "<br><br>";
    $dt = str_replace("T", " ", $deliverytime);
    $deliveryDateTime = strtotime($dt);
    $sqldeliverytime = date("Y-m-d H:i:s", $deliveryDateTime);




    $sql = "";

    if(strcmp($orderstatus, "Cancelled")==0){
      $sql = 'UPDATE orderbook SET deliverystatus="'. $orderstatus .'", status="Cancelled", deliverytime="'. $sqldeliverytime .'" WHERE orderno='. $ordercode .';';
    }else if(strcmp($orderstatus, "Delivered")==0){
      $sql = 'UPDATE orderbook SET deliverystatus="'. $orderstatus .'", status="Success", deliverytime="'. $sqldeliverytime .'" WHERE orderno='. $ordercode .';';
    }else{
      $sql = 'UPDATE orderbook SET deliverystatus="'. $orderstatus .'", deliverytime="'. $sqldeliverytime .'" WHERE orderno='. $ordercode .';';
    }



    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

}

header('Location:  /azone/orders');
exit();

?>
