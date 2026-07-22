<!DOCTYPE html>
<html>
      <head>

          <title>Admin Login - BosheBoshe</title>
          <meta name="robots" content="noindex">
          <meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, initial-scale=1.0, user-scalable=no, shrink-to-fit=no" />


          <!-- CSS For this Page -->
          <link rel="icon" href="../icon.png">
          <link rel="stylesheet" href="login_css.css">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



      </head>
      <body>

        <?php
            require 'cookiesvariablesadmin.php';



            // Encryption Method Starts Here
            // Store the cipher method
            $ciphering = "AES-128-CTR";
            // Use OpenSSl Encryption method
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;
            // Non-NULL Initialization Vector for encryption
            $encryption_iv = '8675992784945782';
            // Store the encryption key
            $encryption_key = "MayeshaMeemMuzahidIslam";
            // Non-NULL Initialization Vector for decryption
            $decryption_iv = '8675992784945782';
            // Store the decryption key
            $decryption_key = "MayeshaMeemMuzahidIslam";
            // Encryption method ends here






            if(isset($_COOKIE[$cookieadminname]) && isset($_COOKIE[$cookieadminstatus]) && isset($_COOKIE[$cookieadminusername]) && isset($_COOKIE[$cookieadminpassword])) {
              header('Location: ' . $serverhost .'/index.php');
              exit;
            }
            // } else {

                $formUsernameError = $formPasswordError = "";
                $formUsername = $formPassword = "";
                $errorcount = 0;


                function test_input($data) {
                  $data = trim($data);
                  $data = stripslashes($data);
                  $data = htmlspecialchars($data);
                  return $data;
                }


                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                  if (empty($_POST["username"])) {
                      $formUsernameError = "Username is required";
                      $errorcount = $errorcount + 1;
                  }else{
                    $formUsername = test_input($_POST["username"]);
                    // check if name only contains letters and whitespace
                    if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,20}$/',$formUsername)) {
                      $formUsernameError = "Place a valid username";
                      $errorcount = $errorcount + 1;
                    }
                  }



                  if (empty($_POST["password"])) {
                      $formPasswordError = "Password is required";
                      $errorcount = $errorcount + 1;
                  }else{
                    $formPassword = test_input($_POST["password"]);
                    // check if name only contains letters and whitespace

                    if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,20}$/',$formPassword)) {
                      $formPasswordError = "Place a valid password";
                      $errorcount = $errorcount + 1;
                    }
                  }

                    if($errorcount <= 0){
//                        echo $formUsername . "<br>" . $formPassword . "<br>";
                        // require 'connectserver.php';
                        //Server Connection Goes Here//
                        require_once __DIR__ . '/../connectserver.php';


                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        //Server Connection Ends Here


                        $sql = 'SELECT fullname username, password, status FROM administertable WHERE username = "'. $formUsername .'" AND password = "'. $formPassword .'";';
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            // output data of each row
                            while($row = $result->fetch_assoc()) {
//                                echo "id: " . $row["username"]. " - password: " . $row["password"]. "<br>" . $row["name"]. "<br>" .  $row["status"];



                                // Use openssl_encrypt() function to encrypt the data
                                $adminnamecookie = openssl_encrypt($row["fullname"], $ciphering,
                                            $encryption_key, $options, $encryption_iv);
                                $adminusernamecookie = openssl_encrypt($row["username"], $ciphering,
                                            $encryption_key, $options, $encryption_iv);
                                $adminstatuscookie = openssl_encrypt($row["status"], $ciphering,
                                            $encryption_key, $options, $encryption_iv);
                                $adminpasswordcookie = openssl_encrypt($row["password"], $ciphering,
                                            $encryption_key, $options, $encryption_iv);

//                                echo $usernamecookie . "<br>";
//                                echo $passwordcookie . "<br>";
//                                echo $nameofusercookie . "<br>";
//                                echo $statuscookie . "<br>";


                                setcookie($cookieadminname, $adminusernamecookie, time() + (86400 * 1), "/"); // 86400 = 1 day
                                setcookie($cookieadminstatus, $adminstatuscookie, time() + (86400 * 1), "/"); // 86400 = 1 day
                                setcookie($cookieadminusername, $adminusernamecookie, time() + (86400 * 1), "/"); // 86400 = 1 day
                                setcookie($cookieadminpassword, $adminpasswordcookie, time() + (86400 * 1), "/"); // 86400 = 1 day


                                header('Location: ' . $serverhost .'/index.php');
                                exit;


                            }


                        } else {
                            echo '<br><h3 style="color: red; text-align: center;">User Not Found</h3>';
                        }


                    }else{
                        echo '<h3 style="color: red; text-align: center;">' . $formUsernameError . "<br>" . $formPasswordError . "</h3>";
                    }



                }

//                CREATE TABLE usertable(serial INTEGER PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255), username VARCHAR(255), password VARCHAR(255), status VARCHAR(255));
//
//                INSERT INTO usertable(name, username, password, status) VALUES("Khairun Nesa", "khairun999", "#kn23", "admin");




                echo '<div id="logindiv">
                    <img id="mylogo" src="icon.png">

                    <form action="'. htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post" autocomplete="off">
                        <input type="text" name="username" placeholder="Username" maxlength="25" required autofocus pattern=".{8,20}" title="8 to 20 characters">

                        <input id="password" type="password" name="password" placeholder="Password" pattern=".{8,20}" required title="8 to 20 characters" required >

                        <input type="submit">
                    </form>
                </div>';
        ?>







    </body>
</html>
