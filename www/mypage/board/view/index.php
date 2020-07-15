<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

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
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(10)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">게시판</div>
                <div class="title2">TREND BOARD</div>
            </div>
            <!--<ul class="sub_menu">
                <li class="on" onclick="location.href='/_go/renewal/qna_list.html'">1:1문의하기</li>
                <li onclick="location.href='/_go/renewal/faq_list.html'">자주묻는 질문</li>
                <li onclick="location.href='/_go/renewal/notice_list.html'">공지사항</li>
            </ul>-->
            <div class="sub_board_view_con">
                <table class="table-black mypage">
                    <thead>
                    <tr>
                        <td>베팅구분</td>
                        <td width="260">승(홈)</td>
                        <td>무</td>
                        <td width="260">패(원정)</td>
                        <td>점수</td>
                        <td>선택</td>
                        <td>결과</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $cur_gid = "";
                    $game_state = 0;
                    $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함
                    $que = "SELECT  * FROM buygame WHERE BG_Key IN ({$row['B_BG_Key']})  ";
                    //echo $que;
                    $arr = getArr($que);
                    if(count($arr)>0){
                        foreach($arr as $rs) {
                            $sql = "SELECT * FROM buygamelist a LEFT JOIN gamelist b ON a.G_Key = b.G_Key LEFT JOIN gameleague c ON b.GL_Key = c.GL_Key_IDX WHERE a.M_Key = '{$_SESSION['S_Key']}' AND a.BG_Key = '{$rs['BG_Key']}'";

                            //echo $sql;
                            $ar = getArr($sql);
                            if(count($ar)>0){
                                foreach($ar as $list){
                                    $rate_win_select = $rate_draw_select = $rate_lose_select = $game_choice = "";
                                    if(in_array($list['BGL_ResultChoice'],array('Win','Draw','Lose'))){
                                        $rate_win = number_format($list['G_QuotaWin'],2);
                                        $rate_draw = number_format($list['G_QuotaDraw'],2);
                                        $rate_lose = number_format($list['G_QuotaLose'],2);
                                        if($list['BGL_ResultChoice']=='Win') {
                                            $rate_win_select = 'selected01';
                                            $game_choice = "홈팀 승";
                                        } else if($list['BGL_ResultChoice']=='Draw') {
                                            $rate_draw_select = 'selected01';
                                            $game_choice = "무승부";
                                        } else {
                                            $rate_lose_select = 'selected01';
                                            $game_choice = "홈팀 패";
                                        }
                                    }
                                    if(in_array($list['BGL_ResultChoice'],array('Under','Over'))){
                                        $rate_win = number_format($list['G_QuotaUnder'],2);
                                        $rate_draw = $list['G_QuotaUnderOver'];
                                        $rate_lose = number_format($list['G_QuotaOver'],2);
                                        if($list['BGL_ResultChoice']=='Over') {
                                            $rate_win_select = 'selected01';
                                            $game_choice = "오버";
                                        } else if($list['BGL_ResultChoice']=='Under') {
                                            $rate_lose_select = 'selected01';
                                            $game_choice = "언더";
                                        }
                                    }
                                    if(in_array($list['BGL_ResultChoice'],array('HandiWin','HandiLose'))){
                                        $rate_win = number_format($list['G_QuotaHandiWin'],2);
                                        $rate_draw = $list['G_QuotaHandicap'];
                                        $rate_lose = number_format($list['G_QuotaHandiLose'],2);
                                        if($list['BGL_ResultChoice']=='HandiWin') {
                                            $rate_win_select = 'selected01';
                                            $game_choice = "핸디 승";
                                        } else if($list['BGL_ResultChoice']=='HandiLose') {
                                            $rate_lose_select = 'selected01';
                                            $game_choice = "핸디 패";
                                        }
                                    }

                                    if($list['G_State'] == 'End' || $list['G_ResultScoreWin'] != '' || $list['G_ResultScoreLose']!=''){
                                        if($list['BGL_State'] != 'Await'){
                                            if($list['BGL_State']=='Success' || $list['BGL_State']=='Cancel'){
                                                $g_state = "적중";
                                                $g_state_css = "shot";
                                            } else if($list['BGL_State']=='Fail'){
                                                $g_state = "미적중";
                                                $g_state_css = "noshot";
                                            }

                                        } else {
                                            $g_state = "종료";
                                            $g_state_css = "";
                                        }
                                    } else if(($list['G_State'] == 'Stop' && $list['G_Locked']==3)){
                                        $g_state = "경기중";
                                        $g_state_css = "";
                                        $game_state++;
                                    } else if($list['G_State'] == 'Await' && $list['G_Locked']==1) {
                                        $g_state = "경기전";
                                        $g_state_css = "";
                                        $game_state++;
                                    } else if($list['G_OddsState']==4){
                                        $g_state = "취소[적특]";
                                        $g_state_css = "";
                                    } else if($list['G_OddsState']==2){
                                        $g_state = "중단";
                                        $g_state_css = "";
                                    }


                                    $pay_result = '처리전';
                                    if($rs['BG_Result']=='Success' || $rs['BG_Result']=='Cancel'){
                                        $pay_result = number_format($rs['BG_ForecastPrice']);
                                    } else if($rs['BG_Result'] == 'Fail'){
                                        $pay_result = 0;
                                    }



                                    if($cur_gid != $list['G_ID']){
                                        if(empty($cur_gid))   $cur_gid = $list['G_ID'];

                                        ?>

                                        <tr class="attach-<?php echo $rs['BG_Key']; ?>">
                                            <td colspan="8" class="league_title">
                                                <img src="/img/icon_<?php echo $ITEMICON[$list['GI_Key']];?>.png" style="width: 20px;" /><img src="/img/league/<?php echo $list['GL_SrvName'];?>" style="width:
22px;" /> &nbsp; <?php echo $list['GL_Type']; ?> &nbsp; - &nbsp; <?php echo date("m/d H:i",strtotime($list['G_Datetime'])); ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $cur_gid = $list['G_ID']; }
                                    ?>
                                    <tr class="line attach-<?php echo $rs['BG_Key']; ?>">
                                        <td><?php echo $GAME_TYPE_TEXT[$list['G_Type2']];?> <?php ($list['G_Type1']=='Special')?$list['G_MarketNameKor']:''; ?></td>
                                        <td class="<?php echo $rate_win_select;?>">
                                    <span>
                                        <span><?php echo $list['G_Team1']; ?></span>
                                        <em><?php echo $rate_win;?></em>
                                    </span>
                                        </td>
                                        <td class="<?php echo $rate_draw_select;?>"><span class="margin"><?php echo $rate_draw;?></span></td>
                                        <td class="<?php echo $rate_lose_select;?>">
                                    <span>
                                        <span><?php echo $rate_lose;?></span>
                                        <em><?php echo $list['G_Team2']; ?></em>
                                    </span>
                                        </td>
                                        <td>
                                            <?php
                                            if(in_array($list['G_State'],array('End','Cancel'))) {
                                                echo $list['G_ResultScoreWin'] . ':' . $list['G_ResultScoreLose'];
                                            } else {
                                                echo '- : -';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $game_choice; ?></td>
                                        <td class="<?php echo $g_state_css;?>"><code><?php echo $g_state; ?></code></td>
                                    </tr>
                                    <tr class="empty_line attach-<?php echo $rs['BG_Key']; ?>">
                                        <td colspan="7" class="empty_line"></td>
                                    </tr>
                                <?php }} ?>
                            <tr class="attach-<?php echo $rs['BG_Key']; ?>">
                                <td colspan="7" class="table-left">
                                    <div>
                                        <span>

                                            베팅일시&nbsp; : &nbsp;<font><?php echo date("Y년m월d일 H시i분",strtotime($rs['BG_BuyDate'])); ?></font>&nbsp; / &nbsp;베팅금액&nbsp; : &nbsp;<font><?php echo number_format($rs['BG_BettingPrice']); ?>원</font><BR/>
                                            배당률&nbsp; : &nbsp;<font><?php echo $rs['BG_TotalQuota']; ?></font>&nbsp; / &nbsp;적중예상금액&nbsp; : &nbsp;<font><?php echo number_format($rs['BG_ForecastPrice']); ?>원</font>&nbsp; / &nbsp;당첨금&nbsp; : &nbsp;<B><?php echo $pay_result;?></B>
                                        </span>

                                    </div>
                                </td>
                            </tr>
                            <?php $cnt++; }} ?>
                    </tbody>
                </table>
            </div>
            <div class="board_wrap board2">
                <div class="qna_view">
                    <div class="qna_title">
                        <!--<div class="sub1">
                            <span>[스포츠북]</span><em><?php /*echo $row['B_Subject']; */?></em>
                        </div>-->
                        <div class="sub2">
                            <span><?php echo $row['M_NICK']; ?></span><var></var><em><?php echo $row['B_RegDate']; ?></em>
                        </div>
                    </div>
                    <div class="content"><?php echo nl2br($row['B_Content']); ?></div>
                    
                </div>

                <div class="line_bottom"></div>

                <div class="betlist_bottom">
                    <div class="left">
                        <a class="choose done">수정</a>
                        <a class="del delete">삭제</a>
                    </div>
                    <div class="right">
                        <a class="cancel">목록</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.done').on('click',function(){
                location.href = '../modify/?tn=board&b_key=<?php echo $b_key; ?>';
            });
            $('.cancel').on('click',function(){
                location.href = '/mypage/board/';
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
                f.HAF_Value_2.value = 'board';

                f.method = "POST";
                f.action = "/action/board_action.php";
                f.submit();

            };
        };
    </script>
<?php
    include_once $root_path.'/include/footer.php';
?>