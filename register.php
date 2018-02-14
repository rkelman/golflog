<?php

require_once 'include/functions.php';

// json response array
$response = array("error" => FALSE);

// Get JSON as a string
$json_str = file_get_contents('php://input');

// Get as an object
$json_obj = json_decode($json_str, TRUE);

//print_r($json_obj);
//print $json_obj->{'name'};

if (isset($json_obj['firstname']) && $json_obj[0]['lastname']) && isset($json_obj['email']) && isset($json_obj['password'])) {

    // receiving the post params
    $firstname = $json_obj['firstname'];
    $lastname = $json_obj['lastname'];
    $email = $json_obj['email'];
    $password = $json_obj['password'];

    //echo $name." ".$email." ".$password;

    // check if user is already existed with the same email
    if (isUserRegistered($email)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with ".$email;
        echo json_encode($response);
    } else {
        $user = storeUser($firstname, $lastname, $email, $password);
        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["uid"] = $user["unique_id"];
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration";
            $response["firstname"]=$firstname;
            $response["lastname"]=$lastname;
            $response["email"]=$email;
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing";
    $response["firstname"]=$json_obj['firstname'];
    $response["lastname"]=$json_obj['lastname'];
    $response["email"]=$json_obj['email'];
    $response["password"]=$json_obj['password'];
    echo json_encode($response);
}
?>
