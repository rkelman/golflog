<?php

function connectDB() {

  $servername = "localhost";
  $user = "daxhundc_misc";
  $passwd = "m1sc-D4x";
  $dbname = "daxhundc_misc";

  $dh_conn = new mysqli($servername, $user, $passwd, $dbname);
  return $dh_conn;
}

    /**
    * Get user by email and password
    */
   public function getUserByEmailAndPassword($email, $password) {

       $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");

       $stmt->bind_param("s", $email);

       if ($stmt->execute()) {
           $user = $stmt->get_result()->fetch_assoc();
           $stmt->close();

           // verifying user password
           $salt = $user['salt'];
           $encrypted_password = $user['encrypted_password'];
           $hash = $this->checkhashSSHA($salt, $password);
           // check for password equality
           if ($encrypted_password == $hash) {
               // user authentication details are correct
               return $user;
           }
       } else {
           return NULL;
       }
   }
?>
