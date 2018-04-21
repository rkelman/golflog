<?php
include 'include/userkey.php';
//include 'include/userFunctions.php';

//print_r($_POST);

//if no post or get; first time to page

if (!isempty($_POST['password']) && !isempty($_POST['password_conf'])) {
  echo "got here";
  //if passwords are both set/not null load variables
  $pass1 = $_POST['password'];
  $pass2 = $_POST['password_conf'];
  $mailID = $_POST['mail'];
  $keyID = $_POST['key'];
  echo "pass: ".$pass1."<BR>\n";
  echo "mail: ".$mailID."<BR>\n";
/*
  if ($pass1 == $pass2) {
    //if passwords match

    if (!storeResetPassword($pass1, $mailID)) {
      // Oh no! The update query failed.
      echo "<neg_mesg>Sorry, Traininglog is experiencing problems.</neg_mesg><BR>";
    } else {
      //if update worked
      echo "<html>\n";
      echo "<head>\n<link rel=\"stylesheet\" href=\"assets\golflog.css\">\n";
      echo "<meta name=\"\" content=\"width=device-width, initial-scale=1.0\">";
      echo "</head>\n";
      echo "<body>\n";
      echo "<img src=\"assets\GolflogLogo.png\">";
      echo "<div>\n";
      echo "Your Password has been updated<BR>";
      echo "<a href=\"./index.html\">Go to login</a><BR>";
      echo "</div></body>\n";
      echo "</html>";
    }
  } else {
    //if passwords don't match return to reset
    header('Location: reset.php?mail='.$mailID.'&key='.$keyID.'&err=passMismatch');
  }
*/
} else {
  echo "got here";
  //if either password was sent over null return to reset
  header('Location: reset.php?mail='.$_POST['mail'].'&key='.$_POST['key'].'&err=passNull');
}

?>
