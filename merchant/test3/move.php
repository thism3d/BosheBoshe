<?php



$fromMovePath = 'uploads/';
$toMovePath = 'del/';


$existedPhoto  = 'BosheBosheSS.png';

$intiateMovePhoto = $fromMovePath . $existedPhoto;

if(file_exists($intiateMovePhoto)){


  // Final File Name To Move
  $finalMovePhoto = $toMovePath . $existedPhoto;


  rename($intiateMovePhoto, $finalMovePhoto);
  echo "Successful";
}else{
  echo "No File In Directory";
}

?>
