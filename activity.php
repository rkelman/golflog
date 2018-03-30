<?php
require_once 'include/functions.php';

$method = $_SERVER['REQUEST_METHOD'];

// json response array
$response["error"] = FALSE;

if (($method != 'POST') && ($method != 'GET')) {
  $response["error"] = TRUE;
  $response["error_msg"] = "Unsupported HTTP Method ".$method." for API Activity";
  http_response_code(400);
  echo json_encode($response);
} elseif ($method == 'POST') {
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
    $elapsedTime = $json_obj['elapsedTime'];
    $notes = $json_obj['notes'];
    $location = $json_obj['location'];

    // troubleshooting
    //$response["json_obj"]=$json_obj;

    //insertActivity into DB
    $response = insertActivity($uid, $activity, $subActivity, $elapsedTime, $notes, $location);

    echo json_encode($response, JSON_PRETTY_PRINT);

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
} elseif ($method == 'GET') {
  //echo "hello ".$method."\n";
  //print_r($_GET);
  if (!isset($_GET['uid']) /*|| isempty($_GET['uid'])*/) {
    $response["error"] = TRUE;
    $response["error_msg"] = "Invalid Activity Get call - requires uid";
    http_response_code(400);
    echo json_encode($response);
  } else {
    //echo "I got here";
    $uid=$_GET['uid'];   
    print_r($_GET);
    //echo "<BR>".$uid;
    $response["error"] = FALSE;
    $response["uid"]=$uid;
    echo json_encode($response);
    /*
    $uid = $_GET['uid'];
    $type = $_GET['type'];
    echo "UID: ".$uid;
    echo "type: ".$type;
    if ($type = 'summary') {
      $response = getActivitySummary($uid);
      if ($response["success"] == FALSE) {
        http_response_code(400);
      }
      echo json_encode($response);
    } else*/
    if (($type = 'list') || (isempty($_GET['type'])) || (!isset($_GET['type']))) {
      echo "got here";
      if (isset($_GET['number'])) {
        echo "got here2";
        $number = $_GET['number'];
      } else {
        echo "got here 3";
        $number = 10;
      }
      $response = getActivityList($uid, $number);
      echo json_encode($response);
    }
  }
}
?>
