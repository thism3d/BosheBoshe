<?php
$realStringCookie = "";
$arraytoholdcartcookie = array();
$sortedarraytoholdcartcookie = array();
$arraytoholdthecookieindex = array();


if(isset($_COOKIE[$cookiecart])) {    // Need Total Code & Qty

  $realStringCookie = $_COOKIE[$cookiecart];

  $replacedString = str_replace("p","",$_COOKIE[$cookiecart]);
  // echo  $replacedString . "<br><br>";





  $token = strtok($replacedString, ",");

  $anotherStrToHoldTheToken = $token;

  while ($token !== false) {
    array_push($arraytoholdcartcookie, $token);
    $token = strtok(",");
  }

  $arraytoholdcartcookieLength = count($arraytoholdcartcookie);
  $arraytoholdcartcookiecounter = 0;

  for(; $arraytoholdcartcookiecounter<$arraytoholdcartcookieLength; $arraytoholdcartcookiecounter++){
    $stringforcookiesplit = $arraytoholdcartcookie[$arraytoholdcartcookiecounter];
    $token = strtok($stringforcookiesplit, "~");

    array_push($arraytoholdthecookieindex, $token);
  }
  // print_r($arraytoholdcartcookie);

  // echo "<br>";

  sort($arraytoholdthecookieindex);
  // print_r($arraytoholdthecookieindex);



  $lengthofarraytoholdthecookieindex = count($arraytoholdthecookieindex);
  $arraytoholdthecookieindexcounter = 0;
  for(;$arraytoholdthecookieindexcounter<$lengthofarraytoholdthecookieindex; $arraytoholdthecookieindexcounter++){
    $strindexfinder =  "p" . $arraytoholdthecookieindex[$arraytoholdthecookieindexcounter];
    // echo $strindexfinder . "<br>";
    $firstCharPosition = strpos($realStringCookie,$strindexfinder);
    $lastCharPostition = strpos($realStringCookie, ",",  $firstCharPosition);
    $retriveTheString = substr($realStringCookie,$firstCharPosition, $lastCharPostition-$firstCharPosition);
    // echo $firstCharPosition . " " . $lastCharPostition . "<br>";

    array_push($sortedarraytoholdcartcookie, $retriveTheString);
    // echo $retriveTheString . "<br>";
  }
  // echo $realStringCookie;



  print_r($arraytoholdcartcookie);
  echo "<br>";
  print_r($arraytoholdthecookieindex);
  echo "<br>";
  print_r($sortedarraytoholdcartcookie);


}

?>
