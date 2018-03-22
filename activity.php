<?php
require_once 'include/functions.php';

// json response array
$json_resp->error = FALSE;

// Get JSON as a string
$json_str = file_get_contents('php://input');

// Get as an object
$json_obj = json_decode($json_str, TRUE);
$json_err = json_last_error();

echo $json_obj;

if (isset($json_obj['token']) && checkToken($json_obj['token'], $json_obj['uid'])) {
    // receiving the post params
    $uid = $json_obj['uid'];
    $activity = $json_obj['activity'];
    $subActivity = $json_obj['subActivity'];
    $elapsedTime = $json_obj['elapsedTime'];
    $notes = $json_obj['notes'];
    $location = $json_obj['location'];

    // get the user by email and password
    $json_resp->json_obj=$json_obj;

    //$response = insertActivity($uid, $activity, $subActivity, $elapsedTime, $notes, $location);

    echo json_encode($json_resp);

} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"]["message"] = "Required parameter token missing or invalid.";
    $response["error_msg"]["token"]=$json_obj['token'];
    $response["json_obj"]=$json_obj;
    $response["json_str"]=$json_str;
    $response["json_err"]=$json_err;
    echo json_encode($response);
}
?>
