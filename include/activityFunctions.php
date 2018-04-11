<?php
include_once('connection.php');
include_once('log.php');

    //function to insert a new practice activity
    function insertActivity($uid, $activity, $subActivity, $elapsedTime, $startTime, $notes, $location) {
      $conn = connectDB();

      $sql = "INSERT INTO glPracticeLog
         (userID, practiceType, practiceSubType, elapsedTime, practiceDateTime, practiceNotes, location)
         VALUES
         (".$uid.", '".$activity."','".$subActivity."', '".$elapsedTime."', '".$startTime."', '".$notes."', '".$location."')";

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
        logStep('Activity', 'Insert failed for new '.$activity.' for user ID: '.$uid.'; '.$sql);

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
                GROUP BY practiceType
                ORDER BY sum_time DESC";

      $getActSumm = $conn->query($sql);

      if ($getActSumm) {
        $result=array();
        while ($row = $getActSumm->fetch_assoc()) {
          $result[] = array(
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
        $result["error"]=TRUE;
        $result["error_msg"]="Golflog experiencing issues: Insert failed on DB";
        $result["sql"]=$sql;
        $conn->close();
        return $result;
      }
    }

    function getActivityList($uid,$number){
      $conn = connectDB();

      $sql = "SELECT practiceID, practiceType, practiceSubType, elapsedTime, DATE_FORMAT(practiceDateTime, \"%a %b %e, %l:%i %p\") practiceTime, practiceNotes
                FROM glPracticeLog
                WHERE userID = $uid
                ORDER BY practiceDateTime DESC
                LIMIT $number";

      $getActList = $conn->query($sql);
  
      if ($getActList) {
        //$result["success"]=TRUE;
        $i = 0;
        $result = array ();
        while ($row = $getActList->fetch_assoc()) {
          $result[] = array(
            'activityID' => $row['practiceID'],
            'type' => $row['practiceType'],
            'subtype' => $row['practiceSubType'],
            'elapsedTime' => $row['elapsedTime'],
            'practiceDateTime' => $row['practiceTime'],
            'notes' => $row['practiceNotes']
          );
        }
        $conn->close();
        return $result;
      } else {
          //error
          $result["error"]=TRUE;
          $result["error_msg"]="Golflog experiencing issues: Insert failed on DB";
          $result["sql"]=$sql;
          $conn->close();
          return $result;
      }
    }

    function deleteActivity($uid, $activityID) {
      $conn = connectDB();

      $sql = "DELETE from glPracticeLog
         WHERE userID = $uid
           AND practiceID = $activityID";

      $delActivity = $conn->query($sql);
      if ($delActivity){
        //successful insert
        logStep('Activity', 'Deleted activity '.$activity.' for user ID: '.$uid);

        $result["success"]=true;
        $result["msg"]="Successfully deleted ".$activity." activity";
        $conn->close();
        return $result;
      } else {
        //error
        logStep('Activity', 'Delete failed for '.$activity.' and user ID: '.$uid);

        $result["error"]=TRUE;
        $result["error_msg"]="Golflog experiencing issues: Insert failed on DB";
        $result["sql"]=$sql;
        $conn->close();
        return $result;
      }
    }
?>
