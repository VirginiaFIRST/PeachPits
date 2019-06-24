<?php
    include "includes/session.php";

    session_destroy();
    echo '<script type="text/javascript">window.location="index"</script>';
?>