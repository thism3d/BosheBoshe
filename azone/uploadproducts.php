<!DOCTYPE html>
<html>
      <head>

          <title>All Products - BosheBoshe | Online Shopping Market in Bangladesh | Stay Home and Do Shopping</title>
    
          <meta charset="UTF-8">
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
    <h2>Sensitive Content *</h2>
    <h2>Handle With Care</h2>

    <img src="undraw_statistic_chart_38b6.svg" alt="Upload Image">
  </div>









  <div id="upload_products_container">



    <div id="inside_uploadproduct_container">
      <form onsubmit="return validateExtraPhotos()" id="upload_newproduct_form" action="uploadtheproduct.php" method="post" enctype="multipart/form-data" autocomplete="off">

          <h2>Upload New Product</h2>

          <img id="output" src="add_product.png">

          <p><input type="file"  accept="image/*" name="fileToUpload" id="file"  onchange="loadFile(event)" style="display: none;" required></p>
          <p id="add_img_btn"><label for="file" style="cursor: pointer;">Upload Image</label></p>

          <input type="text" name="productname" placeholder="Product Name" required>
          <input type="text" name="productnamebangla" placeholder="পণ্যের নাম (বাংলায়)">
          <input type="text" name="metaforproducts" placeholder="Meta For Name (eg. Chal Cal Kal Cyal)" required>

          <br>
          <output id="result" ></output>
          <br>
          <input style="border: 2px solid #f44336;" type="file" name="img[]" multiple id="multiplefiles">


          <div id="preview_from_textarea">
            Description Preview
            <br>
            <br>
          </div>
          <textarea id="textarea_description_input" onkeyup="text_changed_of_description()" type="text" name="productsdetails" placeholder="Product Short Details"></textarea>

          <p id="myformcheckbokcontainer">
            <b style="border-bottom: 0.3px solid gray; padding: 8px 0px;">Select Categories for this Product</b>
            <br><br><br>
            <span>
              <input type="checkbox" id="categorybakeryid" name="categorybakery" value="Bakery">
              <label for="categorybakeryid"> Bakery</label>
            </span>
            <span>
              <input type="checkbox" id="categoryfoodid" name="categoryfood" value="Food">
              <label for="categoryfoodid"> Food</label>
            </span>
            <span>
              <input type="checkbox" id="categoryvegetableid" name="categoryvegetable" value="Vegetable">
              <label for="categoryvegetableid"> Vegetable</label>
            </span>
            <span>
              <input type="checkbox" id="categoryfruitid" name="categoryfruit" value="Fruit">
              <label for="categoryfruitid"> Fruit</label>
            </span>
            <span>
              <input type="checkbox" id="categorystationaryid" name="categorystationary" value="Stationary">
              <label for="categorystationaryid"> Stationary</label>
            </span>
            <span>
              <input type="checkbox" id="categorygroceriesid" name="categorygroceries" value="Groceries">
              <label for="categorygroceriesid"> Groceries</label>
            </span>

            <span>
              <input type="checkbox" id="categorymasalaid" name="categorymasala" value="Masala">
              <label for="categorymasalaid"> Masala</label>
            </span>

            <span>
              <input type="checkbox" id="categorysoapdetergentid" name="categorysoapdetergent" value="Soap And Detergent">
              <label for="categorysoapdetergentid"> Soap and Detergent</label>
            </span>


            <span>
              <input type="checkbox" id="categorymedicineid" name="categorymedicine" value="Medicine">
              <label for="categorymedicineid"> Medicine</label>
            </span>


            <span>
              <input type="checkbox" id="categoryramadanid" name="categoryramadan" value="Ramadan">
              <label for="categoryramadanid"> Ramadan</label>
            </span>


            <span>
              <input type="checkbox" id="categorymeatifishid" name="categorymeatifish" value="MeatAndFish">
              <label for="categorymeatifishid"> Fish And Meat</label>
            </span>


            <span>
              <input type="checkbox" id="categorycosmeticsid" name="categorycosmetics" value="Cosmetics">
              <label for="categorycosmeticsid"> Cosmetics</label>
            </span>


            <span>
              <input type="checkbox" id="categorygadgetsid" name="categorygadgets" value="Gadgets" checked>
              <label for="categorygadgetsid"> Gadgets</label>
            </span>
          </p>


          <input type="number" name="min_price" placeholder="Min Price" required>
          <input type="number" name="max_price" placeholder="Max Price" required>
          <input type="number" name="offered_price" placeholder="Offered Price" required>
          <input type="number" name="besafe_less" placeholder="Less amount for besafe code" required>

          <br>

          <input type="text" name="uploadedby" placeholder="Enter Merchant Name" value="dinajpurmerchant" required hidden="hidden">
          <span id="available_section">
            <label for="availablilityid">Is Available In Market</label>
            <input type="checkbox" id="availablilityid" name="availability" value="y" checked>
          </span>

          <p id="last_paragraph">


            <label for="sellername">Choose Seller:</label>
            <select id="sellername" name="sellername" form="upload_newproduct_form" required>
              <option value="Bosheboshe Private">Bosheboshe Private</option>
              <option value="Sorna Fol Vandar">Sorna Fol Vandar</option>
              <option value="Bhai Bhai Store">Bhai Bhai Store</option>
            </select>

          </p>
          <br>

          <input class="button1" type="submit" value="Upload New Product" name="submit">



          <script>
            // Load Single File
            var loadFile = function(event) {
            	var image = document.getElementById('output');
            	image.src = URL.createObjectURL(event.target.files[0]);
            };




            function validateExtraPhotos() {

              if(document.getElementById("multiplefiles").files.length > 4){
                alert("Maximum 4 Files Allowed");
                return false;
              }else{
                return true;
              }

            }





            // Load Multiple files


            window.onload = function(){
                //Check File API support
                if(window.File && window.FileList && window.FileReader)
                {
                    var filesInput = document.getElementById("multiplefiles");
                    filesInput.addEventListener("change", function(event){
                        var files = event.target.files; //FileList object

            //            console.log(files);
                        var output = document.getElementById("result");

                        var imageViewText = "";
                        for(var i = 0; i< files.length; i++)
                        {
                            var file = files[i];
                            //Only pics
                            if(!file.type.match('image'))
                                continue;
                            var picReader = new FileReader();
                            picReader.addEventListener("load",function(event){
                                var picFile = event.target;
            //                    var div = document.createElement("div");
            //                    div.innerHTML = "<img class='thumbnail' src='" + picFile.result + "'" +
            //                    "title='" + picFile.name + "'/>";
            //                    output.insertBefore(div,null);
                                imageViewText = imageViewText + "<img class='thumbnail' src='" + picFile.result + "'" +
                                "title='" + picFile.name + "'/>";
                                output.innerHTML = imageViewText;

                            });
                            //Read the image
                            picReader.readAsDataURL(file);
                        }
                    });
                }
                else
                {
                    console.log('Your browser does not support File API');
                }
            }












          </script>

      </form>
      </div>


  </div>


  <script>
    var preview_from_textarea = document.getElementById("preview_from_textarea");
    var textarea_description_input = document.getElementById("textarea_description_input");
    function text_changed_of_description() {
      // console.log(textarea_description_input.value);
      preview_from_textarea.innerHTML = textarea_description_input.value;
    }

  </script>
















  <div id="products_main_container" class="clearfix">

    <h2 id="products_list_h2">Products List</h2>




    <?php


      require 'connectserver.php';

      $sql = "SELECT productscode, productpicture, productname, minprice, maxprice, offeredprice, besafeless, sellername FROM productsforsell ORDER BY productscode DESC;";
      $result = $conn->query($sql);

      $image_location = '../productsimage/';

      //$image_location . $row["filename"]

      if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {

            echo '  <a href="../product?p='. $row["productscode"] .'" target="_blank">
                <div class="single_upload_product clearfix">
                  <!-- <img class="upload_product_img" src="'. $image_location . $row["productpicture"] .'" alt="Image of object"> -->
                  <img class="upload_product_img lazy" src="backgroundopacity.png" data-src="'. $image_location . $row["productpicture"] .'">
                  <p class="uploadproductsnamep centertext"><b>'. $row["productname"] .'</b></p>
                  <p class="lefttext clearfix">
                    <span class="price_left_span">Price</span>
                    <span class="price_right_span">'. $row["offeredprice"] .' - '. $row["maxprice"] .'</span>
                  </p>
                  <p class="uploadofferp lefttext"> Offer: besafe ( Less '. $row["besafeless"]  .' )
                  <p class="uploadsellerp lefttext">'. $row["sellername"]  .'</p>
                </div>
              </a>';


          }
      } else {
          echo "0 results";
      }
    ?>


    <!-- <a href="">
      <div class="single_upload_product clearfix">
        <img class="upload_product_img" src="undraw_social_friends_nsbv.svg" alt="Image of object">
        <p class="uploadproductsnamep centertext"><b>Potato Crackers</b></p>
        <p class="lefttext clearfix">
          <span class="price_left_span">Price</span>
          <span class="price_right_span">50 - 75</span>
        </p>
        <p class="uploadofferp lefttext"> Offer: besafe ( Less 7 )
        <p class="uploadsellerp lefttext">National Bahumukhi Seba Songstha</p>
      </div>
    </a>

    <a href="">
      <div class="single_upload_product clearfix">
        <img class="upload_product_img" src="undraw_social_friends_nsbv.svg" alt="Image of object">
        <p class="uploadproductsnamep centertext"><b>Potato Crackers</b></p>
        <p class="lefttext clearfix">
          <span class="price_left_span">Price</span>
          <span class="price_right_span">50 - 75</span>
        </p>
        <p class="uploadofferp lefttext"> Offer: besafe ( Less 7 )
        <p class="uploadsellerp lefttext">National Bahumukhi Seba Songstha</p>
      </div>
    </a> -->

  </div>



















































<?php
        include 'admin_footer.php';
?>


      </body>
<html>
