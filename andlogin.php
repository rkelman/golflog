<?php

    require_once 'user.php';

    $firstName = "";
    $lastName = "";
    $password = "";
    $email = "";

    if(isset($_POST['firstName'])){
        $firstName = $_POST['firstName'];
    }

    if(isset($_POST['lastName'])){
        $lastName = $_POST['lastName'];
    }

    if(isset($_POST['password'])){
        $password = $_POST['password'];
    }

    if(isset($_POST['email'])){
        $email = $_POST['email'];
    }

    $userObject = new User();

    // Registration
    if(!empty($lastName) && !empty($firstName) && !empty($password) && !empty($email)){
        $hashed_password = hash('gost', $password);

        $json_registration = $userObject->createNewRegisterUser($firstName, $lastName, $hashed_password, $email);

        echo json_encode($json_registration);

    }

    // Login

    if(!empty($password) && empty($email)){

        $hashed_password = hash('gost', $password);
        $json_array = $userObject->loginUsers($email, $hashed_password);

        echo json_encode($json_array);
    }
    ?>
