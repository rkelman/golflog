<?php

if ((empty($_POST['password'])) || (empty($_POST['password_conf']))) {
  //if either password was sent over null return to reset
  header('Location: resetform.php?mail='.$_POST['mail'].'&key='.$_POST['key'].'&err=passNull');
} else {
  if ($_POST['password'] == $_POST['password_conf']) {
    echo "reset pass works";
  } else {
    header('Location: resetform.php?mail='.$_POST['mail'].'&key='.$_POST['key'].'&err=passMismatch');
  }
}

?>
