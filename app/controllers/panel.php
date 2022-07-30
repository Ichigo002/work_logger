<?php 
    $type_action = get("%typea%", null);

    switch($type_action) {
        case 'btn_new_log': // new log
            echo "New log";
            // same thing like below
            break;
        case 'btn_up_rate': // Update a rate
            echo "Update A Rate to: ".get("rate");
            // same thing like below
            break;
        case 'btn_ch_pass': // change password
            echo "Change Password to: ".get("pass1");
            // make new small window with change password by JS and AJAX Jquery to send data to panel.php controller
            // load window by JS from other file. NOT In the js [Optional]
            break;
        case 'btn_logout': // log out
            redirect(DEF_ADDRESS, array("pg_v" => "login/login", "stt" => "lgout"));
            break;        
    }
?>

