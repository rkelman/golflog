<?php

function connectDB() {

  $servername = "localhost";
  $user = "daxhundc_misc";
  $passwd = "d4x-M1sc";
  $dbname = "daxhundc_misc";

  $dh_conn = new mysqli($servername, $user, $passwd, $dbname);
  return $dh_conn;
}

?>
