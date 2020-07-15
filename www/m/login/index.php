<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/m/include/common.php";

    if($SITECONFIG['Site_Stop_YN']=='Y'){
        move('/constructor/');
    }
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="HandheldFriendly" content="true">
    <meta name="format-detection" content="telephone=no">
    <link rel="shortcut icon" href="/m/img/favicon.png">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=10,user-scalable=no">
    <title>TEXAS - 텍사스</title>
    <link rel="stylesheet" href="/m/css/login_style.css" />
    <link rel="stylesheet" type="text/css" href="/css/sweetalert.css">
    <script src="/m/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/m/js/_lib.js?time=<?php echo time(); ?>"></script>
    <script type="text/javascript" src="/m/js/sweetalert.js"></script>
</head>
<body>

<div class="login_wrap">
    <em><img src="/img/logo_b4.png" /></em>
    <span><img src="/m/img/login_text.png" /></span>
    <ul class="login_menu">
        <li class="on"><a href="/m/login/">LOGIN</a></li>
        <li class=""><a href="/m/join/">JOIN</a></li>
    </ul>
    <div><input type="text" class="style_input" placeholder="아이디" name="login_id" id="login_id" /></div>
    <div><input type="password" class="style_input" placeholder="비밀번호"  name="login_pass" id="login_pass" /></div>
    <div><input type="submit" class="btn_submit" value="로그인" onClick="LogIn();" /></div>

    <!--<div><input type="button" class="btn_join" value="CREATE ACCOUNT" onClick="location.href='/mobile/join.html'" /></div>-->
</div>

<div class="copy">
    <!--<span><img src="/mobile/img/icon_kakaotalk.png" /> texas</span>
    <span><img src="/mobile/img/icon_telegram.png" /> texas</span><BR /><BR />-->
    <h1><span onclick="location.href = '/login/?pcmode=Y';">PC버전으로 보기</span></h1>
    Copyright TEXAS Corp⒞. All Rights Reserved.
</div>

<form name="HiddenActionForm" style="display:none;" target="HIddenActionFrame" >
    <input type="text" name="HAF_Value_0">
    <input type="text" name="HAF_Value_1">
    <input type="text" name="HAF_Value_2">
    <input type="text" name="HAF_Value_3">
    <input type="text" name="HAF_Value_4">
    <input type="text" name="HAF_Value_5">
    <input type="text" name="HAF_Value_6">
    <input type="text" name="HAF_Value_7">
    <input type="text" name="HAF_Value_8">
    <input type="text" name="HAF_Value_9">
    <input type="text" name="HAF_Value_10">
    <input type="text" name="HAF_Value_11">
    <input type="text" name="HAF_Value_12">
    <input type="text" name="HAF_Value_13">
    <input type="text" name="HAF_Value_14">
    <input type="text" name="HAF_Value_15">
    <input type="text" name="HAF_Value_16">
    <input type="text" name="HAF_Value_17">
    <input type="text" name="HAF_Value_18">
    <input type="text" name="HAF_Value_19">
    <input type="text" name="HAF_Value_20">
</form>
<iframe src="about:blank" style="display:none;width:600px;height:500px;" name="HIddenActionFrame" id="HIddenActionFrame" ></iframe>

</body>
</html>