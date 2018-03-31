<?php
include_once('connection.php');
include_once('log.php');

    /**
     * Storing new user
     * returns user details
     */
    function storeUser($firstname, $lastname, $email, $password) {
        $hash = hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $conn = connectDB();

        $sql = "INSERT INTO glUsers
           (firstName, lastName, email, encrypted_password, salt, created_at, updated_at)
           VALUES
           ('".$firstname."', '".$lastname."', '".$email."', '".$encrypted_password."', '".$salt."', NOW(), now())";

        $sqlIns=$conn->query($sql);

        // check for successful store
        if ($sqlIns) {
            $stmt = "SELECT * FROM glUsers WHERE email = '".$email."'";

            $qyUser=$conn->query($stmt);

            if ($qyUser->num_rows > 0) {
              $user = $qyUser->fetch_assoc();
              $conn->close();
              logStep('Registration', 'Created User '.$email);
              return $user;
            }  else {
              $conn->close();
              logStep('Registration', 'Failed to create user '.$email);
              return false;
            }
        } else {
          $conn->close();
          logStep('Registration', 'Failed to create user '.$email);
          return false;
        }
    }

    /**
     * Storing new/reset passord
     * returns true if successful, false otherwise
     */
    function storeResetPassword($password, $mailID) {
        $hash = hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $conn = connectDB();

        $sql = "Update tlUsers SET password='".$hashPass."', updated_at = now() ".
           "WHERE email ='".$mailID."'";

        $sqlIns=$conn->query($sql);

        // check for successful store
        if ($sqlIns) {
          $conn->close();
          return true;
        } else {
          $conn->close();
          return false;
        }
    }

        /**
     * Get user by email and password
     */
    function getUserByEmailPassword($email, $password) {
        $conn = connectDB();
        $stmt = "SELECT * from glUsers WHERE email = '".$email."'";
  
        $qyUser=$conn->query($stmt);
  
        if ($qyUser->num_rows > 0) {
            $user = $qyUser->fetch_assoc();
  
            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
              // user authentication details are correct
              
              // count user as logged in
              $updateLogin = "UPDATE glUsers set lastLogin=NOW() where id=".$user['id'];
              $update = $conn->query($updateLogin);
  
              $conn->close();
              //$user["error"]= FALSE;
              return $user;
            } else {
              $conn->close();
              $user["error"]=TRUE;
              $user["error_msg"]='Password was incorrect for that account.';
              return $user;
            }
        } else {
          $conn->close();
          $user["error"]=TRUE;
          $user["error_msg"]='No user with that email found.';
          return $user;
        }
    }
  
    /**
    * Check user is registered or not
    */
    function isUserRegistered($email) {
        $conn = connectDB();
        $stmt = "SELECT * from glUsers WHERE email = '".$email."'";
  
        $qyUser=$conn->query($stmt);
  
        if ($qyUser->num_rows > 0) {
            // user registered
            $conn->close();
            return true;
        } else {
            // user not registered
            $conn->close();
            return false;
        }
    }
  
    /**
    * Encrypting password
    * @param password
    * returns salt and encrypted password
    */
    function hashSSHA($password) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
  
    /**
    * Decrypting password
    * @param salt, password
    * returns hash string
    */
    function checkhashSSHA($salt, $password) {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }
  
?>