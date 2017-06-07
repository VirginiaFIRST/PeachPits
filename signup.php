<?php
    /*********************
    Signup page
    **********************/
        
    include "header.php";
    include "includes/password.php";
    if(!isset($_POST['submit'])){
?>

<head>
    <style>
        html,
        body {
            height: calc(100% - 110px);
        }
    </style>
</head>

<div class="site-wrapper">
    <div class="site-wrapper-inner">
        <div class="cover-container">
            <div class="inner cover" style="color:#000000;">
                <div class="formbox signup text-center">
                    <h3>Sign up</h3>
                    <br>
                    <center>
                    <fieldset>
                        <form method="post" action="signup.php" id="registerForm">
                            <p>
                                <input type="text" name="firstname" placeholder="First Name"style="width: 300px;border-radius:0px;" class="form-control">
                            </p>
                            <p>
                                <input type="text" name="lastname" placeholder="Last Name"style="width: 300px;border-radius:0px;" class="form-control">
                            </p>
                            <p>
                                <input type="text" name="email" placeholder="Email" style="width:300px;border-radius:0px;" class="form-control">
                            </p>
                            <p>
                                <input type="password" name="password" placeholder="Password"style="width: 300px;border-radius:0px;" class="form-control">
                            </p>
                            <p>
                                <input type="password" name="repeatPassword" placeholder="Repeat Password"style="width: 300px;border-radius:0px;" class="form-control">
                            </p>
                            <p>
                                <center><input type="submit" name="submit" value="Sign Up" class="btn btn-default"/></center>
                            </p>
                        </form>
                    </fieldset>
                    </center>
                        
                    <p>Already have an account? <a href="signin.php">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    }
    else{
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
        if(!preg_match($regex, $email)){
            $errors[] = "E-mail is not in name@domain format!";
        }
        if(!$firstname || !$lastname || !$email || !$password || !$repeatpassword){
            $errors[] = "You did not fill out the required fields";
        }
        if ($password != $repeatpassword){
            $errors[] = "Your confirmed password does not match you initial password";
        }

        $sql = $mysqli->query("SELECT * FROM `users` WHERE `email`='{$email}'"); 
        if(mysqli_num_rows($sql) > 0){
            $errors[] = "Email already in use, please try another";
        }

        if(count($errors) > 0){
            echo "The following errors occured with your registration";
            echo '<font color="red">';
            foreach($errors AS $error){
                echo $error . "\n";
            }
            echo "</font>";
            echo '<a href="javascript:history.go(-1)">Try again</a>';
        }

        else{
            //If everything is good, add a new user to the database
            $hashedPass = password_hash($password, PASSWORD_BCRYPT);
    
            $sql = $mysqli->query("INSERT into `users`(`firstname`,`lastname`, `email`, `password`, `role`, `events`)
            VALUES ('$firstname','$lastname', '$email', '$hashedPass', '$role', '$events');");

            echo '<script>window.location="signin.php"</script></div>';
        }
    }
    
    include "footer.php";
?>
