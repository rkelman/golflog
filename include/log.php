<?php
include_once('connection.php');

function logStep($appName, $msg) {
  $log_conn = connectDB();

  $logSql = "INSERT into glAppLogs
     (logDateTime, app, message)
     VALUES
     (now(), '".$appName."', '".$msg."')";

  //echo $logSql."<BR>\n";

  $insLog=$log_conn->query($logSql);

  $log_conn->close();
}
/*
function getLogs() {
  $log_conn = connectDB();

  $logSql = "SELECT * from glAppLogs
     order by logDateTime DESC
     limit 100";

  $getLogList = $conn->query($logSql);
  
  if ($getLogList) {
    //$result["success"]=TRUE;
    $i = 0;
    $result = array ();
    while ($row = $getActList->fetch_assoc()) {
      $result[] = array(
        'logID' => $row['logID'],
        'logDateTime' => $row['logDateTime'],
        'app' => $row['app'],
        'message' => $row['message']
      );
    }
    $log_conn->close();
    return $result;
  }
}
*/
?>
