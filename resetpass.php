<?php

if ((empty($_POST['password'])) || (empty($_POST['password_conf']))) {
  //if either password was sent over null return to reset
  header('Location: resetform.php?mail='.$_POST['mail'].'&key='.$_POST['key'].'&err=passNull');
} else {
  echo "reset pass works";
}

?>
