<?php

if ((empty($_POST['password'])) || (empty($_POST['password_conf']))) {
  //if either password was sent over null return to reset
  header('Location: resetform.php?mail='.$_POST['mail'].'&key='.$_POST['key'].'&err=passNull');
} else {
  if ($_POST['password'] == $_POST['password_conf']) {
    if (storeResetPassword($_POST['password'], $_POST['mail'])) {
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
    } else {
      // Oh no! The update query failed.
      echo "<neg_mesg>Sorry, Golflog is experiencing problems.</neg_mesg><BR>";
    }
  } else {
    header('Location: resetform.php?mail='.$_POST['mail'].'&key='.$_POST['key'].'&err=passMismatch');
  }
}

?>
