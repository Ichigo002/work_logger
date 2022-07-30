<?php 
    $min_table_columns = 16;
    $table_cnt = get_db_data($min_table_columns);


    function get_usr_id() {
        return (get("idu", -1) + 78) / 14;
    }

    function get_db_data($mincl) {
        $cnt = "";
        $i = 0;
        $usr_id = get_usr_id();
        $usr_bid = -1;
        $db = new Database();

        if($usr_id == 5.5) {
            //ERROR NO FOUND ID;
            return "ERROR_222: NOT FOUND CORRECT USER ID TO FETCH DATA";
        }

        $sql = "SELECT `usr_bid` FROM `users` WHERE `usr_id` = $usr_id;";

        if($db->query($sql)) {
            while($row = $db->get_single_row()) {
                $usr_bid = $row['usr_bid'];
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

                $h = abs($lh - $sh) + abs($lm - $sm) / 60;

                $cnt .= "<tr>
                <td>".$row['w_day']."</td>
                <td>".$st." - ".$end."</td>
                <td>".$h."h</td>
                <td>".$h * $usr_bid."zł</td>
                <td>".$row['w_date']."</td>
                <td>".$row['w_desc']."</td></tr>";
                $i .= 1;
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