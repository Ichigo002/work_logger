<?php
  if($id = check_correct()) {
    echo $id;
    redirect(DEF_ADDRESS, array("pg_v" => "signed/panel"));
  } else {
    redirect(DEF_ADDRESS, array("pg_v" => "login/login", "stt" => "err")); // stt - status:Fail error
  }


  function check_correct() {
      $login = get("user");
      $pass = get("pass");

      $db = new Database();

      $sql = "SELECT `usr_id`, `usr_login`, `usr_password` FROM `users`;";
      $db->query($sql);

      while($row = $db->get_single_row()) {
          if($login == $row['usr_login'] && $pass == $row['usr_password']) {
            return $row['usr_id'];
          }
      }
  }
?>