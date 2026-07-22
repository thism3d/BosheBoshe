<!DOCTYPE html>
<html lang="en">
      <head>

          <title>All Products - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>

          <?php require_once("headernofollowmeta.php"); ?>


          <style>

          #customers {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
          }

          #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 0px;
          }

          #customers tr:nth-child(even){background-color: #f2f2f2;}

          #customers tr:hover {background-color: #ddd;}

          #customers th {
            padding-top: 0px;
            padding-bottom: 0px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
          }

          </style>



      </head>
      <body>
















                <?php
                  require 'connectserver.php';

                  $sql = "SELECT productscode, productpicture, productname, minprice, maxprice, offeredprice, besafeless, sellername FROM productsforsell;";
                  $result = $conn->query($sql);

                  $image_location = 'productsimage/';

                  //$image_location . $row["filename"]

                  if ($result->num_rows > 0) {

                  echo '<table id="customers">
                    <tr>
                      <th>Code</th>
                      <th>Image</th>
                      <th>Name</th>
                      <th>Price</th>
                      <th>Store</th>
                    </tr>';
                      // output data of each row
                      while($row = $result->fetch_assoc()) {

                        echo '<tr>
                          <td>'. $row["productscode"] .'</td>
                          <td><img style="width: 80px;" src="productsimage/'. $row["productpicture"] .'"></td>
                          <td>'. $row["productname"] .'</td>
                          <td>'. $row["offeredprice"] .'</td>
                          <td>'. $row["sellername"] .'</td>
                        </tr>';


                      }


                    echo '</table>';
                  }

                ?>










              </div>

              <br>
              <br>
              <br>
              <br>
              <!-- <button id="view_more_btn"><i class="fa fa-plus-square"></i> See More Products</button> -->
          </div>                              <!-- Products Section Ends Here -->


</div>
















<!-- <script src="addtocartjavascript.js"></script> -->







      </body>
<html>
