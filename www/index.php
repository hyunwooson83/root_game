<?php
    $root_path = '.';
    include_once $root_path.'/include/common.php';


    if($chkMobile == true){
        move('/m/login/');
    } else {
        move('/login/');
    }

?>
