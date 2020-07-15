<?php
$root_path = '..';
include_once $root_path.'/include/header.php';
//if($_REQUEST['pcmode']=='Y' || empty($_REQUEST['pcmode'])){
if(($_REQUEST['pcmode']=='Y' && $_REQUEST['mobile']=='N') || $_SESSION['pcmode']=='Y'){
    $_SESSION['pcmode'] = 'Y';
} else {
    unset($_SESSION['pcmode']);

    move('/m/main/');
}
?>
<style>
    h1 { font-size:2em !important;}
    h2 { font-size:1.5em !important;}
    h3 { font-size:1.33em !important;}
    h4 { font-size:1.17em !important;}
    h5 { font-size:0.83em !important;}
</style>
<?php
$cnt = 1;
$que = "SELECT * FROM popup WHERE B_StartDate <= NOW() AND B_EndDate > NOW() AND B_State = 'Normal'";
//echo $que;
$pop = getArr($que);
if(count($pop)>0){
    foreach($pop as $list){
        ?>
        <!-- 레이어 팝업 시작 -->
        <div id="apDiv<?php echo $cnt; ?>" class="post_wrapper"  style="display:none; position: absolute; z-index: 999; top:180px; left:<?php echo $list['B_Left'];?>px; background-color: #0a0a0a; opacity: 1;">
            <!--<div class="tit_h"><p><span>마스터에서 알려드립니다!</span> : MASTER NOTICE!</p></div>-->
            <div class="content_wrap" style="font-size:20px;">
                <?php
                    if($list['file_name_srv']!=''){
                        ?>
                        <img src="/img/popup/<?php echo $list['file_name_srv'];?>" alt="">
                        <?
                    } else {
                        echo $list['B_Content'];     
                    }
                ?>
                
            </div>
            <div class="today_clo">
                <input type="checkbox" name="Notice<?php echo $cnt; ?>" id="Notice<?php echo $cnt; ?>" value="1" onClick="notice_closeWin(<?php echo $cnt; ?>);" /><font>오늘 하루 창 닫기</font>
                <span onClick="$('#apDiv<?php echo $cnt; ?>').hide();"><a href="#" class="btn_grey">닫기</a></span>
            </div>
        </div>
        <!-- 레이어 팝업 끝-->
        <?php $cnt++;}} ?>


<div class="main_wrap">
    <div class="contents_top">
        <div class="slide_box">
            <div>
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" onClick="location.href='<?php echo $root_path;?>/live_list.html'"><img src="/img/main/visual01.png" /></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
        <!--<div class="banner_box">
            <img src="<?php /*echo $root_path;*/?>/img/img_main_banner1.jpg"  onClick="location.href='<?php /*echo $root_path;*/?>/casino'"/>
        </div>-->


    </div> <!-- Contents_Top -->


    <div class="contents_bottom">
        <h1>TEXAS GAMES</h1>
        <ul>
            <li class="game-box">
                <div class="over-link">
                    <em class="game-box-shortcut">LIVE SPORTS</em>
                    <span class="game-box-title"><a href="/game/live/">바로가기</a></span>
                </div>
                <img src="/img/main/eight_box_01.jpg" />

            </li>
            <li class="game-box">
                <div class="over-link">
                    <em class="game-box-shortcut">CROSS SPORTS</em>
                    <span class="game-box-title"><a href="/game/sports/cross/">바로가기</a></span>
                </div>
                <img src="/img/main/eight_box_03.jpg" />
            </li>
            <li class="game-box">
                <div class="over-link">
                    <em class="game-box-shortcut">CASINO</em>
                    <span class="game-box-title"><a href="/game/casino/?gcode=1">바로가기</a></span>
                </div>
                <img src="/img/main/eight_box_05.jpg" />
            </li>
            <li class="game-box">
                <div class="over-link">
                    <em class="game-box-shortcut">MINI GAMES</em>
                    <span class="game-box-title"><a href="/game/minigame/powerball/">바로가기</a></span>
                </div>
                <img src="/img/main/eight_box_07.jpg" />
            </li>
            <li class="game-box">
                <div class="over-link">
                    <em class="game-box-shortcut">VIRTUAL SPORTS</em>
                    <span class="game-box-title"><a href="/game/virtual/soccer/">바로가기</a></span>
                </div>
                <img src="/img/main/eight_box_09.jpg" />
            </li>
            <li class="game-box">
                <div class="over-link">
                    <em class="game-box-shortcut">SPECIAL SPORTS</em>
                    <span class="game-box-title"><a href="/game/sports/special/">바로가기</a></span>
                </div>
                <img src="/img/main/eight_box_11.jpg" />
            </li>
            <li class="game-box">
                <div class="over-link">
                    <em class="game-box-shortcut">1ST HALF</em>
                    <span class="game-box-title"><a href="/game/live/">바로가기</a></span>
                </div>
                <img src="/img/main/eight_box_13.jpg" />
            </li>
            <li class="game-box">
                <div class="over-link">
                    <em class="game-box-shortcut">MATCH RESULT</em>
                    <span class="game-box-title"><a href="/game/live/">바로가기</a></span>
                </div>
                <img src="/img/main/eight_box_15.jpg" />
            </li>
        </ul>

        <div class="contents_list_box">

            <div class="cash_box">
                <div class="weekly">
                    <h1>이달의 출금 <font>TOP</font></h1>
                    <div class="con">
                        <ul>
                            <!--<li>
                                <span>1</span>
                                <em>20/05/25&nbsp; &nbsp;02:44</em>
                                <var>13,215,000원</var>
                                <code>**sox9</code>
                            </li>
                            <li>
                                <span>2</span>
                                <em>20/05/22&nbsp; &nbsp;13:10</em>
                                <var>12,850,000원</var>
                                <code>**ttx</code>
                            </li>
                            <li>
                                <span>3</span>
                                <em>20/05/22&nbsp; &nbsp;16:44</em>
                                <var>10,952,000원</var>
                                <code>**ake</code>
                            </li>
                            <li>
                                <span>4</span>
                                <em>20/05/15&nbsp; &nbsp;09:05</em>
                                <var>9,123,000원</var>
                                <code>**3911</code>
                            </li>
                            <li>
                                <span>5</span>
                                <em>20/05/12&nbsp; &nbsp;21:22</em>
                                <var>8,951,000원</var>
                                <code>**jam</code>
                            </li>
                            <li>
                                <span>6</span>
                                <em>20/05/11&nbsp; &nbsp;03:00</em>
                                <var>8,490,100원</var>
                                <code>**misste</code>
                            </li>
                            <li>
                                <span>7</span>
                                <em>20/05/10&nbsp; &nbsp;11:59</em>
                                <var>6,130,020원</var>
                                <code>**wsxas</code>
                            </li>
                            <li>
                                <span>8</span>
                                <em>20/05/10&nbsp; &nbsp;07:31</em>
                                <var>5,998,180원</var>
                                <code>**777</code>
                            </li>
                            <li>
                                <span>9</span>
                                <em>20/05/10&nbsp; &nbsp;19:12</em>
                                <var>4,790,400원</var>
                                <code>**saram</code>
                            </li>
                            <li>
                                <span>10</span>
                                <em>20/05/03&nbsp; &nbsp;19:11</em>
                                <var>1,300,000원</var>
                                <code>**god</code>
                            </li>-->
                        </ul>
                    </div>
                </div>
                <?php

                ?>
                <div class="realtime">
                    <h1>실시간 입금/출금 현황</h1>
                    <div class="con">
                        <ul>
                            <!--<li>
                                <?php /*$type = rand(1,2); */?>
                                <em>20/05/<?php /*echo date("d");*/?>&nbsp; &nbsp;<?php /*$rand = rand(1,24); echo ($rand<10)?'0'.$rand:$rand;*/?>:<?php /*$rand = rand(0,59); echo ($rand<10)?'0'.$rand:$rand;*/?></em>
                                <var style="text-align:right;"><?php /*echo number_format(rand(100000,10000000));*/?>원</var>
                                <label class="<?php /*echo ($type==1)?'in':'out';*/?>"><?php /*echo ($type==1)?'입금':'출금';*/?></label>

                            </li>
                            <li>
                                <?php /*$type = rand(1,2); */?>
                                <em>20/05/<?php /*echo date("d");*/?>&nbsp; &nbsp;<?php /*$rand = rand(1,24); echo ($rand<10)?'0'.$rand:$rand;*/?>:<?php /*$rand = rand(0,59); echo ($rand<10)?'0'.$rand:$rand;*/?></em>
                                <var style="text-align:right;"><?php /*echo number_format(rand(100000,10000000));*/?>원</var>
                                <label class="<?php /*echo ($type==1)?'in':'out';*/?>"><?php /*echo ($type==1)?'입금':'출금';*/?></label>

                            </li>
                            <li>
                                <?php /*$type = rand(1,2); */?>
                                <em>20/05/<?php /*echo date("d");*/?>&nbsp; &nbsp;<?php /*$rand = rand(1,24); echo ($rand<10)?'0'.$rand:$rand;*/?>:<?php /*$rand = rand(0,59); echo ($rand<10)?'0'.$rand:$rand;*/?></em>
                                <var style="text-align:right;"><?php /*echo number_format(rand(100000,10000000));*/?>원</var>
                                <label class="<?php /*echo ($type==1)?'in':'out';*/?>"><?php /*echo ($type==1)?'입금':'출금';*/?></label>

                            </li>
                            <li>
                                <?php /*$type = rand(1,2); */?>
                                <em>20/05/<?php /*echo date("d");*/?>&nbsp; &nbsp;<?php /*$rand = rand(1,24); echo ($rand<10)?'0'.$rand:$rand;*/?>:<?php /*$rand = rand(0,59); echo ($rand<10)?'0'.$rand:$rand;*/?></em>
                                <var style="text-align:right;"><?php /*echo number_format(rand(100000,10000000));*/?>원</var>
                                <label class="<?php /*echo ($type==1)?'in':'out';*/?>"><?php /*echo ($type==1)?'입금':'출금';*/?></label>

                            </li>
                            <li>
                                <?php /*$type = rand(1,2); */?>
                                <em>20/05/<?php /*echo date("d");*/?>&nbsp; &nbsp;<?php /*$rand = rand(1,24); echo ($rand<10)?'0'.$rand:$rand;*/?>:<?php /*$rand = rand(0,59); echo ($rand<10)?'0'.$rand:$rand;*/?></em>
                                <var style="text-align:right;"><?php /*echo number_format(rand(100000,10000000));*/?>원</var>
                                <label class="<?php /*echo ($type==1)?'in':'out';*/?>"><?php /*echo ($type==1)?'입금':'출금';*/?></label>

                            </li>
                            <li>
                                <?php /*$type = rand(1,2); */?>
                                <em>20/05/<?php /*echo date("d");*/?>&nbsp; &nbsp;<?php /*$rand = rand(1,24); echo ($rand<10)?'0'.$rand:$rand;*/?>:<?php /*$rand = rand(0,59); echo ($rand<10)?'0'.$rand:$rand;*/?></em>
                                <var style="text-align:right;"><?php /*echo number_format(rand(100000,10000000));*/?>원</var>
                                <label class="<?php /*echo ($type==1)?'in':'out';*/?>"><?php /*echo ($type==1)?'입금':'출금';*/?></label>

                            </li>
                            <li>
                                <?php /*$type = rand(1,2); */?>
                                <em>20/05/<?php /*echo date("d");*/?>&nbsp; &nbsp;<?php /*$rand = rand(1,24); echo ($rand<10)?'0'.$rand:$rand;*/?>:<?php /*$rand = rand(0,59); echo ($rand<10)?'0'.$rand:$rand;*/?></em>
                                <var style="text-align:right;"><?php /*echo number_format(rand(100000,10000000));*/?>원</var>
                                <label class="<?php /*echo ($type==1)?'in':'out';*/?>"><?php /*echo ($type==1)?'입금':'출금';*/?></label>

                            </li>
                            <li>
                                <?php /*$type = rand(1,2); */?>
                                <em>20/05/<?php /*echo date("d");*/?>&nbsp; &nbsp;<?php /*$rand = rand(1,24); echo ($rand<10)?'0'.$rand:$rand;*/?>:<?php /*$rand = rand(0,59); echo ($rand<10)?'0'.$rand:$rand;*/?></em>
                                <var style="text-align:right;"><?php /*echo number_format(rand(100000,10000000));*/?>원</var>
                                <label class="<?php /*echo ($type==1)?'in':'out';*/?>"><?php /*echo ($type==1)?'입금':'출금';*/?></label>

                            </li>
                            <li>
                                <?php /*$type = rand(1,2); */?>
                                <em>20/05/<?php /*echo date("d");*/?>&nbsp; &nbsp;<?php /*$rand = rand(1,24); echo ($rand<10)?'0'.$rand:$rand;*/?>:<?php /*$rand = rand(0,59); echo ($rand<10)?'0'.$rand:$rand;*/?></em>
                                <var style="text-align:right;"><?php /*echo number_format(rand(100000,10000000));*/?>원</var>
                                <label class="<?php /*echo ($type==1)?'in':'out';*/?>"><?php /*echo ($type==1)?'입금':'출금';*/?></label>

                            </li>
                            <li>
                                <?php /*$type = rand(1,2); */?>
                                <em>20/05/<?php /*echo date("d");*/?>&nbsp; &nbsp;<?php /*$rand = rand(1,24); echo ($rand<10)?'0'.$rand:$rand;*/?>:<?php /*$rand = rand(0,59); echo ($rand<10)?'0'.$rand:$rand;*/?></em>
                                <var style="text-align:right;"><?php /*echo number_format(rand(100000,10000000));*/?>원</var>
                                <label class="<?php /*echo ($type==1)?'in':'out';*/?>"><?php /*echo ($type==1)?'입금':'출금';*/?></label>

                            </li>-->


                        </ul>
                    </div>
                </div>
            </div> <!-- Cash Box -->

            <div class="right_console">
                <div class="latest_box">
                    <h1>COMMUNITY<span class="active" onClick="$('#notice_list').show(); $('#faq_list').hide();">공지사항</span><span onClick="$('#notice_list').hide(); $('#faq_list').show();"">FAQ</span></h1>
                    <ul id="notice_list">
                        <?php
                            $que = "SELECT * FROM board WHERE B_ID = 'Notice' AND B_Delete = 'N' ORDER BY B_RegDate DESC LIMIT 4";
                            $notice = getArr($que);
                            if(count($notice)>0){
                                foreach($notice as $notice){
                        ?>
                        <li onClick="location.href='/mypage/notice/view/?b_key=<?php echo $notice['B_Key'];?>'">
                            <span><?php echo $notice['B_Subject'];?></span>
                            <em><?php echo date("y-m-d",strtotime($notice['B_RegDate']));?></em>
                        </li>
                        <?php }} ?>
                    </ul>
                    <ul id="faq_list" style="display: none;">
                        <?php
                        $que = "SELECT * FROM board WHERE B_ID = 'Faq' AND B_Delete = 'N' ORDER BY B_RegDate DESC LIMIT 4";
                        $notice = getArr($que);
                        if(count($notice)>0){
                            foreach($notice as $notice){
                                ?>
                                <li onClick="location.href='/mypage/faq/view/?b_key=<?php echo $notice['B_Key'];?>'">
                                    <span><?php echo $notice['B_Subject'];?></span>
                                    <em><?php echo date("y-m-d",strtotime($notice['B_RegDate']));?></em>
                                </li>
                            <?php }} ?>
                    </ul>
                </div>

                <div class="cash_in_out_btn">
                    <ul>
                        <li onClick="location.href='/money/charge/'">
                            <h1 style="background:url('/img/icon_cash_btn1.png') no-repeat"><img src="/img/icon_cash_btn1.png" /></h1>
                            <h2>충전</h2>
                            <h3>MONEY CHARGE</h3>
                        </li>
                        <li onClick="location.href='/money/refund/'">
                            <h1 style="background:url('/img/icon_cash_btn2.png') no-repeat"><img src="/img/icon_cash_btn2.png" /></h1>
                            <h2>환전</h2>
                            <h3>EXCHANGE</h3>
                        </li>
                        <!--<li onClick="location.href='<?php /*echo $root_path;*/?>/point_exchange.html'">
                            <h1 style="background:url('<?php /*echo $root_path;*/?>/img/icon_cash_btn3.png') no-repeat"><img src="<?php /*echo $root_path;*/?>/img/icon_cash_btn3_on.png" /></h1>
                            <h2>포인트 전환</h2>
                            <h3>POINT EXCHANGE</h3>
                        </li>-->
                    </ul>
                </div>
            </div>
        </div>

        <!--<div class="cs_center">
            <dl>
                <dd onclick="location.href='<?php /*echo $root_path;*/?>/bettingrule/sports_soccer.html'">
                    <code>MASTER</code>
                    <span>베팅규정</span>
                </dd>
                <dd onclick="location.href='#'">
                    <code>MASTER</code>
                    <span>진행이벤트</span>
                </dd>
                <dd onclick="location.href='/mypage/point/exchange/l'">
                    <code>MASTER</code>
                    <span>포인트전환</span>
                </dd>
                <dd onclick="location.href='/mypage/customer/'">
                    <code>MASTER</code>
                    <span>고객센터</span>
                </dd>
                <dd class="confirm" onclick="alert('계좌번호를 쪽지로 발송 해드렸습니다. 쪽지확인 부탁드립니다.'); location.href='<?php /*echo $root_path;*/?>/mypage/memo_list.html'">
                    <em>입금계좌</em>
                    <em>확인요청</em>
                </dd>
                <dt><img src="/img/img_kakaotalk.jpg" /></dt>
                <dt><img src="/img/img_telegram.png" /></dt>
            </dl>
        </div>-->


    </div> <!-- Contents_Bottom -->

</div> <!-- main_wrap -->


<script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        slidesPerView: 1,
        paginationClickable: true,
        spaceBetween: 0,
        loop: true,
        autoplay: 4000
    });

    $('.game-box').mouseenter(function(){
        $(this).find('.over-link').show();
    });
    $('.game-box').mouseleave(function(){
        $(this).find('.over-link').hide();
    });

    notice_getCookie('Notice1',1);
    notice_getCookie('Notice2',2);
    notice_getCookie('Notice3',3);
    notice_getCookie('Notice4',4);
    notice_getCookie('Notice5',5);
    function notice_getCookie( name, idx ){
        var nameOfCookie = name + "=done";
        var x = 0;
        var cok = document.cookie;
        var sp = cok.split(';');
        var cnt = 0;

        for(var i=0;i<sp.length;i++){
            if(sp[i].trim()==nameOfCookie){
                cnt++;
            }
        }

        if(cnt==0){
            $('#apDiv'+idx).show();
        }
        return "";
    }



    /*if ( notice_getCookie( "Notice1" ) != "done" ){
        $('#apDiv1').show(); // 팝업윈도우의 경로와 크기를 설정 하세요
    }

    if ( notice_getCookie( "Notice2" ) != "done" ){
        $('#apDiv2').show(); // 팝업윈도우의 경로와 크기를 설정 하세요
    }*/

    function notice_closeWin(type){

        if ($('#Notice'+type).is(':checked')==true);
        notice_setCookie( "Notice"+type, "done" , 1); // 1=하룻동안 공지창 열지 않음
        $('#apDiv'+type).hide();
    }

    function notice_setCookie( name, value, expiredays )
    {
        var todayDate = new Date();
        todayDate.setDate( todayDate.getDate() + expiredays );
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
        console.log(document.cookie)
    }

    if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ) { // IE 일 경우
        console.log('사이트를 정삭적으로 이용하시려면 크롬브라우저를 이용해주세요.');
    }
</script>

<?php
include_once $root_path.'/include/footer.php';
?>
