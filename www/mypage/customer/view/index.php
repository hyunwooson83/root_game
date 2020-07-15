<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

// 로그인 체크
if ( !$_SESSION['S_Key'] ) {
    //swal_move('로그인이 필요한 페이지 입니다.', 'login');
}

$mem = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");
// 게시판 확인
$board_title = $lib24c->Check_Board('customer');

$row = $lib24c->Get_Board_Read( $_GET['b_key'] );

if ( $row['B_ReplyCount'] > 0 ) $result = $lib24c->Get_Board_Reply( $_GET['b_key'] );

$check_auth_board = $lib24c->Check_Auth_Board( $_GET['b_key'] );
?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(11)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">1:1문의</div>
                <div class="title2">TREND Q&A</div>
            </div>
            <ul class="sub_menu">
                <li class="" onclick="location.href='/mypage/message/'">쪽지</li>
                <li class="on" onclick="location.href='/_go/renewal/qna_list.html'">1:1문의하기</li>
                <li onclick="location.href='/_go/renewal/faq_list.html'">자주묻는 질문</li>
                <li onclick="location.href='/_go/renewal/notice_list.html'">공지사항</li>
            </ul>

            <div class="board_wrap board2">
                <div class="qna_view">
                    <div class="qna_title">
                        <div class="sub1">
                            <span>[<?php echo $row['B_Category']; ?>]</span><em><?php echo $row['B_Subject']; ?></em>
                        </div>
                        <div class="sub2">
                            <span><?php echo $row['M_NICK']; ?></span><var></var><em><?php echo $row['B_RegDate']; ?></em>
                        </div>
                    </div>
                    <div class="content"><?php echo nl2br($row['B_Content']); ?></div>
                    <?php
                        if(!empty($row['B_Answer'])){
                    ?>
                    <div class="answer_box">
                        <div class="answer">
                            <div class="qna_title">
                                <div class="sub1">
                                    <label>답변</label><em>답변드립니다.</em>
                                </div>
                                <div class="sub2">
                                    <span>관리자</span><var></var><em><?php echo $row['B_RegDate']; ?></em>
                                </div>
                            </div>
                            <div class="answer_con">
                                <?php echo nl2br($row['B_Answer']); ?>
                            </div>
                        </div>
                    </div>
                            <div class="line_bottom"></div>
                    <?php } ?>
                </div>

                <div class="betlist_bottom" style="margin-top:10px;">
                    <div class="left">
                        <a class="choose done">수정</a>
                        <a class="del delete">삭제</a>
                    </div>
                    <div class="right">
                        <a class="cancel">목록</a>
                    </div>
                </div>


                <!--<div class="btn_wrap" style="margin-top:10px;">
                    <a href="../" class="bl">목록</a>
                    <a href="../modify/?b_key=<?php /*echo $row['B_Key']; */?>">수정</a>
                </div>-->
            </div>

        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.done').on('click',function(){
                location.href = '../modify/?tn=customer&b_key=<?php echo $b_key; ?>';
            });
            $('.cancel').on('click',function(){
                location.href = '/mypage/customer/';
            });
            $('.delete').on('click',function(){
                Action_Write();
            });
        });
    </script>
    <script>

        function Action_Write() {
            var f = document.HiddenActionForm;


            if ( confirm("게시물을 삭제 하시겠습니까?") ) {
                f.HAF_Value_0.value = "BoardDelete";
                f.HAF_Value_1.value = "<?php echo $b_key;?>";
                f.HAF_Value_2.value = 'customer';

                f.method = "POST";
                f.action = "/action/board_action.php";
                f.submit();

            };
        };
    </script>
<?php
include_once $root_path.'/include/footer.php';
?>