<?php
include 'include/connection.php';
include 'include/userkey.php';
// json response array
$response = array("error" => FALSE);

// Get JSON as a string
$json_str = file_get_contents('php://input');

// Get as an object
$json_obj = json_decode($json_str, TRUE);

if (isset($json_obj['email']) && !isset($json_obj['key'])) {

  $conn = connectDB();

  $username = $json_obj["email"];
  //echo $username."<BR>\n";
  $res_sql = "SELECT * from tlUsers where email = '".$username."'";
  //echo $res_sql;

  if (!$res_result = $conn->query($res_sql)) {
    // Oh no! The query failed.
    echo "<neg_mesg>Sorry, Traininglog is experiencing problems.</neg_mesg><BR>";
    echo $res_sql;
  }

  if ($res_result->num_rows > 0) {
    $key = createUserKey($username);
    //echo $key;
    mailUserKey($username, $key);

    $response["success"] = "TRUE";
    $response["step"]=1;
    $response["message"] = "A link to reset your password has been sent to your email";
    echo json_encode($response);
  } else {
    $response["success"] = "FALSE";
    $response["message"] = "Sorry, The email entered is not registered to a Golflog user.";
    echo json_encode($response);
  }
} elseif (isset($json_obj['email']) && isset($json_obj['key']) && !isset($json_obj['password'])) {
  $mailID = $_json_obj['mail'];
  $keyID = $_json_obj['key'];

  if (validateUserKey($mailID, $keyID)) {
    //allow user to update password
    $response["success"] = "TRUE";
    $response["step"]=2;
    $response["message"] = "Please update your password.";
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
    //Allow user to enter new password_conf
    $response["success"] = "FALSE";
    $response["step"]=3;
    $response["message"] = "The key you are using is invalid or expired";
    echo json_encode($response);
  }
}

$conn->close();

?>
