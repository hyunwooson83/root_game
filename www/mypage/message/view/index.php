<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';
$que = "UPDATE message SET readDate = NOW() WHERE idx = '{$_REQUEST['idx']}'";
setQry($que);
$que = "SELECT * FROM message WHERE idx = '{$_REQUEST['idx']}'";
$row = getRow($que);
?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(9)").addClass('active');
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

            <div class="board_wrap board2">
                <div class="qna_view">
                    <div class="content">
                        <?php echo nl2br($row['message']); ?>

                    </div>
                </div>


                <div class="btn_wrap" style="margin-top: 10px;">
                    <a href="../" class="bl">목록</a>
                </div>
            </div>

        </div>
    </div>

<?php
include_once $root_path.'/include/footer.php';
?>