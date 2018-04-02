<?php
include_once('connection.php');
include_once('log.php');

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
        logStep('Activity', 'Logged new '.$activity.' for user ID: '.$uid);

        $result["success"]=true;
        $result["msg"]="New ".$activity." Activity Saved.";
        $conn->close();
        return $result;
      } else {
        //error
        logStep('Activity', 'Insert failed for new '.$activity.' for user ID: '.$uid);

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
        logStep('Activity', 'Failed to show Activity Summary for user ID: '.$uid);
        $result["success"]=FALSE;
        $result["error_msg"]="Golflog experiencing issues: Insert failed on DB";
        $result["sql"]=$sql;
        $conn->close();
        return $result;
      }
    }

    function getActivityList($uid,$number){
      $conn = connectDB();

      $sql = "SELECT practiceType, elapsedTime, practiceDateTime, practiceNotes
                FROM glPracticeLog
                WHERE userID = $uid
                ORDER BY practiceDateTime DESC
                LIMIT $number";

      $getActList = $conn->query($sql);
  
      if ($getActList) {
        //$result["success"]=TRUE;
        $i = 0;
        $result = array ('Activities' => array(),);
        while ($row = $getActList->fetch_assoc()) {
          $result['Activities'][$i++] = array(
            'type' => $row['practiceType'],
            'elapsedTime' => $row['elapsedTime'],
            'practiceDateTime' => $row['practiceDateTime'],
            'notes' => $row['notes']
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
?>
