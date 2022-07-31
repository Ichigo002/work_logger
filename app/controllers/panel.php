<?php 
    $type_action = get("%typea%", null);

    // ERROR TYPES:
    
    // 101 -> End time of work must be later than start time of work

    // 301 -> Old Password field doesn't fit to current password
    // 302 -> New Passwords are not same

    // 200 -> Success Update A Rate!
    // 300 -> Success Change Password!
    
    $_id = decryptID(get("id"));

    switch($type_action) {
        case 'btn_new_log': // new log
            create_new_log($_id);
            break;
        case 'btn_up_rate': // Update a rate
            update_rate($_id);
            break;
        case 'btn_ch_pass': // change password
            change_password($_id);
            break;
        case 'btn_logout': // log out
            redirect(DEF_ADDRESS, array("pg_v" => "login/login", "stt" => "lgout"));
            break;        
    }

    function create_new_log($id) {
        $start_t = get("st_time", null);
        $end_t = get("end_time", null);
        $date = get("date", null);
        $desc = get("txt", null);

        echo $start_t.', '.$end_t.', '.$date;
    }

    function update_rate($id) {
        redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "300"));
        
    }

    function change_password($id) {
        $old_p = get("old-pass", null);
        $new_p1 = get("pass1", null);
        $new_p2 = get("pass2", null);

        $db = new Database();

        $sql = "SELECT `usr_password` FROM `users` WHERE `usr_id` = $id";

        if($db->query($sql)) {
            while($row = $db->get_single_row()) {
                if($row['usr_password'] == $old_p) {

                    if($new_p1 != $new_p2) {
                        // Not same pass
                        redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "302"));
                        return -1;
                    }

                    $sql = "UPDATE `users` SET `usr_password`='$new_p1' WHERE `usr_id` = $id;";
                    if($db->query($sql)) {
                        // Success change password!
                        redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "300"));
                        return 0;
                    }
                } else {
                    // Bad old pass
                    redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "301"));
                    return -1;
                }
            }
        } else {
            return "ERROR CONNECT TO DATABASE";
        }
    }
?>

