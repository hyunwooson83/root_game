<?php
ini_set('display_errors',0);
$pstyle = 'game';
$root_path = $_SERVER['DOCUMENT_ROOT'];
include_once $root_path."/include/common.php";
//$_SESSION['S_Key'] = '13111';


// 로그인 체크
if ( empty($_SESSION['S_Key']) ) {
    move('/login/');
}

$_SESSION['S_LoginTime'] = time();



?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="imagetoolbar" content="no">
    <meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">
    <meta name="viewport" content="width=1100,user-scalable=yes">
    <link rel="shortcut icon" href="/img/favicon.png">
    <title>TEXAS - 텍사스</title>
    <link rel="stylesheet" href="/css/bean_style.css?t=2" />
    <link rel="stylesheet" href="/css/swiper.min.css" />
    <link rel="stylesheet" href="/css/sweetalert.css" />

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="/js/jquery-1.11.1.min.js"></script>
    <script src="/js/jquery.easing.1.3.js"></script>
    <script src="/js/gobet_script.js"></script>
    <script src="/js/placeholders.min.js"></script>
    <script src="/js/swiper.min.js"></script>
    <script src="/js/slick.min.js"></script>
    <script src="/js/sweetalert.js"></script>
    <script src="/js/_lib.js?t=<?php echo time();?>"></script>


    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/js/cookie.js"></script>





</head>

<?php

switch ($pstyle) {
    case "casino" : $body_bg = "bg_casino"; $wrap_bg = "wrap_casino"; break;
    case "game" : $body_bg = "bg_game"; $wrap_bg = "wrap_game";	break;
    case "main" : $body_bg = "bg_main"; $wrap_bg = "wrap_main"; break;
    default : $body_bg = "bg_main"; $wrap_bg = "wrap_sub"; break;
}
?>

<body class="<?php echo $body_bg ?>">
<div id="wrap" class="<?php echo $wrap_bg ?>">

    <div id="header" class="main_header">

        <div class="header_top">
            <div class="center_wrap">
			<span class="top_notice">
				<em>긴급공지</em>
				<div><code id="notice_slide" onclick="location.href='/mypage/notice/'" class="active"><?php echo $MARQUEE;?></code></div>
			</span>
                <ul class="g_menu">
                    <?php if($_SESSION['S_Level'] < 7 && !empty($_SESSION['S_Level'])){ ?>
                        <li onclick="location.href='/mypage/branch/'">총판관리</li>
                    <?php } ?>
                    <li onclick="location.href='/mypage/notice/'">공지사항</li>

                    <!--<li onclick="location.href='/mypage/money_money_charge.html'">충전/환전내역</li>
                    <li onclick="alert('계좌번호를 쪽지로 발송 해드렸습니다. 쪽지확인 부탁드립니다.'); location.href='/mypage/memo_list.html'">계좌문의</li>-->
                    <!--<li onclick="location.href='/bettingrule/sports_soccer.html'">베팅규정</li>-->
                    <!--<li onclick="location.href='/event_list.html'">이벤트</li>-->
                    <li class="logout_st" onClick="location.href='/logout/'">로그아웃</li>
                    <li class="lang_change" onClick="lang_change();"><img src="/img/lang_kr.png" /></li>
                </ul>
            </div>
        </div> <!-- Menu_Top -->

        <div class="header_middle">
            <div class="center_wrap">

                <div class="header_middle_left">
				<span class="main_logo">
					<img src="/img/l_logo5.png" onClick="location.href='/main/'" style="cursor:pointer;" />
                    <BR />
					<label>
						<font><img src="/img/icon_clock.png" />KOREA</font>

						<code id="site_date_timer"><?php echo date("Y-m-d H:i:s");?></code>
					</label>
				</span>
                </div>

                <div class="header_middle_right">

                    <ul class="login_st">
                        <li>
                            <em class="level lv<?php echo $disp_mb_lv[$_SESSION['S_Level']];?>">Lv.<?php echo $disp_mb_lv[$_SESSION['S_Level']];?></em>&nbsp;
                            <span class="hover" title="마이페이지 바로가기" onClick="location.href='/mypage/betlist/'"><?php echo $_SESSION['S_Nick']; ?>님</span>
                        </li>
                        <li>
                            <span>보유머니</span>&nbsp;&nbsp;
                            <em class="hover" id="member_cur_money" title="보유금액충전내역 바로가기" onClick="location.href='/mypage/charge'"><?php echo number_format($meminfo['M_Money']); ?>원</em>
                        </li>
                        <li>
                            <span>포인트</span>&nbsp;&nbsp;
                            <em class="hover" title="포인트적립내역 바로가기" onClick="location.href='/mypage/point/exchange/'"><?php echo number_format($meminfo['M_Point']); ?>P</em>
                        </li>
                        <li>
                            <span>쪽지</span>&nbsp;&nbsp;
                            <em class="hover" title="읽지 않은 쪽지 <?php echo $lib24c->GetNewMemo($lib24c->member_info['M_Key']); ?>건" onClick="location.href='/mypage/message/'"><?php echo $lib24c->GetNewMemo($lib24c->member_info['M_Key']); ?></em>
                        </li>
                    </ul>
                    <ol class="login_st">
                        <li>
                            <span onclick="location.href='/mypage/betlist/'">마이페이지</span>
                        </li>
                        <li>
                            <span onclick="location.href='/attend/'">출석부</span>
                        </li>
                        <li onclick="location.href='/mypage/point/exchange/'">
                            <span>포인트</span>
                        </li>
                        <li>
                            <span onclick="location.href='/mypage/betlist/'">베팅내역</span>
                        </li>
                        <!--<li onclick="location.href='/mypage/money_money_charge.html'">충환전내역</li>-->
                        <li onclick="location.href='/mypage/customer/'">문의하기</li>
                        <!--<li onclick="location.href='/mypage/recom_add.html'">총판페이지</li>-->
                    </ol>

                </div>
            </div> <!-- Center_Wrap -->
        </div> <!-- Menu_Middle -->

        <div class="header_bottom sub_header">
            <ul class="top2">
                <!--<li onClick="location.href='/game/live/';">라이브</li>-->
                <li onClick="swal('','오픈준비중 입니다.','warning')">라이브</li>
                <li onClick="location.href='/game/sports/cross/'">조합</li>
                <li onClick="location.href='/game/sports/special/'">스페셜</li>
                <!--<li onClick="location.href='#'">전반전</li>-->
                <li><span>카지노</span>
                    <div>
                        <dl>
                            <dt></dt>
                            <dd onclick="location.href='/game/casino/?gcode=1'">VIVO</dd>
                            <dd onclick="location.href='/game/casino/?gcode=21'">마이크로게임</dd>
                            <dd onclick="location.href='/game/casino/?gcode=28'">드림게임</dd>
                            <dd onclick="location.href='/game/casino/?gcode=45'">에볼루션</dd>
                        </dl>
                    </div>
                </li>
                <li><span>슬롯게임</span>
                    <div>
                        <dl>
                            <dt></dt>
                            <dd onclick="location.href='/game/slot/?gcode=8'">플레메틱</dd>
                            <dd onclick="location.href='/game/slot/?gcode=24'">하바네로</dd>
                            <dd onclick="location.href='/game/slot/?gcode=33'">제네시스</dd>
                            <dd onclick="location.href='/game/slot/?gcode=36'">플레이선</dd>
                        </dl>
                    </div>
                </li>
                <li><span onclick="location.href='/game/minigame/powerball/'">미니게임</span>
                    <div>
                        <dl>
                            <dt></dt>
                            <dd onclick="location.href='/game/minigame/powerball/'">파워볼게임</dd>
                            <dd onclick="location.href='/game/minigame/pwladder/'">파워사다리</dd>
                            <dd onclick="location.href='/game/minigame/kenoladder/'">키노사다리</dd>
                            <!--<dd onclick="location.href='/game/minigame/kick/'">파워프리킥</dd>
                            <dd onclick="location.href='/game/minigame/homerun/'">스피드홈런</dd>
                            <dd onclick="location.href='/game/minigame/dunk/'">파워스피드 덩크</dd>-->
                            <!--<dd onclick="location.href='/game/minigame/soccer/'">벳이스트 축구</dd>
                            <dd onclick="location.href='/game/minigame/basketball/'">벳이스트 농구</dd>
                            <dd onclick="location.href='/game/minigame/baseball/'">벳이스트 야구</dd>
                            <dd onclick="location.href='/game/minigame/cricket/'">베이스트 크리켓</dd>-->

                        </dl>
                    </div>
                </li>
                <li>
                    <span onclick="location.href='/game/virtual/soccer/'">가상게임</span>
                    <div>
                        <dl>
                            <dt></dt>
                            <dd onclick="location.href='/game/virtual/soccer/'">가상축구리그</dd>
                            <dd onclick="location.href='/game/virtual/horse/'">가상경마</dd>
                            <dd onclick="location.href='/game/virtual/dog/'">가상개경주</dd>
                        </dl>
                    </div>
                </li>
                <li>
                    <span onclick="location.href='/result/sports/'">경기결과</span>
                    <div>
                        <dl>
                            <dt></dt>
                            <dd onclick="location.href='/result/sports/'">스포츠게임</dd>
                            <dd onclick="location.href='/result/minigame/power/'">미니게임</dd>
                            <dd onclick="location.href='/result/virtual/soccer/'">가상게임</dd>
                        </dl>
                    </div>
                </li>
                <li onClick="swal('','라이브영상은 준비중입니다.','success');">라이브영상</li>
                <!--<li onClick="location.href='/game_result.html'">경기결과-->
                </li>
                <li onClick="location.href='/mypage/board/'">게시판</li>
                <li><span onclick="location.href='/mypage/notice/'">고객센터</span>
                    <div>
                        <dl>
                            <dt></dt>
                            <dd onclick="location.href='/mypage/notice/'">공지사항</dd>
                            <dd onclick="location.href='/mypage/customer/'">1:1문의</dd>
                            <!--<dd onclick="location.href='/mypage/faq/'">자주묻는질문</dd>-->
                        </dl>
                    </div>
                </li>
                <li><span onclick="location.href='/money/charge/'">충전</span>
                    <div>
                        <dl>
                            <dt></dt>
                            <dd onclick="location.href='/money/charge/'">보유머니 충전</dd>
                            <dd onclick="location.href='/mypage/charge/'">보유머니 충전내역</dd>
                            <dd onclick="location.href='/money/casino/'">카지노머니 충전</dd>
                            <dd onclick="location.href='/money/casino/'">카지노 충전내역</dd>
                        </dl>
                    </div>
                </li>
                <li><span onclick="location.href='/money/refund/'">환전</span>
                    <div>
                        <dl>
                            <dt></dt>
                            <dd onclick="location.href='/money/refund/'">보유머니 환전</dd>
                            <dd onclick="location.href='/mypage/refund/'">보유머니 환전내역</dd>
                            <dd onclick="location.href='/money/casino/'">카지노머니 환전</dd>
                            <dd onclick="location.href='/money/casino/'">카지노 환전내역</dd>
                        </dl>
                    </div>
                </li>
            </ul>
        </div> <!-- Menu_Bottom -->

    </div> <!-- header -->

    <div id="body-contents">