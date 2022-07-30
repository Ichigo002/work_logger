<?php 
    $type_action = get("%typea%", null);

    switch($type_action) {
        case 'btn_new_log': // new log

            break;
        case 'btn_up_rate': // Update a rate

            break;
        case 'btn_ch_pass': // change password

            break;
        case 'btn_logout': // log out
            redirect(DEF_ADDRESS, array("pg_v" => "login/login", "stt" => "lgout"));
            break;        
    }
?>

