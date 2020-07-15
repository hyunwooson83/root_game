<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';


    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) {
        swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }


    $tb = "message";

    $view_article = 15; // 한화면에 나타날 게시물의 총 개수
    if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
    $start = ($_GET['page']-1)*$view_article;
    $href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}";


    $where = " 1 AND M_Key = {$_SESSION['S_Key']} ";

    #성명으로 정렬시
    $order_by = " ORDER BY regDate DESC ";

    $query = "SELECT COUNT(*) FROM {$tb} WHERE {$where}  ";
    //echo $query;
    $row = getRow($query);
    $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함
?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(10)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">쪽지</div>
                <div class="title2">TREND MESSAGE</div>
            </div>
            <ul class="sub_menu">
                <li class="on" onclick="location.href='/mypage/message/'">쪽지</li>
                <li class="" onclick="location.href='/mypage/customer/'">1:1문의하기</li>
                <li onclick="location.href='/mypage/faq/'">자주묻는 질문</li>
                <li onclick="location.href='/mypage/notice/'">공지사항</li>
            </ul>
            <!--<div class="qna_cate">
                <a class="none on">전체</a>
                <a>스포츠북</a>
                <a>가상게임</a>
                <a>마이크로게임</a>
                <a>타이산게임</a>
                <a>올벳게임</a>
                <a>아시아게이밍</a>
                <a>플레이텍게임</a>
                <br>
                <a class="none">파워볼</a>
                <a>파워사다리</a>
                <a>스피드키노</a>
                <a>키노사다리</a>
                <a>키노사다리</a>
                <a>회원문의</a>
                <a>정산문의</a>
                <a>기타문의</a>
            </div>-->

            <div class="board_wrap board2">
                <div class="line_top"></div>
                <table class="qna_list">
                    <thead>
                    <tr>
                        <th>번호</th>                        
                        <th>제목</th>
                        <th>작성자</th>
                        <th>상태</th>
                        <th>등록일</th>
                        <th>확인일자</th>
                        <th>삭제</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if($total_article > 0){
                        $cnt = 0;
                        $que = "
                                        SELECT 
                                            *
                                        FROM 
                                            $tb 
                                        WHERE 
                                             $where                                        
                                            $order_by
                                        LIMIT 
                                            $start, $view_article
                                        
                                    ";
                        //echo $que;
                        $arr = getArr($que);
                        foreach($arr as $list){
                    ?>
                        <tr>
                            <td><?=($total_article-$cnt-(($_GET['page']-1)*$view_article))?></td>
                            <td class="subject" onclick="location.href='./view/?idx=<?php echo $list['idx']; ?>'">
                                <div><?php echo (!empty($list['subject']))?$list['subject']:'메세지 입니다.'; ?></div>
                            </td>
                            <td>관리자</td>
                            <td class="state"><span class="<?php echo ($list['readDate']=='0000-00-00 00:00:00')?'off':'on'; ?>"><?php echo ($list['readDate']=='0000-00-00 00:00:00')?'확인전':'확인완료'; ?></span></td>
                            <td><?php echo substr($list['regDate'],0,10); ?></td>
                            <td><?php echo ($list['readDate']=='0000-00-00 00:00:00')?'':$list['readDate']; ?></td>
                            <td><a href="javascript:del_message(<?php echo $list['idx']; ?>);"><img src="/mobile/img/img_trash_icon.png" style="width: 20px;" /></a> </td>
                        </tr>

                    <?php $cnt++; }} ?>
                    </tbody>

                </table>
                <div class="line_bottom"></div>
                <div class="btn_wrap">
                    <a onclick="get_bank();">계좌문의</a>
                </div>
                <?php
                if($total_article>0) {
                    include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
                }
                ?>
            </div>

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

        function del_message(idx){
            swal({
                title : "쪽지삭제",
                text: "선택한 쪽지를 삭제하시겠습니까?",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "확인",
                cancelButtonText: "취소"
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url: './proc/',
                        dataType: 'json',
                        data: 'HAF_Value_0=delMessage&idx='+idx,
                        success: function (data) {
                            if (data.flag == true) {
                                swal('', '쪽지가 정상적으로 삭제되었습니다.', 'success');
                                setTimeout(function () {
                                    location.reload();
                                }, 2000);
                            } else {
                                swal('', data.error, 'success');
                            }
                        }
                    });
                }
            });

        }
    </script>
<?php
include_once $root_path.'/include/footer.php';
?>