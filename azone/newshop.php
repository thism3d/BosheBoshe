<!DOCTYPE html>
<html>
      <head>

          <title>Shop Summary - BosheBoshe | Bosheboshe Admin Panel</title>

          <meta name="robots" content="noindex">
          <meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, initial-scale=1.0, user-scalable=no, shrink-to-fit=no" />


          <!-- CSS For this Page -->
          <link rel="icon" href="../icon.png">
  	      <link rel="stylesheet" href="main_admin.css">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



      </head>
      <body>


<?php
        include 'admin_header.php';
?>




  <div id="admin_panel_home">
    <h2>Registered Shop.</h2>

    <img src="undraw_small_town_rxs3.svg" alt="Welcome to Admin Panel">


  </div>

  <div id="new_shop_registration">


    <div id="exact_form_div">

      <h2>Register New Shop</h2>
      <form method="post" action="uploadnewshop.php">
        <label for="shopnameid">Shop Name<span class="requiredasterisk"> *</span></label>
        <input id="shopnameid" name="shopname" type="text" placeholder="Enter Shop Name" required>

        <label for="ownernameid">Shop Owner Name<span class="requiredasterisk"> *</span></label>
        <input id="ownernameid" name="ownername" type="text" placeholder="Enter Shop Owner Name" required>

        <label for="shopcategoryid">Shop Category<span class="requiredasterisk"> *</span></label>
        <input id="shopcategoryid" name="shopcategory" type="text" placeholder="Enter Shop Category" required>

        <label for="shopdetailsid">Short Details of Shop</label>
        <input id="shopdetailsid" name="shopdetails" type="text" placeholder="Enter Short Details">


        <label for="ownerphoneid">Owner Phone<span class="requiredasterisk"> *</span></label>
        <input id="ownerphoneid" name="ownerphone" type="text" placeholder="Enter Owner Phone" required>

        <label for="owneremailid">Owner Email</label>
        <input id="owneremailid" name="owneremail" type="text" placeholder="Enter Owner Email">

        <label for="shopaddressid">Shop Address<span class="requiredasterisk"> *</span></label>
        <input id="shopaddressid" name="shopaddress" type="text" placeholder="Enter Shop Address" required>

        <label for="registrationnumberid">Shop Registration Number</label>
        <input id="registrationnumberid" name="registrationnumber" type="text" placeholder="Registration Number">

        <input type="submit" value="Add Shop" name="submit">
      </form>
    </div>

  </div>


  <div id="registered_shop_list">

    <h2>Registered Shop List</h2>



      <?php


        require 'connectserver.php';

        $sql = 'SELECT shopid, shopname, shopownername, shopcategory, shopdetails, ownerphone, owneremail, shopaddress, registratonnumber, totalproducts FROM registershop;';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo '
            <a>
               <div class="single_shop_container">
                 <h3>'. $row["shopname"] .' ('. $row["shopid"] .')</h3>
                 <p><i class="fa fa-tag"></i> '. $row["shopcategory"] .'</p>
                 <p><i class="fa fa-map-marker"></i> '. $row["shopaddress"] .'</p>
                 <hr>
                 <p><i class="fa fa-address-card"></i> '. $row["shopownername"] .'</p>
                 <p><i class="fa fa-phone"></i> '. $row["ownerphone"] .'</p>
                 <p><i class="fa fa-archive"></i> Total Products: ( <b>'. $row["totalproducts"] .'</b> )</p>

               </div>
             </a>';
          }
        }

       ?>



  </div>



















































<?php
        include 'admin_footer.php';
?>


      </body>
<html>
