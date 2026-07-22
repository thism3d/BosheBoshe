
<?php

require 'cookiesvariablesadmin.php';

setcookie($cookieadminname, "", time() - 3600, "/");
setcookie($cookieadminstatus, "", time() - 3600, "/");
setcookie($cookieadminusername, "", time() - 3600, "/");
setcookie($cookieadminpassword, "", time() - 3600, "/");

header('Location: ' . $serverhost .'/login.php');
exit;

 ?>
