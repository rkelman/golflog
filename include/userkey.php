<?php

function getPassHash ($pass) {
  return hash('gost', $pass);
}

function createUserKey($name) {
  $key=hash('gost',$name.date('z'));
  return $key;
}

function validateUserKey($name, $key) {
  if ($key == hash('gost',$name.date('z'))) {
    return true;
  } else {
    return false;
  }
}

function mailUserKey($name, $key) {
  $headers = 'From: Golflog Assistant <info@daxhund.com>' . "\r\n" .
      'Reply-To: info@daxhund.com' . "\r\n" .
      'X-Mailer: PHP/' . phpversion();
  $subject = "Golflog Password Reset";

  $message = "As you requested here is the link to reset your password
  golflog.daxhund.com/resetform.php?mail=".$name."&key=".$key;
  //troubleshooting - 042118
  //echo 'Name: '.$name;
  //echo 'Subject: '.$subject;
  //echo 'Message: '.$message;
  //echo 'Headers: '.$headers;
  mail($name, $subject, $message, $headers);
}

?>
