<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

if ( !$_SESSION['S_Key'] ) {
    swal_move('로그인이 필요한 페이지 입니다.', 'login');
}

if(empty($startDate)) $startDate = date("Y-m-d");
if(empty($endDate))   $endDate = date("Y-m-d");

$tb = "buygame";

$view_article = 8; // 한화면에 나타날 게시물의 총 개수
if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
$start = ($_GET['page']-1)*$view_article;
$href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}&startDate={$_GET['startDate']}&endDate={$_GET['endDate']}";


$where = " 1 AND M_Key = {$_SESSION['S_Key']} AND BG_Visible = '1' AND  BG_Gubun IN ('prematch','live') ";

if(!empty($startDate)){
    $where .= " AND DATE_FORMAT(BG_BuyDate,'%Y-%m-%d') BETWEEN '{$startDate}' AND '{$endDate}' ";
}

#성명으로 정렬시
$order_by = " ORDER BY BG_BuyDate DESC ";

$cnt = 0;
$betting_text = "";
$betting_game_text = "";
$query = "SELECT COUNT(*) FROM {$tb} WHERE {$where}   ";

$row = getRow($query);


?>
    <div id="sub_wrap">
        <div class="sub_title">
            <ul class="sub_title_category">
                <li onClick="location.href='/m/mypage/betlist/sports/'" class="active">스포츠</li>
                <!--<li onClick="location.href='/mobile/mypage/list_evolution.html'">카지노</li>-->
                <li onClick="location.href='/m/mypage/betlist/minigame/power/'">미니게임</li>
                <li onClick="location.href='/m/mypage/betlist/virtual/'">가상게임</li>
            </ul>
            <h1>
                <span>스포츠 베팅내역</span>
                <em>SPORTS BETTING LIST</em>
            </h1>
        </div>
        <form name="f" id="f" method="get" action="./">
            <div class="sub_mypage_wrap">
                <!-- 경기검색 { -->

                <div class="sub_searchbox">

                    <div class="search">
                        <ul>
                            <li>
                                <input type="text" class="date" name="startDate" id="startDate" value="<?php echo $startDate; ?>"> ~ <input type="text" class="date" name="endDate" id="endDate" value="<?php echo $endDate; ?>">
                            </li>
                            <li>
                                <ol>
                                    <li class="active" data-day="<?php echo date("Y-m-d"); ?>">오늘</li>
                                    <li data-day="<?php echo date("Y-m-d",strtotime("-7 day")); ?>">1주일</li>
                                    <li data-day="<?php echo date("Y-m-d",strtotime("-15 day")); ?>">15일</li>
                                </ol>
                            </li>
                        </ul>
                        <ul>
                            <li>
                                <select>
                                    <option value="리그선택">리그선택</option>
                                    <option value="라리가">라리가</option>
                                    <option value="세리아A">세리아A</option>
                                    <option value="맨체스터 유나이티드">맨체스터 유나이티드</option>
                                </select>
                            </li>
                            <li>
                                <input type="text" class="name" placeholder="팀명검색">
                            </li>
                            <li>
                                <span class="view">검색하기</span>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
        </form>
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
            $que = "SELECT  * FROM {$tb} WHERE {$where} {$order_by}  LIMIT {$start},{$view_article}";
            //echo $que;
            $arr = getArr($que);
            if(count($arr)>0){
                foreach($arr as $rs) {
                    $sql = "SELECT * FROM buygamelist a LEFT JOIN gamelist b ON a.G_Key = b.G_Key LEFT JOIN gameleague c ON b.GL_Key = c.GL_Key_IDX WHERE a.M_Key = '{$_SESSION['S_Key']}' AND a.BG_Key = '{$rs['BG_Key']}'";

                    //echo $sql;
                    $ar = getArr($sql);
                    if(count($ar)>0){
                        foreach($ar as $list){
                            $rate_win_select = $rate_draw_select = $rate_lose_select = $game_choice = $type_over = $type_under = "";
                            if(in_array($list['BGL_ResultChoice'],array('Win','Draw','Lose'))){
                                $rate_win = number_format($list['G_QuotaWin'],2);
                                $rate_draw = number_format($list['G_QuotaDraw'],2);
                                $rate_lose = number_format($list['G_QuotaLose'],2);
                                if($list['BGL_ResultChoice']=='Win') {
                                    $rate_win_select = 'on';
                                    $game_choice = "홈팀 승";
                                } else if($list['BGL_ResultChoice']=='Draw') {
                                    $rate_draw_select = 'on';
                                    $game_choice = "무승부";
                                } else {
                                    $rate_lose_select = 'on';
                                    $game_choice = "홈팀 패";
                                }
                            }
                            if(in_array($list['BGL_ResultChoice'],array('Under','Over'))){
                                $rate_win = number_format($list['G_QuotaOver'],2);
                                $rate_draw = $list['G_QuotaUnderOver'];
                                $rate_lose = number_format($list['G_QuotaUnder'],2);
                                if($list['BGL_ResultChoice']=='Over') {
                                    $rate_win_select = 'on';
                                    $game_choice = "오버";

                                } else if($list['BGL_ResultChoice']=='Under') {
                                    $rate_lose_select = 'on';
                                    $game_choice = "언더";

                                }
                            }
                            if(in_array($list['BGL_ResultChoice'],array('HandiWin','HandiLose'))){
                                $rate_win = number_format($list['G_QuotaHandiWin'],2);
                                $rate_draw = $list['G_QuotaHandicap'];
                                $rate_lose = number_format($list['G_QuotaHandiLose'],2);
                                if($list['BGL_ResultChoice']=='HandiWin') {
                                    $rate_win_select = 'on';
                                    $game_choice = "핸디 승";
                                } else if($list['BGL_ResultChoice']=='HandiLose') {
                                    $rate_lose_select = 'on';
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
                            ///img/icon_bonus.png

                            if($list['bgkey']==1){
                                $list['G_Team1'] = '3폴더 이상';
                                $list['G_Team2'] = '배팅금지';
                                $list['GI_Key'] = '10000001';
                                $list['GL_Type'] = '보너스';
                                $g_state = "적중";
                                $g_state_css = "shot";
                            } else if($list['bgkey']==2){
                                $list['G_Team1'] = '4폴더 이상';
                                $list['G_Team2'] = '배팅금지';
                                $list['GI_Key'] = '10000001';
                                $list['GL_Type'] = '보너스';
                                $g_state = "적중";
                                $g_state_css = "shot";
                            } else if($list['bgkey']==3){
                                $list['G_Team1'] = '5폴더 이상';
                                $list['G_Team2'] = '배팅금지';
                                $list['GI_Key'] = '10000001';
                                $list['GL_Type'] = '보너스';
                                $g_state = "적중";
                                $g_state_css = "shot";
                            }


                            $type_over = '<var class="arr_up arr_wave">▲</var>';
                            $type_under = '<var class="arr_down arr_wave">▼</var>';
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
                                        <dd class="center <?php echo $rate_draw_select;?>">
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
                            <?php
                            $betCancelTime =strtotime($rs['BG_BuyDate']." +".$SITECONFIG['member_bet_cancel_time']." minute");
                            if($betCancelTime>time() && $SITECONFIG['member_bet_cancel_YN']=='Y'&& $meminfo['M_Bet_Cancel_Cnt']>0){
                                ?>
                                <a href="javascript:;" class="betting-cancel" data-bgkey="<?php echo $rs['BG_Key'];?>">베팅취소</a> &nbsp;
                            <?php } ?>
                            <a href="">삭제</a> &nbsp;<a href="javascript:;" class="betting-upload">내역올리기</a>
                        </div>
                    </fieldset>
                    <?php $cnt++; }} else { ?>
                <ul>
                    <li style="text-align: center; color:#fff;">현재 등록된 구매내역이 없습니다.</li>
                </ul>
            <?php } ?>

        </div> <!-- sports_list -->

        <div class="sub_board" style="border-top:none">

            <div class="sub_board_btn">
                <a href="#" class="btn_gray selectAll">전체선택</a>
                <a href="#" class="btn_gray del" data-game_state="<?php echo $game_state;?>">선택삭제</a>
            </div>
            <div class="sub_board_btn margin betting-upload">
                <a href="javascript:;" class="">선택항목 게시판에 올리기</a>
            </div>

            <?php
            if($total_article>0) {
                include_once($_SERVER['DOCUMENT_ROOT'] . "/m/lib/page.php");
            }
            ?>
        </div>

    </div>
    <script>
        $(document).ready(function(){
            $('div.sub_mypage_wrap > div > div > ul:nth-child(1) > li > ol > li').on('click',function(){
                var startDay  = $(this).data("day");
                $('input[name="startDate"]').val(startDay);
            });
            $('div.sub_mypage_wrap > div > div > ul:nth-child(1) > li > ol > li').on('click',function(){
                var startDay  = $(this).data("day");
                $('input[name="startDate"]').val(startDay);
            });
            $('.view').on('click',function(){
                $('#f').submit();
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

        $('.selectAll').on('click',function(){
            if($(this).text()=='전체선택'){
                $('.bgkey').prop('checked',true);
                $(this).text('선택해제');
            } else {
                $('.bgkey').prop('checked',false);
                $(this).text('전체선택');
            }
        });

        $('.del').on('click',function(){
            var cnt = 0;
            var bgkey = new Array();
            $('.bgkey').each(function(){
                if($(this).is(':checked')==true){
                    bgkey[cnt] = $(this).val();
                    cnt++;
                }
            });
            if(!cnt){
                swal('','삭제할 배팅내역을 선택해주세요.','warning');
                return false;
            }

            $.ajax({
                type : 'post',
                url : './proc/',
                dataType : 'json',
                data : 'HAF_Value_0=deleteBetList&bgkey='+bgkey,
                success : function(data){
                    if(data.flag == true){
                        swal('','배팅내역이 정상적으로 삭제되었습니다.','success');
                        setTimeout(function(){location.reload()},3000);
                    } else {
                        swal('',data.error,'warning');
                    }
                }
            });
        });

        $('.betting-upload').on('click',function(){
            var cnt = 0;
            var bgkey = new Array();
            $('.bgkey').each(function(){
                if($(this).is(':checked')==true){
                    bgkey[cnt] = $(this).val();
                    cnt++;
                }
            });
            if(!cnt){
                swal('','등록할 배팅내역을 선택해주세요.','warning');
                return false;
            }

            swal({
                text: "베팅정보를 등록하시겠습니까?",
                type: "success",
                confirmButtonText: "확인",
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type : 'post',
                        url : '/m/action/board_action.php',
                        dataType : 'json',
                        data : 'HAF_Value_0=BoardGameResultWrite&HAF_Value_1='+bgkey+'&HAF_Value_0=BoardGameResultWrite',
                        success : function(data){
                            if(data.flag == true){
                                location.href = '/m/mypage/board/modify/?&tn=betting&b_key='+data.bkey;
                            } else {
                                swal('',data.error,'warning');
                            }
                        }
                    });
                }
            });

        });

        $('.betting-cancel').on('click',function(){
            var cnt = 0;
            var bgkey = $(this).data('bgkey');
            if(!bgkey){
                swal('','취소할 배팅을 선택해주세요.','warning');
                return false;
            }

            swal({
                text: "베팅을 취소하시겠습니까?",
                type: "success",
                confirmButtonText: "확인",
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type : 'post',
                        url : './proc/',
                        dataType : 'json',
                        data : 'mode=bettingCancel&bgkey='+bgkey,
                        success : function(data){
                            if(data.flag == true){
                                swal('','배팅이 정상적으로 취소되었습니다.','success');
                                setTimeout(function(){ location.reload();},2000);
                            } else {
                                swal('',data.error,'warning');
                            }
                        }
                    });
                }
            });
        });
    </script>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php';
?>