<?php
    $fail = false;
    $success = false;
    $lg = false; // log out value

    if(get("stt") == "err301") {
        $fail = true;
    }
    if(get("stt") == "scc010") {
        $success = true;
    }
    if(get("stt") == "lgout") {
        $lg = true;
    }
?>