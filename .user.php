<?php

    include_once 'connection.php';

    class User {

        private $db_table = "glUsers";

        $conn = connectDB();
        public function isLoginExist($username, $password){

            $query = "select * from ".$this->db_table." where email = '$username' AND password = '$password'";

            $result = mysqli_query($conn, $query);

            if(mysqli_num_rows($result) > 0){
                mysqli_close($conn);
                return true;
            }

            mysqli_close($conn);

            return false;

        }

        public function isEmailUsernameExist($email){
            $query = "select * from ".$this->db_table." where email = '$email'";
            $result = mysqli_query($conn, $query);

            if(mysqli_num_rows($result) > 0){
                mysqli_close($conn);
                return true;
            }
            return false;
        }

        public function isValidEmail($email){
            return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        }

        public function createNewRegisterUser($firstName, $lastName, $password, $email){

            $isExisting = $this->isEmailUsernameExist($email);
            if($isExisting){
                $json['success'] = 0;
                $json['message'] = "Error in registering; username/email already registered";
            } else {

              $isValid = $this->isValidEmail($email);
              if($isValid) {
                $query = "Insert into ".$this->db_table."
                  (firstName, lastName, encrypted_password, email, created_at, updated_at) values
                  ('$firstName', '$lastNname', '$password', '$email', NOW(), NOW())";
                $inserted = mysqli_query($conn, $query);

                if($inserted == 1){
                    $json['success'] = 1;
                    $json['message'] = "Successfully registered user";
                }else{
                    $json['success'] = 0;
                    $json['message'] = "Error in registering. Site may be down";
                }

                mysqli_close($conn);
                }
                else{
                    $json['success'] = 0;
                    $json['message'] = "Error in registering. Email Address is not valid";
                }

            }
            return $json;
        }

        public function loginUsers($email, $password){

            $json = array();

            $canUserLogin = $this->isLoginExist($email, $password);

            if($canUserLogin){
                $json['success'] = 1;
                $json['message'] = "Successfully logged in";
            } else {
                $json['success'] = 0;
                $json['message'] = "Incorrect Username/Password Combination";
            }
            return $json;
        }
    }
    ?>
