<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    if ( !$_SESSION['S_Key'] ) {
        swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }

    $sql = "SELECT                
                    (SELECT SUM(R_Money)  FROM requests b WHERE a.M_Key = b.M_Key AND R_Type1 = 'Charge' AND R_Type2 = 'Money' AND R_State = 'Done' AND DATE_FORMAT(R_RegDate,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')) SP
                    , (SELECT SUM(R_Money)  FROM requests b WHERE a.M_Key = b.M_Key AND  R_Type1 = 'Charge' AND R_Type2 = 'Money' AND R_State = 'Done' AND DATE_FORMAT(R_RegDate,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m')) MP
                    , (SELECT SUM(R_Money)  FROM requests b WHERE a.M_Key = b.M_Key AND  R_Type1 = 'Charge' AND R_Type2 = 'Money' AND R_State = 'Done' AND DATE_FORMAT(R_RegDate,'%Y-%m') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -1 MONTH),'%Y-%m')) PP
                   
                FROM requests a WHERE M_Key = '{$_SESSION['S_Key']}' ";
    //echo $sql;
    $row = getRow($sql);

if(empty($startDate)) $startDate = date("Y-m-d");
if(empty($endDate))   $endDate = date("Y-m-d");

?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(12)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">보유머니 충전내역</div>
                <div class="title2">MONEY CHARGE LIST</div>
            </div>
            <div class="sub-box">

                <div class="mypage_menu1">
                    <a href="/mypage/betlist/">베팅내역</a>
                    <a href="/mypage/charge/" class="active">충전내역</a>
                    <a href="/mypage/refund/">환전내역</a>
                    <a href="/mypage/point/exchangelist/">포인트내역</a>
                    <!--<a href="/mypage/recom/">총판관리</a>-->
                    <a href="/mypage/message/">쪽지관리</a>
                    <a href="/mypage/member/modify/">회원정보수정</a>
                </div>
                <div class="mypage_menu2">
                    <a href="/mypage/charge/" class="active">보유머니 충전내역</a>
                    <a href="/mypage/refund/">보유머니 환전내역</a>
                    <!--<a href="/_go/renewal/mypage/money_casino_charge.html">카지노 충전내역</a>
                    <a href="/_go/renewal/mypage/money_casino_exchange.html">카지노 환전내역</a>-->
                </div>

                <div class="board_wrap">


                    <div class="b_title1">
                        <span>애드워드</span>님의 <span>충전정보</span>입니다.
                    </div>
                    <ul class="recom_state cnt4">
                        <li class="no_bg">
                            <div class="text1">잔여보유머니</div>
                            <var></var>
                            <div class="text2"><?php echo number_format($meminfo['M_Money']);?>원</div>
                            <a href="/money/charge/">보유머니 충전</a>
                        </li>
                        <li>
                            <div class="text1">오늘 충전합계</div>
                            <var></var>
                            <div class="text2 white"><?php echo number_format($row['SP']);?>원</div>
                        </li>
                        <li>
                            <div class="text1">당월 충전합계</div>
                            <var></var>
                            <div class="text2 white"><?php echo number_format($row['MP']);?>원</div>
                        </li>
                        <li>
                            <div class="text1">전월 충전합계</div>
                            <var></var>
                            <div class="text2 white"><?php echo number_format($row['PP']);?>원</div>
                        </li>
                    </ul>

                    <div class="mypage-day-search">
                        <div class="title">조회기간</div>
                        <div class="input_box">
                            <span class="active" data-day="<?php echo date("Y-m-d"); ?>">오늘</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-7 day")); ?>">1주일</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-15 day")); ?>">15일</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-1 month")); ?>">1개월</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-3 month")); ?>">3개월</span> &nbsp;
                            <input type="text" name="startDate" id="startDate" value="<?php echo $startDate; ?>">&nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;<input type="text" name="endDate" id="endDate" value="<?php echo $endDate; ?>">&nbsp;
                            <code class="view">조회하기</code>
                        </div>
                    </div>

                    <table class="table-black table-mypage-moneylist big">
                        <thead>
                        <tr>
                            <td>번호</td>
                            <td>닉네임</td>
                            <td>요청일시</td>
                            <td width="20%">충전금액</td>
                            <td width="10%">보너스금액</td>
                            <td width="20%">처리일시</td>
                            <td>처리상태</td>
                            <td>삭제</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $tb = "requests a LEFT JOIN members b ON a.M_Key = b.M_Key ";

                        $view_article = 10; // 한화면에 나타날 게시물의 총 개수
                        if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
                        $start = ($_GET['page']-1)*$view_article;
                        $href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}";
                        $where = " 1 AND R_Type1 = 'Charge' AND R_Type2 = 'Money' AND R_Money > 0 AND a.M_Key = '{$_SESSION['S_Key']}'";

                        if(!empty($search_text)){
                            $where .= " AND (B_Subject LIKE '%{$search_text}%' OR B_Content LIKE '%{$search_text}%') ";
                        }

                        #성명으로 정렬시
                        $order_by = " ORDER BY R_RegDate DESC ";

                        $query = "SELECT COUNT(*) FROM {$tb} WHERE {$where} ";
                        //echo $query;
                        $row = getRow($query);
                        $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함
                        if($total_article > 0){
                        $cnt = 0;
                        $que = "
                                    SELECT 
                                        *
                                    FROM 
                                        {$tb} 
                                    WHERE 
                                         {$where}          
                                         AND R_Visible = 'Y'                          
                                    ORDER BY R_RegDate DESC
                                    LIMIT 
                                        {$start}, {$view_article}
                                    
                                ";
                        //echo $que;
                        $arr = getArr($que);
                        foreach($arr as $list){
                            $rstate  = "확인중";
                            if($list['R_State']=='Done') {
                                $rstate = "처리완료";
                            } else if($list['R_State']=='Cancel'){
                                $rstate = "취소";
                            }

                        ?>
                        <tr>
                            <td><?=($total_article-$cnt-(($_GET['page']-1)*$view_article))?></td>
                            <td><?php echo $list['M_NICK']; ?></td>
                            <td><?php echo $list['R_RegDate']; ?></td>
                            <td><font class="mypage-grnfont"><?php echo number_format($list['R_Money']); ?></font></td>
                            <td><font class="mypage-redfont"><?php echo number_format($list['R_Bonus']); ?></font></td>
                            <td><?php echo $list['R_ResultDate']; ?></td>
                            <td><font class="mypage-grnfont"><?php echo $rstate; ?></font></td>
                            <td><code class="del" data-rkey="<?php echo $list['R_Key'];?>" style="cursor: pointer;">삭제</code></td>
                        </tr>
                        <?php
                            $cnt++;
                        }} else {
                            ?>
                            <tr><td colspan="7" class="text-center">등록된 내역이 없습니다.</td></tr>
                        <?php } ?>
                        </tbody>
                    </table>


                </div> <!-- board_wrap -->
                <?php
                if($total_article>0) {
                    include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
                }
                ?>
            </div> <!-- sub-box -->

        </div> <!-- sub_wrap -->

    </div> <!-- sub_bg -->

    <script>
        $(document).ready(function(){
            $('code.view').on('click',function(){
                location.href = './?startDate='+$('#startDate').val()+'&endDate='+$('#endDate').val();
            });
            $('.input_box > span').on('click',function(){
                var startDay  = $(this).data("day");
                $('input[name="startDate"]').val(startDay);
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

            $('.del').on('click',function(){
                var rkey = $(this).data('rkey');
                $.ajax({
                    type: 'get',
                    url: './proc/',
                    dataType: 'json',
                    data: {
                        'mode': 'chargeListDel'
                        , 'rkey': rkey
                    },
                    success: function (data) {
                        if (data.flag == true) {
                            swal('','충전내역이 삭제되었습니다.','success');
                            setTimeout(function(){ location.reload();},3000);
                        } else {
                            swal('', data.error, 'warning');
                        }
                    }
                });
            });
        });
    </script>

<?php
include_once $root_path.'/include/footer.php';
?>