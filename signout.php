<?php
    include "includes/session.php";
 
    if(loggedOn())
    {
        session_destroy();
        echo '<script type="text/javascript">window.location="index"</script>';
    }
?>