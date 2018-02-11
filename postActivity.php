<?php
include_once "connection.php";
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_POST["x"], false);

$conn = connectDB();
$sql_ins = "INSERT into golfLog
      (logDateTime, elapsedTime, type, userID)
      VALUES
      (now(), '".$obj->time."', '".$obj->activity."', $obj->uid)";

$result = $conn->query($sql_ins);

?>
