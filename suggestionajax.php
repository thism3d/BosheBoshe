<?php





if ($_SERVER["REQUEST_METHOD"] == "POST") {



  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }




  if(isset($_POST["suggestionName"]) && isset($_POST["suggestionNumber"]) && isset($_POST["suggestionText"])){
    $suggestionName = test_input($_POST["suggestionName"]);
    $suggestionNumber = test_input($_POST["suggestionNumber"]);
    $suggestionText = test_input($_POST["suggestionText"]);




    /* CREATE TABLE productssuggestion(serial INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT, customername VARCHAR(150) NOT NULL, customerphone VARCHAR(150) NOT NULL, customertext VARCHAR(4005) NOT NULL);


      INSERT INTO productssuggestion(customername, customerphone, customertext) VALUES("Arpa", "01714526039", "Some Text");
   */


    $servername = "localhost";
    $username = "muzahid_udtxasd";
    $password = "@RGYhjfasdtU1245";
    $dbname = "muzahid_userdatabase";

    $conn = new mysqli($servername, $username, $password, $dbname);


    $stmt = $conn->prepare('INSERT INTO productssuggestion(customername, customerphone, customertext) VALUES(?, ?, ?)');
    $stmt->bind_param("sss", $suggestionName, $suggestionNumber, $suggestionText);


    if ($stmt->execute()) {
      // echo $stmt->insert_id;



      /* On Complete Order Sent Us a Mail */

      $to = "muzahid221@gmail.com, atiyafahmida42@gmail.com";
      $subject = "Products Suggestion";

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
                  <b>Name : </b>'. $suggestionName .'<br>
                  <b>Phone: </b>'. $suggestionNumber .'<br>
              </p>

              <hr>
              <h3>Suggestion Text!</h3>
              <hr>
              <p>
                  '. $suggestionText .'
              </p>
          </body>
      </html>
      ';



      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

      $headers .= 'From: Bosheboshe Support <support@bosheboshe.com>' . "\r\n";

      $retval = mail($to,$subject,$message,$headers);





      $to = "01714526039," . $suggestionNumber;


      $finalTrimmedName = strtok($suggestionName, " ");
      if(strlen($finalTrimmedName)>20){
        $finalTrimmedName = substr($finalTrimmedName, 0, 20);
      }

      $message = "Dear ". $finalTrimmedName .", your suggestion has been received by bosheboshe.com. Thank You and Happy Shopping.";

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

      echo $finalTrimmedName;

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
