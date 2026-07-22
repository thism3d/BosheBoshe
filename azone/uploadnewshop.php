<?php


if(isset($_POST["submit"])) {

    require 'connectserver.php';

    $stmt = $conn->prepare('INSERT INTO registershop(shopname, shopownername, shopcategory, shopdetails, ownerphone, owneremail, shopaddress, registratonnumber) VALUES(?, ?, ?, ?, ?, ?, ?, ?);');
    $stmt->bind_param("ssssssss", $shopnamesql, $shopownernamesql, $shopcategorysql, $shopdetailssql, $ownerphonesql, $owneremailsql, $shopaddresssql, $registratonnumbersql);


    $shopnamesql = $_POST["shopname"];
    $shopownernamesql = $_POST["ownername"];
    $shopcategorysql = $_POST["shopcategory"];
    $shopdetailssql = $_POST["shopdetails"];
    $ownerphonesql = $_POST["ownerphone"];
    $owneremailsql = $_POST["owneremail"];
    $shopaddresssql = $_POST["shopaddress"];
    $registratonnumbersql = $_POST["registrationnumber"];



    $stmt->execute();


    echo "New records created successfully";

    $stmt->close();
    $conn->close();


}

header('Location:  /bosheboshe/azone/newshop.php');
exit();

?>
