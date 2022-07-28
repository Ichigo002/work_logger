<?php 
    const DS = DIRECTORY_SEPARATOR;
    defined('APP_PATH') || define('APP_PATH', realpath(dirname(__FILE__).DS."..".DS."app"));

    require APP_PATH.DS.'cfg'.DS.'config.php';
?>