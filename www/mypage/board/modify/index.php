<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) {
        //swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }

    if($SITECONFIG['M_Board_YN']!='Y'){
        msg('정상적인 방법으로 접근하세요.'); back();
    }


    $board_title = $lib24c->Check_Board($_GET['tn']);


    $row = $lib24c->Get_Board_Read( $_GET['b_key'] );


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

            <div class="sub-box">

                <div class="board_wrap">

                    <input type="text" name="b_subject" id="b_subject" class="sub_board_write_title" value="<?php echo $row['B_Subject']; ?>" placeholder="제목을 입력해주세요." />
                    <!--<h2 class="sub_board_write_info add_btn" onClick="$(this).removeClass('add_btn'); $('table.hide').removeClass('hide'); $('.popup_box').removeClass('hide');">베팅내역 첨부</h2>-->

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
                                                <em>
                                                    <!--<B>베팅취소</B>-->
                                                    
                                                    <font class="betting-del" data-dkey="<?php echo $rs['BG_Key']; ?>">첨부삭제</font>
                                                </em>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $cnt++; }} ?>
                            </tbody>
                        </table>

                        <h2 class="sub_board_write_info">
                            글내용 입력
                            <font>
                                욕설, 상대방 비방글, 타사이트 언급, 홍보 등은 경고없이 삭제되며 사이트 이용에 제한을 받을 수 있습니다.
                            </font>
                        </h2>
                        <textarea class="board_write_contents" name="b_content" id="summernote" placeholder="내용을 입력해주세요."><?php echo nl2br($row['B_Content']); ?></textarea>
                    </div>

                    <div class="btn_box btn_box_center">
                        <a href="javascript:;" class="btn_green"  OnClick="javascript:Action_Write();">수정</a>
                        <a href="../" class="btn_gray">취소</a>
                    </div>

                </div> <!-- board_wrap -->

            </div> <!-- sub-box -->


        </div>

    </div> <!-- sub_wrap -->
    </div> <!-- sub_bg -->

<?php
    if($tn == 'betting')    $tn = 'board';
?>
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#summernote').summernote({
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                ],
                lang: 'ko-KR',
                placeholder: '내용을 입력하세요.',
                tabsize: 2,
                height: 300
            });
            $('.betting-del').on('click',function(){
               var delkey = $(this).data('dkey');
               $.ajax({
                   type : 'post',
                   url : './proc/',
                   dataType : 'json',
                   data : 'mode=attachDel&bkey=<?php echo $_GET['b_key'];?>&delkey='+delkey,
                   success : function(data){
                        if(data.flag == true){
                            $('.attach-'+data.bgkey).hide();
                        }
                   }
               });
            });
        });
    </script>
    <script>

        function Action_Write() {
            var f = document.HiddenActionForm;

            var _subject  = document.getElementById( "b_subject" );
            var _type     = document.getElementById( "b_type" );
            var _content = document.getElementById( "summernote" );

            if ( _subject.value.trim() == "" ) {
                swal("","제목을 입력해 주세요.","warning");
                _subject.focus();
                return;
            }

            if(_content.value.trim() == ''){
                swal('','내용을 입력해주세요.','warning');
                _content.focus();
                return;
            }

            if ( confirm("게시물을 수정 하시겠습니까?") ) {
                f.HAF_Value_0.value = "BoardModify";
                f.HAF_Value_1.value = "<?=$tn;?>";
                f.HAF_Value_2.value = _subject.value;
                f.HAF_Value_3.value = _content.value;
                f.HAF_Value_4.value = <?=$_GET['b_key'];?>;
                f.HAF_Value_5.value = "&b_key=<?php echo $b_key; ?>";
                f.HAF_Value_7.value = "";
                f.HAF_Value_8.value = _type;
                f.method = "POST";
                f.action = "/action/board_action.php";
                f.submit();

            };
        }
    </script>
<?php
include_once $root_path.'/include/footer.php';
?>