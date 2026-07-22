<?php





if ($_SERVER["REQUEST_METHOD"] == "POST") {



  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  if(isset($_POST["customerName"]) && isset($_POST["customerPhone"]) && isset($_POST["cutsomerDelivery"]) && isset($_POST["customerCity"]) && isset($_POST["customerProducts"]) && isset($_POST["customerCoupon"])){
    $customerName = test_input($_POST["customerName"]);
    $customerPhone = test_input($_POST["customerPhone"]);
    $cutsomerDelivery = test_input($_POST["cutsomerDelivery"]);
    $customerCity = test_input($_POST["customerCity"]);
    $customerProducts = test_input($_POST["customerProducts"]);
    $customerCoupon = test_input($_POST["customerCoupon"]);


    // echo $customerName;
    // echo $customerPhone;
    // echo $cutsomerDelivery;
    // echo $customerCity;
    // echo $customerProducts;
    // echo $customerCoupon;


    require_once __DIR__ . '/connectserver.php';


    $stmt = $conn->prepare('INSERT INTO orderbook(customername, customerphone, customerdelivery, customercity, customerproducts, customercoupon) VALUES(?, ?, ?, ?, ?, ?)');
    $stmt->bind_param("ssssss", $customerName, $customerPhone, $cutsomerDelivery, $customerCity, $customerProducts, $customerCoupon);


    if ($stmt->execute()) {
      echo $stmt->insert_id;



      /* On Complete Order Sent Us a Mail */

      $to = "muzahid221@gmail.com";
      $subject = "Order Confirmation";

      $message = '
      <html>
          <head>
              <style>

                  body{
                      text-align: center;
                  }

                  img{
                      max-width: 300px;
                      width: 100%;
                      padding: 30px;
                      box-sizing: border-box;
                  }

                  p{
                      text-align: left;
                      padding:
                  }
              </style>
          </head>
          <body>
              <img src="https://bosheboshe.com/bosheboshefinal.png" alt="BosheBoshe.com">
              <hr>
              <p>
                  <b>Name : </b>'. $customerName .'<br>
                  <b>Phone: </b>'. $customerPhone .'<br>
                  <b>Delivery Address: </b>'. $cutsomerDelivery .'<br>
                  <b>City: </b>'. $customerCity .'<br>
              </p>

              <hr>
              <h3>Order Code '. $stmt->insert_id .'</h3>
              <hr>
              <p>
                  Order has been placed successfully.<br>
                  Use your admin panel to confirm this order.
              </p>
          </body>
      </html>
      ';



      
         
      require 'sendmail.php';
      $retval = sendmail($to, $subject, $message);
             
      $to = "atiyafahmida42@gmail.com";
      $retval = sendmail($to, $subject, $message);





      $to = "01714526039," . $customerPhone;
      $token = "5c2197ff8b4626b10524566425b51066";
      $message = "Your order " . $stmt->insert_id . " has been received by bosheboshe.com";

      $url = "http://bulksmsbd.net/api/smsapi";
      $api_key = "OKfopQjVVNmaMjM1O98E";
      $senderid = "8809617626388";
      $number = $to;
      $message = $message;
 
      $data = [
          "api_key" => $api_key,
          "senderid" => $senderid,
          "number" => $number,
          "message" => $message
      ];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $response = curl_exec($ch);
      curl_close($ch);
      $smsresult = $response;

      // if( $retval == true ) {
      //   echo "Sent";
      // }else {
      //   echo "No";
      // }
      /* Mail Section Ends Here */



    } else {
      echo "N";
    }


  }else{
    echo "N";
  }

}else{
  echo "N";
}


 ?>
