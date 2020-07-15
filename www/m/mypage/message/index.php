<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

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

    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>쪽지함</span>
                <em>Message</em>
            </h1>
            <code>쪽지보관은 최장5일 입니다.</code>
        </div>

        <div class="sub_mypage_wrap">
            <ul class="sub_cash_list sub_freeboard_list">
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

                        $sql = "UPDATE message SET readDate = NOW() WHERE idx = '{$list['idx']}' ";
                        setQry($sql);
                ?>                
                <li class="read">
                    <h3  ><?php echo $list['message']; ?></h3>
                    <em><b class="<?php echo ($list['readDate']=='0000-00-00 00:00:00')?'':'on'; ?>"><?php echo ($list['readDate']=='0000-00-00 00:00:00')?'안읽음':'읽음'; ?></b> &nbsp;|&nbsp; 보낸시간 : <?php echo ($list['readDate']=='0000-00-00 00:00:00')?$list['regDate']:$list['readDate']; ?></em>
                    <label class="no_bg" onclick="del_message(<?php echo $list['idx']; ?>);" style="cursor: pointer;"><img src="/mobile/img/img_trash_icon.png" /></label>
                </li>
                        <?php $cnt++; }} else { ?>
                    <li style="text-align: center;">등록된 쪽지가 없습니다.</li>
                <?php } ?>
            </ul>

            <?php
            if($total_article>0) {
                include_once($_SERVER['DOCUMENT_ROOT'] . "/m/lib/page.php");
            }
            ?>

            <div class="sub_board_btn">
                <a href="">전체 읽음처리</a>
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
                        data: 'mode=delMessage&idx='+idx,
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
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php';
?>