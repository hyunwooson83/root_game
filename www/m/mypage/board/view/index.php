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

    $nick = getRow("SELECT M_NICK FROM members WHERE M_Key = '{$row['M_Key']}'");
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

        <?php if($mini != 'Y') { ?>
        <!--배팅내역 첨부 시작 -->
        <div class="sports_list">

            <dl class="sports_list_top">
                <dd>승(홈)오버 <var class="arr_up arr_wave">▲</var></dd>
                <dt>무/핸/합</dt>
                <dd>패(원정)언더 <var class="arr_down arr_wave blink">▼</var></dd>
            </dl>
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
                                        $g_state_css = "win";
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
                                <!-- League start { -->
                                <h1>
                                    <span><img src="/img/icon_<?php echo $ITEMICON[$list['GI_Key']];?>.png" /></span><img src="/img/league/<?php echo $list['GL_SrvName']; ?>" /><?php echo $list['GL_Type']; ?>
                                    <var><?php echo date("m/d H:i",strtotime($list['G_Datetime'])); ?></var>
                                    <em>
                    <span><?php
                        if(in_array($list['G_State'],array('End','Cancel'))) {
                            echo $list['G_ResultScoreWin'] . ':' . $list['G_ResultScoreLose'];
                        } else {
                            echo '- : -';
                        }
                        ?> (<?php echo $game_choice; ?>)</span>
                                        <em class="<?php echo $g_state_css;?>"><?php echo $g_state; ?></em>
                                    </em>
                                </h1>
                                <?php
                                $cur_gid = $list['G_ID']; }
                            ?>
                            <ul>

                                <!-- 한경기 부분 { -->
                                <li style="border-bottom:none">
                                    <dl>
                                        <dd class="<?php echo $rate_win_select;?>">
                                            <div>
                                                <span><?php echo $list['G_Team1']; ?></span>
                                                <em><?php echo ($list['G_Type2']=='UnderOver')?$type_over:''; ?> <?php echo $rate_win;?></em>
                                            </div>
                                        </dd>
                                        <dd class="space"></dd>
                                        <dd class="center <?php echo $rate_drawn_select;?>">
                                            <div>
                                                <em><?php echo $rate_draw;?></em>
                                            </div>
                                        </dd>
                                        <dd class="space"></dd>
                                        <dd class="<?php echo $rate_lose_select;?>">
                                            <div class="right">
                                                <em><?php echo $rate_lose;?><?php echo ($list['G_Type2']=='UnderOver')?$type_under:'';?></em>
                                                <span><?php echo $list['G_Team2']; ?></span>
                                            </div>
                                        </dd>
                                    </dl>
                                </li>
                                <!-- } 한경기 부분 -->
                            </ul>

                        <?php }} ?>
                    <!-- } League end -->

                    <fieldset>
                        <em  ></em>
                        베팅시간 : <b><?php echo date("m월 d일 H시i분",strtotime($rs['BG_BuyDate'])); ?></b> / 배당금액 : <b><?php echo number_format($rs['BG_BettingPrice']); ?>원</b><br>
                        배당률 : <b><?php echo $rs['BG_TotalQuota']; ?></b> / 예상 적중금액 : <b><?php echo number_format($rs['BG_ForecastPrice']); ?>원</b> / 당첨금 : <b class="lose"><?php echo $pay_result;?></b>
                        <div>

                        </div>
                    </fieldset>
                    <?php $cnt++; }} else { ?>
                <ul>
                    <li style="text-align: center; color:#fff;">현재 등록된 구매내역이 없습니다.</li>
                </ul>
            <?php } ?>

        </div>
        <!--배팅내역 첨부 끝 -->
        <?php } else { ?>
            <div class="sports_list">

                <table class="sub_board_list sub_board_list_betlist">
                    <thead>
                    <tr>
                        <td>파워볼 베팅내역 <dfn>1,274</dfn></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $cnt = 0;
                    $sql = "SELECT a.*,b.*, d.BG_BuyDate, d.BG_TotalQuota, d.BG_BettingPrice, d.BG_ForecastPrice, c.GL_Type FROM buygamelist a LEFT JOIN gamelist_other b ON a.G_Key = b.G_Key LEFT JOIN gameleague c ON b.GL_Key = c.GL_Key LEFT JOIN buygame d ON a.BG_Key = d.BG_Key WHERE a.BG_Key IN ({$row['B_BG_Key']})";
                    //echo $sql;
                    $rs = getArr($sql);
                    foreach($rs as $rs){
                        if($rs['BG_Result']=='Success'){
                            $rseult_text = '적중';
                            $result_css = "shot";
                        } else if($rs['BG_Result']=='Fail'){
                            $rseult_text =  '미적중';
                            $result_css = "noshot";
                        } else if($rs['BG_Result']=='Cancel'){
                            $rseult_text =  '취소(적특)';
                            $result_css = "tk";
                        } else {
                            $rseult_text =  '진행중';
                            $result_css = "ing";
                        }


                        if(in_array($rs['GL_Key'],array(1))){
                            if($rs['BGL_ResultChoice']=='Odd'){
                                $betting_text = "<span style='color:dodgerblue'>홀</span>";
                            } else if($rs['BGL_ResultChoice']=='Even'){
                                $betting_text = "<span style='color:orangered'>짝</span>";
                            }
                            $betting_game_text = "일반볼 홀/짝";
                        } else if(in_array($rs['GL_Key'],array(9))){
                            if($rs['BGL_ResultChoice']=='Odd'){
                                $betting_text = "<span style='color:dodgerblue'>언더</span>";
                            } else if($rs['BGL_ResultChoice']=='Even'){
                                $betting_text = "<span style='color:orangered'>오버</span>";
                            }
                            $betting_game_text = "파워볼 언더/오버";
                        } else if(in_array($rs['GL_Key'],array(8))){
                            if($rs['BGL_ResultChoice']=='Odd'){
                                $betting_text = "<span style='color:dodgerblue'>홀</span>";
                            } else if($rs['BGL_ResultChoice']=='Even'){
                                $betting_text = "<span style='color:orangered'>짝</span>";
                            }
                            $betting_game_text = "파워볼 홀/짝";
                        } else if(in_array($rs['GL_Key'],array(2,9))){
                            if($rs['BGL_ResultChoice']=='Under'){
                                $betting_text = "<span style='color:dodgerblue'>언더</span>";
                            } else if($rs['BGL_ResultChoice']=='Over'){
                                $betting_text = "<span style='color:orangered'>오버</span>";
                            }
                        } else if(in_array($rs['GL_Key'],array(4))){
                            if($rs['BGL_ResultChoice']=='Under'){
                                $betting_text = "<span style='color:dodgerblue'>홀+언더</span>";
                            } else if($rs['BGL_ResultChoice']=='Over'){
                                $betting_text = "<span style='color:orangered'>홀+오버</span>";
                            }
                            $betting_game_text = "일반볼 홀+조합";
                        } else if(in_array($rs['GL_Key'],array(5))){
                            if($rs['BGL_ResultChoice']=='Under'){
                                $betting_text = "<span style='color:dodgerblue'>짝+언더</span>";
                            } else if($rs['BGL_ResultChoice']=='Over'){
                                $betting_text = "<span style='color:orangered'>짝+오버</span>";
                            }
                            $betting_game_text = "일반볼 짝+조합";
                        } else if($rs['GL_Key']==3){
                            if($rs['BGL_ResultChoice']=='Big'){
                                $betting_text = "대";
                            } else if($rs['BGL_ResultChoice']=='Middle'){
                                $betting_text = "중";
                            } else if($rs['BGL_ResultChoice']=='Small'){
                                $betting_text = "소";
                            }
                            $betting_game_text = "일반볼 대/중/소";
                        } else if($rs['GL_Key']==10){
                            $betting_text = '숫자 '.$rs['BGL_ResultChoice'];
                            $betting_game_text = "파워볼 숫자";
                        } else if(in_array($rs['GL_Key'],array(11,16))){
                            if($rs['BGL_ResultChoice']=='Odd'){
                                $betting_text = "<span style='color:dodgerblue'>홀</span>";
                            } else if($rs['BGL_ResultChoice']=='Even'){
                                $betting_text = "<span style='color:orangered'>짝</span>";
                            }
                            $betting_game_text = "홀/짝";
                        } else if(in_array($rs['GL_Key'],array(12,17))){
                            if($rs['BGL_ResultChoice']=='Odd'){
                                $betting_text = "<span style='color:dodgerblue'>좌</span>";
                            } else if($rs['BGL_ResultChoice']=='Even'){
                                $betting_text = "<span style='color:orangered'>우</span>";
                            }
                            $betting_game_text = "좌출/우출";
                        } else if(in_array($rs['GL_Key'],array(13,18))){
                            if($rs['BGL_ResultChoice']=='Odd'){
                                $betting_text = "<span style='color:dodgerblue'>3줄</span>";
                            } else if($rs['BGL_ResultChoice']=='Even'){
                                $betting_text = "<span style='color:orangered'>4줄</span>";
                            }
                            $betting_game_text = "3줄/4줄";
                        } else if(in_array($rs['GL_Key'],array(14,19))){
                            if($rs['BGL_ResultChoice']=='Odd'){
                                $betting_text = "<span style='color:dodgerblue'>좌3짝</span>";
                            } else if($rs['BGL_ResultChoice']=='Even'){
                                $betting_text = "<span style='color:orangered'>좌4홀</span>";
                            }
                            $betting_game_text = "줄출조합";
                        } else if(in_array($rs['GL_Key'],array(15,20))){
                            if($rs['BGL_ResultChoice']=='Odd'){
                                $betting_text = "<span style='color:dodgerblue'>우3홀</span>";
                            } else if($rs['BGL_ResultChoice']=='Even'){
                                $betting_text = "<span style='color:orangered'>우4짝</span>";
                            }
                            $betting_game_text = "줄출조합";
                        }

                        ?>
                        <tr>
                            <td>
                                <!-- 베팅내역 { -->
                                <div>
                                    <dfn class="font_gobet">No. <?php echo $rs['BG_Key'];?> &nbsp; <span><?php echo $rs['GI_Type']; ?></span></dfn>
                                    <label><span class="ing">진행중</span></label>
                                </div>
                                <fieldset>
                                    
                                    <dl>
                                        <dt>베팅내역</dt>
                                        <dd class="blue"><?php echo $betting_text;?></dd>
                                        <dt>적중상태</dt>
                                        <dd class="green"><?php echo $rseult_text;?></dd>
                                    </dl>
                                    <dl>
                                        <dt>게임회차</dt>
                                        <dd><?php echo date("m월-d일",strtotime($rs['G_Datetime']));?> - <?php echo $rs['G_Num']?>회</dd>
                                        <dt>게임구분</dt>
                                        <dd><?php echo $rs['GL_Type']; ?></dd>
                                    </dl>
                                    <dl>
                                        <dt>베팅일시</dt>
                                        <dd><?php echo date("m월-d일 H:i:s",strtotime($rs['BG_BuyDate']));?></dd>
                                        <dt>배당률</dt>
                                        <dd><?php echo $rs['BG_TotalQuota'];?></dd>
                                    </dl>
                                    <dl>
                                        <dt>베팅금액</dt>
                                        <dd><?php echo number_format($rs['BG_BettingPrice']);?>원</dd>
                                        <dt>적중/손실</dt>
                                        <dd class="green"><?php echo number_format($rs['BG_ForecastPrice']);?>원</dd>
                                    </dl>
                                </fieldset>
                                <!-- } 베팅내역 -->
                            </td>
                        </tr>

                        <?php
                        $cnt++;
                    }
                    ?>

                    </tbody>
                </table>

            </div>
        <?php } ?>
        <div class="sub_board">
            <div class="sub_board_view">
                <div>
                    <?php echo $row['B_Content']; ?>
                </div>
            </div>

            <!--<div class="sub_board_reply">
                <div class="sub_board_reply_input">
                    <textarea placeholder='욕설, 상대방 비방글, 타사이트 언급, 홍보 등은 경고없이 삭제되며 사이트 이용에 제한을 받을 수 있습니다.'></textarea>

                    <input type="button" value="댓글 등록" class="reply_btn">

                </div>
                <ul>
                    <li>
                        <code class="lvname"><label class="lv3"></label>돼지국밥</code>
                        <em>안녕하세요</em>
                    </li>
                    <li>
                        <code class="lvname"><label class="lv4"></label>추어탕</code>
                        <em>한화 김성근 감독은 비야누에바의 엔트리 제외에 대해 "크게 심각한 건 아니다.
                            원래 갖고 있던 통증이라 본인이 던지겠다고 하는데 무리하지 말라고 했다. 일본 요코하마로 보냈는데 열흘 정도 쉬어야 한다.
                            로테이션을 두 번 정도 건너뛸 것이다"고 밝혔다.</em>
                    </li>
                    <li>
                        <code class="lvname"><label class="lv5"></label>갈비탕</code>
                        <em>안녕하세요</em>
                    </li>
                    <li>
                        <code class="lvname"><label class="lv1"></label>소고기먹자</code>
                        <em>한화 김성근 감독은 비야누에바의 엔트리 제외에 대해 "크게 심각한 건 아니다.
                            원래 갖고 있던 통증이라 본인이 던지겠다고 하는데 무리하지 말라고 했다. 일본 요코하마로 보냈는데 열흘 정도 쉬어야 한다.
                            로테이션을 두 번 정도 건너뛸 것이다"고 밝혔다.</em>
                        <span><a href="">수정</a> <a href="">삭제</a></span>
                    </li>
                </ul>
            </div>-->

            <div class="sub_board_btn">
                <a href="../" class="btn_gray">목록</a>
                <a href="javascript:;" class="btn_green done">수정</a>
                <a href="javascript:;" class="btn_red delete">삭제</a>
            </div>

        </div>

    </div> <!-- Sub Wrap -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('.done').on('click',function(){
                location.href = '../modify/?tn=board&b_key=<?php echo $b_key; ?>';
            });
            $('.cancel').on('click',function(){
                location.href = '/m/mypage/board/';
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
                f.action = "/m/action/board_action.php";
                f.submit();

            };
        };
    </script>
<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php';
?>