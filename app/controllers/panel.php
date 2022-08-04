<?php 
    $type_action = get("%typea%", null);

    // ERROR TYPES:
    
    // 101 -> Error inserting new log data into the database :(

    // 301 -> Old Password field doesn't fit to current password
    // 302 -> New Passwords are not same

    // 401 -> Bad old Password to delete;
    // 402 -> Incorrect Day Number Typed;

    // 502 -> Incorrect Day Number Typed;

    // 100 -> Success Add New Log!
    // 200 -> Success Update A Rate!
    // 300 -> Success Change Password!
    // 400 -> Success Delete A Log!
    // 500 -> Success Update A Log!
    
    $_id = decryptID(get("id"));
    $_db = new Database();

    switch($type_action) {
        case 'btn_new_log': // new log
            create_new_log($_id, $_db);
            break;
        case 'btn_up_rate': // Update a rate
            update_rate($_id, $_db);
            break;
        case 'btn_ch_pass': // change password
            change_password($_id, $_db);
            break;
        case 'btn_logout': // log out
            redirect(DEF_ADDRESS, array("pg_v" => "login/login", "stt" => "lgout"));
            break;   
        case "btn_del_log": // delete log
            del_log($_id, $_db);
            break;
        case "show_desc": // show description of log
            show_descrpition($_id, $_db);
            break;
        case "btn_load_up_log": // load data to edit from update form
            load_update_form_data($_id, $_db);
            break;
        case "btn_up_log":
            updateLog($_id, $_db);
            break;
        default: 
            redirect(DEF_ADDRESS, array("pg_v" => "PANEL_CONTROLLER_COULD_NOT_FIND:___".$type_action."___"));
        break;
    }

    function updateLog($id, $db) {

        if(get("cancel") == "Cancel") {
            redirect(DEF_ADDRESS, array(
                "pg_v" => "signed/panel", 
                "idu" => get("id"))); 
            return 0;
        }

        $uplog_sttime = $db->safety_str(get("st_time", ""));
        $uplog_etime = $db->safety_str(get("end_time", ""));
        $uplog_desc = $db->safety_str(get("txt", ""));
        $uplog_date = $db->safety_str(get("date", ""));
        $day = $db->safety_str(get("day", ""));

        $record = get_record_by_day($id, $day);
        $rec_id = $record['w_id'];

        $sql = "UPDATE `work_logs` SET `w_start`='$uplog_sttime',`w_end`='$uplog_etime',`w_date`='$uplog_date',`w_desc`='$uplog_desc' WHERE `w_id` = $rec_id";

        if($db->query($sql)) {
            redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "500"));
        }
    }

    function load_update_form_data($id, $db) {
        $day = get("day_no", null);

        $record = get_record_by_day($id, $day);

        if($record == null) {
            // Incorrect Day Number
            redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "502"));
            return -1;
        }

        $rec_id = $record['w_id'];
        $sql = "SELECT * FROM `work_logs` WHERE `w_id` = $rec_id";

        if($db->query($sql)) {
            while($row = $db->get_single_row()) {
                $uplog_sttime = $row['w_start'];
                $uplog_etime  = $row['w_end'];
                $uplog_desc   = $row['w_desc'];
                $uplog_date   = $row['w_date'];

                redirect(DEF_ADDRESS, array(
                "pg_v" => "signed/panel", 
                "idu" => get("id"), 
                "sttime" => $uplog_sttime,
                "etime" => $uplog_etime,
                "desc" => $uplog_desc,
                "date" => $uplog_date,
                "day" => $day,
                "ready" => "1",));                
                return -1;
            }
        }
    }

    function show_descrpition($id, $db) {
        $day = get("day", null);

        $desc = "NONE DESCRIPTION";

        $sql = "SELECT `w_desc` FROM `work_logs` WHERE `w_day` = $day  AND `w_user` = $id" ;

        if($db->query($sql)) {
            while($row = $db->get_single_row()) {
                $desc = $row['w_desc'];
                redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "shd" => $desc));
                return -1;
            }
        }
    }

    function del_log($id, $db) {
        $pass = $db->safety_str(get("pass", null));
        $day_no = $db->safety_str(get("nod", null));

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

        $record = get_record_by_day($id, $day_no);

        if($record == null) {
            redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "402"));
            return -1;
        }
        $rec_id = $record['w_id'];

        $sql = "DELETE FROM `work_logs` WHERE `w_id` = $rec_id";

        if($db->query($sql)) {
            redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "400"));
            return 0;
        } else {
            return -1;
        }
    }

    function create_new_log($id, $db) {
        
        $start_t = $db->safety_str(get("st_time", null));
        $end_t = $db->safety_str(get("end_time", null));
        $date = $db->safety_str(get("date", null));
        $desc = $db->safety_str(get("txt", null));

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

    function update_rate($id, $db) {
        $new_rate = $db->safety_str(intval(get("rate", null)));

        $sql = "UPDATE `users` SET `usr_bid` = $new_rate WHERE `usr_id` = $id;";

        if($db->query($sql)) {
            redirect(DEF_ADDRESS, array("pg_v" => "signed/panel", "idu" => get("id"), "err" => "200"));
        } else {

            return "ERROR UPDATE A RATE";
        }
    }

    function change_password($id, $db) {
        $old_p = $db->safety_str(get("old-pass", null));
        $new_p1 = $db->safety_str(get("pass1", null));
        $new_p2 = $db->safety_str(get("pass2", null));

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

