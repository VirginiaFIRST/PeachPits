<title>PeachPits - Reset Password</title>
<?php
  include "header.php";
  include "includes/password.php";
  $validCode = false;
  $codeExpired = true;
  $email = "";
  $verificationCode = $_GET['prc'];
  $sql = $mysqli->query("SELECT * FROM `password_resets` WHERE `verification` = '$verificationCode'");
  $row = mysqli_fetch_assoc($sql);
  if ($row) {
    $validCode = true;
    $email = $row['email'];
    $now = new DateTime('now');
    $timeRequested = new DateTime($row['time_requested']);
    $timeDiff = $now->diff($timeRequested);
    $minutesDiff = $timeDiff->i;
    if ($minutesDiff > 300) {
      $sql = $mysqli->query("DELETE FROM `password_resets` WHERE `verification` = '$verificationCode'");
    } else {
      $codeExpired = false;
    }
  }
  if (isset($_POST['password'])) {
    // Form submitted
    $password = protect($_POST['password']);
    $repeatpassword = protect($_POST['repeatPassword']);
    $hashedPass = password_hash($password, PASSWORD_BCRYPT);
    $sql = $mysqli->query("UPDATE `users` SET `password` = '$hashedPass' WHERE `email` = '$email'");
    $sql = $mysqli->query("DELETE FROM `password_resets` WHERE `verification` = '$verificationCode'");
?>

<!-- Form Submission successful -->
<div class="site-wrapper" style="height: calc(100vh - 330px)">
  <div class="site-wrapper-inner">
    <div class="cover-container">
      <div class="inner cover" style="color:#000000;">
        <div class="formbox signin text-center" style="margin:auto;">
          <h2>Password Reset Successful</h2>
          <br>
          <br>
          <br>
          <h4>Your password has been updated.</h4>
          <a class="btn btn-default" href="signin">Click here to login</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  } else {
    // Form not submitted
    if ($validCode && !$codeExpired) {
      // display form
?>

<!-- Form -->
<div class="site-wrapper" style="height: calc(100vh - 330px)">
  <div class="site-wrapper-inner">
    <div class="cover-container">
      <div class="inner cover" style="color:#000000;">
        <div class="formbox signup text-center" style="margin:auto;">
          <h3>Enter your new password</h3>
          <br>
          <fieldset>
            <?php echo '<form method="post" action="new-password?prc='.$verificationCode.'" id="resetPasswordForm">'; ?>
              <p><input type="password" id="password" name="password" placeholder="Password" style="border-radius:0px;" class="form-control" oninput="validatePasswordForm()"></p>
              <p><input type="password" id="repeatPassword" name="repeatPassword" placeholder="Repeat Password" style="border-radius:0px;" class="form-control" oninput="validatePasswordForm()"></p>
              <p style="display:none;color:red;" id="error-text"></p>
              <p><button class="btn btn-default" id="submit-button" onclick="submitForm()" disabled>Reset Password</button></p>
            </form>
          </fieldset>
          <?php
            echo '<p>Know your password? <a href="signin?event='.$currentEvent.'">Sign in</a></p>';
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function submitForm() {
    if (validatePasswordForm()) {
      document.getElementById("resetPasswordForm").submit();
    }
  }
  function validatePasswordForm() {
    let allValid = true;
    let password = document.getElementById("password").value;
    let repeatPW = document.getElementById("repeatPassword").value;
    let errorText = document.getElementById("error-text");
    if (!password || !repeatPW) {
      errorText.innerText = "Please complete all of the required fields";
      allValid = false;
    } else {
      if (password != repeatPW) {
        errorText.innerText = "Your confirmed password does not match your initial password";
        allValid = false;
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
      if ((event.keyCode == 13) && (validatePasswordForm() == false)) {
        event.preventDefault();
        return false;
      }
    });
  });
</script>

<?php
    } else {
      if (!$validCode) {
        // display invalid code error
?>

<!-- Display invalid code error -->
<div class="site-wrapper" style="height: calc(100vh - 330px)">
  <div class="site-wrapper-inner">
    <div class="cover-container">
      <div class="inner cover" style="color:#000000;">
        <div class="formbox signin text-center" style="margin:auto;">
          <h2>Error: Invalid Code Provided</h2>
          <br>
          <br>
          <br>
          <h4>An invalid verification code was provided. Ensure the URL was entered correctly. Click the button below if this problem persists.</h4>
          <br>
          <a class="btn btn-primary" href="contact">Contact Us</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
      } else if ($codeExpired) {
        // display code expired error
?>

<!-- Display code expired error -->
<div class="site-wrapper" style="height: calc(100vh - 330px)">
  <div class="site-wrapper-inner">
    <div class="cover-container">
      <div class="inner cover" style="color:#000000;">
        <div class="formbox signin text-center" style="margin:auto;">
          <h2>Error: Link Expired</h2>
          <br>
          <br>
          <br>
          <h4>This link to reset the password has expired. <a href="reset-password">Click here to resend the verification email.</a> Click the button below if this problem persists.</h4>
          <br>
          <a class="btn btn-primary" href="contact">Contact Us</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
      }
    }
  }

  include "footer.php";
?>