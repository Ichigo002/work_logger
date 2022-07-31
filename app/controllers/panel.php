<?php 
    $type_action = get("%typea%", null);

    switch($type_action) {
        case 'btn_new_log': // new log
            create_new_log();
            break;
        case 'btn_up_rate': // Update a rate
            update_rate();
            break;
        case 'btn_ch_pass': // change password
            change_password();
            break;
        case 'btn_logout': // log out
            redirect(DEF_ADDRESS, array("pg_v" => "login/login", "stt" => "lgout"));
            break;        
    }

    function create_new_log() {
        $start_t = get("st_time", null);
        $end_t = get("end_time", null);
        $date = get("date", null);

        echo $start_t.', '.$end_t.', '.$date;
    }

    function update_rate() {
        $new_rate = get("rate", null);

        echo $new_rate;
    }

    function change_password() {
        $old_p = get("old-pass", null);
        $new_p1 = get("pass1", null);
        $new_p2 = get("pass2", null);

        if($new_p1 != $new_p2) {
            redirect(DEF_ADDRESS, array("pg_v" => "signed/panel"));
            // KEEP GOING
        }
    }
?>

