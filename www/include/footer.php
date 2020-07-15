</div> <!-- body-contents -->

<div id="footer">
    <div class="fmenu">
        <ul>
            <!--<li onclick="location.href='/game/live/'">라이브베팅</li>
            <li onclick="location.href='/game/sports/cross/'">크로스</li>
            <li onclick="location.href='/game/sports/special/'">스페셜</li>
            <li onclick="location.href='/game/sports/WDL/'">승무패</li>
            <li onclick="location.href='/game/sports/handicap/'">핸디캡</li>
            <li onclick="location.href='/game/sports/underover/'">언오버</li>
            <li onclick="location.href='/game/casino/?gcode=1'">카지노게임</li>
            <li onclick="location.href='/game/minigame/powerball/'">미니게임</li>
            <li onclick="location.href='/game/virtual/soccer/'">가상게임</li>
            <li onclick="location.href='/mypage/customer/'">고객센터</li>
            <li class="request-bank">계좌문의</li>
            <li onclick="location.href='/mypage/notice/'">공지사항</li>-->
            <li onclick="location.href='/m/main/?mobile=Y'">모바일버전</li>
            <!--<li onclick="location.href='/bettingrule/sports_soccer.html'">베팅규정</li>-->
        </ul>
    </div>

    <div class="partnership">
        <img src="/img/img_partner_banner.jpg" />
    </div>

    <div class="site_info">
        <h1 style="font-size:12px !important;">
            텍사스는 게임 엔터테인먼트기업의 선두주자이며, 세계 최고 수준의 온라인게임을 제공합니다. 베팅플레이어가 재미있게 즐길 수 있고 최고의 만족감을 선사하는 것이 미션이며,<BR />
            고객의 니즈에 맞는 서비스를 제공하는 것을 목표로 하고있습니다. 당사는 어디서나 이용할 수 있는 최고품질의 게임을 제공하며, 수 천 번 이상 테스트 및 검증을 통하여<BR />
            최고 수준의 기술력과 세계 최고 수준의 보안기술을 보유하고 있습니다. 또한 스포츠게임의 최고배당률을 제공과 현지에서 느낄 수 있는 사실적인 카지노, 미니게임등 다양한 게임을 제공하고 있습니다.
        </h1>
    </div>
</div> <!-- footer -->

</div> <!-- wrap -->

<?php
    $page_link = explode("/",$_SERVER['PHP_SELF']);
?>

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

<script>
    $(document).ready(function(){
        $.datepicker.setDefaults({
            dateFormat: 'yy-mm-dd',
            prevText: '이전 달',
            nextText: '다음 달',
            monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
            monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
            dayNames: ['일', '월', '화', '수', '목', '금', '토'],
            dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
            dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
            showMonthAfterYear: true,
            yearSuffix: '년'
        });
        $( '#startDate,#endDate').datepicker();
        sameLoginChk();
        setInterval('sameLoginChk()',5000);
        setInterval('chk_message()',5000);
        setInterval('site_time()',1000);
    });

    <?php if($_SESSION['S_ID']!= 'ppppp1'){ ?>
    //동일 로그인 체크하기
    function sameLoginChk(){
        $.ajax({
           type : 'post',
           dataType : 'json',
           url : '/include/loginChk.php',
           success : function(data){
                if(data.flag==true){
                    swal('','중복 로그인으로 로그아웃됩니다.','warning');
                    setTimeout(function(){location.href='/login/'},3000);
                }
           }
        });
    }
    <?php } ?>
    //동일 로그인 체크하기
    function site_time(){
        $.ajax({
            type : 'post',            
            url : '/include/ajax.php',
            data : 'mode=serverTimer',
            success : function(data){
                $('#site_date_timer').text(data);
            }
        });
    }
    
    function pageWaitTimeChk(){
        $.ajax({
            type : 'post',
            dataType : 'json',
            url : '/include/ajax.php',
            data : 'mode=pageWaitTimeChk',
            success : function(data){
                if(data.flag==false){
                    swal('','3분동안 페이지 이동이 없어 로그아웃됩니다.','warning');
                    setTimeout(function(){location.href='/login/'},3000);
                }
            }
        });
    }

    <?php if($page_link[2] != 'message'){ ?>
    function chk_message(){
        $.ajax({
            type : 'post',
            dataType : 'json',
            url : '/include/ajax.php',
            data : 'mode=messageChk',
            success : function(data){
                if(data.flag==true){

                    if(data.cnt > 0) {
                        swal({
                            text: "쪽지를 확인해주세요.",
                            type: "success",
                            confirmButtonText: "확인",
                        }).then(function (isConfirm) {
                            if (isConfirm) {
                                location.href = '/mypage/message/';
                            }
                        });
                    }
                }
            }
        });
    }
    <?php } ?>
</script>

</body>
</html>
