<?php


if(isset($_POST["merchant_liveid"])) {


    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }


    $merchant_expected_id = $_POST["merchant_expected_id"];
    $shopname = $_POST["merchant_name"];
    $shopowner = $_POST["person_name"];
    $shopcategory = $_POST["merchant_category"];
    $ownerphone = $_POST["person_phone"];
    $shortname = $_POST["merchant_liveid"];
    $shopdetails = $_POST["merchant_details"];
    $owneremail = $_POST["person_email"];
    $shopaddress = $_POST["merchant_address"];
    $registrationnumber = $_POST["merchant_reg_num"];


    require 'connectserver.php';

    // echo $shopname . "<br>" . $shopowner . "<br>" . $shopcategory . "<br>" . $ownerphone . "<br>" . $shortname . "<br>" . $shopdetails . "<br>" . $owneremail . "<br>" . $shopaddress . "<br>" . $registrationnumber;

    $sql = 'UPDATE registershop SET shopname = "'. $shopname .'", shopownername = "'. $shopowner .'", shopcategory = "'. $shopcategory .'", ownerphone = "'. $ownerphone .'", shortname = "'. $shortname .'", shopdetails = "'. $shopdetails .'", owneremail = "'. $owneremail .'", shopaddress = "'. $shopaddress .'", registratonnumber = "'. $registrationnumber .'", approval = "Y" WHERE shopid = '. $merchant_expected_id .'; ';

    if ($conn->query($sql) === TRUE) {
        echo "Y";
    } else {
        echo "N";
    }


}
//
header('Location:  /bosheboshe/azone/merchant.php');
exit();

?>
