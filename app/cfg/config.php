<?php

$config = [
    'MODEL_PATH' => APP_PATH.DS.'model'.DS,
    'VIEW_PATH' => APP_PATH.DS.'view'.DS,
        'LOGIN_PATH' => APP_PATH.DS.'view'.DS.'login'.DS,
    'LIB_PATH' => APP_PATH.DS.'lib'.DS,
];

require $config['LIB_PATH'].'functions-lib.php';
require $config['LIB_PATH'].'database-lib.php';

/*$page  = get('page', 'home');
$model = $config['MODEL_PATH'].$page.'.php';
$view  = $config['VIEW_PATH'].$page.'.phtml';
$_404  = $config['VIEW_PATH'].'404.phtml';

if(file_exists(($model)) && file_exists(($view))) {
    require $model;
    require $view;
} else {
    require $_404;
}*/

?>