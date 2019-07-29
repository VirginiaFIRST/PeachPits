<?php
  /*********************
  Processes change password requests
  **********************/
  
  include dirname(__DIR__) . "/header.php";
  include "includes/password.php";

  //Get data from form and send to variable
  $newPassword = protect($_POST['newpassword']);
  $confirmNewPassword = protect($_POST['confirmnewpassword']);

  //Form Validation
  $errors = array();

  if (!$newPassword || !$confirmNewPassword) {
    $errors[] = "You did not fill out the required fields";
  }

  //Make sure password and confirmed password are the same
  if ($newPassword != $confirmNewPassword) {
    $errors[] = "Your confirmed password does not match you initial password";
  }

  if (count($errors) > 0) {
    echo '<script type="text/javascript">;
    alert("Sorry, there was an error creating your account. Please try again.");
    window.location="/peachpits/admin/dashboard"</script>';
  } else {
    //If everything is good send to database
    $hashedPass = password_hash($newPassword, PASSWORD_BCRYPT);

    $sql = "UPDATE `users`SET password = '$hashedPass' WHERE email = '$sessionEmail';";
    $query = $mysqli->query($sql);

    echo '<script type="text/javascript">window.location="/peachpits/admin/dashboard"</script></div>';
  }

?>