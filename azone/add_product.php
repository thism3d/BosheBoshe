<!DOCTYPE html>
<html lang="en">
<head>
    <title>Products List</title>

    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, initial-scale=1.0, user-scalable=no, shrink-to-fit=no" />

    <link rel="shortcut icon" type="image/x-icon" href="nbss_icon.png" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        #container{
            margin-top: 20px;
            border: 1px solid black;
            width: 100%;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        #output{
            padding: 10px;
            max-height: 300px;
            max-width: 300px;
        }

        #addimage{
              background-color: #555555;
              color: white;
              border: 2px solid #555555;
              text-align: center;
              text-decoration: none;
              display: inline-block;
              font-size: 16px;
              margin: 4px 2px;
              -webkit-transition-duration: 0.4s; /* Safari */
              transition-duration: 0.4s;
              cursor: pointer;
        }

        #addimage:hover{
          background-color: white;
          color: black;
        }

        input[type=text], input[type=password]{
            display: inline;
        }

        input[type=text]{
            font-size: 14px;
            padding: 8px;
            height: 20px;
            text-align: left;
            border: 3px solid #ccc;
            -webkit-transition: 0.5s;
            width: 60%;
            transition: 0.5s;
            outline: none;
            margin-bottom: 7px;
        }

        p{
            margin: 0px;
        }
        p:last-child{
            padding: 10px 20px;
        }

        input[type=text]:focus{
          border: 3px solid #555;
        }

        .button1 {
  background-color: white;
  color: black;
  border: 2px solid #4CAF50;
  padding: 16px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  -webkit-transition-duration: 0.4s; /* Safari */
  transition-duration: 0.4s;
  cursor: pointer;
            margin: 0px 0px 20px 0px;
            width: 30%;
}




.button1:hover {
  background-color: #4CAF50;
  color: white;
}

        #add_img_btn label{
            background-color: #555555;
              color: white;
              border: 2px solid #555555;
              text-align: center;
              text-decoration: none;
              display: inline-block;
              font-size: 16px;
            padding: 10px 20px 10px 20px;
              -webkit-transition-duration: 0.4s; /* Safari */
              transition-duration: 0.4s;
              cursor: pointer;
        }

        #add_img_btn label:hover{
            background-color: white;
          color: black;
        }


    </style>


</head>

<body>

    <form action="upload.php" method="post" enctype="multipart/form-data">


    <div id="container">
        <center>
        <img id="output" src="add_product.jpg">
        </center>
        <center>

                <p>
                    <input type="file"  accept="image/*" name="fileToUpload" id="file"  onchange="loadFile(event)" style="display: none;"></p>

                <p id="add_img_btn"><label for="file" style="cursor: pointer;">Upload Image</label></p>


        </center>
        <br>
        <center>
        <input type="text" name="productname" placeholder="Product Name"><br>
        <input type="text" name="real_price" placeholder="Real Price"><br>
        <input type="text" name="online_price" placeholder="Online Price"><br>
        <input type="text" name="selling_price" placeholder="Selling Price">
            </center>
        <br>

        <center>
            <input class="button1" type="submit" value="Save" name="submit">
        </center>


    </div>



<script>
var loadFile = function(event) {
	var image = document.getElementById('output');
	image.src = URL.createObjectURL(event.target.files[0]);
};
</script>

        </form>




</body>

</html>
