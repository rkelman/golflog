<?php

function connectDB() {

  $servername = "localhost";
  $user = "db_user";
  $passwd = "db_pass";
  $dbname = "db_name";

  $dh_conn = new mysqli($servername, $user, $passwd, $dbname);
  return $dh_conn;
}

?>
