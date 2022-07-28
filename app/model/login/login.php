<?php
    $fail = false;
    $success = false;

    if(get("stt") == "err301") {
        $fail = true;
    }
    if(get("stt") == "scc010") {
        $success = true;
    }
?>