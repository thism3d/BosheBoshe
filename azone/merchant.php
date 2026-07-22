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


<div id="merchant_panel_container">

  <br>
  <br>

  <h2 id="new_request_header"><i class="fa fa-flash"></i> New Requested Merchant</h2>

  <div id="merchant_request_container">

    <?php


      require 'connectserver.php';

      $sql = 'SELECT shopid, shopname, shopownername, ownerphone FROM registershop WHERE approval = "N";';
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo '
          <div class="merchant_request_single clearfix">
            <div class="firstdiv_merchantreq">
              <b>'. $row["shopname"] .'</b>
            </div>
            <div class="seconddiv_merchantreq">
              '. $row["shopownername"] .'
            </div>
            <div class="thirddiv_merchantreq">
              <a class="phone_number_merchant" href="tel:'.  $row["ownerphone"] .'">'.  $row["ownerphone"] .'</a>
            </div>
            <div class="fourthdiv_merchantreq">
              <a class="merchant_view_acnhor" href="not_approved_merchant?merchantid='. $row["shopid"] .'">View</a>
            </div>
          </div>';
        }
      }else{
        echo '<p>No New Request!</p>';
      }

     ?>




  </div>

  <br>
  <br>

  <h2 id="approved_merchant_header"><i class="fa fa-check-circle" aria-hidden="true"></i> Approved Merchant</h2>

  <div style="overflow-x: auto;">
    <table id="merchants_table">
      <tr>
        <th>Merchant Name</th>
        <th>Live Id</th>
        <th>Owner</th>
        <th>Phone</th>
        <th>Address</th>
      </tr>



      <?php


        $sql = 'SELECT shopid, shopname, shopownername, ownerphone, shopaddress, shortname FROM registershop WHERE approval = "Y";';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo '
            <tr>
              <td>'. $row["shopname"] .'</td>
              <td><a href="#">'. $row["shortname"] .'</a></td>
              <td>'. $row["shopownername"] .'</td>
              <td>'. $row["ownerphone"] .'</td>
              <td>'. $row["shopaddress"] .'</td>
            </tr>';
          }
        }

       ?>








    </table>
    <br>
    <br>
  </div>



</div>

























<?php
        include 'admin_footer.php';
?>


      </body>
<html>
