<?php    
    include dirname(__DIR__) . "/header.php";
	
    /*

    random number = (int) random (-30 sec to 30 sec)
    time interval = 5 * 60 sec + random number
    if (last updated time && last updated time != 00 {
        last updated time = the time right now
    }
    breakflag = false
    if (NOT breakflag and current time > time interval + last updated time) {
        call the javascript populate function
        if (the javascript scraped the matches successfully) {
            breakflag = true
        }
        last updated time = the time right now
    } 


    Server Side

    get sql of all events and get start date
    today = get today's date
    todayArr;
    while looping through sql {
        if row eventstartdate = today {
            todayArr [] = row eventid
        }
    }

    every five minutes {
        foreach(todayArr as eventid){
            call the javascript populate function, pass through eventid
            if eventid_matches is not empty {
                remove eventid from todayArr
            }
        }
    }
    

    */
?>