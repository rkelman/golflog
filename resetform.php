<?php
//include 'include/connection.php';
include 'include/userkey.php';
include 'include/userFunctions.php';

//$conn = connectDB();

//if no post or get; first time to page
if (!isset($_POST["email"]) && !isset($_GET['mail'])) {
  echo "<html>\n";
  echo "<head>\n<link rel=\"stylesheet\" href=\"golflog.css\">\n";
  echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
  echo "</head>\n";
  echo "<body>\n";
  echo "<img src=\"assets\GolflogLogo.png\">";
  if (isset($_GET["err"])) {
    if ($_GET["err"]=="InvalidKey") {
      echo "<neg_mesg>Sorry, The key that you used appears to be expired, please request a new one.</neg_mesg><BR>";
    } elseif ($_GET["err"]=="InvalidName") {
      echo "<neg_mesg>Sorry, The email you entered is not registered to a Traininglog user.</neg_mesg><BR>";
      echo "<a href=\"register.php\">Create New Account</a><BR>";
    }
  }
  echo "<div>";
  echo "Please enter e-mail to reset password<BR><BR>";
  echo "<form action=\"reset.php\" method=\"post\">\n";
  echo "eMail: ";
  echo "<input type=\"text\" name=\"email\"><BR>\n";
  echo "<input type=\"submit\" name=\"Request Reset\"><BR>\n";
  echo "</form>\n";
  echo "</div>\n";
  echo "</body>\n";
  echo "</html>";
}  elseif (isset($_POST["email"])) {
  // should not be required since angular should take of initial request request
  // will handle any users that inadvertantly bypass initial form in angular
  $username = $_POST["email"];
  //echo $username."<BR>\n";
  $isRegistered = isUserRegistered($_POST["email"]);

  if ($isRegistered) {
    $key = createUserKey($username);
    //echo $key;
    mailUserKey($username, $key);

    echo "<html>\n";
    echo "<head>\n<link rel=\"stylesheet\" href=\"assets\golflog.css\">\n";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
    echo "</head>\n";
    echo "<body>\n";
    echo "<img src=\"assets\GolflogLogo.png\">";
    echo "<div>";
    echo "A link to reset your password has been sent to your email<BR><BR>";
    echo "Please check your email to complete the reset process<BR>\n";
    echo "</div>\n";
    echo "</body>\n";
    echo "</html>";
  } else {
    header('Location: resetform.php?err=InvalidName');
  }
} elseif (isset($_GET['mail']) && isset($_GET['key'])) {
  $mailID = $_GET['mail'];
  $keyID = $_GET['key'];
  if (validateUserKey($mailID, $keyID)) {
    //Allow user to enter new password_conf
    echo "<html>\n";
    echo "<head>\n<link rel=\"stylesheet\" href=\"assets\golflog.css\">\n";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
    echo "</head>\n";
    echo "<body>\n";
    echo "<img src=\"assets\GolflogLogo.png\">";
    echo "<div>";
    if (isset($_GET['err'])) {
      $err = $_GET['err'];
      if ($err == "passMismatch") {
        echo "<neg_mesg>Sorry, Passwords did not match.</neg_mesg><BR>";
      } elseif ($err == "passNull") {
        echo "<neg_mesg>Sorry, Passwords cannot be null.</neg_mesg><BR>";
      }
    }
    echo "<form action=\"resetpass.php\" method=\"post\">\n";
    echo "Password: ";
    echo "<input type=\"password\" name=\"password\"><BR><BR>\n";
    echo "Confirm Password: ";
    echo "<input type=\"password\" name=\"password_conf\"><BR><BR>\n";
    echo "<input type=\"hidden\" id=\"mailID\" name=\"mail\" value=\"".$mailID."\">\n";
    echo "<input type=\"hidden\" id=\"keyID\" name=\"key\" value=\"".$keyID."\">\n";
    echo "<input type=\"submit\" name=\"Reset Password\"><BR>\n";
    echo "</form>\n";
    echo "</div>\n";
    echo "</body>\n";
    echo "</html>";
  } else {
    //otherwise let the user know their key is invalid
    //echo "email: ".$mailID."<BR>\n";
    //echo "emailed key: ".$keyID."<BR>\n";
    //echo "function: ".hash('gost',$mailID.date('z'));
    header('Location: reset.php?err=InvalidKey');
  }
}

//$conn->close();

?>
