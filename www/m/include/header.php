<?php
    ini_set('display_errors',1);
    $pstyle = 'game';
    $mobile_path = "/m";
    $root_path = $_SERVER['DOCUMENT_ROOT']."/m";
    include_once $root_path."/include/common.php";
    // 로그인 체크
    if ( empty($_SESSION['S_Key']) ) {
        move('/login/');
    }

    $_SESSION['S_LoginTime'] = time();


    @error_log('회원번호 : '.$_SESSION['S_Key'].' : '.$_SESSION['S_ID'].'- 위치 : '.$_SERVER['PHP_SELF'].' 접속시간 : '.date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/log/location.log");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=1.0,user-scalable=no">
<meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width" />
<meta http-equiv=imagetoolbar	content=no> 
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
<meta name="format-detection" content="telephone=no,address=no,email=no">
<title>TEXAS - 텍사스</title>
<link rel="shortcut icon" href="<?php echo $mobile_path;?>/img/favicon.png">
<link rel="stylesheet" href="<?php echo $mobile_path;?>/css/style.css?v=2" />
<link rel="stylesheet" href="<?php echo $mobile_path;?>/css/slick.css?ver=161020">
<link rel="stylesheet" href="<?php echo $mobile_path;?>/css/slick-theme.css?ver=161020">
<link rel="stylesheet" href="/css/sweetalert.css" />
<script src="<?php echo $mobile_path;?>/js/jquery-1.11.1.min.js"></script>
<script src="<?php echo $mobile_path;?>/js/jquery.easing.1.3.js"></script>
<script src="<?php echo $mobile_path;?>/js/placeholders.min.js"></script>
<script src="<?php echo $mobile_path;?>/js/iscroll.js"></script>
<script src="<?php echo $mobile_path;?>/js/slick.min.js"></script>
    <script src="<?php echo $mobile_path;?>/js/main1.js?time=<?php echo time(); ?>"></script>
<script src="/js/sweetalert.js"></script>
<script src="/m/js/_lib.js?t=<?php echo time();?>"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
<!-- wrap -->
<div id="wrap">
<!-- 헤더 -->
<div id="header">
	<div class="head_top1">
		<div>
		<span>
		<em class="left_btn" onclick="menu_flag('left')"><img src="<?php echo $mobile_path;?>/img/icon_left_menu_btn.png"></em>
		</span>
		<em class="logo" onclick="location.href='/m/main/'"><img src="/img/logo_b4.png" /></em>
		<em class="right_btn" onclick="menu_flag('right')"><img src="<?php echo $mobile_path;?>/img/icon_right_menu_btn.png"></em>
		</div>
	</div>

	<div class="header_profile">
		<ul>
			<li class="name">
				<span><img src="<?php echo $mobile_path;?>/img/icon_lv<?php echo $disp_mb_lv[$_SESSION['S_Level']];?>.png" /></span>
				<em><?php echo $_SESSION['S_Nick']; ?></em>
			</li>
			<li class="memo" onClick="location.href='<?php echo $mobile_path;?>/mypage/message/'">
				<span><img src="<?php echo $mobile_path;?>/img/icon_memo.png" /></span>
				<em><?php echo $lib24c->GetNewMemo($lib24c->member_info['M_Key']); ?></em>
			</li>
			<li onClick="location.href='<?php echo $mobile_path;?>/money/charge/list/'">
				<span><img src="<?php echo $mobile_path;?>/img/icon_m.png" /></span>
				<em><?php echo number_format($meminfo['M_Money']); ?></em>
			</li>
			<li onClick="location.href='<?php echo $mobile_path;?>/mypage/point/exchange/'">
				<span><img src="<?php echo $mobile_path;?>/img/icon_p.png" /></span>
				<em><?php echo number_format($meminfo['M_Point']); ?></em>
			</li>
		</ul>
	</div>

	<div class="head_top2 notice">
		<em class="notice_icon1">긴급공지</em>
		<div class="ment"><span><?php echo $MARQUEE; ?></span></div>
		<div class="notice_close" onclick="notice_close();">
			<span>X</span>
		</div>
	</div>
<!-- 헤더 닫기 -->
</div>
<div id="body_wrap">