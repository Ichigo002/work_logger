<?php

function get($name, $def='') {
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $def;
}

function redirect($address, $v_get_array = null) {
    $r = "Location: ".$address;

    if($v_get_array != null) {
        $r .= "?";
        foreach ($v_get_array as $namev => $val) {
            $r .= $namev . "=" . $val . "&";
        }
    }
    
    header($r);
}
    

?>