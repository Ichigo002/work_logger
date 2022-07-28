<?php

function get($name, $def='') {
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $def;
}

function redirect($address, $v_get_array = null) {
    header("Location: ".mk_ready_redirect($address, $v_get_array));
}

function mk_ready_redirect($address, $v_get_array = null) {
    $r = $address;

    if($v_get_array != null) {
        $r .= "?";
        foreach ($v_get_array as $namev => $val) {
            $r .= $namev . "=" . $val . "&";
        }
    }
    return $r;
}

function str_contains_any($haystack, $string_chars) {
    for ($i=0; $i < strlen($string_chars) ; $i++) { 
        if(str_contains($haystack, $string_chars[$i])) {
            return true;
        }
    }
    return false;
}
    

?>