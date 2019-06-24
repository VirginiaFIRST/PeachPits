<?php
    /*********************
    Changes the refresh time
    **********************/
    include "header.php"; 

    $_SESSION['refreshTime'] = $_GET['newRefreshTime'];

    echo $_SESSION['refreshTime'];

    if ($_GET['returnLocation'] == "display") {
        echo '<script>window.location="display?event='.$currentEvent.'"</script>'; 
    }
    if ($_GET['returnLocation'] == "teams") {
        echo '<script>window.location="teams?event='.$currentEvent.'"</script>'; 
    }
    if ($_GET['returnLocation'] == "matches") {
        echo '<script>window.location="matches?event='.$currentEvent.'"</script>'; 
    }
?>