<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

if ( !$_SESSION['S_Key'] ) {
    swal_move('로그인이 필요한 페이지 입니다.', 'login');
}

if(empty($startDate)) $startDate = date("Y-m-d",strtotime("-7 day"));
if(empty($endDate))   $endDate = date("Y-m-d");

$tb = "buygame_live";

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
    <script>
        $(document).ready(function(){
            $("ol.login_st > li:nth-child(4)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">스포츠게임 베팅내역</div>
                <div class="title2">SPORTS BOOK BETTING LIST</div>
            </div>
            <div class="sub-box">
                <div class="mypage_menu1">
                    <a href="/mypage/betlist/sports/" class="active">베팅내역</a>
                    <a href="/mypage/charge/">충전내역</a>
                    <a href="/mypage/refund/">환전내역</a>
                    <a href="/mypage/point/exchangelist/">포인트내역</a>
                    <?php if(in_array($_SESSION['S_Level'],array(4,5))){ ?>
                        <a href="/mypage/branch/">총판관리</a>
                    <?php } ?>
                    <a href="/mypage/message/">쪽지관리</a>
                    <a href="/mypage/member/modify/">회원정보수정</a>
                </div>
                <div class="mypage_menu2">
                    <a href="/mypage/betlist/sports/">스포츠게임</a>
                    <a href="/mypage/betlist/live/" class="active" >라이브</a>
                    <a href="/mypage/betlist/casino/" >카지노</a>
                    <a href="/mypage/betlist/minigame/power/">미니게임</a>
                    <a href="/mypage/betlist/virtual/" >가상게임</a>
                </div>



                <div class="board_wrap">

                    <form name="f" id="f" method="get" action="./">
                        <div class="mypage-day-search">
                            <div class="title">베팅기간</div>
                            <div class="input_box">
                                <span class="active" data-day="<?php echo date("Y-m-d"); ?>">오늘</span>
                                <span data-day="<?php echo date("Y-m-d",strtotime("-7 day")); ?>">1주일</span>
                                <span data-day="<?php echo date("Y-m-d",strtotime("-15 day")); ?>">15일</span>
                                <span data-day="<?php echo date("Y-m-d",strtotime("-1 month")); ?>">1개월</span>
                                <span data-day="<?php echo date("Y-m-d",strtotime("-3 month")); ?>">3개월</span> &nbsp;
                                <input type="text" name="startDate" id="startDate" value="<?php echo $startDate; ?>">&nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;<input type="text" name="endDate" id="endDate" value="<?php echo $endDate; ?>">&nbsp;
                                <code class="view">조회하기</code>
                            </div>
                        </div>
                    </form>
                    <table class="table-black mypage">
                        <thead>
                        <tr>
                            <td>베팅구분</td>
                            <td width="260">승(홈)</td>
                            <td>무</td>
                            <td width="260">패(원정)</td>
                            <td>점수</td>
                            <td>선택</td>
                            <td >결과</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $cur_gid = "";
                        $game_state = 0;
                        $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함
                        $que = "SELECT  * FROM {$tb} WHERE {$where} {$order_by}  LIMIT {$start},{$view_article}";
                        //echo $que;
                        $arr = getArr($que);
                        if(count($arr)>0){
                            foreach($arr as $rs) {
                                $sql = "SELECT *, a.G_Key as bgkey FROM buygamelist_live a LEFT JOIN live_gamelist b ON a.G_Key = b.G_Key WHERE a.M_Key = '{$_SESSION['S_Key']}' AND a.BG_Key = '{$rs['BG_Key']}'";
                                //echo $sql."<br>";

                                $ar = getArr($sql);
                                if(count($ar)>0){
                                    foreach($ar as $list){

                                        $rate_win_select = $rate_draw_select = $rate_lose_select = $game_choice = "";
                                        if(in_array($list['BGL_ResultChoice'],array('Win','Draw','Lose'))){
                                            $rate_win = number_format($list['BGL_QuotaWin'],2);
                                            $rate_draw = ($list['BGL_QuotaDraw']>0)?number_format($list['BGL_QuotaDraw'],2):'VS';
                                            $rate_lose = number_format($list['BGL_QuotaLose'],2);
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
                                        } else if(in_array($list['BGL_ResultChoice'],array('Under','Over'))){

                                            $rate_win = number_format($list['BGL_QuotaOver'],2);
                                            $rate_draw = $list['BGL_QuotaUnderOver'];
                                            $rate_lose = number_format($list['BGL_QuotaUnder'],2);
                                            if($list['BGL_ResultChoice']=='Over') {
                                                $rate_win_select = 'selected01';
                                                $game_choice = "오버";
                                            } else if($list['BGL_ResultChoice']=='Under') {
                                                $rate_lose_select = 'selected01';
                                                $game_choice = "언더";
                                            }
                                        } else if(in_array($list['BGL_ResultChoice'],array('HandiWin','HandiLose'))){
                                            $rate_win = number_format($list['BGL_QuotaHandiWin'],2);
                                            $rate_draw = $list['BGL_QuotaHandicap'];
                                            $rate_lose = number_format($list['BGL_QuotaHandiLose'],2);
                                            if($list['BGL_ResultChoice']=='HandiWin') {
                                                $rate_win_select = 'selected01';
                                                $game_choice = "핸디 승";
                                            } else if($list['BGL_ResultChoice']=='HandiLose') {
                                                $rate_lose_select = 'selected01';
                                                $game_choice = "핸디 패";
                                            }
                                        }


                                        if($list['G_State'] == 'End' || $list['G_ResultScoreWin'] != '' || $list['G_ResultScoreLose']!='' || $list['BGL_State']=='Cancel'){
                                            if($list['BGL_State'] != 'Await'){
                                                if($list['BGL_State']=='Success' || $list['BGL_State']=='Cancel'){

                                                    if($list['BGL_State']=='Cancel'){
                                                        $g_state = "적특[취소]";
                                                        $g_state_css = "shot";
                                                    } else {
                                                        $g_state = "적중";
                                                        $g_state_css = "shot";
                                                    }
                                                } else if($list['BGL_State']=='Fail'){
                                                    $g_state = "미적중";
                                                    $g_state_css = "noshot";
                                                }

                                            } else {
                                                $g_state = "종료";
                                                $g_state_css = "";
                                            }
                                        } else if($list['status'] == 1){
                                            $g_state = "경기중";
                                            $g_state_css = "";
                                            $game_state++;
                                        } else if($list['status']==3){
                                            $g_state = "경기종료";
                                            $g_state_css = "";
                                        }


                                        $pay_result = '처리전';
                                        if($rs['BG_Result']=='Success' || $rs['BG_Result']=='Cancel'){
                                            $pay_result = number_format($rs['BG_ForecastPrice']);
                                        } else if($rs['BG_Result'] == 'Fail'){
                                            $pay_result = 0;
                                        }

                                        $que = "SELECT * FROM gameleague_live WHERE GL_Key_IDX = '{$list['GL_Key']}'";
                                        //echo $que;
                                        $gl = getRow($que);

                                        $mk = ($list['G_MarketNameKor']!='')?$list['G_MarketNameKor']:$list['G_MarketName'];
                                        $mk = explode(" ",$mk);
                                        $mkt = "[".$mk[1]."]";
                                        /*if($cur_gid != $list['G_ID']){
                                            if(empty($cur_gid))   $cur_gid = $list['G_ID'];*/

                                            ?>

                                            <tr>
                                                <td colspan="8" class="league_title">
                                                    <img src="/img/icon_<?php echo $ITEMICON[$list['GI_Key']];?>.png" style="width: 20px;" /><!--<img src="/_go/renewal/img/icon_pic.png" />--> &nbsp; <?php echo $gl['GL_Type']; ?> &nbsp; - &nbsp; <?php echo date("m/d H:i",strtotime($list['G_Datetime'])); ?>
                                                </td>
                                            </tr>
                                            <?php
                                            //$cur_gid = $list['G_ID']; }
                                        ?>
                                        <tr class="line">
                                            <td><?php echo $GAME_TYPE_TEXT[$list['G_Type2']];?> <?php echo ($list['G_Type1']=='Special')?$mkt:''; ?></td>
                                            <td class="<?php echo $rate_win_select;?>">
                                    <span>
                                        <span><?php echo (!empty($list['G_Team1']))?$list['G_Team1']:$list['G_Team1']; ?></span>
                                        <em><?php echo $rate_win;?></em>
                                    </span>
                                            </td>
                                            <td class="<?php echo $rate_draw_select;?>"><span class="margin"><?php echo $rate_draw;?></span></td>
                                            <td class="<?php echo $rate_lose_select;?>">
                                    <span>
                                        <span><?php echo $rate_lose;?></span>
                                        <em><?php echo (!empty($list['G_Team2']))?$list['G_Team2']:$list['G_Team2'];; ?></em>
                                    </span>
                                            </td>
                                            <td>
                                                <?php
                                                if(in_array($list['G_State'],array('End','Stop'))) {
                                                    echo $list['G_ResultScoreWin'] . ':' . $list['G_ResultScoreLose'];
                                                } else {
                                                    echo '- : -';
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $game_choice; ?></td>
                                            <td class="<?php echo $g_state_css;?>"><code style="width:80px;"><?php echo $g_state; ?></code></td>
                                        </tr>
                                        <tr class="empty_line">
                                            <td colspan="7" class="empty_line"></td>
                                        </tr>
                                    <?php }} ?>
                                <tr>
                                    <td colspan="7" class="table-left">
                                        <div>
                                        <span>
                                            <input type="checkbox" name="bgkey[]" class="bgkey" value="<?php echo $rs['BG_Key']; ?>">
                                            베팅일시&nbsp; : &nbsp;<font><?php echo date("Y년m월d일 H시i분",strtotime($rs['BG_BuyDate'])); ?></font>&nbsp; / &nbsp;베팅금액&nbsp; : &nbsp;<font><?php echo number_format($rs['BG_BettingPrice']); ?>원</font><BR/>
                                            배당률&nbsp; : &nbsp;<font><?php echo $rs['BG_TotalQuota']; ?></font>&nbsp; / &nbsp;적중예상금액&nbsp; : &nbsp;<font><?php echo number_format($rs['BG_ForecastPrice']); ?>원</font>&nbsp; / &nbsp;당첨금&nbsp; : &nbsp;<B><?php echo $pay_result;?></B>
                                        </span>
                                            <em>
                                                <!--<B>베팅취소</B>-->
                                                <B>베팅내역삭제</B>
                                                <font class="betting-upload">내역올리기</font>

                                            </em>
                                        </div>
                                    </td>
                                </tr>
                                <?php $cnt++; }} else { ?>
                            <tr>
                                <td colspan="10">현재 등록된 구매내역이 없습니다.</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>



                </div> <!-- board_wrap -->
                <?php
                if($total_article>0) {
                    include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
                }
                ?>
                <div class="betlist_bottom">
                    <div class="left">
                        <a class="choose selectAll">전체선택</a>
                        <a class="del" data-game_state="<?php echo $game_state;?>">선택삭제</a>
                    </div>
                    <div class="right">
                        <a class="betting-upload">게시판에 베팅내역 올리기</a>
                    </div>
                </div>

            </div> <!-- sub-box -->

        </div> <!-- sub_wrap -->

    </div> <!-- sub_bg -->
    <script>
        $(document).ready(function(){
            $('.input_box > span').on('click',function(){
                var startDay  = $(this).data("day");
                $('input[name="startDate"]').val(startDay);
            });
            $('.input_box > span').on('click',function(){
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
                        url : '/action/board_action.php',
                        dataType : 'json',
                        data : 'HAF_Value_0=BoardGameResultWrite&HAF_Value_1='+bgkey+'&HAF_Value_0=BoardGameResultWrite',
                        success : function(data){
                            if(data.flag == true){
                                location.href = '/mypage/board/modify/?&tn=betting&b_key='+data.bkey;
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
include_once $root_path.'/include/footer.php';
?>