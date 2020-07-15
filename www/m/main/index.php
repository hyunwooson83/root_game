<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/header.php');

    if($_REQUEST['pcmode']=='N' || $_REQUEST['mobile']=='Y'){
        unset($_SESSION['pcmode']);
    }


?>
<?php
$cnt = 1;
$que = "SELECT * FROM popup WHERE B_StartDate <= NOW() AND B_EndDate > NOW() AND B_State = 'Normal'";
//echo $que;
$pop = getArr($que);
if(count($pop)>0){
    foreach($pop as $list){
        ?>
        <!-- 레이어 팝업 시작 -->
        <div id="apDiv<?php echo $cnt; ?>" class="post_wrapper"  style="display:none; position: absolute; z-index: 999; top:180px; left:5px; background-color: #0a0a0a; opacity: 1;">
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
    <div id="main">
        <div class="main_slide_wrap">
            <em><img src="/m/img/slide_img1.jpg"></em>
            <!--<em><img src="/m/img/slide_img3.jpg"></em>-->
            <!--<em><img src="/m/img/slide_img2.jpg"></em>-->
        </div>
        <div class="main_menu_go0">
            <a href="../money/charge/"><img src="/m/img/main_menu_icon01.png" />보유금 충전</a>
            <var></var>
            <a href="../money/refund/"><img src="/m/img/main_menu_icon02.png" />보유금 환전</a>
            <var></var>
            <a href="../mypage/point/exchange/"><img src="/m/img/main_menu_icon03.png" />포인트 전환</a>
        </div>
        
        <div class="main_menu_go2">
            <a href="/m/game/sports/live/"><img src="/m/img/go_main_icon01.png"></a>
            <a href="/m/game/sports/cross/"><img src="/m/img/go_main_icon02.png"></a>
            <a href="/m/game/sports/handicap/"><img src="/m/img/go_main_icon03.png"></a>
            <a href="/m/game/casino/?gcode=45"><img src="/m/img/go_main_icon04.png"></a>
            <a href="/m/game/minigame/powerball/"><img src="/m/img/go_main_icon05.png"></a>
            <a href="/m/game/minigame/pwladder/"><img src="/m/img/go_main_icon06.png"></a>
            <a href="#"><img src="/m/img/go_main_icon07.png"></a>
            <a href="/m/game/minigame/kenoladder/"><img src="/m/img/go_main_icon08.png"></a>
            <a href="/m/result/sports/"><img src="/m/img/go_main_icon09.png"></a>
            <a href="/m/mypage/betlist/sports/"><img src="/m/img/go_main_icon10.png"></a>
            <a href="/m/mypage/board/"><img src="/m/img/go_main_icon11.png"></a>
            <a href="/m/mypage/customer/"><img src="/m/img/go_main_icon12.png"></a>
        </div>

        <div class="main_notice">
            <div class="title">
                공지사항 <span>TEXAS NOTICE</span>
            </div>
            <ul class="main_notice_list">
            <?php
                $que = "SELECT * FROM board WHERE B_ID = 'Notice' AND B_Type = 'Notice' AND B_Delete = 'N' ORDER BY B_RegDate DESC LIMIT 4";
                $notice = getArr($que);
                if(count($notice)>0){
                    foreach($notice as $notice){
            ?>
                <li onclick="location.href='/m/mypage/notice/view/?b_key=<?php echo $notice[B_Key];?>'">
                    <span><?php echo $notice['B_Subject'];?></span><em>| &nbsp&nbsp <?php echo date("m/d",strtotime($notice['B_RegDate']));?></em>
                </li>
                    <?php }} ?>
            </ul>
        </div>
        <div class="cs_center">
            <div class="text1">현재 텍사스 상담원이 상담 대기중 입니다. 문의사항이 있으시면 언제든지 문의해주세요. 친절하고 자세하게 답변드리겠습니다.</div>
            <div class="cs_btn">
                <a class="request-bank">계좌문의</a>
                <a href="/m/mypage/customer/">1:1문의 신청</a>
            </div>
            <div class="cscenter_box">
                <div class="katalk">
                    <em><img src="/m/img/icon_kakaotalk.png"></em>
                    <?php echo $SITECONFIG['kakaotalk'];?>
                </div>
                <div class="katalk">
                    <em><img src="/m/img/icon_telegram.png"></em>
                    <?php echo $SITECONFIG['telegram'];?>
                </div>
            </div>
        </div>

        <!--<div class="main_event">
            <a href="./event_list.html">
                <img src="/m/img/main_event_img_n.png">
            </a>
        </div>-->

        <!--<div class="main_money_list first">
            <div class="title">금주의 출금<span>TOP</span></div>
            <div class="time_box">
                <ul class="week_money">
                    <li>
                        <em>1</em>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="name">te***122</span>
                    </li>
                    <li>
                        <em>2</em>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="name">te***122</span>
                    </li>
                    <li>
                        <em>3</em>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="name">te***122</span>
                    </li>
                    <li>
                        <em>4</em>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="name">te***122</span>
                    </li>
                    <li>
                        <em>5</em>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="name">te***122</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="main_money_list">
            <div class="title">실시간 <span style="font-style:normal;">입금/출금</span> 현황</div>
            <div class="time_box">
                <ul class="realtime">
                    <li>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="status dp">입금</span>
                        <span class="name">te***122</span>
                    </li>
                    <li>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="status dp">입금</span>
                        <span class="name">te***122</span>
                    </li>
                    <li>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="status wd">출금</span>
                        <span class="name">te***122</span>
                    </li>
                    <li>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="status wd">출금</span>
                        <span class="name">te***122</span>
                    </li>
                    <li>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="status wd">출금</span>
                        <span class="name">te***122</span>
                    </li>
                    <li>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="status dp">입금</span>
                        <span class="name">te***122</span>
                    </li>
                    <li>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="status dp">입금</span>
                        <span class="name">te***122</span>
                    </li>
                    <li>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="status wd">출금</span>
                        <span class="name">te***122</span>
                    </li>
                    <li>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="status wd">출금</span>
                        <span class="name">te***122</span>
                    </li>
                    <li>
                        <span class="date">17.09.12 00:11</span>
                        <span class="money">20,000,000원</span>
                        <span class="status wd">출금</span>
                        <span class="name">te***122</span>
                    </li>
                </ul>
            </div>
        </div>-->





        <!-- 메인 닫기 -->
    </div>


    <script>
        notice_getCookie('Notice1',1);
        notice_getCookie('Notice2',2);
        notice_getCookie('Notice3',3);
        notice_getCookie('Notice4',4);
        notice_getCookie('Notice5',5);
        function notice_getCookie( name, idx ){
            var nameOfCookie = name + "=done";
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
                /*$('#apDiv2').show();
                $('#apDiv3').show();
                $('#apDiv4').show();
                $('#apDiv5').show();*/
            }
            return "";
        }

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
    </script>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>