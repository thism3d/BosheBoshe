<!DOCTYPE html>
<html>
      <head>

          <title>Payment Successful</title>


          <?php require_once("headernofollowmeta.php"); ?>


          <style>
            #left_text_notification{
              display: inline-table;
              text-align: left;
            }
          </style>




      </head>
      <body>


<?php


$transaction_status = $transaction_date = $transaction_id = $transaction_card_type = $transactionamount = $valueOfOrderCode = "";
$ordernofound = $ordercustomername =  $customerPhone = "";


$post_success = 0;

if(isset($_POST['val_id'])) {

$post_success = 1;


$val_id=urlencode($_POST['val_id']);
$store_id=urlencode("bosheboshelive");
$store_passwd=urlencode("5EA80343D2D8468941");
$requested_url = ("https://securepay.sslcommerz.com/validator/api/validationserverAPI.php?val_id=".$val_id."&store_id=".$store_id."&store_passwd=".$store_passwd."&v=1&format=json");

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $requested_url);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); # IF YOU RUN FROM LOCAL PC
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); # IF YOU RUN FROM LOCAL PC

$result = curl_exec($handle);

$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if($code == 200 && !( curl_errno($handle)))
{

	# TO CONVERT AS ARRAY
	# $result = json_decode($result, true);
	# $status = $result['status'];

	# TO CONVERT AS OBJECT
	$result = json_decode($result);

	# TRANSACTION INFO
	$status = $result->status;
	$tran_date = $result->tran_date;
	$tran_id = $result->tran_id;
	$val_id = $result->val_id;
	$amount = $result->amount;
	$store_amount = $result->store_amount;
	$bank_tran_id = $result->bank_tran_id;
	$card_type = $result->card_type;

	# EMI INFO
	$emi_instalment = $result->emi_instalment;
	$emi_amount = $result->emi_amount;
	$emi_description = $result->emi_description;
	$emi_issuer = $result->emi_issuer;

	# ISSUER INFO
	$card_no = $result->card_no;
	$card_issuer = $result->card_issuer;
	$card_brand = $result->card_brand;
	$card_issuer_country = $result->card_issuer_country;
	$card_issuer_country_code = $result->card_issuer_country_code;

  # Order Code Finder
  $valueOfOrderCode = $result->value_a;

	# API AUTHENTICATION
	$APIConnect = $result->APIConnect;
	$validated_on = $result->validated_on;
	$gw_version = $result->gw_version;


  $transaction_status = $status;
  $transaction_date = $tran_date;
  $transaction_id = $tran_id;
  $transaction_card_type = $card_type;
  $transactionamount = $amount;


  if(strcmp("VALID", $transaction_status)==0){


    // SQL Update Starts Here
    require 'connectserver.php';

    $sql = 'UPDATE orderbook SET status = "Success", deliverystatus = "Confirmed", transaction_id="'. $transaction_id .'" WHERE orderno = "'. $valueOfOrderCode .'";';

    if ($conn->query($sql) === TRUE) {
        $sql2 = 'SELECT orderno, customername, customerPhone FROM orderbook WHERE orderno = "'. $valueOfOrderCode .'";';
        $result = $conn->query($sql2);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $ordernofound = $row["orderno"];
                $ordercustomername = $row["customername"];
                $customerPhone = $row["customerPhone"];
            }
        }
    }
    // SQL Update Finished Here



    // Email Starts Here
    $to = "muzahid221@gmail.com, atiyafahmida42@gmail.com";
    $subject = "Payment Confirmation";

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
                <b>Name : </b>'. $ordercustomername .'<br>
                <b>Phone: </b>'. $customerPhone .'<br>
                <b>Amount: </b>'. $transactionamount .'<br>
            </p>

            <hr>
            <h3>Order No '. $ordernofound .'</h3>
            <hr>
            <p>
                Payment Successful.<br>
                Use your admin panel to process this order.
            </p>
        </body>
    </html>
    ';



    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    $headers .= 'From: Bosheboshe Payments <orders@bosheboshe.com>' . "\r\n";

    $retval = mail($to,$subject,$message,$headers);
    // Email Ends Here





    // Message Starts Here
    $to = "01714526039," . $customerPhone;
    $message = "Payment Succesful. Total Amount (TK ". $transactionamount ."), Order No: " . $ordernofound . ". Happy Shopping with bosheboshe.com";


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
   
    // Message Ends Here


  }

} else {

	echo "Failed to connect with SSLCOMMERZ";
}

}





include 'header.php';









if($post_success==1){
  echo'
    <div id="user_sent_message_container">
      <img src="images/undraw_confirmation_2uy0.svg">
      <br>
      <br>
      <br>
      <p id="message_query_number_p">
        <b style="font-size:21px;">Payment Successful</b>
        <br>
        <br>

        <hr>
        <br>
        <div id="left_text_notification">
        <b>Order Code: '. $valueOfOrderCode .'</b><br>
        <b>Status: '. $transaction_status .'</b><br>
        <b>Date: '. $transaction_date .'</b><br>
        <b>Transaction Id: '. $transaction_id .'</b><br>
        <b>Method: '. $transaction_card_type .'</b><br>
        </div>
        <br>
        <br>
      </p>

      <p>Happy shopping. 😊</p>



      <a href="profile"><button id="return_shopping">View Summary</button></a>
    </div>';

}else{
  echo'
    <div id="user_sent_message_container">
      <img src="images/undraw_at_home_octe.svg">
      <br>
      <br>
      <br>
      <p id="message_query_number_p">
        <b style="font-size:21px;">Start Shopping with BosheBoshe!</b>
      </p><br>



      <a href="index"><button id="return_shopping">BosheBoshe Home</button></a>
    </div>';
}







?>



































<?php
        include 'footer.php';
?>


      </body>
<html>
