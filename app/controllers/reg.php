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
        redirect("ERROR", array("pg_v" => "ERR_REG_DB"));
    }
    
} else {
    redirect("register", array("pg_v" => "register", "stt" => $errv));
}

function create_account() {
    $db = new Database();

    $name = $db->safety_str(get("name"));
    $login = $db->safety_str(get("user"));
    $pass = $db->safety_str(get("pass1"));
    $bid = $db->safety_str(get("bid"));

    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $name = ucfirst($name);

    $sql = "INSERT INTO `users`(`usr_id`, `usr_name`, `usr_login`, `usr_password`, `usr_bid`) VALUES (NULL,'$name','$login','$hash','$bid');";

    if($db->query($sql)) {
        redirect("login", array("pg_v" => "login/login", "stt" => "scc010"));
    } else {
        redirect("login", array("pg_v" => "ERR_REG_DB"));
    }
}

?>