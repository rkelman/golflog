<?php
require_once 'include/functions.php';

// json response array
$response = array("error" => FALSE);

// Get JSON as a string
$json_str = file_get_contents('php://input');

// Get as an object
$json_obj = json_decode($json_str, TRUE);

if (isset($json_obj['email']) && isset($json_obj['password'])) {
    // receiving the post params
    $email = $json_obj['email'];
    $password = $json_obj['password'];

    // get the user by email and password
    $user = getUserByEmailPassword($email, $password);

    if ($user["error"] != TRUE) {
        // use is found
//        $response["error"] = FALSE;
        $response["uid"] = $user["id"];
//        $response["user"]["firstname"] = $user["firstName"];
        $response["firstname"] = $user["firstName"];
//        $response["user"]["lastname"] = $user["lastName"];
        $response["lastname"] = $user["lastName"];
//        $response["user"]["email"] = $user["email"];
//        $response["user"]["created_at"] = $user["created_at"];
        $response["created_at"] = $user["created_at"];
//        $response["user"]["updated_at"] = $user["updated_at"];
        $response["updated_at"] = $user["updated_at"];
        echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = $user["error_msg"];
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"]["message"] = "Required parameters email or password is missing.";
    $response["error_msg"]["email"]=$json_obj['email'];
    $response["error_msg"]["password"]=$json_obj['password'];
    echo json_encode($response);
}
?>
