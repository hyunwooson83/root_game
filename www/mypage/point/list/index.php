<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

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

    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">포인트 적립내역</div>
                <div class="title2">POINT SAVING LIST</div>
            </div>
            <div class="sub-box">

                <div class="mypage_menu1">
                    <a href="/mypage/betlist/">베팅내역</a>
                    <a href="/money/charge/list/">충전내역</a>
                    <a href="/money/refund/list/">환전내역</a>
                    <a href="/mypage/point/exchange/list/" class="active">포인트내역</a>
                    <!--<a href="/mypage/recom/">총판관리</a>-->
                    <a href="/mypage/message/">쪽지관리</a>
                    <a href="/mypage/member/modify/">회원정보수정</a>
                </div>
                <div class="mypage_menu2">
                    <a href="/mypage/point/exchange/" class="">포인트전환신청</a>
                    <a href="/mypage/point/list/" class="active">포인트적립내역</a>
                    <a href="/mypage/point/exchangelist/">포인트전환내역</a>
                </div>

                <div class="board_wrap">

                    <div class="b_title1">
                        <span><?php echo $meminfo['M_NICK']; ?></span>님의 <span>포인트적립내역</span>입니다.
                    </div>
                    <ul class="recom_state">
                        <li>
                            <div class="text1">잔여포인트</div>
                            <var></var>
                            <div class="text2"><?php echo number_format($meminfo['M_Point']); ?> P</div>
                        </li>
                        <li>
                            <div class="text1">당월 적립 포인트</div>
                            <var></var>
                            <div class="text2 white"><?php echo number_format($row['SP']);?> P</div>
                        </li>
                        <li>
                            <div class="text1">전월 적립 포인트</div>
                            <var></var>
                            <div class="text2 white"><?php echo number_format($row['PP']);?> P</div>
                        </li>
                        <li>
                            <div class="text1">총 누적포인트</div>
                            <var></var>
                            <div class="text2 yellow"><?php echo number_format($row['TP']);?> P</div>
                        </li>
                        <li>
                            <div class="text1">총 사용포인트</div>
                            <var></var>
                            <div class="text2 white"><?php echo number_format($row['MP']);?> P</div>
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
                            <input type="text" name="startDate" id="startDate" value="<?php echo date('Y-m-d'); ?>">&nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;<input type="text" name="endDate" id="endDate" value="<?php echo date('Y-m-d'); ?>">&nbsp;
                            <code class="view">조회하기</code>
                        </div>
                    </div>

                    <table class="table-black table-mypage-moneylist big">
                        <thead>
                        <tr>
                            <td width="20%">적립일시</td>
                            <td width="15%">적립포인트</td>
                            <td width="15%">사용포인트</td>
                            <td>사용구분</td>
                            <td>비고</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $tb = "pointinfo ";

                            $view_article = 15; // 한화면에 나타날 게시물의 총 개수
                            if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
                            $start = ($_GET['page']-1)*$view_article;
                            $href = "&startDate={$_GET['startDate']}&endDate={$_GET['endDate']}";
                            $where = " 1 AND M_Key = '{$_SESSION['S_Key']}'";

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
                            <tr>
                                <td><?php echo $list['PI_RegDate']; ?></td>
                                <td><font class="mypage-grnfont"><?php echo ($list['PI_Point']>0)?number_format($list['PI_Point']):'';?></font></td>
                                <td><font class="mypage-redfont"><?php echo ($list['PI_Point']<0)?number_format($list['PI_Point']):'';?></font></td>
                                <td><?php echo ($list['PI_Point']>0)?'적립':'사용';?></td>
                                <td><?php echo $list['PI_Memo'];?></td>
                            </tr>
                            <?php }} else {?>
                                <tr><td colspan="5" style="text-align: center;">등록된 내역이 없습니다.</td></tr>
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
include_once $root_path.'/include/footer.php';
?>