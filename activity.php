<?php
require_once 'include/activityFunctions.php';
require_once 'include/tokenFunctions.php';

$method = $_SERVER['REQUEST_METHOD'];

// json response array
$response["error"] = FALSE;

if ($method === 'POST') {
  // Get JSON as a string
  $json_str = file_get_contents('php://input');

  // Get as an object
  $json_obj = json_decode($json_str, TRUE);
  //$json_err = json_last_error();

  if (isset($json_obj['token']) && checkToken($json_obj['token'], $json_obj['uid'])) {
    // receiving the post params
    $uid = $json_obj['uid'];
    $activity = $json_obj['activity'];
    $subActivity = $json_obj['subActivity'];
    $startTime = $json_obj['startTime'];
    $elapsedTime = $json_obj['elapsedTime'];
    $notes = $json_obj['notes'];
    $location = $json_obj['location'];

    // troubleshooting
    //$response["json_obj"]=$json_obj;

    //insertActivity into DB
    $response = insertActivity($uid, $activity, $subActivity, $elapsedTime, $startTime, $notes, $location);

    echo json_encode($response, JSON_PRETTY_PRINT);

  } else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"]["message"] = "Required parameter token missing or invalid.";
    $response["error_msg"]["token"]=$json_obj['token'];
    $response["json_obj"]=$json_obj;
    $response["json_str"]=$json_str;
    $response["json_err"]=$json_err;
    http_response_code(400);
    echo json_encode($response);
  }
} elseif ($method === 'DELETE') {
  $json_str = file_get_contents('php://input');

  // Get as an object
  $json_obj = json_decode($json_str, TRUE);
  //$json_err = json_last_error();

  if (isset($json_obj['uid']) && isset($json_obj['activityID'])) {
    $response = deleteActivity($json_obj['uid'], $json_obj['activityID']);
    if ($response["error"] == TRUE) {
      http_response_code(400);
    } else {
      http_response_code(200);
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
  } else {
    $response["error"] = TRUE;
    $response["error_msg"]["message"] = "Required parameters uid and/or activityID missing.";
    http_response_code(400);
    echo json_encode($response);
  }
} elseif ($method === 'GET') {
  echo "hello ".$method."\n";
  //print_r($_GET);
  if (!isset($_GET['uid']) /*|| isempty($_GET['uid'])*/) {
    $response["error"] = TRUE;
    $response["error_msg"] = "Invalid Activity Get call - requires uid";
    http_response_code(400);
    echo json_encode($response);
  } else {
    $uid=$_GET['uid'];

    if ($_GET['type'] == 'summary') {
      $response = getActivitySummary($uid);
      
      //if getActivitySummary failed
      if ($response["error"] == TRUE) {
        http_response_code(400);
      } else {
        http_response_code(200);
      }
      echo json_encode($response, JSON_PRETTY_PRINT);
    //} elseif (/*($_GET['type'] == 'list') ||*/ (isempty($_GET['type'])) || (!isset($_GET['type']))) {
    } else {
      //troubleshooting:
      //echo " got here 2";
      if (isset($_GET['number'])) {
        $number = $_GET['number'];
      } else {
        $number = 10;
      }
      $response = getActivityList($uid, $number);
      if ($response["error"] == TRUE) {
        http_response_code(400);
      } else {
        http_response_code(200);
      }
      echo json_encode($response, JSON_PRETTY_PRINT);
    }
  }
} else {
  $response["error"] = TRUE;
  $response["error_msg"] = "Unsupported HTTP Method ".$method." for API Activity";
  http_response_code(501);
  echo json_encode($response);
}
?>
