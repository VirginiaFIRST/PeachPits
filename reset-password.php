<title>PeachPits - Signin</title>
<?php
  include "header.php";
  $validEmail = false;
  $username = "";
  if (isset($_POST['submit'])) {
    $email = protect($_POST['email']);
    if ($email) {
      $sql = $mysqli->query("SELECT * FROM `users` WHERE `email`='$email'");
      $row = mysqli_fetch_assoc($sql);
      if ($row) {
        $validEmail = true;
        $username = $row['firstname'] . " " . $row['lastname'];
      }
    }
  }
  // Show form if not submitted or not email not found
  if (!isset($_POST['submit']) || !$validEmail) {
?>

<div class="site-wrapper" style="height: calc(100vh - 330px)">
  <div class="site-wrapper-inner">
    <div class="cover-container">
      <div class="inner cover" style="color:#000000;">
        <div class="formbox signup text-center" style="margin:auto;">
          <h3>Password Reset</h3>
          <h4>Enter the email you use for PeachPits, and we'll send you instructions to reset your password.</h4>
          <br>
          <fieldset>
            <?php
              echo '<form method="post" action="reset-password?event='.$currentEvent.'">';
              if (isset($_POST['submit']) && !$validEmail) {
                echo '<div class="alert alert-danger" role="alert"><p>No user found with that email</p></div>';
              }
            ?>
              <p><input type="text" name="email" placeholder="Email" style="border-radius:0px;" class="form-control"></p>
              <p>
                <a class="btn btn-default" href="signin">Back</a>
                <input type="submit" name="submit" value="Send Password Reset Email" class="btn btn-default"/>
              </p>
            </form>
          </fieldset>
          <?php
            echo '<p>Know your password? <a href="signin?event='.$currentEvent.'">Sign in</a></p>';
          ?>
          <br>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  } else {
    // Form submitted
    $verificationCode = md5(uniqid("roboticsfirst", true));
    $verificationLink = "http://gafirst.org/peachpits/new-password?prc=" . $verificationCode;

    $body = "";
    $body .= "Hi " . $username . ",<br><br>";
    $body .= "Please click the link below to reset your password.";
    $body .= "<br><br><br>";
    $body .= "<a href='{$verificationLink}' target='_blank' style='padding:1em; font-weight:bold; background-color:#DC7633; color:#fff;'>Choose a new password</a>";
    $body .= "<br><br><br><br>";
    $body .= "If you didn't meant to reset your password, then you can just ignore this email; your password will not change.";
    $body .= "<br><br><br>";
    $body .= "Enjoy using PeachPits!";
    $body .= "<br><br><br>";
    $body .= "Trouble clicking the button? Copy and paste this URL into your browser: ";
    $body .= "<a href='" . $verificationLink . "'>" . $verificationLink . "</a>";

    $name = "PeachPits";
    $email_sender = "no-reply@peachpits.com";
    $subject = "Reset Password Link | PeachPits Account";
    $recipient_email = $email;

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers .= "From: {$name} <{$email_sender}> \n";

    if (mail($recipient_email, $subject, $body, $headers)) {
      $sql = $mysqli->query("INSERT `password_resets` SET `verification` = '$verificationCode' WHERE `email` = '$email'");
      $sql = $mysqli->query("INSERT into `password_resets`(`email`,`verification`) VALUES ('$email','$verificationCode');");
      // Tell user the verification email was sent
?>

<div class="site-wrapper" style="height: calc(100vh - 330px)">
  <div class="site-wrapper-inner">
    <div class="cover-container">
      <div class="inner cover" style="color:#000000;">
        <h3>Password Reset Email Sent</h3>
        <h4>An email was sent to <b><?php echo $email; ?></b>. Follow the directions in the email to reset your password.</h4>
        <br>
        <a href="signin" class="btn btn-default">Return to Sign In</a>
        <br>
      </div>
    </div>
  </div>
</div>

<?php
    } else {
      die("Sending failed.");
    }
  }
  include "footer.php";
?>