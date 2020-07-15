<!-- body_wrap 닫기-->
</div>

<?php
    $total_item = 0;
    $tb = "gamelist a LEFT JOIN gameitem c ON a.GI_Key = c.GI_Key LEFT JOIN gameleague b ON a.GL_Key = b.GL_Key_IDX";
    $where = " 1 AND G_Locked =  '1' AND G_Datetime > NOW() AND G_QuotaWin > 0 AND G_SubType IN (60461,352321,482646,1549366,1548352) AND G_OType1 = 'G' AND b.GL_State = 'Normal' AND a.G_State = 'Await' ";
    $where .= " AND G_QuotaWin > {$SITECONFIG['sport_rate_base']} AND G_QuotaLose > {$SITECONFIG['sport_rate_base']} ";
    $que = "SELECT COUNT(DISTINCT(inPlayMatchIdx)) AS cnt FROM {$tb} WHERE {$where} ";
    $cross = getRow($que);

    $tb = "gamelist a LEFT JOIN gameitem c ON a.GI_Key = c.GI_Key LEFT JOIN gameleague b ON a.GL_Key = b.GL_Key_IDX";
    $where = " 1 AND G_Locked =  '1' AND G_Datetime > NOW() AND G_QuotaWin > 0 AND G_SubType IN (60461,352321,482646,1549366,1548352) AND G_OType1 = 'G' AND G_Type1 = 'Full' AND G_Type2 = 'WDL' AND b.GL_State = 'Normal' ";
    $where .= " AND G_QuotaWin > {$SITECONFIG['sport_rate_base']} AND G_QuotaLose > {$SITECONFIG['sport_rate_base']} ";
    $que = "SELECT COUNT(DISTINCT(inPlayMatchIdx)) AS cnt FROM {$tb} WHERE {$where} ";
    $wdl = getRow($que);


    $tb = "gamelist a LEFT JOIN gameitem c ON a.GI_Key = c.GI_Key LEFT JOIN gameleague b ON a.GL_Key = b.GL_Key_IDX";
    $where = " 1 AND G_Locked =  '1' AND G_Datetime > NOW() AND G_Type2 = 'Handicap' AND G_OType1 = 'G' AND b.GL_State = 'Normal' ";
    $where .= " AND G_QuotaHandiWin > {$SITECONFIG['sport_rate_base']} AND G_QuotaHandiLose > {$SITECONFIG['sport_rate_base']}";
    $que = "SELECT COUNT(DISTINCT(inPlayMatchIdx)) AS cnt FROM {$tb} WHERE {$where} ";
    $handicap = getRow($que);


    $tb = "gamelist a LEFT JOIN gameitem c ON a.GI_Key = c.GI_Key LEFT JOIN gameleague b ON a.GL_Key = b.GL_Key_IDX";
    $where = " 1 AND G_Locked =  '1' AND G_Datetime > NOW() AND G_Type2 = 'UnderOver' AND G_Type1 = 'Full' AND G_SubType NOT IN (60562,60561) AND b.GL_State = 'Normal' ";
    $where .= " AND G_QuotaUnder > {$SITECONFIG['sport_rate_base']} AND G_QuotaOver > {$SITECONFIG['sport_rate_base']} ";
    $que = "SELECT COUNT(DISTINCT(inPlayMatchIdx)) AS cnt FROM {$tb} WHERE {$where} ";
    $underover = getRow($que);


    $tb = "gamelist a LEFT JOIN gameitem c ON a.GI_Key = c.GI_Key  LEFT JOIN gameleague b ON a.GL_Key = b.GL_Key_IDX ";
    $where = " 1 AND G_Locked =  '1' AND G_Datetime > NOW() AND G_SubType NOT IN (60461,352321,482646,1549366,1548352) AND G_OType1 = 'S' AND b.GL_State = 'Normal' ";
    $where .= " AND (G_QuotaHandiWin > {$SITECONFIG['sport_rate_base']} AND G_QuotaHandiLose > {$SITECONFIG['sport_rate_base']} ";
    $where .= " OR G_QuotaUnder > {$SITECONFIG['sport_rate_base']} AND G_QuotaOver > {$SITECONFIG['sport_rate_base']} OR G_QuotaWin > {$SITECONFIG['sport_rate_base']} AND G_QuotaDraw > {$SITECONFIG['sport_rate_base']} AND G_QuotaLose > {$SITECONFIG['sport_rate_base']}  ) ";
    $que = "SELECT COUNT(DISTINCT(inPlayMatchIdx)) AS cnt FROM {$tb} WHERE {$where} ";
    $special = getRow($que);



    $brower_sam = "N";
    if(strpos($_SERVER['HTTP_USER_AGENT'],'SamsungBrowser')!==false){
        $brower_sam = 'Y';
    }

?>
<!-- 푸터 -->
<div id="footer">
	<div class="footer_go_top go_top" onclick="$('html,body').animate({scrollTop:0}, 400);">
		BACK TO PAGE TOP <em><img src="/mobile/img/go_top_icon.png"></em>
	</div>
		<div class="pathner"><img src="/mobile/img/foot_parthner.png"></div>
	<div class="about">

		<div class="cscenter_box">
			<div class="katalk">
				<em><img src="/mobile/img/icon_kakaotalk.png"></em>
                <?php echo $SITECONFIG['kakaotalk'];?>
			</div>
			<div class="katalk">
				<em><img src="/mobile/img/icon_telegram.png"></em>
                <?php echo $SITECONFIG['telegram'];?>
			</div>
		</div>
		<!--<h1><span onclick="location.href = '/main/?pcmode=Y&mobile=N';">PC버전으로 보기</span></h1>-->
		<div class="copyright" onclick="location.href = '/main/?pcmode=Y&mobile=N';">Copyright TEXAS Corp⒞. All Rights Reserved.</div>
	</div>
	
<!-- 메뉴 -->
<div class="menu_wrap">
	<div class="menu_mask" onclick="menu_flag()"><div class="menu_close"><img src="/mobile/img/icon_menu_close.png"></div></div>
	<div class="menu left" id="menu_left" data-menu="left">
		<div>
			<!--<ul class="left_top_btn">
				<li onclick="location.href='/mobile/mypage/cash_in.html'"><img src="/mobile/img/menu_icon/menu_icon32.png"><span>충전</span></li>
				<li onclick="location.href='/mobile/mypage/cash_out.html'"><img src="/mobile/img/menu_icon/menu_icon33.png"><span>환전</span></li>
				<li onclick="location.href='/mobile/mypage/memo.html'"><img src="/mobile/img/menu_icon/menu_icon34.png"><span>포인트</span></li>
				<li onclick="location.href='/mobile/login.html'"><img src="/mobile/img/menu_icon/menu_icon35.png"><span>문의</span></li>
			</ul>-->
			<ul class="left_top_btn">
				<li onclick="location.href='/m/money/charge/'">
					<span><img src="/mobile/img/menu_icon/menu_icon001.png"></span>
					<var>충전</var>
				</li>
				<li onclick="location.href='/m/money/refund/'">
					<span><img src="/mobile/img/menu_icon/menu_icon002.png"></span>
					<var>환전</var>
				</li>
				<li onclick="location.href='/m/mypage/point/list/'">
					<span><img src="/mobile/img/menu_icon/menu_icon003.png"></span>
					<var>포인트</var>
				</li>
				<li onclick="location.href='/m/mypage/customer/'">
					<span><img src="/mobile/img/menu_icon/menu_icon004.png"></span>
					<var>문의</var>
				</li>
			</ul>

			<ul class="menu_list one_line">
				<li class="blue top open">
					<span><em><img src="/mobile/img/menu_icon/new/menu_icon01.png"></em><code>스포츠게임<var>SPORTS GAME</var></code><h1></h1></span>
					<div class="m_type1 blue">
						<a href="javascript:;" onclick="swal('','오픈준비중 입니다.','warning');"><span>· 라이브베팅</span> <em>0</em></a>
						<a href="/m/game/sports/cross/"><span>· 조합베팅</span> <em><?php echo number_format($cross[0]); ?></em></a>
						<a href="/m/game/sports/WDL/"><span>· 승무패베팅</span> <em><?php echo number_format($wdl[0]); ?></em></a>
						<a href="/m/game/sports/handicap/"><span>· 핸디캡베팅</span> <em><?php echo number_format($handicap[0]); ?></em></a>
						<a href="/m/game/sports/underover/"><span>· 언오버베팅</span> <em><?php echo number_format($underover[0]); ?></em></a>
						<a href="/m/game/sports/special/"><span>· 스페셜베팅</span> <em><?php echo number_format($special[0]); ?></em></a>
						<!--<a><span>· 전반전베팅</span> <em>1,021</em></a>
						<a><span>· 코너킥베팅</span> <em>1,021</em></a>-->
					</div>
				</li>
				<li class="blue">
					<span class="bg_none"><em><img src="/mobile/img/menu_icon/new/menu_icon02.png"></em><code>카지노<var>EVOLUTION CASINO</var></code><h1></h1></span>
                    <div class="m_type1 blue">
                        <a href="/m/game/casino/?gcode=1"><span>· VIVO</span> </a>
                        <a href="/m/game/casino/?gcode=21"><span>· 마이크로게임</span> </a>
                        <a href="/m/game/casino/?gcode=28"><span>· 드림게임</span> </a>
                        <a href="/m/game/casino/?gcode=45"><span>· 에볼루션</span> </a>

                    </div>
				</li>
                <li class="blue">
                    <span class="bg_none"><em><img src="/mobile/img/menu_icon/new/menu_icon02.png"></em><code>슬롯게임<var>SLOT GAMES</var></code><h1></h1></span>
                    <div class="m_type1 blue">
                        <a href="/m/game/slot/?slot_type=8"><span>· 플레메틱</span> </a>
                        <a href="/m/game/slot/?slot_type=24"><span>· 하바네로</span> </a>
                        <a href="/m/game/slot/?slot_type=33"><span>· 제네시스</span> </a>
                        <a href="/m/game/slot/?slot_type=36"><span>· 플레이선</span> </a>

                    </div>
                </li>
				<li class="cyan top open">
					<span><em><img src="/mobile/img/menu_icon/new/menu_icon04.png"></em><code>미니게임<var>TEXAS MINIGAMES</var></code><h1></h1></span>
					<div class="m_type2 cyan">
						<a href="/m/game/minigame/powerball">· 파워볼게임</a>
						<a href="/m/game/minigame/pwladder">· 파워사다리</a>
						<!--<a href="/mobile/minigame/kino_speed.html">· 스피드키노</a>-->
						<a href="/m/game/minigame/kenoladder">· 키노사다리</a>
					</div>
				</li>
				<li class="cyan bottom open">
					<span><em><img src="/mobile/img/menu_icon/new/menu_icon05.png"></em><code>가상게임<var>TEXAS VIRTUALGAMES</var></code><h1></h1></span>
					<div class="m_type2 cyan">
						<a href="/m/game/virtual/soccer/">· 가상축구컵</a>
						<a href="/m/game/virtual/horse/">· 가상경마</a>
                        <a href="/m/game/virtual/dog/">· 가상개경주</a>
					</div>
				</li>
				<li class="green top open">
					<span class="bg_none"><em><img src="/mobile/img/menu_icon/new/menu_icon06.png"></em><code>경기결과<var>GAME RESULT</var></code><h1></h1></span>
					<div class="m_type2">
						<a href="/m/result/sports/">· 스포츠 경기결과</a>
						<a href="/m/result/minigame/power/">· 미니게임 경기결과</a>
						<a href="/m/game/virtual/dog/">· 가상게임 경기결과</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="menu right" id="menu_right" data-menu="right">
		<div>
			<h1 class="myprofile">
				<span class="lv<?php echo $disp_mb_lv[$_SESSION['S_Level']];?>"><?php echo $_SESSION['S_Nick']; ?> <b onClick="location.href='/m/mypage/message/'"><?php echo $lib24c->GetNewMemo($lib24c->member_info['M_Key']); ?></b></span>
				<div>
					<em onClick="location.href='/m/attend/'">출석부</em>
					<!--<em onClick="location.href='/mobile/mypage/coupon.html'">쿠폰관리</em>-->
					<!--<em class="tel" onclick="alert('전화상담신청이 완료되었습니다. 5분 이내에 전화드리겠습니다.'); location.href='/mobile/mypage/memo.html'">전화상담</em>-->
					<em class="logout" onClick="location.href='/m/logout/'">로그아웃</em>
				</div>
			</h1>

			<ul class="menu_list">
				<li class="right_top_btn">
					<span><em><img src="/mobile/img/menu_icon/new/menu_icon06.png"></em><code>베팅내역<var>BETTING LIST</var></code></span>
					<ul>
						<li onclick="location.href='/m/mypage/betlist/sports/'">
							<span>스포츠<BR />베팅내역</span>
						</li>
						<li onclick="location.href='/m/mypage/betlist/casino/'">
							<span>카지노<BR />베팅내역</span>
						</li>
						<li onclick="location.href='/m/mypage/betlist/minigame/power/'">
							<span>미니게임<BR />베팅내역</span>
						</li>
						<li onclick="location.href='/m/mypage/betlist/virtual/soccer/'">
							<span>가상게임<BR />베팅내역</span>
						</li>
					</ul>
				</li>

				<li class="blue top open">
					<span class="quick1"><em><img src="/m/img/menu_icon/new/menu_icon07.png"></em><code>보유머니충전<var>TEXAS MONEY CHARGE</var></code><h1></h1></span>
					<div class="m_type2 blue">
						<a href="/m/money/charge/" class="hit">· 보유머니 충전신청</a>
						<a href="/m/money/charge/list/">· 보유머니 충전내역</a>
					</div>
				</li>
				<li class="blue open">
					<span class="quick2"><em><img src="/m/img/menu_icon/new/menu_icon08.png"></em><code>보유머니환전<var>TEXAS MONEY EXCHANGE</var></code><h1></h1></span>
					<div class="m_type2 blue">
						<a href="/m/money/refund/" class="hit">· 보유머니 환전신청</a>
						<a href="/m/money/refund/list/">· 보유머니 환전내역</a>
					</div>
				</li>
				<li class="blue bottom open">
					<span class="quick3"><em><img src="/m/img/menu_icon/new/menu_icon09.png"></em><code>포인트전환<var>TEXAS POINT EXCHANGE</var></code><h1></h1></span>
					<div class="m_type2 blue">
						<a href='/m/mypage/point/exchange/'" class="on">· 포인트 전환 신청</a>
						<!--<a href='/m/mypage/point/save/list/'">· 포인트 적립 내역</dd>-->
						<a href='/m/mypage/point/exchange/list/'">· 포인트 전환 내역</a>
					</div>
				</li>
                <li class="blue top open">
                    <span class="quick1"><em><img src="/m/img/menu_icon/new/menu_icon07.png"></em><code>카지노머니충전/환전<var>TEXAS CASINO CHARGE</var></code><h1></h1></span>
                    <div class="m_type2 blue">
                        <a href="/m/money/charge/casino/" class="hit">· 카지노머니 충전/환전신청</a>
                        <a href="/m/money/charge/casino/list/">· 카지노머니 충전/환전내역</a>
                    </div>
                </li>
                <li class="cyan top bottom open">
					<span><em><img src="/mobile/img/menu_icon/new/menu_icon10.png"></em><code>라이브영상<var>LIVESTREAM</var></code><h1></h1></span>
					<div class="m_type2 cyan">
						<a href='javascript:;' onclick="swal('','라이브영상 서비스는 준비중입니다.','success');" class="on">· 라이브영상</a>
					</div>
				</li>
				<!--<li class="cyan top bottom open">
					<span><em><img src="/mobile/img/menu_icon/new/menu_icon10.png"></em><code>총판관리<var>TEXAS RECOMMAND</var></code><h1></h1></span>
					<div class="m_type2 cyan">
						<a href='/mobile/mypage/recom.html'" class="on">· 총판현황</a>
						<a>· 정산내역</a>
					</div>
				</li>-->
				<li onClick="location.href='/m/mypage/message/'" class="green msg">
					<span class="bg_none"><em><img src="/m/img/menu_icon/new/menu_icon12.png"></em><code>쪽지함<var>TEXAS MASSENGER</var></code><label><?php echo $lib24c->GetNewMemo($lib24c->member_info['M_Key']); ?></label></span>
				</li>
				<li onClick="location.href='#'" class="green">
					<span class="bg_none"><em><img src="/m/img/menu_icon/new/menu_icon13.png"></em><code>진행중인이벤트<var>TEXAS HOT EVENT</var></code></span>
				</li>
				<li onClick="location.href='/m/mypage/board/'" class="green bottom">
					<span class="bg_none"><em><img src="/m/img/menu_icon/new/menu_icon14.png"></em><code>게시판<var>TEXAS FREEBOARD</var></code></span>
				</li>
				<li class="brown top open">
					<span><em><img src="/m/img/menu_icon/new/menu_icon15.png"></em><code>고객센터<var>TEXAS CUSTOMER CENTER</var></code><h1></h1></span>
					<div class="m_type2 brown">
						<a href='/m/mypage/customer/'">· 문의하기</a>
						<a href='/m/mypage/faq/'">· 자주묻는질문</a>
						<a href='/m/mypage/notice/'">· 공지사항</a>
					</div>
				</li>
				<li class="brown request-bank">
					<span class="bg_none"><em><img src="/mobile/img/menu_icon/new/menu_icon16.png"></em><code>계좌문의신청<var>TEXAS CUSTOMER CENTER</var></code></span>
				</li>
				<!--<li onclick="alert('전화상담신청이 완료되었습니다. 5분 이내에 전화드리겠습니다.'); location.href='/mobile/mypage/memo.html'" class="brown">
					<span class="bg_none"><em><img src="/mobile/img/menu_icon/new/menu_icon17.png"></em><code>전화상담신청<var>TEXAS CUSTOMER CENTER</var></code></span>
				</li>-->
				<li>
					<a href="/mobile/mypage/member_confirm.html">회원정보수정</a>
					<a href="/m/logout/">로그아웃</a>
				</li>
			</ul>
		</div>
	</div>
	


</div>



<!-- wrap 닫기-->
</div>
<?php
$page_link = explode("/",$_SERVER['PHP_SELF']);

?>
<form name="HiddenActionForm1" style="display:none;" >
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

        $('.request-bank').on('click',function(){
            swal({
                title: "계좌번호",
                text: "계좌번호를 요청 하시겠습니까?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "확인",
                cancelButtonText: "취소"
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url : '/m/money/charge/proc/',
                        type : 'post',
                        dataType : 'json',
                        data : {'HAF_Value_0' : 'requestBank'},
                        success : function(res){
                            if(res.flag == true){
                                swal('','완료되었습니다. 쪽지를 확인해주세요.','success');
                            } else {
                                swal('','계좌요청시 오류가 발생했습니다. 잠시후에 다시 시도해주세요.'+res.error,'warning');
                            }
                            //setTimeout(function(){ location.reload();},3000);
                        },complete:function(data){
                        },error:function(request, status, error){
                            console.log(request);
                            console.log(status);
                            console.log(error);
                        }
                    });
                }
            });
        });
        $( '#startDate,#endDate').datepicker();
        sameLoginChk();
        setInterval('sameLoginChk()',5000);
        setInterval('chk_message()',5000);
    });

    //동일 로그인 체크하기
    function sameLoginChk(){
        $.ajax({
            type : 'post',
            dataType : 'json',
            url : '/m/include/ajax.php',
            data : 'mode=sameloginchk',
            success : function(data){
                if(data.flag==false){
                    swal('','중복 로그인으로 로그아웃됩니다.','warning');
                    setTimeout(function(){location.href='/m/login/'},3000);
                }
            }
        });
    }

    function pageWaitTimeChk(){
        $.ajax({
            type : 'post',
            dataType : 'json',
            url : '/m/include/ajax.php',
            data : 'mode=pageWaitTimeChk',
            success : function(data){
                if(data.flag==false){
                    swal('','3분동안 페이지 이동이 없어 로그아웃됩니다.','warning');
                    setTimeout(function(){location.href='/login/'},1000);
                }
            }
        });
    }

    <?php if($page_link[3] != 'message'){ ?>
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
                                location.href = '/m/mypage/message/';
                            }
                        });
                    }
                }
            }
        });
    }
    <?php } ?>

    <?php if($brower_sam == 'Y'){ ?>
    swal({
        text: "삼성브라우저는 배팅시 오류가 발생할 수 있습니다. 크롬을 사용해주세요.",
        type: "success",
        confirmButtonText: "확인",
    }).then(function (isConfirm) {
        if (isConfirm) {
            location.href = '/m/main/';
        }
    });
        setTimeout(function(){

            swal({
                text: "삼성브라우저는 배팅시 오류가 발생할 수 있습니다. 크롬을 사용해주세요.",
                type: "success",
                confirmButtonText: "확인",
            }).then(function (isConfirm) {
                if (isConfirm) {
                    location.href = '/m/main/';
                }
            });
        },3000);
    <?php } ?>

</script>
</body>
</html>