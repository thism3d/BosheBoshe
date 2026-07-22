<!DOCTYPE html>
<html>
      <head>

          <meta name="robots" content="noindex">
          <meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, initial-scale=1.0, user-scalable=no, shrink-to-fit=no" />

          <title>Merchant Dashboard BosheBoshe</title>


          <!-- CSS For this Page -->
          <link rel="icon" href="../icon.png">
  	      <link rel="stylesheet" href="main_merchant.css">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



      </head>
      <body>


<?php
        include 'merchant_header.php';



        require 'cookiesvariablesmerchant.php';


        require 'connectadminserver.php';



        // Decryption System Starts Here

        // Store the cipher method
        $ciphering = "AES-128-CTR";

        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        // Non-NULL Initialization Vector for decryption
        $decryption_iv = '8675992784945782';

        // Store the decryption key
        $decryption_key = "MayeshaMeemMuzahidIslam";


        // Decryption System Starts Here



        // Intitializing Information
        $decryptedMerchantName = "";
        $decryptedMerchantOwner = "";
        $decryptedMerchantPhone = "";
        $decryptedMerchantShortName = "";




        $decryptedMerchantName = openssl_decrypt ($_COOKIE[$cookiemerchantname], $ciphering,
        $decryption_key, $options, $decryption_iv);

        $decryptedMerchantOwner = openssl_decrypt ($_COOKIE[$cookiemerchantowner], $ciphering,
        $decryption_key, $options, $decryption_iv);

        $decryptedMerchantPhone = openssl_decrypt ($_COOKIE[$cookiemerchantphone], $ciphering,
        $decryption_key, $options, $decryption_iv);

        $decryptedMerchantShortName = openssl_decrypt ($_COOKIE[$cookiemerchantshortname], $ciphering,
        $decryption_key, $options, $decryption_iv);
?>











































  <div id="admin_panel_home">
    <h2>*Upload Your Products</h2>
    <h2>Leave the rest to us</h2>

    <!-- <img src="undraw_statistic_chart_38b6.svg" alt="Upload Image"> -->
  </div>









  <div id="upload_products_container">



    <div id="inside_uploadproduct_container">
      <form onsubmit="return validateExtraPhotos()" id="upload_newproduct_form" action="uploadtheproduct.php" method="post" enctype="multipart/form-data" autocomplete="off">

          <h2>Upload New Product</h2>

          <img id="output" src="add_product.png">

          <p><input type="file"  accept="image/*" name="fileToUpload" id="file"  onchange="loadFile(event)" style="display: none;" required></p>
          <p id="add_img_btn"><label for="file" style="cursor: pointer;">Select A Cover Image</label></p>

          <input type="text" name="productname" placeholder="Product Name" required>
          <input type="text" name="metaforproducts" placeholder="Meta Search (eg. Morich Chili Murich)" required>

          <br>
          <output id="result" ></output>
          <br>
          <p id="multiple_image_chooser_text">Choose Multiple Images <span style="color: red;">(Max 4)</span></p>
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
              <input type="checkbox" id="categorygadgetsid" name="categorygadgets" value="Gadgets">
              <label for="categorygadgetsid"> Gadgets</label>
            </span>
          </p>


          <input type="number" name="min_price" placeholder="Cost Price" required>
          <input type="number" name="max_price" placeholder="Sell Price" required>
          <input type="number" name="offered_price" placeholder="Customer Price" required>
          <input type="number" name="besafe_less" placeholder="Offer Amount Less" required>

          <br>
          <?php
            echo '<input type="text" name="uploadedby" placeholder="Enter Merchant Name" value="'. $decryptedMerchantShortName .'" required hidden="hidden">';
          ?>
          <span id="available_section">
            <label for="availablilityid">Is Available In Market</label>
            <input type="checkbox" id="availablilityid" name="availability" value="y" checked>
          </span>


          <p id="last_paragraph">


            <label for="sellername">Choose Seller:</label>
            <select id="sellername" name="sellername" form="upload_newproduct_form" required>
              <?php
                echo '<option value="'. $decryptedMerchantName .'">'.  $decryptedMerchantName.'</option>';
              ?>
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


















<?php
        include 'merchant_footer.php';
?>


      </body>
<html>
