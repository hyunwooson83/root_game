	<!-- 카트시작 -->
	<?
		$BettingQuota = "1.00";
		$que = "SELECT * FROM cartgamelist a, gamelist b WHERE a.G_Key = b.G_Key AND a.M_Key = {$_SESSION[S_Key]}";
		//echo $que;
		$arr = getArr($que);
		$cnt = count($arr);
		if($cnt>0){
			foreach($arr as $rows){
				
				
			  $prefix = "";
			  switch($rows['G_Type1']) {
				case "Half" : $prefix = "(하프)"; break;
				case "Full" : $prefix = "(풀)"; break;
				case "Special" : $prefix = "(스)"; break;
			  };
	
	
			  switch($rows['CGL_ResultChoice']){
				case "Win"        : $cgl_result = "승"; $cgl_quota = $rows[CGL_QuotaWin]; break;
				case "Draw"       : $cgl_result = "무"; $cgl_quota = $rows[CGL_QuotaDraw]; break;
				case "Lose"       : $cgl_result = "패"; $cgl_quota = $rows[CGL_QuotaLose]; break;
				case "HandiWin"   : $cgl_result = "핸승"; $cgl_quota = $rows[CGL_QuotaHandiWin]; break;
				case "HandiLose"  : $cgl_result = "핸패"; $cgl_quota = $rows[CGL_QuotaHandiLose]; break;
				case "Under"      : $cgl_result = "언더"; $cgl_quota = $rows[CGL_QuotaUnder]; break;
				case "Over"       : $cgl_result = "오버"; $cgl_quota = $rows[CGL_QuotaOver]; break;
				case "Odd"        : $cgl_result = "홈"; $cgl_quota = $rows[CGL_QuotaOdd]; break;
				case "Even"       : $cgl_result = "원정"; $cgl_quota = $rows[CGL_QuotaEven]; break;
			  };
			 $BettingQuota *= $cgl_quota;			 
			 $BettingQuota = substr($BettingQuota,0,4);			 
	 			 
			 $html1 .= '
				<li>
					<dl>
						<dt>'.$rows['G_Team1'].'</dt>
						<dd><span>'.$prefix.'</span><a href="javascript:;" onclick="GameCartDelete('.$rows[CGL_Key].');"><img src="../images/icons/x-blue-icon.png" alt="베팅취소" /></a></dd>
						<dt>'.$rows['G_Team2'].'</dt>
						<dd><span>'.$cgl_quota.'</span></dd>
					</dl>
				</li>			
			';
			 $cnt++; }} 
			 $price = ($price>0)?$price:5000;
	?>		

<div id="right_wrapper">
	<div class="sidebar">
		<div id="floatdiv"
		style="position:absolute;
			width:232px;
			z-index:999;
			left:auto !important;
			right:1px !important;
			top:248px;">
			
			<!-- 시계 -->
			<div class="time_con">
				<embed src="http://www.clocklink.com/clocks/5012-black.swf?TimeZone=KoreaRepublicof_Seoul&"  width="200" height="60" wmode="transparent" type="application/x-shockwave-flash">
			</div>
			<!-- 배당률 -->
			<div class="cart_cost">
				<div class="header">
					<span>게임선택</span>
					<p>배팅카트고정<input type="checkbox" id="gostop" onclick="cart_status(this);"  /></p>
				</div>
				<dl>
					<dt>예상배당률</dt>
					<dd id="BettingQuota"><?=$BettingQuota?>&nbsp;&nbsp;</dd>
				</dl>
				<dl>
					<dt>예상배당금</dt>
					<dd id="BettingQuotaMoney"><?=number_format((int)($price*$BettingQuota))?>&nbsp;&nbsp;</dd>
				</dl>
				<dl>
					<dt>배팅금액</dt>
					<dd>
						<span style="float:right;"><a href="javascript:;"  onclick="GameCartAllDelete();">삭제</a></span>
						<input name="BettingMoney" type="text" id="BettingMoney" style="width:80px; margin:0; font-weight:bold; float:right; font-size:14px;" value="<?=$price?>" onkeyup="javascript:is_onlynumeric( this.value, this );CalcCart(this.value);" />
					</dd>
				</dl>
			</div>
			<!-- 베팅선택게임 리스트 -->
			<div class="b_g_list">
				<ul>
					<?=$html1?>              					
				</ul>
				<p class="btn_betting"><a href="javascript:;" OnClick="GameCartBuy(<?=$Cart_Cnt?>);" class="ui-button_red">베팅하기</a></p>
			</div>               
		</div>
	</div>
	<div class="clear"></div> 
</div>
        
<script language="JavaScript">
	<!--	
		//$(document).ready(function(){
			cart_go();
		//});
	//-->
    
	
	function cart_status()
	{
		if($('#gostop').is(':checked')==true){
			cart_stop();
		} else {
			cart_go();
		}
	}
	
	function cart_stop()
	{
		var options = { 'speed' : 0, // 스피드
						'initTop':0 ,  // 기본 top 위치
						'alwaysTop' : true, // 항상고정 true , false 이동
						'default_x' : '#body'  //레어아웃이 가운데 정렬 일때 레이어가 붙는 아이디값
					   }
		$('#floatdiv').Floater(options);
		$('#floatdiv').show();
	}
	function cart_go()
	{
		var options = { 'speed' : 0, // 스피드
						'initTop':245 ,  // 기본 top 위치
						'alwaysTop' : false, // 항상고정 true , false 이동
						'default_x' : '#body'  //레어아웃이 가운데 정렬 일때 레이어가 붙는 아이디값
					   }
		$('#floatdiv').Floater(options);
		$('#floatdiv').show();
	}	
	
	//GameCartLoading();
</script>