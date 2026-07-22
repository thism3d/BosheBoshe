<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if(isset($_POST["userphone"])){

    $userphone = $_POST["userphone"];

    if(ctype_digit($userphone)){
      require 'connectserver.php';


      $sql = 'SELECT phonenumber FROM customers WHERE phonenumber = "'. $userphone .'";';
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        session_start();

        $six_digit_random_number = mt_rand(100910, 991909);

        $_SESSION["phonenumber"] = $_POST["userphone"];
        $_SESSION["userotp"] = $six_digit_random_number;



        /* Code Send SMS Starts */
        $to = $_SESSION["phonenumber"];
        $message = "Your OTP is " . $_SESSION["userotp"] . " for bosheboshe.com login process. Do not share it with anyone.";
        
        /*
        $token = "5c2197ff8b4626b10524566425b51066";
        $url = "http://api.greenweb.com.bd/api.php";


        $data= array(
        'to'=>"$to",
        'message'=>"$message",
        'token'=>"$token"
        ); // Add parameters in key value
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        */
        /* Code Send SMS Ends */
        
        
       
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
        
        // print_r($response);
        

        echo "Found";
      }else{
        echo "Not Found";
      }
    }

  }

}


 ?>
