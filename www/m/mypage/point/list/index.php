<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

if ( !$_SESSION['S_Key'] ) {
    swal_move('로그인이 필요한 페이지 입니다.', 'login');
}


    $row = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");


    $sql = "SELECT                
                (SELECT SUM(PI_Point) AS save_point FROM pointinfo b WHERE a.M_Key = b.M_Key AND PI_Point > 0 AND DATE_FORMAT(PI_RegDate,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m')) SP
                , (SELECT SUM(PI_Point) AS save_point FROM pointinfo b WHERE a.M_Key = b.M_Key AND PI_Point > 0 AND DATE_FORMAT(PI_RegDate,'%Y-%m') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -1 MONTH),'%Y-%m')) PP
                , (SELECT SUM(PI_Point) AS save_point FROM pointinfo b WHERE a.M_Key = b.M_Key AND PI_Point > 0 ) TP
                , (SELECT SUM(PI_Point) AS save_point FROM pointinfo b WHERE a.M_Key = b.M_Key AND PI_Point < 0 ) MP
            FROM pointinfo a WHERE M_Key = '{$_SESSION['S_Key']}' ";
    //echo $sql;
    $row = getRow($sql);



?>

    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>포인트 적립내역</span>
                <em>POINT SAVING LIST</em>
            </h1>
            <ul class="sub_title_category">
                <li onclick="location.href='/m/mypage/point/exchange/'" >포인트전환신청</li>
                <li onclick="location.href='/m/mypage/point/list/'" class="active">포인트적립내역</li>
                <li onclick="location.href='/m/mypage/point/exchangelist/'">포인트전환내역</li>
            </ul>
        </div>

        <div class="sub_mypage_wrap">
            <ul class="sub_member_top five">
                <li>
                    <em>잔여 포인트</em>
                    <var></var>
                    <span class="hit">500,000P</span>
                </li>
                <li>
                    <em>당월 적립포인트</em>
                    <var></var>
                    <span class="today">180,000P</span>
                </li>
                <li>
                    <em>전월 적립포인트</em>
                    <var></var>
                    <span class="month">1,180,000P</span>
                </li>
                <li>
                    <em>총 누적포인트</em>
                    <var></var>
                    <span class="year">1,180,000P</span>
                </li>
                <li>
                    <em>총 사용포인트</em>
                    <var></var>
                    <span class="give">1,180,000P</span>
                </li>
            </ul>
            <div class="sub_searchbox">
                <div>
                    <h1>
                        <input type="text" class="style_input date" value="2017-05-03">
                        &nbsp;&nbsp;~&nbsp;&nbsp;
                        <input type="text" class="style_input date" value="2017-05-03">
                        <ol>
                            <li class="active">오늘</li>
                            <li>1주일</li>
                            <li>15일</li>
                        </ol>
                    </h1>
                    <div class="sub_searchbox_btnbox">
                        <a href="" class="style_btn_confirm">검색하기</a>
                    </div>
                </div>
            </div>
            <ul class="sub_cash_list">
                <?php
                $tb = "pointinfo ";

                $view_article = 5; // 한화면에 나타날 게시물의 총 개수
                if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
                $start = ($_GET['page']-1)*$view_article;
                $href = "&startDate={$_GET['startDate']}&endDate={$_GET['endDate']}";
                $where = " 1 AND PI_Type IN ('FChange','Charge','ChuBetFail','ChuBetting') AND M_Key = '{$_SESSION['S_Key']}'";

                #성명으로 정렬시
                $order_by = " ORDER BY PI_RegDate DESC ";

                $query = "SELECT COUNT(*) FROM {$tb} WHERE {$where} ";

                $row = getRow($query);
                $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함
                if($total_article > 0){
                $cnt = 0;
                $que = "
                                        SELECT 
                                            *
                                        FROM 
                                            $tb 
                                        WHERE 
                                             $where                                       
                                        ORDER BY PI_RegDate DESC
                                        LIMIT 
                                            $start, $view_article
                                        
                                    ";
                //echo $que;
                $arr = getArr($que);
                foreach($arr as $list){
                ?>
                <li>
                    <em>적립포인트 <b>+<?php echo number_format($list['PI_Point']);?></b></em>
                    <em>구분 <b><?php echo $list['PI_Memo']; ?></b></em>
                    <font>일시 : <?php echo $list['PI_RegDate']; ?></font>
                    <span class="cash_out_icon">완료</span>
                </li>
                <?php }} else {?>
                    <li style="text-align: center;">등록된 내역이 없습니다</li>
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
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php';
?>