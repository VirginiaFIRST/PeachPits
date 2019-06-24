<title>PeachPits - Signup</title>
<?php
    /*********************
    Signup page
    **********************/
        
    include "header.php";
    include "includes/password.php";
    $refer = $_GET['refer'];
    if(!isset($_POST['submit'])){
?>

<style>
    .formbox {
        width:300px;
    }
    @media screen and (max-width:340px) {
        .formbox {
            width:calc(100vw - 40px);
        }
    }
</style>
<div class="site-wrapper" style="height:calc(100vh - 330px)">
    <div class="site-wrapper-inner">
        <div class="cover-container">
            <div class="inner cover" style="color:#000000;">
                <div class="formbox signup text-center" style="margin:auto;">
                    <h3>Sign up</h3><br>
                    <fieldset>
                        <?php
                            if ($refer == '') {
                                echo '<form method="post" action="signup?event='.$currentEvent.'" id="registerForm">';
                            }
                            else {
                                echo '<form method="post" action="signup?refer='.$refer.'&event='.$currentEvent.'" id="registerForm">';
                            }
                        ?>
                            <p><input type="text" name="firstname" placeholder="First Name"style="border-radius:0px;" class="form-control"></p>
                            <p><input type="text" name="lastname" placeholder="Last Name"style="border-radius:0px;" class="form-control"></p>
                            <p><input type="text" name="email" placeholder="Email" style="border-radius:0px;" class="form-control"></p>
                            <p><input type="password" name="password" placeholder="Password"style="border-radius:0px;" class="form-control"></p>
                            <p><input type="password" name="repeatPassword" placeholder="Repeat Password" style="border-radius:0px;" class="form-control"></p>
                            <p><input type="submit" name="submit" value="Sign Up" class="btn btn-default"/></p>
                        </form>
                    </fieldset>                       
                    <p>Already have an account? <a href="signin">Sign in</a></p>
                    <br>
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

            if ($refer == 'peachtalk') {
                echo '<script>window.location="signin?event='.$currentEvent.'&refer='.$refer.'"</script></div>';
            }
            else {
                echo '<script>window.location="signin?event='.$currentEvent.'"</script></div>';
            }
        }
    }
    
    include "footer.php";
?>
