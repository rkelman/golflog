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
?>
