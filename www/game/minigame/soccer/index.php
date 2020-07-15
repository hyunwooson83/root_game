<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';


// 로그인 체크
if ( !$_SESSION['S_Key'] ) {
    swal_move('로그인이 필요한 페이지 입니다.', 'login');
}

setQry("DELETE FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'");

// 풀타임 - 승무패
$offset = $_GET['offset'];

//echo date("Y-m-d H:i:s")."------";
$bettime = date("Y-m-d H:i:s",strtotime("+30 seconds",strtotime(date("Y-m-d H:i:s"))));

$que = "
                    
                    SELECT 
                        *
                    FROM 
                        gamelist_other a 
                    WHERE                                                        
                        a.G_Type1='Full' 
                    AND 
                        a.G_Type2='WDL' 
                    AND  
                        a.G_State = 'Await'
                    AND 
                        a.G_Datetime > NOW()
                        $where 
                    AND
                        a.GL_Key IN (16,17,18,19,20)
                    
                    ORDER BY a.G_Datetime ASC, a.GL_Key asc
                    LIMIT 5
            ";

$rows = getArr($que);

foreach($rows as $rows){
    $real_num[]     = $rows['G_Num2'];
    $num[]          = $rows['G_Num'];
    $glkey[]        = $rows['GL_Key'];
    $gkey[]         = $rows['G_Key'];
    $gdatetime[]    = $rows['G_Datetime'];
}

$endtimeint = strtotime($gdatetime[0])-time();
$game_type_gubun = "키노사다리";
$pl = $MINIGAME_RATE['MG_KenoLadder'];
$pl = explode("|",$pl);



?>

<link rel="stylesheet" href="/css/minigame.css">
<script>
    $(document).ready(function(){
        $(".sub_header > .top2 > li:nth-child(6)").addClass('active');
    });
</script>
<div class="sub_bg">
    <div class="sub_wrap type2">
        <div class="sub_title">
            <div class="title1">벳이스트-축구</div>
            <div class="title2">BETEAST SOCCER GAME</div>
        </div>
        <!--<ul class="sub_menu">
            <li class="on" onclick="location.href='/money/charge/'">보유머니 충전</li>
            <li onclick="location.href='/money/charge/list/'">보유머니 충전내역</li>
            <li onclick="location.href='/money/casino/charge/'">카지노 충전</li>
            <li onclick="location.href='/money/casino/charge/list/'">카지노 충전내역</li>
        </ul>-->
        <div class="sub_box money_box">
            <div class="money_caution">
                <div id="body-contents" class="black_bg">
                    <div class="body-wrap">
                        <div style="height:27px"></div>
                        <div id="mini_wrap">
                            <!-- 파워볼 상단 타이머 -->
                            <div class="minigame_menu">
                                <?php include_once "../include/time_box_top.php"; ?>
                            </div>

                            <div class="mini_midwrapbox" id="game-page">
                                <input type="hidden" name="ntry_power"  id="ntry_power" value="0">
                                <input type="hidden" name="ntry_min" id="ntry_min" value="0">
                                <input type="hidden" name="ntry_remind" id="ntry_remind" value="0">

                                <div class="games-wrap" id="mini_boxwrap">
                                    <div id="mini_box_right">
                                        <div class="mini_gamemovie">
                                            <div class="power_ladder">
                                                <iframe src="https://api.xyzblue.com/docs/contents/mobile/basketball.html" scrolling="no" style="left:-230px; position: absolute"></iframe>
                                            </div>
                                        </div>
                                        <!-- 파워볼 베팅 타이머 -->
                                        <div class="mini_timecount">
                                            <span id="last-play-date"><?php echo substr($gdatetime[0],0,10); ?></span>
                                            <font>[<span id="play_num_view" class="timer-num-text"><?php echo $num[0]; ?></span> 회차]</font>
                                            <span id="last_play_time"><?php echo substr($gdatetime[0],-8,5); ?></span>
                                            <span class="count" id="remaind_time" style="margin-left: 10px; font-size:20px; color:yellowgreen; font-weight: bold;">00:52</span>
                                            <em onclick="location.reload();">새로고침</em>
                                            <span id="endtime" style="display:none;"><?php echo $gdatetime[0]; ?></span>
                                            <span class="timer-time-text" style="display:none;">123456789</span>
                                        </div>

                                        <!--베팅판 시작 -->
                                        <ul class="mini_bettingbtn">
                                            <li>
                                                <dl>
                                                    <dd class="game-odd-active pl-odd btn-box"
                                                        data-gkey="<?php echo $gkey[0]; ?>"
                                                        data-datetime="<?php echo $gdatetime[0]; ?>"
                                                        data-glkey = "<?php echo $glkey[0]; ?>"
                                                        data-rate="<?php echo $pl[0]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-gnum="<?php echo $num[0]; ?>"
                                                        data-selected="홀"
                                                        data-selected-eng="Odd"
                                                        data-allrate="<?php echo $pl[0]."|".$pl[1];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_odd.png">
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[0]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active pl-even btn-box"
                                                        data-gkey="<?php echo $gkey[0]; ?>"
                                                        data-datetime="<?php echo $gdatetime[1]; ?>"
                                                        data-glkey = "<?php echo $glkey[0]; ?>"
                                                        data-rate="<?php echo $pl[1]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[1]; ?>"
                                                        data-selected="짝"
                                                        data-selected-eng="Even"
                                                        data-allrate="<?php echo $pl[0]."|".$pl[1];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_even.png">
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[1]; ?></dfn>
                                                    </dd>

                                                </dl>
                                                <em>게임 1</em>
                                                <span>홀/짝</span>
                                            </li>
                                            <li>
                                                <dl>
                                                    <dd class="game-odd-active pl-left btn-box"
                                                        data-gkey="<?php echo $gkey[1]; ?>"
                                                        data-datetime="<?php echo $gdatetime[1]; ?>"
                                                        data-glkey = "<?php echo $glkey[1]; ?>"
                                                        data-rate="<?php echo $pl[2]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[1]; ?>"
                                                        data-selected="좌"
                                                        data-selected-eng="Left"
                                                        data-allrate="<?php echo $pl[2]."|".$pl[3];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_left.png">
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[2]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active pl-right btn-box"
                                                        data-gkey="<?php echo $gkey[1]; ?>"
                                                        data-datetime="<?php echo $gdatetime[1]; ?>"
                                                        data-glkey = "<?php echo $glkey[1]; ?>"
                                                        data-rate="<?php echo $pl[3]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[1]; ?>"
                                                        data-selected="우"
                                                        data-selected-eng="Right"
                                                        data-allrate="<?php echo $pl[2]."|".$pl[3];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_right.png">
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[3]; ?></dfn>
                                                    </dd>

                                                </dl>
                                                <em>게임 2</em>
                                                <span>출발점</span>
                                            </li>
                                            <li>
                                                <dl>
                                                    <dd class="game-odd-active pl-line-3 btn-box"
                                                        data-gkey="<?php echo $gkey[2]; ?>"
                                                        data-datetime="<?php echo $gdatetime[2]; ?>"
                                                        data-glkey = "<?php echo $glkey[2]; ?>"
                                                        data-rate="<?php echo $pl[4]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[2]; ?>"
                                                        data-selected="언더"
                                                        data-selected-eng="Under"
                                                        data-allrate="<?php echo $pl[4]."|".$pl[5];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_three.png">
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[4]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active pl-line-4 btn-box"
                                                        data-gkey="<?php echo $gkey[2]; ?>"
                                                        data-datetime="<?php echo $gdatetime[2]; ?>"
                                                        data-glkey = "<?php echo $glkey[2]; ?>"
                                                        data-rate="<?php echo $pl[5]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[2]; ?>"
                                                        data-selected="오버"
                                                        data-selected-eng="Over"
                                                        data-allrate="<?php echo $pl[4]."|".$pl[5];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_four.png">
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[5]; ?></dfn>
                                                    </dd>

                                                </dl>
                                                <em>게임 3</em>
                                                <span>줄갯수</span>
                                            </li>
                                            <li>
                                                <dl class="type4">
                                                    <dd class="game-odd-active pl-line-even-left-3 btn-box"
                                                        data-gkey="<?php echo $gkey[3]; ?>"
                                                        data-datetime="<?php echo $gdatetime[3]; ?>"
                                                        data-glkey = "<?php echo $glkey[3]; ?>"
                                                        data-rate="<?php echo $pl[6]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[3]; ?>"
                                                        data-selected="좌3짝"
                                                        data-selected-eng="L3E"
                                                        data-allrate="<?php echo $pl[6]."|".$pl[7];?>"
                                                    >
                                                        <label>
                                                            <div class="bet_circle">
                                                                <h1 class="red">짝</h1>
                                                                <h2 class="left">3</h2>
                                                            </div>
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[6]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active pl-line-odd-right-3 btn-box"
                                                        data-gkey="<?php echo $gkey[3]; ?>"
                                                        data-datetime="<?php echo $gdatetime[3]; ?>"
                                                        data-glkey = "<?php echo $glkey[4]; ?>"
                                                        data-rate="<?php echo $pl[7]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[3]; ?>"
                                                        data-selected="우3홀"
                                                        data-selected-eng="R3O"
                                                        data-allrate="<?php echo $pl[6]."|".$pl[7];?>"
                                                    >
                                                        <label>
                                                            <div class="bet_circle">
                                                                <h1 class="blue">홀</h1>
                                                                <h2 class="right">3</h2>
                                                            </div>
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[7]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active pl-line-odd-left-4 btn-box"
                                                        data-gkey="<?php echo $gkey[4]; ?>"
                                                        data-datetime="<?php echo $gdatetime[4]; ?>"
                                                        data-glkey = "<?php echo $glkey[3]; ?>"
                                                        data-rate="<?php echo $pl[8]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[4]; ?>"
                                                        data-selected="좌4홀"
                                                        data-selected-eng="L4O"
                                                        data-allrate="<?php echo $pl[8]."|".$pl[9];?>"
                                                    >
                                                        <label>
                                                            <div class="bet_circle">
                                                                <h1 class="blue">홀</h1>
                                                                <h2 class="left">4</h2>
                                                            </div>
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[8]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active pl-line-even-right-4 btn-box"
                                                        data-gkey="<?php echo $gkey[4]; ?>"
                                                        data-datetime="<?php echo $gdatetime[4]; ?>"
                                                        data-glkey = "<?php echo $glkey[4]; ?>"
                                                        data-rate="<?php echo $pl[9]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[4]; ?>"
                                                        data-selected="우4짝"
                                                        data-selected-eng="R4E"
                                                        data-allrate="<?php echo $pl[8]."|".$pl[9]; ?>"
                                                    >
                                                        <label>
                                                            <div class="bet_circle">
                                                                <h1 class="red">짝</h1>
                                                                <h2 class="right">4</h2>
                                                            </div>
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[9]; ?></dfn>
                                                    </dd>

                                                </dl>
                                                <em>게임 4</em>
                                                <span>좌우, 출발, 3/4줄</span>
                                            </li>
                                        </ul>

                                        <!-- 베팅판 끝 -->


                                        <!-- 미니게임 베팅 박스 -->
                                        <?php include_once "../include/money_box.php"; ?>

                                        <div class="betting-history mini_table betting-history">
                                            <h2>베팅내역</h2>
                                            <table class="palign" style="margin-bottom: 10px;">
                                                <colgroup>
                                                    <col width="70px">
                                                    <col width="120px">
                                                    <col width="150px">
                                                    <col width="150">
                                                    <col width="134px">
                                                    <col width="113px">
                                                    <col width="71px">
                                                    <col width="95px">
                                                    <col width="96px">
                                                </colgroup>
                                                <thead>
                                                <tr>
                                                    <td scope="col">번호</td>
                                                    <td scope="col">회차</td>
                                                    <td scope="col">베팅시간</td>
                                                    <td scope="col">게임 분류</td>
                                                    <td scope="col">베팅내역</td>
                                                    <td scope="col">배당</td>
                                                    <td scope="col">베팅 금액</td>
                                                    <td scope="col">적중 금액</td>
                                                    <td scope="col">승/패</td>
                                                    <td scope="col">삭제</td>
                                                </tr>
                                                </thead>
                                                <tbody class="betting_history">
                                                <?php

                                                $tb = "buygame a LEFT JOIN buygamelist b ON a.BG_Key = b.BG_Key LEFT JOIN gamelist_other c ON b.G_Key = c.G_Key  ";

                                                $view_article = 8; // 한화면에 나타날 게시물의 총 개수
                                                if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
                                                $start = ($_GET['page']-1)*$view_article;
                                                $href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}";


                                                $where = " 1 AND a.M_Key = {$_SESSION['S_Key']} AND BG_Visible = '1' AND b.GL_Key IN (16,17,18,19,20)";
                                                #성명으로 정렬시
                                                $order_by = " ORDER BY BG_BuyDate DESC ";

                                                $cnt = 0;
                                                $betting_text = "";
                                                $betting_game_text = "";
                                                $query = "SELECT COUNT(*) FROM {$tb} WHERE {$where}   ";
                                                $row = getRow($query);
                                                $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함
                                                $que = "SELECT  a.*, b.*, c.G_Num2, c.G_Num FROM {$tb} WHERE {$where} {$order_by}  LIMIT {$start},{$view_article}";
                                                //echo $que;
                                                $arr = getArr($que);
                                                if(count($arr)>0){
                                                    foreach($arr as $arr){

                                                        if(in_array($arr['GL_Key'],array(11,16))){
                                                            if($arr['BGL_ResultChoice']=='Odd'){
                                                                $betting_text = "<span style='color:dodgerblue'>홀</span>";
                                                            } else if($arr['BGL_ResultChoice']=='Even'){
                                                                $betting_text = "<span style='color:orangered'>짝</span>";
                                                            }
                                                            $betting_game_text = "홀/짝";
                                                        } else if(in_array($arr['GL_Key'],array(12,17))){
                                                            if($arr['BGL_ResultChoice']=='Odd'){
                                                                $betting_text = "<span style='color:dodgerblue'>좌</span>";
                                                            } else if($arr['BGL_ResultChoice']=='Even'){
                                                                $betting_text = "<span style='color:orangered'>우</span>";
                                                            }
                                                            $betting_game_text = "좌출/우출";
                                                        } else if(in_array($arr['GL_Key'],array(13,18))){
                                                            if($arr['BGL_ResultChoice']=='Odd'){
                                                                $betting_text = "<span style='color:dodgerblue'>3줄</span>";
                                                            } else if($arr['BGL_ResultChoice']=='Even'){
                                                                $betting_text = "<span style='color:orangered'>4줄</span>";
                                                            }
                                                            $betting_game_text = "3줄/4줄";
                                                        } else if(in_array($arr['GL_Key'],array(14,19))){
                                                            if($arr['BGL_ResultChoice']=='Odd'){
                                                                $betting_text = "<span style='color:dodgerblue'>좌3짝</span>";
                                                            } else if($arr['BGL_ResultChoice']=='Even'){
                                                                $betting_text = "<span style='color:orangered'>좌4홀</span>";
                                                            }
                                                            $betting_game_text = "줄출조합";
                                                        } else if(in_array($arr['GL_Key'],array(15,20))){
                                                            if($arr['BGL_ResultChoice']=='Odd'){
                                                                $betting_text = "<span style='color:dodgerblue'>우3홀</span>";
                                                            } else if($arr['BGL_ResultChoice']=='Even'){
                                                                $betting_text = "<span style='color:orangered'>우4짝</span>";
                                                            }
                                                            $betting_game_text = "줄출조합";
                                                        }

                                                        ?>
                                                        <tr>
                                                            <td scope="col" class="num"><?=($total_article-$cnt-(($_GET['page']-1)*$view_article))?></td>
                                                            <td scope="col" class="date"><?php echo $arr['G_Num']; ?>회차</td>
                                                            <td scope="col" class="time"><?php echo $arr['BG_BuyDate']; ?></td>
                                                            <td scope="col" class="sort"><b><?php echo $betting_game_text; ?></b></td>
                                                            <td scope="col" class="sort"><b><?php echo $betting_text; ?></b></td>
                                                            <td scope="col" class="per"><?php echo $arr['BG_TotalQuota']; ?></td>
                                                            <td scope="col" class="money01 td_right"><?php echo number_format($arr['BG_BettingPrice']); ?>원</td>
                                                            <td scope="col" class="money02 td_right">
                                                                <?php
                                                                if($arr['BG_Result']!='Await') {
                                                                    echo ($arr['BG_Result'] == 'Success') ? '<span class="font-blue">'.number_format($arr['BG_ForecastPrice']).'원</span>' : '';
                                                                } else {
                                                                    echo '';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td class="result">
                                                                <?php
                                                                if($arr['BG_Result']!='Await') {
                                                                    echo ($arr['BG_Result'] == 'Success') ? '<span class="font-blue">적중</span>' : '<span class="font-gray">미적중</span>';
                                                                } else {
                                                                    echo '진행중';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td class="result" style="color:orangered; font-weight: bold;">삭제</td>
                                                        </tr>
                                                        <?php $cnt++; }} else { ?>
                                                    <tr>
                                                        <td colspan="10">현재 등록된 구매내역이 없습니다.</td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                            <?php
                                            if($total_article>0) {
                                                include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
                                            }
                                            ?>
                                            <h3>
                                                <code data-value="4" class="btn-view-allhistory" onclick="location.href='/betting-history/minigame/powerball/?game=PB'">모든 베팅 내역</code>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div><!-- sub_wrap -->
</div><!-- sub_bg -->
<input type="hidden" id="bet_list" value="" style="width: 100%;">
<script>
    var config_bet_bound_min = parseInt("5000", 10);
    var config_bet_bound_max = parseInt("1000000", 10);
    var config_bet_reward_max =  parseInt("1000000", 10);
    var config_bet_finish_time = parseInt("<?php echo $endtimeint; ?>");
    var game_code = 'KL';

    //타이머 시작
    //타이머 시작
    function game_result_rollback(){
        swal('','배팅이 완료되었습니다.','success');
        setTimeout(function(){ location.reload();},1500);
    }
</script>
<script src="/js/keno.js"></script>
<?php
include_once $root_path.'/include/footer.php';
?>
