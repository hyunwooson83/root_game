<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

$board_title = $lib24c->Check_Board($_GET['tn']);

$row = $lib24c->Get_Board_Read( $_GET['b_key'] );


?>
    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>게시판</span>
                <em>Board</em>
            </h1>

        </div>

        <div class="sub_board">

            <div class="sub_board_write">
                <div><input type="text" placeholder="제목을 입력해주세요." name="b_subject" id="b_subject"  value="<?php echo $row['B_Subject']; ?>" style="color:#222;" /></div>
                <!--<div><a class="b_list" href="/mobile/board_write_bettinglist.html" target="_blank">베팅내역 첨부</a></div>-->
                <?php if($_REQUEST['mini'] != 'Y') { ?>
                <!--스포츠 배팅내역 첨부 시작 -->
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
                                <em  ><input type="checkbox" name="bgkey[]" class="bgkey" value="<?php echo $rs['BG_Key']; ?>"></em>
                                베팅시간 : <b><?php echo date("m월 d일 H시i분",strtotime($rs['BG_BuyDate'])); ?></b> / 배당금액 : <b><?php echo number_format($rs['BG_BettingPrice']); ?>원</b><br>
                                배당률 : <b><?php echo $rs['BG_TotalQuota']; ?></b> / 예상 적중금액 : <b><?php echo number_format($rs['BG_ForecastPrice']); ?>원</b> / 당첨금 : <b class="lose"><?php echo $pay_result;?></b>
                                <div>
                                    <!--<a href="">베팅취소</a>--> &nbsp;<a href="javascript:;" class="betting-del" data-dkey="<?php echo $rs['BG_Key']; ?>">삭제</a> &nbsp;
                                </div>
                            </fieldset>
                            <?php $cnt++; }} else { ?>
                        <ul>
                            <li style="text-align: center; color:#fff;">현재 등록된 구매내역이 없습니다.</li>
                        </ul>
                    <?php } ?>

                </div>
                <!--스포츠 배팅내역 첨부 끝 -->
                <?php } else { ?>
                
                <!--미니게임 배팅내역 첨부 시작 -->
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
                                            <em><input type="checkbox" name="bgkey[]" class="bgkey" value="<?php echo $rs['BG_Key']; ?>"></em>
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
                <!--미니게임 배팅내역 첨부 끝 -->
                <?php } ?>

                <div class="text"><textarea placeholder='내용을 입력해주세요.' name="b_content" id="summernote"><?php echo nl2br($row['B_Content']); ?></textarea></div>
            </div>



            <div class="sub_board_btn">
                <a href="javascript:;" OnClick="javascript:Action_Write();">등록</a>
                <a href="../" class="btn_gray">취소</a>
            </div>

        </div>

    </div> <!-- Sub Wrap -->

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
                f.action = "/m/action/board_action.php";
                f.submit();

            };
        }
    </script>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php';
?>