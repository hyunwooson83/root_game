<?php
    $root_path = '/bs';
    $include_path = "/home/trend/www/bs";
    include $include_path . "/include/common.php";
    include_once($include_path . "/include/Snoopy.class.php");

    $snoopy = new Snoopy;

    // 헤더값에 따라 403 에러가 발생 할 경우 셋팅
    $snoopy->agent = $_SERVER['HTTP_USER_AGENT'];
    $snoopy->fetch('http://api.oddsapi-game.com/powerball/result?corp=c142c8aa-51bc-42af-8ec1-3781685ba508&round=968026');
    $content = $snoopy->results;
    $result = json_decode($content, true);
    print_r($result);

    