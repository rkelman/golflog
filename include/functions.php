<?php
include_once('connection.php');
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
              return $user;
            }  else {
              $conn->close();
              return false;
            }
        } else {
          $conn->close();
          return false;
        }
    }

    //function to insert a new practice activity
    function insertActivity($uid, $activity, $subActivity, $elapsedTime, $notes, $location) {
      $conn = connectDB();

      $sql = "INSERT INTO glPracticeLog
         (userID, practiceType, practiceSubType, elapsedTime, practiceDateTime, practiceNotes, location)
         VALUES
         (".$uid.", '".$activity."','".$subActivity."', '".$elapsedTime."', NOW(), '".$notes."', '".$location."')";

      $insActivity = $conn->query($sql);
      if ($insActivity){
        //successful insert
        $result["success"]=true;
        $result["msg"]="New ".$activity." Activity Saved.";
        $conn->close();
        return $result;
      } else {
        //error
        $result["success"]=false;
        $result["error_msg"]="Golflog experiencing issues: Insert failed on DB";
        $result["sql"]=$sql;
        $conn->close();
        return $result;
      }
    }

    function getActivitySummary($uid) {
      $conn = connectDB();

      $sql = "SELECT practiceType, count(practiceType) count_type, SEC_TO_TIME(SUM(TIME_TO_SEC(elapsedTime))) sum_time
                FROM glPracticeLog
                WHERE userID = $uid
                GROUP BY practiceType";

      $getActSumm = $conn->query($sql);

      if ($getActSumm) {
        $result["success"]=TRUE;
        while ($row = $getActSumm->fetch_assoc()) {
          $result[$row['practiceType']] = array(
            'type' => $row['practiceType'],
            'elapsedTime' => $row['sum_time'],
            'count' => $row['count_type']
          );
        }
        $conn->close();
        return $result;
      } else {
        //error
        $result["success"]=FALSE;
        $result["error_msg"]="Golflog experiencing issues: Insert failed on DB";
        $result["sql"]=$sql;
        $conn->close();
        return $result;
      }
    }

    function getActivityList($uid,$number){
      $conn = connectDB();

      $sql = "SELECT practiceType, elapsedTime, practiceDateTime
                FROM glPracticeLog
                WHERE userID = $uid
                ORDER BY practiceDateTime DESC
                LIMIT $number";

      $getActList = $conn->query($sql);

      if ($getActList) {
        $result["success"]=TRUE;
        while ($row = $getActList->fetch_assoc()) {
          $result[] = array(
            'type' => $row['practiceType'],
            'elapsedTime' => $row['elapsedTime'],
            'practiceDateTime' => $row['practiceDateTime']
          );
        }
        $conn->close();
        return $result;
      } else {
          //error
          $result["success"]=FALSE;
          $result["error_msg"]="Golflog experiencing issues: Insert failed on DB";
          $result["sql"]=$sql;
          $conn->close();
          return $result;
      }
    }

    //function to check token submitted on API calls
    function checkToken($uid, $token){
      return true;
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
