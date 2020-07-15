<?php
    include "../include/common.php";
    error_log('회원번호 : ' . $_SESSION['S_Key'] . ' 아이디 : ' . $_SESSION['S_ID'] . '- 로그 : '.$type.' 시간 : '.date('Y-m-d H:i:s') . PHP_EOL, 3, "/home/trend/www/m/log/click.log");


