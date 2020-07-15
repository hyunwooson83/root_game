<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/header.php');

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

    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>보유머니 충전내역</span>
                <em>MONEY CHARGE LIST</em>
            </h1>
            <ul class="sub_title_category">
                <li onclick="location.href='<?php echo $mobile_path;?>/money/charge/'">충전하기</li>
                <li onclick="location.href='<?php echo $mobile_path;?>/money/charge/list/'"  class="active">충전내역</li>
                <li onclick="location.href='<?php echo $mobile_path;?>/money/refund/'">환전하기</li>
                <li onclick="location.href='<?php echo $mobile_path;?>/money/refund/list/'">환전내역</li>
            </ul>
        </div>

        <div class="sub_mypage_wrap">
            <ul class="sub_member_top four">
                <li>
                    <em>잔여 보유머니</em>
                    <var></var>
                    <span class="hit"><?php echo number_format($meminfo['M_Money']);?>원</span>
                </li>
                <li>
                    <em>오늘 충전금합계</em>
                    <var></var>
                    <span class="today"><?php echo number_format($row['SP']);?>원</span>
                </li>
                <li>
                    <em>당월 충전금합계</em>
                    <var></var>
                    <span class="month"><?php echo number_format($row['MP']);?>원</span>
                </li>
                <li>
                    <em>전월 충전금합계</em>
                    <var></var>
                    <span class="give"><?php echo number_format($row['PP']);?>원</span>
                </li>
            </ul>

            <div class="sub_searchbox">
                <div>
                    <h1>
                        <input type="text" class="style_input date" name="startDate" id="startDate" value="<?php echo $startDate; ?>">
                        &nbsp;&nbsp;~&nbsp;&nbsp;
                        <input type="text" class="style_input date" name="endDate" id="endDate" value="<?php echo $endDate; ?>">
                        <ol>
                            <li class="active" data-day="<?php echo date("Y-m-d"); ?>">오늘</li>
                            <li data-day="<?php echo date("Y-m-d",strtotime("-7 day")); ?>">1주일</li>
                            <li data-day="<?php echo date("Y-m-d",strtotime("-15 day")); ?>">15일</li>
                        </ol>
                    </h1>
                    <div class="sub_searchbox_btnbox">
                        <a href="javascript:;" class="style_btn_confirm view">검색하기</a>
                    </div>
                </div>
            </div>

            <ul class="sub_cash_list">
        <?php
            $tb = "requests a LEFT JOIN members b ON a.M_Key = b.M_Key ";

            $view_article = 5; // 한화면에 나타날 게시물의 총 개수
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
                                                ORDER BY R_RegDate DESC
                                                LIMIT 
                                                    {$start}, {$view_article}
                                                
                                            ";

                $arr = getArr($que);
                foreach($arr as $list){
                    $state_css = "";
                    $rstate  = "확인중";
                    if($list['R_State']=='Done') {
                        $rstate = "완료";
                        $state_css = "success";
                    } else if($list['R_State']=='Cancel'){
                        $rstate = "취소";
                    }

        ?>
                <li>
                    <em>신청금액 <b><?php echo number_format($list['R_Money']); ?></b></em>
                    <font>신청일시 : <?php echo $list['R_RegDate']; ?></font>
                    <font>처리일시 : <?php echo $list['R_ResultDate']; ?></font>
                    <span class="<?php echo $state_css;?>" style="font-size:12px;"><?php echo $rstate; ?></span>
                </li>
         <?php
                    $cnt++;
                }} else {
                ?>
                <ul><li>등록된 내역이 없습니다.</li></ul>
            <?php } ?>
            </ul>
            <?php
            if($total_article>0) {
                include_once($_SERVER['DOCUMENT_ROOT'] . "/m/lib/page.php");
            }
            ?>

        </div>

    </div>
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
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>