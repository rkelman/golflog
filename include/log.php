<?php
include_once('connection.php');

function log_cron($appName, $msg) {
  $log_conn = connectDB();

  $logSql = "INSERT into glLogs
     (logDateTime, app, message)
     VALUES
     (now(), '".$appName."', '".$msg."')";

  //echo $logSql."<BR>\n";

  $insLog=$log_conn->query($logSql);

  $log_conn->close();
}
?>
