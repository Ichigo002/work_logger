<?php 
    $type_action = get("%typea%", null);

    // ERROR TYPES:
    
    // 101 -> Error inserting new log data into the database :(

    // 301 -> Old Password field doesn't fit to current password
    // 302 -> New Passwords are not same

    // 401 -> Bad old Password to delete;
    // 402 -> Incorrect Day Number Typed;

    // 100 -> Success Add New Log!
    // 200 -> Success Update A Rate!
    // 300 -> Success Change Password!
    // 400 -> Success Delete A Log!
    
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
        case "btn_del_log": // delete log
            del_log($_id);
            break;
        case "show_desc": // show description of log
            show_descrpition($_id);
            break;
        default: 
            redirect(DEF_ADDRESS, array("pg_v" => "PANEL_CONTROLLER_COULD_NOT_FIND:___".$type_action."___"));
        break;
    }

    function show_descrpition($id) {
        $day = get("day", null);

        $desc = "NONE DESCRIPTION";
        $db = new Database();

        $sql = "SELECT `w_desc` FROM `work_logs` WHERE `w_day` = $day";

        if($db->query($sql)) {
            while($row = $db->get_single_row()) {
                $desc = $row['w_desc'];
                redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "shd" => $desc));
                return -1;
            }
        }
    }

    function del_log($id) {
        $pass = get("pass", null);
        $day_no = get("nod", null);

        $db = new Database();

        $sql = "SELECT `usr_password` FROM `users` WHERE `usr_id` = $id";

        if($db->query($sql)) {
            while($row = $db->get_single_row()) {
                if($row['usr_password'] != $pass) {
                    // Bad old pass
                    redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "401"));
                    return -1;
                }
            }
        } else {
            return "ERROR CONNECT TO DATABASE ";
        }

        //Check does typed day exist

        $sql = "SELECT * FROM `work_logs` WHERE `w_day` = $day_no";
        $exist = false;

        if($db->query($sql)) {
            while($db->get_single_row()) {
                $exist = true;
            }
        }

        //Delete Log

        if(!$exist) {
            redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "402"));
            return -1;
        }

        $sql = "DELETE FROM `work_logs` WHERE `w_day` = $day_no";

        if($db->query($sql)) {
            redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "400"));
            return 0;
        } else {
            return -1;
        }
    }

    function create_new_log($id) {
        $start_t = get("st_time", null);
        $end_t = get("end_time", null);
        $date = get("date", null);
        $desc = get("txt", null);

        $db = new Database();
        $count = $db->count_table("work_logs", "`w_user` = $id") + 1;

        $sql = "INSERT INTO `work_logs`(`w_id`, `w_day`, `w_start`, `w_end`, `w_date`, `w_desc`, `w_user`) VALUES (NULL, $count,'$start_t','$end_t','$date','$desc', $id)";
    
        if($db->query($sql)) {
            redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "100"));
            return 0;
        } else {
            redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "101"));
            return -1;
        }
    }

    function update_rate($id) {
        $new_rate = intval(get("rate", null));

        $db = new Database();

        $sql = "UPDATE `users` SET `usr_bid` = $new_rate WHERE `usr_id` = $id;";

        if($db->query($sql)) {
            redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "200"));
        } else {

            return "ERROR UPDATE A RATE";
        }
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

