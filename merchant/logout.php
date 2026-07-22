
<?php

require 'cookiesvariablesmerchant.php';
setcookie($cookiemerchantname, "", time() - 3600, "/"); // 86400 = 1 day
setcookie($cookiemerchantowner, "", time() - 3600, "/"); // 86400 = 1 day
setcookie($cookiemerchantphone, "", time() - 3600, "/"); // 86400 = 1 day
setcookie($cookiemerchantshortname, "", time() - 3600, "/"); // 86400 = 1 day
setcookie($cookiemerchantpassword, "", time() - 3600, "/"); // 86400 = 1 day

header('Location: ' . $serverhost .'/index.php');
exit;

 ?>
