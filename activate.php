<title>PeachPits - Activate</title>
<?php 
  include "header.php";
  $verificationCode = $_GET['vc'];
  $sql = $mysqli->query("SELECT * FROM `users` WHERE `verification`='$verificationCode'");
  $row = mysqli_fetch_assoc($sql);
  if ($row) {
    // valid code
    $verificationStatus = $row['verificationstatus'];
    $sql = $mysqli->query("UPDATE `users` SET `verificationstatus` = 'Verified' WHERE `verification`='$verificationCode'");
?>

<div class="container content">
  <div class="row">
    <div class="col-md-12 text-center">
      <h2>Verification Successful</h2>
      <br>
      <br>
      <br>
      <h4>Your email addess has been successfully verified. Click the button the below to sign in.</h4>
      <br>
      <a class="btn btn-primary" href="signin">Continue to Sign In</a>
    </div>
  </div>
</div>

<?php } else { // invalid code ?>

<div class="container content">
  <div class="row">
    <div class="col-md-12 text-center">
      <h2>Error: Verification Unsuccessful</h2>
      <br>
      <br>
      <br>
      <h4>An invalid verification code was provided. Ensure the URL was entered correctly. Visit the link below if this problem persists.</h4>
      <br>
      <a class="btn btn-primary" href="contact">Contact Us</a>
    </div>
  </div>
</div>

<?php 
  }
  include "footer.php";
?>