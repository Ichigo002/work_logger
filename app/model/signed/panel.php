<?php 
    $min_table_columns = 16;
    $max_leng_desc = 15;


    $usrname = "NONE";
    $rate = "--";
    $table_cnt = get_db_data($min_table_columns);
    $error = get("err", null);
    $desc = get("shd", "");

    $today = date("Y-m-d");

    function get_usr_id() {
        return decryptID(get("idu"));
    }

    function get_db_data($mincl) {
        $cnt = "";
        $i = 0;
        $usr_id = get_usr_id();
        $GLOBALS['rate'] = -1;
        $db = new Database();

        $sql = "SELECT `usr_name`, `usr_bid` FROM `users` WHERE `usr_id` = $usr_id;";

        if($db->query($sql)) {
            while($row = $db->get_single_row()) {
                $GLOBALS['rate'] = $row['usr_bid'];
                $GLOBALS['usrname'] = $row['usr_name'];
            }
        }else {
            return "ERROR_000: DATABASE RETURNED EMPTY RESULT OR INCORRECT CONNECTING WITH DATABASE.";
        }

        $sql = "SELECT `w_id`, `w_day`, `w_start`, `w_end`, `w_date`, `w_desc` FROM `work_logs` WHERE `w_user` = $usr_id;";
        if($db->query($sql)) {
            while($row = $db->get_single_row()) {
                
                $st = substr($row['w_start'], 0, -3);
                $end = substr($row['w_end'], 0, -3);

                $sh = date("H", strtotime($row['w_start']));
                $sm = date("i", strtotime($row['w_start']));

                $lh = date("H", strtotime($row['w_end']));
                $lm = date("i", strtotime($row['w_end']));

                $h = round((abs($lh - $sh) + abs($lm - $sm) / 60) * 10) / 10;

                $desc = $row['w_desc'];

                if(strlen($desc) > $GLOBALS['max_leng_desc']) {
                    $d = $desc;
                    $desc = "<a class='desc-link' href='".mk_ready_redirect(DEF_ADDRESS, array("pg_v" => "%cntl_panel", "id" => encryptID($usr_id), "%typea%" => "show_desc", "day" => $row['w_day']))."'>";

                    for ($l=0; $l < $GLOBALS['max_leng_desc']; $l++) { 
                        $desc .= $d[$l];
                    }
                    $desc .= " <span class='desc-link-ext'>[...]</span></a>";
                    
                }

                $cnt .= "<tr>
                <td>".$row['w_day']."</td>
                <td>".$st." - ".$end."</td>
                <td>".$h."h</td>
                <td>".$h * $GLOBALS['rate']."zł</td>
                <td>".$row['w_date']."</td>
                <td>".$desc."</td></tr>";
                $i = intval($i + 1);
            }
        } else {
            return "ERROR_111: DATABASE RETURNED EMPTY RESULT OR INCORRECT CONNECTING WITH DATABASE.";
        }

        for ($j=$i; $j < $mincl; $j++) { 
            $cnt .= "<tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td></tr>";
        }
        return $cnt;
    }

?>