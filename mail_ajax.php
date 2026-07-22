<?php


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {


  $nameofsender = "";
  $emailofsender = "";
  $mobileofsender = "";
  $messageofsender = "";

  $nameofsender = test_input($_POST["sendername"]);
  $emailofsender = test_input($_POST["senderemail"]);
  $mobileofsender = test_input($_POST["sendermobile"]);
  $messageofsender = test_input($_POST["sendermessage"]);

  if (strlen($nameofsender)>80 || strlen($emailofsender)>255  || strlen($mobileofsender)>15  || strlen($messageofsender)>4000) {
    echo "Error";
  }else{



    // echo $nameofsender . ' ' . $emailofsender . ' ' . $mobileofsender . ' ' . $messageofsender;

    $to = "muzahid221@gmail.com";
    $subject = "Customer Enquiries";

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
                <b>Name : </b>'. $nameofsender .'<br>
                <b>Email: </b>'. $emailofsender .'<br>
                <b>Phone: </b>'. $mobileofsender .'<br>
            </p>

            <hr>
            <h3>Customer Message</h3>
            <hr>
            <p>
                '. $messageofsender .'
            </p>
        </body>
    </html>
    ';


    /*
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: Bosheboshe Enquiries <enquiries@bosheboshe.com>' . "\r\n";
    $retval = mail($to,$subject,$message,$headers);  
    */
    
    require 'sendmail_enquiries.php';
    $retval = sendmail($to, $subject, $message);
    $to = "atiyafahmida42@gmail.com";
    $retval = sendmail($to, $subject, $message);

    if( $retval == true ) {
      echo "Sent";
    }else {
      echo "No";
    }





  }




}



?>
