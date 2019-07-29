<title>PeachPits - Signup</title>
<?php
  include "header.php";
  include "includes/password.php";
  $refer = $_GET['refer'];
  // If no submission has been made then display the form
  if (!isset($_POST['firstname'])) {
?>

<div class="site-wrapper" style="height:calc(100vh - 330px)">
  <div class="site-wrapper-inner">
    <div class="cover-container">
      <div class="inner cover" style="color:#000000;">
        <div class="formbox signup text-center" style="margin:auto;">
          <h3>Sign up</h3>
          <br>
          <fieldset>
            <?php
              if ($refer == '') {
                echo '<form method="post" action="signup?event='.$currentEvent.'" id="registerForm">';
              }
              else {
                echo '<form method="post" action="signup?refer='.$refer.'&event='.$currentEvent.'" id="registerForm">';
              }
            ?>
              <p><input type="text" name="firstname" placeholder="First Name" style="border-radius:0px;" class="form-control" id="firstname" oninput="validateForm()"></p>
              <p><input type="text" name="lastname" placeholder="Last Name" style="border-radius:0px;" class="form-control" id="lastname" oninput="validateForm()"></p>
              <p><input type="text" name="email" placeholder="Email" style="border-radius:0px;" class="form-control" id="email" oninput="validateForm()"></p>
              <p><input type="password" name="password" placeholder="Password" style="border-radius:0px;" class="form-control" id="password" oninput="validateForm()"></p>
              <p><input type="password" name="repeatPassword" placeholder="Repeat Password" style="border-radius:0px;" class="form-control" id="repeatPassword" oninput="validateForm()"></p>
              <p style="display:none;color:red;" id="error-text"></p>
              <p><button class="btn btn-default" id="submit-button" onclick="submitForm()" disabled>Sign Up</button></p>
            </form>
          </fieldset>                       
          <p>Already have an account? <a href="signin">Sign in</a></p>
          <br>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function submitForm() {
    if (validateForm()) {
      document.getElementById("registerForm").submit();
    }
  }
  function validateForm() {
    let allValid = true;
    let firstName = document.getElementById("firstname").value;
    let lastName = document.getElementById("lastname").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let repeatPW = document.getElementById("repeatPassword").value;
    let errorText = document.getElementById("error-text");
    if (!firstName || !lastName || !email || !password || !repeatPW) {
      errorText.innerText = "Please complete all of the required fields";
      allValid = false;
    } else {
      let regexEmail = /^[a-z0-9]+([_.-][a-z0-9]+)*@([a-z0-9]+([.-][a-z0-9]+)*)+.[a-z]{2,}$/i;
      if (!regexEmail.test(email)) {
        errorText.innerText = "E-mail is not in name@domain format!";
        allValid = false;
      } else {
        if (password != repeatPW) {
          errorText.innerText = "Your confirmed password does not match your initial password";
          allValid = false;
        }
      }
    }
    document.getElementById("submit-button").disabled = !allValid;
    if (allValid) {
      errorText.style.display = "none";
    } else {
      errorText.style.display = "block";
    }
    return allValid;
  }
  $(document).ready(function() {
    $(window).keydown(function(event) {
      if ((event.keyCode == 13) && (validateForm() == false)) {
        event.preventDefault();
        return false;
      }
    });
  });
</script>

<?php
  } else {
    //Collects form submission and sets variables
    $firstname = protect($_POST['firstname']);
    $lastname = protect($_POST['lastname']);
    $email = protect($_POST['email']);
    $password = protect($_POST['password']);
    $repeatpassword = protect($_POST['repeatPassword']);
    $role = "User";
    $events = "No Event";

    //Form Validation
    $errors = array();
    $regex = "/^[a-z0-9]+([_.-][a-z0-9]+)*@([a-z0-9]+([.-][a-z0-9]+)*)+.[a-z]{2,}$/i";
    if (!preg_match($regex, $email)) {
      $errors[] = "E-mail is not in name@domain format!";
    }
    if (!$firstname || !$lastname || !$email || !$password || !$repeatpassword) {
      $errors[] = "You did not fill out the required fields";
    }
    if ($password != $repeatpassword){
      $errors[] = "Your confirmed password does not match you initial password";
    }

    $sql = $mysqli->query("SELECT * FROM `users` WHERE `email`='{$email}'"); 
    if (mysqli_num_rows($sql) > 0 || count($errors) > 0) {
      if (mysqli_num_rows($sql) > 0) {
        echo '<script type="text/javascript">;
        alert("Email already in use. Type in your email to reset your password.");
        window.location="reset-password"</script>';
      } else {
        echo '<script type="text/javascript">;
        alert("Sorry, there was an error creating your account. Please try again.");
        window.location="signup"</script>';
      }
    } else {
      //If everything is good, add a new user to the database
      $hashedPass = password_hash($password, PASSWORD_BCRYPT);

      $verificationCode = md5(uniqid("firstrobotics", true));
      $verificationLink = "http://gafirst.org/peachpits/activate?vc=" . $verificationCode;
      $username = $firstname . " " . $lastname;

      $body = "";
      $body .= "Hi " . $username . ",<br><br>";

      $body .= "Please click the link below to verify your PeachPits account.";
      $body .= "<br><br><br>";
      $body .= "<a href='{$verificationLink}' target='_blank' style='padding:1em; font-weight:bold; background-color:#DC7633; color:#fff;'>Verify My Email Address</a>";
      $body .= "<br><br><br>";
      $body .= "Can't verify your email by clicking the button? Copy and paste this URL into your browser to verify your email: ";
      $body .= "<a href='" . $verificationLink . "'>" . $verificationLink . "</a>";
      $body .= "<br><br>";
      $body .= "Enjoy using PeachPits!";
      $body .= "<br><br><br>";
      $body .= "Need help? Visit <a href='http://gafirst.org/peachpits/contact'>http://gafirst.org/peachpits/contact</a>";

      $name = "PeachPits";
      $email_sender = "no-reply@peachpits.com";
      $subject = "Verification Link | PeachPits Account";
      $recipient_email = $email;

      $headers  = "MIME-Version: 1.0\r\n";
      $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
      $headers .= "From: {$name} <{$email_sender}> \n";

      $sql = $mysqli->query("INSERT into `users` (`firstname`,`lastname`, `email`, `password`, `role`, `events`, `verification`, `verificationstatus`)
          VALUES ('$firstname','$lastname', '$email', '$hashedPass', '$role', '$events', '$verificationCode', 'Pending');");

      if (mail($recipient_email, $subject, $body, $headers)) {
        // Tell the user a verification email was sent
?>

<div class="site-wrapper" style="height: calc(100vh - 330px)">
  <div class="site-wrapper-inner">
    <div class="cover-container">
      <div class="inner cover" style="color:#000000;">
        <h3>Verification Email Sent</h3>
        <h4>An email was sent to <b><?php echo $email; ?></b>. Follow the directions in the email to verify your account.</h4>
        <br>
        <h4>Check your spam folder if you don't see the email.</h4>
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
  }
  
  include "footer.php";
?>
