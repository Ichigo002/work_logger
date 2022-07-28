<?php 

$errv = null;

if(get("pass1") != get("pass2")) {
    $errv = "err303";
}

if(str_contains_any(get("user"), "'`,;")) {
    $errv = "err297";
}

if(str_contains_any(get("name"), "'`-_.,!@#$%^&*(){}[]|;:+=1234567890")) {
    $errv = "err298";
}

if($errv == null) {
    try {
        create_account();
    } catch (\Throwable $th) {
        redirect(DEF_ADDRESS, array("pg_v" => "ERR_REG_DB"));
    }
    
} else {
    redirect(DEF_ADDRESS, array("pg_v" => "register", "stt" => $errv));
}

function create_account() {
    $db = new Database();

    $name = get("name");
    $login = get("user");
    $pass = get("pass1");
    $bid = get("bid");

    $sql = "INSERT INTO `users`(`usr_id`, `usr_name`, `usr_login`, `usr_password`, `usr_bid`) VALUES (NULL,'$name','$login','$pass','$bid');";

    if($db->query($sql)) {
        redirect(DEF_ADDRESS, array("pg_v" => "login/login", "stt" => "scc010"));
    } else {
        redirect(DEF_ADDRESS, array("pg_v" => "ERR_REG_DB"));
    }
}

?>