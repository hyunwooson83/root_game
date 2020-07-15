<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';
    $mkey = $mkeyi = "";
    $sql = "SELECT M_Key FROM members WHERE M_Recom = '{$meminfo['M_Recom_Code']}'";
    $arr = getArr($sql);
    if(count($arr)>0){
        foreach($arr as $list){
            $mkey[] = $list['M_Key'];
        }
        $mkeyi = implode(",",$mkey);
    }

    if(empty($startDate)) $startDate = date("Y-m-d");
    if(empty($endDate))   $endDate = date("Y-m-d");
?>

<div class="sub_bg">
	<div class="sub_wrap type2">
		<div class="sub_title">
			<div class="title1">하부회원보기</div>
			<div class="title2">MY MEMBER</div>
		</div>	
		<div class="sub-box">

			<div class="mypage_menu1">
				<a href="/_go/renewal/mypage/betlist_sports.html">베팅내역</a>
				<a href="/_go/renewal/mypage/money_money_charge.html">충전/환전내역</a>
				<a href="/_go/renewal/point_exchange.html">포인트내역</a>
				<a href="/_go/renewal/mypage/recom_main.html" class="active">총판관리</a>
				<a href="/_go/renewal/mypage/coupon_use.html">쿠폰관리</a>
				<a href="/_go/renewal/mypage/memo_list.html">쪽지관리</a>
				<a href="/_go/renewal/mypage/member_confirm.html">회원정보수정</a>
			</div>
			<div class="mypage_menu2">
				<a href="/_go/renewal/mypage/recom_main.html">총판현황</a>
				<a href="/_go/renewal/mypage/recom_add.html">나를추천한회원</a>
				<a href="/_go/renewal/mypage/recom_list.html" class="active">하부회원보기</a>
				<a>승패보고서</a>
				<a>승패정산보고서</a>
				<a>정산내역</a>
			</div>

			<div class="board_wrap">


                <form name="f" id="f" method="get" action="./recom_list.php">
                    <div class="mypage-day-search">
                        <div class="title">베팅기간</div>
                        <div class="input_box">
                            <span class="active" data-day="<?php echo date("Y-m-d"); ?>">오늘</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-7 day")); ?>">1주일</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-15 day")); ?>">15일</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-1 month")); ?>">1개월</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-3 month")); ?>">3개월</span> &nbsp;
                            <input type="text" name="startDate" id="startDate" value="<?php echo $startDate; ?>">&nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;<input type="text" name="endDate" id="endDate" value="<?php echo $endDate; ?>">&nbsp;
                            <code class="view" onclick="document.f.submit();">조회하기</code>
                        </div>
                    </div>
                </form>

				<div class="b_title2">
					<span>집계현황</span> | 아래 회원현황의 집계정보를 확인할 수 있습니다.
				</div>	

				<table class="table-black calc_info big">
					<thead>
						<tr>
							<td width="13%">총 보유머니</td>
							<td width="13%">총 입금금액</td>
							<td width="13%">총 출금금액</td>
							<td width="13%">스포츠게임</td>
							<td width="13%">카지노게임</td>
							<td width="13%">미니게임</td>
							<td width="13%">가상게임</td>
							<td>나의 회원수</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>50,000</td>
							<td>500.000</td>
							<td>500.000</td>
							<td>500.000</td>
							<td>0</td>
							<td>500.000</td>
							<td>500.000</td>
							<td>5</td>
						</tr>
					</tbody>
				</table>
				<table class="table-black">
					<thead>
						<tr>
							<td width="15%">날짜</td>
							<td width="10%">입금</td>
							<td width="7%">출금</td>
							<td width="7%">입출손익</td>
							<td width="8%">배팅금액</td>
							<td width="8%">당첨금</td>
							<td width="8%">총</td>
						</tr>
					</thead>
					<tbody>
                    <?php
                        for($i=$startDate;$i<=$endDate;$i++){
                        $que = "
                                    SELECT 
                                      (SELECT SUM(R_Money) FROM requests WHERE R_Type1 = 'Charge' AND R_Type2 = 'Money' AND R_State = 'Done' AND M_Key IN ({$mkeyi}) AND DATE_FORMAT(R_ResultDate,'%Y-%m-%d') = '{$i}' ) AS charge_money
                                    , (SELECT SUM(R_Money) FROM requests WHERE R_Type1 = 'Refund' AND R_Type2 = 'Money' AND R_State = 'Done' AND M_Key IN ({$mkeyi}) AND DATE_FORMAT(R_ResultDate,'%Y-%m-%d') = '{$i}' ) AS refud_money                                    
                                    FROM requests a WHERE DATE_FORMAT(R_ResultDate,'%Y-%m-%d') = '{$i}'  ";
                        echo $que;
                        $chargeArefund = getRow($que);

                        $que = "
                                        SELECT                                           
                                         (SELECT SUM(MI_Money) FROM moneyinfo WHERE MI_Type = 'GameBetting'  AND M_Key IN ({$mkeyi}) AND DATE_FORMAT(MI_RegDate,'%Y-%m-%d') = '{$i}' ) AS betting_money
                                        , (SELECT SUM(MI_Money) FROM moneyinfo WHERE MI_Type = 'Quota'  AND M_Key IN ({$mkeyi}) AND DATE_FORMAT(MI_RegDate,'%Y-%m-%d') = '{$i}' ) AS hit_money
                                        FROM moneyinfo a WHERE DATE_FORMAT(MI_RegDate,'%Y-%m-%d') = '{$i}' ";
                    echo $que;
                        $betAhit = getRow($que);
                    ?>
						<tr>
							<td><?php echo $i;?></td>
							<td><font class="mypage-grnfont"><?php echo number_format($chargeArefund['charge_money']);?></td>
							<td><font class="mypage-orgfont"><?php echo number_format($chargeArefund['refud_money']);?></font></td>
                            <td class="table-right10"><?php echo number_format($chargeArefund['charge_money']-$chargeArefund['refud_money']);?></td>
							<td class="table-right10"><?php echo number_format(abs($betAhit['betting_money']));?></td>
							<td class="table-right10"><?php echo number_format($betAhit['hit_money']);?></td>
                            <td class="table-right10"><?php echo number_format(abs($betAhit['betting_money'])-$betAhit['hit_money']);?></td>
						</tr>
                    <?php } ?>
					</tbody>
				</table>
				<div id="recom_pop_bg"></div>
				<div id="recom_pop">
					<h1>미니게임 상세정보</h1>
					<div class="recom_popbody">
						<table>
							<thead>
								<tr>
									<th>분류</th>
									<td>베팅금액</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th>파워볼</th>
									<td>30,000</td>
								</tr>
								<tr>
									<th>파워사다리</th>
									<td>30,000</td>
								</tr>	
								<tr>
									<th>스피드키노</th>
									<td>30,000</td>
								</tr>	
								<tr>
									<th>키노사다리</th>
									<td>30,000</td>
								</tr>		
								<tr>
									<th>방구차사다리</th>
									<td>30,000</td>
								</tr>	
								<tr>
									<th>주사위게임</th>
									<td>30,000</td>
								</tr>
								<tr>
									<th>합계</th>
									<td>30,000</td>
								</tr>	
							</tbody>
						</table>
					</div>
					<div>
						<a class="cancel" onClick="$('#recom_pop_bg').hide();$('#recom_pop').hide();">닫기</a>
					</div>
				</div> <!-- 회원별 미니게임 상세정보 팝업 -->

			</div> <!-- board_wrap -->

			<div class="recom_ment">
				※리스트의 미니게임의 금액을 클릭하시면 상세내역을 확인할 수 있습니다.
			</div>
			<div class="paging_box">
				<a href="">◀</a><a href="">1</a><a href="" class="hit">2</a><a href="">3</a><a href="">4</a><a href="">5</a><a href="">6</a><a href="">7</a><a href="">8</a><a href="">9</a><a href="">10</a><a href="">▶</a>
			</div>
		</div> <!-- sub-box -->

	</div> <!-- sub_wrap -->

</div> <!-- sub_bg -->
    <script>
        $(document).ready(function(){
            $('.input_box > span').on('click',function(){
                var startDay  = $(this).data("day");
                $('input[name="startDate"]').val(startDay);
            });
            $('.input_box > span').on('click',function(){
                var startDay  = $(this).data("day");
                $('input[name="startDate"]').val(startDay);
            });
            $('.view').on('click',function(){
                $('#f').submit();
            });
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
        });

    </script>
<?php
include_once $root_path.'/include/footer.php';
?>