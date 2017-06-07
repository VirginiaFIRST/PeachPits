<?php
    include "header.php"; 
    include "includes/password.php";
    /*********************
    Signin page
    **********************/
    
    //If no submission has been made then display the form
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
                <div class="formbox signin text-center">
                    <h3>Sign in</h3>
                    <br>
                    <center>
                    <fieldset>
                        <form method="post" action="signin.php">
                            <p>
                                <input type="text" name="email" placeholder="Email" style="width:300px;border-radius:0px;" class="form-control">
                            </p>
                            <p>
                                <input type="password" name="password" placeholder="Password"style="width: 300px;border-radius:0px;" class="form-control">
                            </p>   
                            <p>
                                <center><input type="submit" name="submit" value="Sign In" class="btn btn-default"/></center>
                            </p>
                        </form>
                    </fieldset>
                    </center>
                    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    }
    else{
        //Once a form has been submitted collect the data 
        $email = protect($_POST['email']);
        $pass = protect($_POST['password']);
        $hashedPass = password_hash($pass, PASSWORD_BCRYPT);
    
        if($email){
            $sql = $mysqli->query("SELECT * FROM `users` WHERE `email`='$email'");
            $row = mysqli_fetch_assoc($sql);
            
            //Verify that the email and password match and if correct send to dashboard page
            $hash = $row['password'];
            if(password_verify($pass, $hash)){
                $_SESSION['email'] = $row['email'];     
                $events = $row['events'];
                $eArr = explode(';',$events);
                $eventStr = $eArr[0];
                $arr = explode('@',$eventStr);
                $eventName = $arr[1];
                $sql = $mysqli->query("SELECT `eventid` FROM `events` WHERE `eventname` LIKE '$eventName'");
                $row = mysqli_fetch_assoc($sql);
                
                echo '<script type="text/javascript">window.location="admin/dashboard.php?event=' . $row['eventid'] . '"</script>';
            }
            
            //If incorrect, send back to signin
            else{
                echo '<script type="text/javascript">;
                alert("Email and password combination is incorrect!");
                window.location="signin.php"</script>';
            }
        }
        else{			
            echo '<script type="text/javascript">;
            alert("You need to gimme a username AND password!!");
            window.location="signin.php"</script>';
        }
    }
    
    include "footer.php";
?>