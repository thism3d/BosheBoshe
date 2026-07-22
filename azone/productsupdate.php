<!DOCTYPE html>
<html>
      <head>

          <title>Update Products BosheBoshe</title>

          <meta name="robots" content="noindex">
          <meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, initial-scale=1.0, user-scalable=no, shrink-to-fit=no" />


          <!-- CSS For this Page -->
          <link rel="icon" href="../icon.png">
  	      <link rel="stylesheet" href="main_admin.css">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



      </head>
      <body>


        <style>



              #notification_div{
                z-index: 100;
                position: fixed;
                top: 0;
                width: 100%;
                padding: 14px 0px;
                background-color: black;
                color: white;
                display: none;
                top: 40%;
              }

              #notification_div h3{
                padding: 4px;
              }

              #notification_div button{
                border: none;
                padding: 4px 12px;
                cursor: pointer;
                margin-bottom: 5px;
                background-color: #4CAF50;
                color: white;
                font-size: 14px;
                margin-right: 10px;
              }

              #notification_div button:last-child{
                background-color: rgba(183, 50, 57, 1);
              }

              #notification_div button:hover{
                background-color: green;
              }

              #notification_div button:last-child:hover{
                background-color: red;
              }






              .single_product_update{
                text-align: center;
                width: 100%;
                border-bottom: 1px solid gray;
              }

              .single_product_update div{
                line-height: 60px;
                float: left;
              }

              .single_product_update input{
                width: 100%;
                box-sizing: border-box;
                padding: 6px;
              }

              .serialdelete{
                width: 7%;
              }

              .serialupdate{
                width: 5%;
              }

              .serialpicture{
                width: 10%;
              }

              .serialpicture img{
                width: 100%;
                max-width: 60px;
              }

              .serialname{
                width: 30%;
                text-align: left;
                padding-left: 8px;
              }

              .serialmin{
                width: 10%;
              }

              .serialmax{
                width: 10%;
              }

              .serialofferprice{
                width: 10%;
              }

              .serialbesafeless{
                width: 10%;
              }

              .main_serial_code{
                font-size: 14px;
              }


              .serialdelete button{
                width: 80%;
                box-sizing: border-box;
                height: 24px;
                font-size: 16px;
                background-color: firebrick;
                color: white;
                outline: none;
                cursor: pointer;
              }

              .serialupdateprice{
                width: 8%;
              }

              .serialupdateprice button{
                width: 80%;
                box-sizing: border-box;
                height: 24px;
                font-size: 16px;
                background-color: #3d8a33;
                color: white;
                outline: none;
                cursor: pointer;
              }

              #update_product_h2{
                text-align: center;
                padding: 10px;
              }

              @media only screen and (max-width: 860px) {
                #all_updates_deletion_container{
                  /* display: none; */
                    overflow-x: auto;
                }

                .single_product_update div {
                    padding: 4px 0px;
                    line-height: normal;
                    float: left;
                }

                .serialname{
                  font-size: 12px;
                }
              }










        </style>







<?php
        include 'admin_header.php';

        ?>



            <div id="notification_div">
              <center>
                <h3>Confirmation Div</h3>
                <button onclick="hideNotification()">Confirm</button>
              </center>
            </div>



            <div id="all_updates_deletion_container">

              <h2 id="update_product_h2">Update Products</h2>

              <div class="single_product_update clearfix main_serial_code">
                <div class="serialdelete">Delete</div>
                <div class="serialupdate">Serial</div>
                <div class="serialpicture">Picture</div>
                <div class="serialname">Name</div>
                <div class="serialmin">Min Price</div>
                <div class="serialmax">Max Price</div>
                <div class="serialofferprice">Offer Price</div>
                <div class="serialbesafeless">Besafe Less</div>
                <div class="serialupdateprice">Update</div>
              </div>
              <hr>




<?php

        require 'connectserver.php';



        $sql = 'SELECT productscode, productpicture, productname, minprice, maxprice, offeredprice, besafeless FROM productsforsell;';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {

            echo '  <div id="div_of_product'. $row["productscode"] .'" class="single_product_update clearfix">
                <div class="serialdelete"><button id="deleteid'. $row["productscode"]  .'" onclick="deleteclicked(this.id, '. $row["productscode"]  .')"><i class="fa fa-close"></i></button></div>
                <div class="serialupdate">'.  $row["productscode"] .'</div>
                <div class="serialpicture"><img class="lazy" src="backgroundopacity.png" data-src="../productsimage/'. $row["productpicture"]  .'"></div>
                <div class="serialname" id="nameOfProduct'.  $row["productscode"] .'" contenteditable="true">'. $row["productname"] .'</div>
                <div class="serialmin"><input id="mininput'.  $row["productscode"] .'" type="number" placeholder="Min" value="'. intval($row["minprice"]) .'"></div>
                <div class="serialmax"><input id="maxinput'.  $row["productscode"] .'" type="number" placeholder="Max" value="'.  intval($row["maxprice"]) .'"></div>
                <div class="serialofferprice"><input id="offeredpriceinput'.  $row["productscode"] .'" type="number" placeholder="Offer Price" value="'.  intval($row["offeredprice"]) .'"></div>
                <div class="serialbesafeless"><input id="besafeinput'.  $row["productscode"] .'" type="number" placeholder="Besafe" value="'.  intval($row["besafeless"]) .'"></div>
                <div class="serialupdateprice"><button onclick="updateclicked(this.id, '.  $row["productscode"] .')" id="besafeless'.  $row["productscode"] .'"><i class="fa fa-check"></i></button></div>
              </div>';


          }
        }

 ?>



              <!-- <div id="div_of_product24" class="single_product_update clearfix">
                <div class="serialdelete"><button id="deleteid24" onclick="deleteclicked(this.id, 24)"><i class="fa fa-close"></i></button></div>
                <div class="serialupdate">10</div>
                <div class="serialpicture"><img src="../productsimage/1587238107.IMG_1950.jpg"></div>
                <div class="serialname" id="nameOfProduct24">Bottle Guard</div>
                <div class="serialmin"><input id="mininput24" type="number" placeholder="Min" value="20"></div>
                <div class="serialmax"><input id="maxinput24" type="number" placeholder="Max" value="30"></div>
                <div class="serialofferprice"><input id="offeredpriceinput24" type="number" placeholder="Offer Price" value="24"></div>
                <div class="serialbesafeless"><input id="besafeinput24" type="number" placeholder="Besafe" value="4"></div>
                <div class="serialupdateprice"><button onclick="updateclicked(this.id, 24)" id="updateid24"><i class="fa fa-check"></i></button></div>
              </div> -->

            </div>




        <?php


?>







































<script>



var notification_div = document.getElementById("notification_div");

var notification_div_details;

function hideNotification() {
  notification_div.style.display = "none";
}



function updateclicked(updatebtnid, productserial){
  var updatebtn = document.getElementById(updatebtnid);


  updatebtn.innerHTML = '<i class="fa fa-circle-o-notch fa-spin"></i>';

  var selectedMinPrice = document.getElementById("mininput" + productserial).value;
  var selectedMaxPrice = document.getElementById("maxinput" + productserial).value;
  var selectedOfferPrice = document.getElementById("offeredpriceinput" + productserial).value;
  var selectedBeSafeLess = document.getElementById("besafeinput" + productserial).value;
  var selectedProductName = document.getElementById("nameOfProduct" + productserial).innerHTML;


  // console.log(selectedMinPrice + " " + selectedMaxPrice +  " " + selectedOfferPrice + " " + selectedBeSafeLess);

  updatebtn.disabled = true;


  var updating_product_status = '<center>' +
    '<h3>Updating <i class="fa fa-circle-o-notch fa-spin"></i></h3>' +
    '<h3>Please Wait!</h3>' +
  '</center>';
  notification_div.innerHTML = updating_product_status;
  notification_div.style.display = "block";



    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {

        // document.getElementById("demo").innerHTML = this.responseText;



        var requestResponse = this.responseText;
        // console.log(requestResponse);
        if(requestResponse.localeCompare("N")==0){

          var final_updating_product_status = '<center>' +
            '<h3><i class="fa fa-close"></i> Updated Failed</h3>' +
            '<h3>Try Again !</h3>' +
          '</center>';
          notification_div.innerHTML = final_updating_product_status;
          // Try Again
          setTimeout(function(){
            hideNotification();
            updatebtn.disabled = false;
            updatebtn.innerHTML = '<i class="fa fa-check"></i>';
          }, 2000);


        }else if (requestResponse.localeCompare("Y")==0){

          var final_updating_product_status = '<center>' +
            '<h3><i class="fa fa-check"></i> Products Updated Successfully</h3>' +
          '</center>';
          notification_div.innerHTML = final_updating_product_status;



          // Found
          setTimeout(function(){
            hideNotification();
            updatebtn.disabled = false;
            updatebtn.innerHTML = '<i class="fa fa-check"></i>';
          }, 2000);

        }




      }else{
        // var final_updating_product_status = '<center>' +
        //   '<h3><i class="fa fa-close"></i> Products Not Updated</h3>' +
        //   '<h3>Try Again!</h3>' +
        // '</center>';
        // notification_div.innerHTML = final_updating_product_status;
        // // Try Again
        // setTimeout(function(){
        //   hideNotification();
        // }, 2000);
        //
        //
        // updatebtn.innerHTML = '<i class="fa fa-check"></i>';
        // updatebtn.disabled = false;
      }
    };
    xhttp.open("POST", "updatingproductajax.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("productsid="+ productserial +"&minPrice=" + selectedMinPrice + "&maxPrice=" + selectedMaxPrice + "&offeredPrice=" + selectedOfferPrice + "&besafeLess="+ selectedBeSafeLess + "&productName=" + selectedProductName );

}






function deleteclicked(deleteBtnId, deleteProductserial){
  var nameOfProduct = document.getElementById("nameOfProduct" + deleteProductserial);
  notification_div_details = '<center>' +
    '<h3>Delete Product : '+ nameOfProduct.innerHTML +'</h3>' +
    '<button id="finaldeletion'+ deleteProductserial +'" onclick="deteleProductFinally('+ deleteProductserial +')">Confirm</button>' + '<button id="finalcancelbtn'+ deleteProductserial +'" onclick="hideNotification()">Cancel</button>' +
  '</center>';

  notification_div.innerHTML = notification_div_details;
  notification_div.style.display = "block";
}
























// Deletion of Products Starts Here

function deteleProductFinally(productsserialofdeltion){
  var finaldeletionbutton = document.getElementById("finaldeletion"+productsserialofdeltion);
  finaldeletionbutton.innerHTML = 'Deleting <i class="fa fa-circle-o-notch fa-spin"></i>';
  finaldeletionbutton.disabled = true;

  var finalcancelbtn = document.getElementById("finalcancelbtn"+ productsserialofdeltion);
  finalcancelbtn.disabled = true;



  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      // document.getElementById("demo").innerHTML = this.responseText;


      //
      //
      var requestResponse = this.responseText;
      // console.log(requestResponse);
      if(requestResponse.localeCompare("N")==0){

        var deletion_of_product_text = '<center>' +
          '<h3><i class="fa fa-close"></i> Products Not Deleted</h3>' +
          '<h3>Try Again!</h3>' +
        '</center>';
        notification_div.innerHTML = deletion_of_product_text;
        // Try Again
        setTimeout(function(){
          hideNotification();
        }, 2000);


      }else if (requestResponse.localeCompare("Y")==0){

        var deletion_of_product_text = '<center>' +
          '<h3><i class="fa fa-check"></i> Products Deleted</h3>' +
        '</center>';
        notification_div.innerHTML = deletion_of_product_text;

        var found_product_div_to_remove = document.getElementById("div_of_product" + productsserialofdeltion);
        found_product_div_to_remove.remove(this);

        // Found
        setTimeout(function(){
          hideNotification();
        }, 2000);

      }



    }else{
      //
      // var deletion_of_product_text = '<center>' +
      //   '<h3>Products Not Deleted</h3>' +
      //   '<h3>Try Again!</h3>' +
      // '</center>';
      // notification_div.innerHTML = deletion_of_product_text;
      // // Try Again
      // setTimeout(function(){
      //   hideNotification();
      // }, 2000);

    }
  };
  xhttp.open("POST", "deletingproductajax.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("productsid="+ productsserialofdeltion);




}





</script>


















<?php
        include 'admin_footer.php';
?>


      </body>
<html>
