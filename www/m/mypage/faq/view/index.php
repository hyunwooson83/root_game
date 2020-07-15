<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

// 로그인 체크
if ( !$_SESSION['S_Key'] ) {
    swal_move('로그인이 필요한 페이지 입니다.', 'login');
}

$mem = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");
// 게시판 확인
$board_title = $lib24c->Check_Board('board');

$row = $lib24c->Get_Board_Read( $_GET['b_key'] );

if ( $row['B_ReplyCount'] > 0 ) $result = $lib24c->Get_Board_Reply( $_GET['b_key'] );

$check_auth_board = $lib24c->Check_Auth_Board( $_GET['b_key'] );
?>

    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>게시판</span>
                <em>Board</em>
            </h1>

        </div>
        <div class="sub_board" style="padding-bottom:0">

            <div class="sub_board_view">
                <h1><?php echo $row['B_Subject']; ?></h1>
                <h2 class="hit">
                    <span><?php echo $nick['M_NICK']; ?></span>
                    <var>|</var>
                    <em>조회수 : <?php echo $row['B_Count']; ?></em>
                    <var>|</var>
                    <em><?php echo $row['B_RegDate']; ?></em>
                </h2>
            </div>
        </div>



        <div class="sub_board">
            <div class="sub_board_view">
                <div>
                    <?php echo $row['B_Content']; ?>
                </div>
            </div>
            <?php
            if(!empty($row['B_Answer'])){
            ?>
            <div class="sub_board_view">
                <div>
                    텍사스에서 답변드립니다.
                    <br>
                    <?php echo $row['B_Answer']; ?>
                </div>
            </div>
            <?php } ?>

            <div class="sub_board_btn">
                <a href="../" class="btn_gray ">목록</a>

            </div>

        </div>

    </div> <!-- Sub Wrap -->

    <script type="text/javascript">
        $(document).ready(function() {
            $('.done').on('click',function(){
                location.href = '../modify/?tn=customer&b_key=<?php echo $b_key; ?>';
            });
            $('.cancel').on('click',function(){
                location.href = '/m/mypage/customer/';
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
                f.action = "/m/action/board_action.php";
                f.submit();

            };
        };
    </script>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php';
?>