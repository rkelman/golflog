<?php
include '../include/connection.php';
include '../include/userkey.php';

// json response array
//$response = array("error" => FALSE);
$response = array();

$method = $_SERVER['REQUEST_METHOD'];

if ($method != 'POST') {
  $response["error"] = TRUE;
  $response["error_msg"] = "Unsupported HTTP Method ".$method." for API Activity";
  logStep('Activity', "Unsupported HTTP Method ".$method." for API Activity");
  http_response_code(501);
  echo json_encode($response);
} else {
  // Get JSON as a string
  $json_str = file_get_contents('php://input');

  // Get as an object
  $json_obj = json_decode($json_str, TRUE);

  if (isset($json_obj['email']) && !isset($json_obj['key'])) {
    $user = $json_obj['email'];

    if (isUserRegistered($user)) {
      $key = createUserKey($username);
      //echo $key;
      mailUserKey($username, $key);

      $response["success"] = "TRUE";
      $response["step"]=1;
      $response["msg"] = "A link to reset your password has been sent to your email";
      echo json_encode($response);
    } else {
      $response["success"] = "FALSE";
      $response["msg"] = "Sorry, The email entered is not registered to a Golflog user.";
      http_response_code(400);
      echo json_encode($response);
    }
  } elseif (isset($json_obj['email']) && isset($json_obj['key']) && !isset($json_obj['password'])) {
    $mailID = $_json_obj['mail'];
    $keyID = $_json_obj['key'];

    if (validateUserKey($mailID, $keyID)) {
      //allow user to update password
      $response["success"] = "TRUE";
      $response["step"]=2;
      $response["msg"] = "Please update your password.";
      echo json_encode($response);
    } else {
      //Allow user to enter new password_conf
      $response["success"] = "FALSE";
      $response["step"]=2;
      $response["message"] = "The key you are using is invalid or expired";
      echo json_encode($response);
    }
  } elseif (isset($json_obj['email']) && isset($json_obj['key']) && isset($json_obj['password'])) {
    $mailID = $_json_obj['mail'];
    $keyID = $_json_obj['key'];
    $passwd = $_json_obj['password'];

    if (validateUserKey($mailID, $keyID)) {
      //allow password to be updated
      if (!storeResetPassword($passwd, $mailID)) {
        // Oh no! The update query failed.
        $response["success"] = "FALSE";
        $response["step"]=3;
        $response["message"] = "Password not able to be updated Golflog experiencing issues.";
        echo json_encode($response);
      } else {
        $response["success"] = "TRUE";
        $response["step"]=3;
        $response["message"] = "Password Updated";
        echo json_encode($response);
      }
    } else {
      //user key has expired
      $response["success"] = "FALSE";
      $response["step"]=3;
      $response["message"] = "The key you are using is invalid or expired";
      echo json_encode($response);
    }
  }
} 

?>
