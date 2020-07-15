<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

    $mem = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");
    // 게시판 확인
    $board_title = $lib24c->Check_Board('customer');

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
                    <span>텍사스</span>
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
                    <?php echo nl2br($row['B_Content']); ?>
                </div>
            </div>


            <div class="sub_board_btn">
                <a href="../" class="btn_gray">목록</a>
            </div>

        </div>

    </div> <!-- Sub Wrap -->

<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php';
?>