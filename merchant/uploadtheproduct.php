<?php

require 'upload_merchantheader.php';


function compressImage($source_url, $destination_url, $quality) {
    $info = getimagesize($source_url);

    if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url);
    else if ($info['mime'] == 'image/jpg') $image = imagecreatefromjpeg($source_url);
    elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url);
    elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url);

    //save file
    imagejpeg($image, $destination_url, $quality);

    //return destination file
    return $destination_url;
}




$target_dir = "../productsnotupload/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Extra Fields
$productname = "";
$productnamebangla = "";
$metaforproducts = "";
$productsdetails = "";

$categorybakery = "";
$categoryfood = "";
$categoryvegetable = "";
$categoryfruit = "";
$categorystationary = "";
$categorygroceries = "";
$categorymasala = "";
$categorysoapdetergent = "";
$categorymedicine = "";
$categoryramadan = "";
$categorymeatifish = "";
$categorycosmetics = "";
$categorygadgets = "";

$min_price = "";
$max_price = "";
$offered_price = "";
$besafe_less = "";
$sellername = "";
$isavailable = "";
$uploadedby = "";
$availablein = "all";


$filenameforserver = round(microtime(true)) . '.' .basename($_FILES["fileToUpload"]["name"]);
$target_file_name = $target_dir . $filenameforserver;
//echo $target_file_name;
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    $productname = $_POST["productname"];
    // $productnamebangla = $_POST["productnamebangla"];
    $metaforproducts = $_POST["metaforproducts"];
    $uploadedby = $_POST["uploadedby"];

    if(isset($_POST["productsdetails"])){
      $productsdetails = $_POST["productsdetails"];
    }

    $min_price = $_POST["min_price"];
    $max_price = $_POST["max_price"];
    $offered_price = $_POST["offered_price"];
    $besafe_less = $_POST["besafe_less"];
    $sellername = $_POST["sellername"];


    // if(strcmp($_POST["availability"], "y")==0){
    //
    // }else{
    // }

    $isavailable = "";

    if(isset($_POST["availability"])){
      $isavailable = "y";
    }else{
      $isavailable = "n";
    }

    echo "<br>". $isavailable . " " . $uploadedby . "<br>";


    // Make Single Category After Joining All

    $finalcategoryforform = "";

    function isCategoryChecked($valuetocheck) {
      global $finalcategoryforform;

      if (isset($_POST[$valuetocheck])) {
        if(strcmp($_POST[$valuetocheck], "")!=0){
          	$finalcategoryforform = $finalcategoryforform . $_POST[$valuetocheck] . ", ";
        }
      }

    }






    //
    // $categorybakery = $_POST["categorybakery"];
    // $categoryfood = $_POST["categoryfood"];
    // $categoryvegetable = $_POST["categoryvegetable"];
    // $categoryfruit = $_POST["categoryfruit"];
    // $categorystationary = $_POST["categorystationary"];
    // $categorygroceries = $_POST["categorygroceries"];




    isCategoryChecked("categorybakery");
    isCategoryChecked("categoryfood");
    isCategoryChecked("categoryvegetable");
    isCategoryChecked("categoryfruit");
    isCategoryChecked("categorystationary");
    isCategoryChecked("categorygroceries");
    isCategoryChecked("categorymasala");
    isCategoryChecked("categorysoapdetergent");
    isCategoryChecked("categorymedicine");
    isCategoryChecked("categoryramadan");
    isCategoryChecked("categorymeatifish");
    isCategoryChecked("categorycosmetics");
    isCategoryChecked("categorygadgets");






}






  function reArrayFiles($file)
  {
      $file_ary = array();
      $file_count = count($file['name']);
      $file_key = array_keys($file);

      for($i=0;$i<$file_count;$i++)
      {
          foreach($file_key as $val)
          {
              $file_ary[$i][$val] = $file[$val][$i];
          }
      }
      return $file_ary;
  }









// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {   // 5000 KB LIMIT
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {

  $img = $_FILES['img'];



  $initializedExtraPhotosCount = 0;
  $finalExtraPhotosCount = 0;
  $extraProductsPhotos = "";


  if(!empty($img))
  {

      $img_desc = reArrayFiles($img);
      $initializedExtraPhotosCount = count($img_desc);
    //   print_r($img_desc);


      foreach($img_desc as $val)
      {
          if(strcmp(trim($val["name"]) , "")!=0){
            $result = preg_replace("/[^a-zA-Z0-9.]+/", "", $val['name']);
            $result = round(microtime(true)) . $result;
            // $result = $result;
            $finalExtraPhotosCount = $finalExtraPhotosCount + 1;


            // $newname = date('YmdHis',time()).mt_rand().'.jpg';
            $extraProductsPhotos = $extraProductsPhotos .  $result . "~";
            echo  "<br>Address: " . $val['tmp_name'] . "<br>";
            // move_uploaded_file($val['tmp_name'],'../extradetailsnotupload/'.$result);

            // Compress Algorith
            if($destignationFIle = compressImage($val['tmp_name'], '../extradetailsnotupload/'.$result, 20)){
              echo $destignationFIle;
            }else{
              echo "Failed";
            }

          }
      }




  }



    // if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file_name)) {
    if ($destignationFIle = compressImage($_FILES["fileToUpload"]["tmp_name"], $target_file_name, 20)) {

        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

    if($initializedExtraPhotosCount == $finalExtraPhotosCount){
      echo $extraProductsPhotos . "<br>";
      echo $initializedExtraPhotosCount . " " . $finalExtraPhotosCount . "<br>";
      echo "Upload Successful";
    }


        require 'connectadminserver.php';

        $stmt = $conn->prepare("INSERT INTO productsforsellreview(productpicture, productname, productbanglaname, productmetadata, productshortdetails, productcategories, minprice, maxprice, offeredprice, besafeless, sellername, extraimages, uploadedby, availability, availablein) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssiiiisssss", $filenameforserver, $productname, $productnamebangla, $metaforproducts, $productsdetails, $finalcategoryforform, $min_price, $max_price, $offered_price, $besafe_less, $sellername, $extraProductsPhotos, $uploadedby, $isavailable, $availablein);


        $stmt->execute();
        //

        echo "New records created successfully";

        $stmt->close();
        $conn->close();


    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}


// header('Location: ' . $serverhost .'/uploadproducts.php');

//
// require 'cookiesvariablesmerchant.php';
// exit();


?>
