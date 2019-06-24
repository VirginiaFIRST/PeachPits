<title>PeachPits - Signin</title>
<?php
    include "header.php"; 
    include "includes/password.php";
    /*********************
    Signin page
    **********************/
    $refer = $_GET['refer'];
    //If no submission has been made then display the form
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
<div class="site-wrapper" style="height: calc(100vh - 330px)">
    <div class="site-wrapper-inner">
        <div class="cover-container">
            <div class="inner cover" style="color:#000000;">
                <div class="formbox signin text-center" style="margin:auto;">
                    <h3>Sign in</h3><br>
                    <fieldset>
                        <?php 
                            if ($refer == '') { 
                                echo '<form method="post" action="signin?event='.$currentEvent.'">';
                            }
                            else { 
                                echo '<form method="post" action="signin?refer='.$refer.'&event='.$currentEvent.'">';
                            }
                        ?>
                            <p><input type="text" name="email" placeholder="Email" style="border-radius:0px;" class="form-control"></p>
                            <p><input type="password" name="password" placeholder="Password"style="border-radius:0px;" class="form-control"></p>   
                            <p><input type="submit" name="submit" value="Sign In" class="btn btn-default"/></p>
                        </form>
                    </fieldset>
                    <?php 
                        if ($refer == '') { 
                            echo '<p>Don\'t have an account? <a href="signup?event='.$currentEvent.'">Sign up</a></p>';
                        }
                        else { 
                            echo '<p>Don\'t have an account? <a href="signup?refer='.$refer.'&event='.$currentEvent.'">Sign up</a></p>';
                        }
                    ?>
                    <br>
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
                
                if ($refer == 'peachtalk') {
                    echo '<script type="text/javascript">window.location="peachtalk/peachtalk-home?event='.$currentEvent.'"</script>';
                }
                else {
                    echo '<script type="text/javascript">window.location="admin/dashboard?event='.$row['eventid'].'"</script>';
                }
            }
            
            //If incorrect, send back to signin
            else{
                echo '<script type="text/javascript">;
                alert("Email and password combination is incorrect!");
                window.location="signin"</script>';
            }
        }
        else{			
            echo '<script type="text/javascript">;
            alert("You need to gimme a username AND password!!");
            window.location="signin"</script>';
        }
    }
    
    include "footer.php";
?>