<?php
include 'connection.php';

if (!isset($_COOKIE["uid"])) {
  header("Location:login.php");
}else {
  $s_uid = $_COOKIE['uid'];
}

$conn = connectDB();

if (isset($_POST["sport"])) {
  $sport = $_POST["sport"];
  $time = $_POST["time"];

  //if distance is not set (like for circuit) set it to 0
  if (!empty($_POST["distance"])) {
    $dist = $_POST["distance"];
  } else {
    $dist = "0";
  }

  if ($conn->connect_errno > 0) {
    die("<neg_mesg>Connection failed: ".$conn->connect_error."</neg_mesg>");
  }

  $ins_sql = "INSERT into trainingLog
      (trainDate, distance, elapsedTime, type, userID)
      VALUES
      (now(), ".$dist.", '".$time."', '".$sport."', $s_uid)";

  $ins_trainlog=$conn->query($ins_sql);

}
echo "<html>\n";
echo "<head>\n<link rel=\"stylesheet\" href=\"traininglog.css\">\n";
echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
echo "</head>\n";
echo "<body>\n";
//troubleshooting insert statements
//echo $ins_sql."; distance='".$_POST["distance"]."'";
if ($ins_trainlog) {
  if ($sport == 'Cycling') {
    $activity = 'ride';
  } elseif ($sport == 'Running') {
    $activity = 'run';
  } else {
    $activity = strtolower($sport);
  }
  echo "<pos_mesg>New ".$activity." logged successfully</pos_mesg><BR><BR>\n";
}

echo "<form action=\"index.php\" method=\"post\">\n";
echo "Enter new training activity:<BR>\n";
echo "Distance (mi/yds): ";
echo "<input type=\"text\" name=\"distance\"><BR>\n";
echo "Elapsed Time (0:00:00): ";
echo "<input type=\"text\" name=\"time\"><BR>\n";
echo "Sport: ";
echo "<select name=\"sport\">\n";
echo "  <option value=\"Cycling\">Cycling</option>\n";
echo "  <option value=\"Run\">Running</option>\n";
echo "  <option value=\"Swim\">Swim</option>\n";
echo "  <option value=\"Circuit\">Circuit</option>\n";
echo "</select><BR>\n";
echo "<input type=\"submit\" value=\"Log Training\">";
echo "</form>\n";
echo "<BR><BR>";
echo "Annual Totals for ".date('M j').":<BR>\n";
$tot_sql = "SELECT type, count(type) count_type, sum(distance) sum_dist, SEC_TO_TIME(SUM(TIME_TO_SEC(elapsedTime))) sum_time
  FROM trainingLog
  WHERE userID = $s_uid
  GROUP BY type";

if (!$tot_result = $conn->query($tot_sql)) {
  // Oh no! The query failed.
  echo "<neg_mesg>Sorry, Traininglog is experiencing problems.</neg_mesg><BR>";
  echo $tot_sql;
}

if ($tot_result->num_rows > 0) {
  // output data of each row
  while($row = $tot_result->fetch_assoc()) {
    if ($row['type']=='Cycling') {
      $dist_unit = 'Miles';
      $target = number_format(4000*(date('z')+1)/365, 2);
    } elseif ($row['type']=='Run')  {
      $dist_unit = 'Miles';
      $target = number_format(370*(date('z')+1)/365, 2);
    } else {
      $dist_unit = 'Yds';
      $target = "";
    }
    echo $row['type'].": ".$row['count_type'].", ".$row['sum_dist']." ".$dist_unit."; Total Duration: ".$row['sum_time']."; Target: ".$target." ".$dist_unit."<BR>\n";
  }
} else {
  echo "<neg_mesg>Sorry - no training logged this year</neg_mesg><BR>\n";
}

$conn->close();

echo "</body>\n";
echo "</html>";
?>
