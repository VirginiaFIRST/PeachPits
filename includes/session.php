<?php
    //Starts the session when a user is logged in
    session_start();
    include  (dirname(__DIR__) .  "/includes/config.php");

    function loggedOn()
    {
        return isset($_SESSION['email']);
    }
    
?>