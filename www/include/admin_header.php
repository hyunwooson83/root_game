<?
  include("../include/common.php");
  
  // 관리자 권한 체크
  if ( $_SESSION[S_Admin] != 'Y' ) {
  	@session_destroy();
  	$lib->AlertMSG( "관리자 로그인 후 사용 가능합니다.","/admin/login.php" );
  }
  
  // 왼쪽 메뉴 설정
  $left_menu = $lib24c->Admin_Left($lib->ThisPageName())  ;
  
  // 액션 폴더시 HTML Header 출력하지 않음
  if ( $lib->ThisFolderName() != "action" && !eregi("popup", $lib->ThisPageName() ) && !eregi("game_register", $lib->ThisPageName() ) ) {  
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>:::::관리자 페이지입니다.:::::</title>
<link href="/admin/css/admin.css" rel="stylesheet" type="text/css">
<script src="/js/scroll.js" type="text/javascript"></script>
<script src="/js/flash.js" type="text/javascript"></script>
<script src="/js/ajax.js" type="text/javascript"></script>
<script src="/js/_lib.js" type="text/javascript"></script>
<script src="/js/_admin_lib.js" type="text/javascript"></script>
<script src="/js/jquery-1.5.1.min.js" type="text/javascript"></script>
<script type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

//-->
</script>
</head>
<? 

if($_GET[tn]=='join'){
	$left_menu = "left_menu01.php";
}
?>
<? include ("scroll.php"); ?>
<body id="bg_body">
<div id="wrap">
	<div id="header"><? include ("top.php"); ?> </div>
	<div id="left_bg">
	 	<div id="left_container"><? include ($left_menu); ?><? include ("live_message.php"); ?></div>
<?
  } else if ( eregi("popup", $lib->ThisPageName() ) || eregi("game_register", $lib->ThisPageName() )  ) {
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title>:::::관리자 페이지입니다.:::::</title>
<link href="css/popup.css" rel="stylesheet" type="text/css">
<script src="/js/ajax.js" type="text/javascript"></script>
<script src="/js/_lib.js" type="text/javascript"></script>
<script src="/js/_admin_lib.js" type="text/javascript"></script>
<script src="/js/jquery-1.5.1.min.js" type="text/javascript"></script>
</head>

<body>
<div id="wrap">
<?    
  };
?>
