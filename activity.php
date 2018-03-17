<?php
require_once 'include/functions.php';

// json response array
$response = array("error" => FALSE);

// Get JSON as a string
$json_str = file_get_contents('php://input');

// Get as an object
$json_obj = json_decode($json_str, TRUE);

if (isset($json_obj['token']) && checkToken($json_obj['token'], $json_obj['uid']) {
    // receiving the post params
    $uid = $json_obj['uid'];
    $activity = $json_obj['activity'];
    $elapsedTime = $json_obj['elapsedTime'];
    $notes = $json_obj['notes'];

    // get the user by email and password
    $response = insertActivity($uid, $activity, $elapsedTime);

    echo json_encode($response);

} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"]["message"] = "Required parameter token missing or invalid.";
    $response["error_msg"]["token"]=$json_obj['token'];
    echo json_encode($response);
}
?>
