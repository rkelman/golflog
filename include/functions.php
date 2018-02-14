<?php
include_once('../connection.php');
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

  /*      // check for successful store
        if ($sqlIns) {
            $stmt = "SELECT * FROM glUsers WHERE email = '".$email."'";

            $qyUser=$conn->query($stmt);
            if ($qyUser->num_rows > 0) {
              $user = $qyUser->fetch_assoc();
              $conn->close();
              return $user;
            }  else {
              $conn->close();
              return false;
            }
        } else {
          $conn->close();
          return false;
        } */
    }

    /**
     * Get user by email and password
     */
  /*  getUserByEmailAndPassword($email, $password) {
        $conn = connectDB();

        $stmt = "SELECT * FROM users WHERE email = '".$email."'";

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
            $conn->close();
            return $user;
          } else {
            $conn->close();
            return NULL;
          }
        } else {
            $conn->close();
            return NULL;
        }
    }

    /**
     * Check user is existed or not
     */
    function isUserRegistered($email) {
        $conn = connectDB();
        $stmt = "SELECT email from glUsers WHERE email = '".$email."'";

        $qyUser=$conn->query($stmt);

        if ($qyUser->num_rows > 0) {
            // user existed
            $conn->close();
            return true;
        } else {
            // user not existed
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
/*    function checkhashSSHA($salt, $password) {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }
*/
?>
