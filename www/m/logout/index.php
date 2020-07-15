<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
include_once $root_path."/m/include/common.php";

unset($_SESSION['S_Key']);
unset($_SESSION);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="imagetoolbar" content="no">
    <meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">
    <meta name="viewport" content="width=1100,user-scalable=yes">
    <link rel="shortcut icon" href="/img/favicon.png">
    <title>TREND</title>
    <link rel="stylesheet" href="/css/gobet_style.css" />
    <link rel="stylesheet" href="/css/swiper.min.css" />
    <link rel="stylesheet" href="/css/sweetalert.css" />
    <script src="/js/jquery-1.11.1.min.js"></script>
    <script src="/js/jquery.easing.1.3.js"></script>
    <script src="/js/gobet_script.js"></script>
    <script src="/js/placeholders.min.js"></script>
    <script src="/js/swiper.min.js"></script>
    <script src="/js/slick.min.js"></script>
    <script src="/js/sweetalert.js"></script>


</head>
</html>

<script>
    swal('','정상적으로 로그아웃 되었습니다.','success');
    setTimeout(function(){ location.href = '/m/login/' },2000);
</script>