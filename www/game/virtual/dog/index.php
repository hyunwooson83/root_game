<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

if($SITECONFIG['Dog_Stop_YN'] == 'Y'){
    echo "<script>swal('','개경주 게임이 점검중입니다.','warning');
        setTimeout(function(){location.href = '/main/';},2000);</script>;
        ";
}

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
                                        1
                                    AND  
                                        a.G_State = 'Await'
                                        
                                    AND 
                                        a.G_Datetime > NOW()                         
                                    AND
                                        a.GL_Key IN (38920)
                                    
                                    ORDER BY a.G_Datetime ASC
                                    LIMIT 1
                            ";


$rows = getArr($que);

foreach($rows as $rows){
    $real_num[]     = $rows['G_Num2'];
    $num[]          = $rows['G_Num'];
    $glkey[]        = $rows['GL_Key'];
    $gkey[]         = $rows['G_Key'];
    $gdatetime[]    = $rows['G_Datetime'];
    $team1[]        = $rows['G_Team1'];
    $team2[]        = $rows['G_Team2'];
    for($i=0;$i<15;$i++){
        if($rows['G_Section'.$i]>0) {
            $cnt = $i+1;
            $team[$i] = $rows['G_Team'.$cnt];
            $rate[$i] = $rows['G_Section' . $i];
        }
    }
}



$endtimeint = strtotime($gdatetime[0])-10;
$game_type_gubun = "힐사이드파크";

?>

<link rel="stylesheet" href="/css/minigame.css">
<script>
    $(document).ready(function(){
        $(".sub_header > .top2 > li:nth-child(7)").addClass('active');
    });
</script>
<div class="sub_bg">
    <div class="sub_wrap type2">
        <div class="sub_title">
            <div class="title1">가상경마</div>
            <div class="title2">VIRTUAL HORSE GAME</div>
        </div>
        <div class="sub_box money_box">
            <div class="money_caution">
                <div id="body-contents" class="black_bg">
                    <div class="body-wrap">
                        <div style="height:27px"></div>
                        <div id="mini_wrap">
                            <!-- 파워볼 상단 타이머 -->
                            <div class="minigame_menu">
                                <?php include_once "../include/time_box_soccer.php"; ?>
                            </div>


                            <div class="mini_midwrapbox" id="game-page">
                                <div id="soccer_remind" style="display: none;">0</div>
                                <div class="games-wrap" id="mini_boxwrap">
                                    <div id="mini_box_right">
                                        <div class="mini_gamemovie" style="margin-top:20px;">
                                            <iframe src="http://linkedurl4.net/bet365/v2_1.html?vod=&src=https://bet365kor.com" scrolling="no" width="520" height="300"></iframe>
                                        </div>
                                        <!-- 파워볼 베팅 타이머 -->
                                        <div class="mini_timecount">
                                            <span id="last-play-date"><?php echo substr($rows['G_Datetime'],0,10); ?></span>
                                            <font>[<span id="play_num_view" class="timer-num-text"><?php echo $rows['G_Num']; ?></span> 회차]</font>
                                            <span id="last_play_time" style="color:yellow;"><?php echo substr($rows['G_Datetime'],11,5); ?></span>
                                            <b class="count" id="remaind_time" style="position:absolute;left: 310px; font-size:20px; color:yellowgreen; font-weight: bold;">00:00</b>
                                            <em onclick="location.reload();">새로고침</em>
                                            <span id="endtime" style="display:none;"><?php echo $rows['G_Datetime']; ?></span>
                                            <span class="timer-time-text" style="display:none;">123456789</span>
                                        </div>

                                        <style>
                                            ul.mini_bettingbtn > li > table { width:80%;}
                                            ul.mini_bettingbtn > li > table > tr > td { border:#eee solid 1px;}
                                            td.type-wdl { height:30px;}
                                            td.type-gubun { width:100px;}
                                            span._title { margin:5px; border: #feff2a solid 1px; display: inline-block; width: 90%; height:30px; line-height: 30px; }
                                            span._blue { margin:5px; border: #3da0ff solid 1px; display: inline-block; width: 90%; height:30px; line-height: 30px; }
                                            div._rate { margin:5px; border:#fff solid 1px; height:30px; line-height: 30px;}
                                            div._rate > span._text_left { display: inline-block; float: left; padding-left:5px; }
                                            div._rate > span._text_right { display: inline-block; float: right; padding-right:5px; }
                                            td.btn-box {  width: 28%; cursor: pointer; }
                                            td > div._rate:hover { background-color: #fff; color:#000;}
                                            td.active {background-color: #fff; color:#000;}
                                        </style>
                                        <!--베팅판 시작 -->
                                        <ul class="mini_bettingbtn" style="height: 290px;">
                                            <li style="border-right:none !important; width:100%; display: flex; justify-content: center; height: 290px;">
                                                <table width="80%" border="1">
                                                    <tr>
                                                        <td class="type-wdl type-gubun">
                                                            <span class="_title">우승견선택</span>
                                                        </td>
                                                        <?php
                                                        for($i=0;$i<count($rate);$i++){
                                                            if($i%3==0) echo "</tr><tr>";
                                                            ?>
                                                            <td class="type-wdl btn-box"
                                                                data-gkey="<?php echo $gkey[0]; ?>"
                                                                data-datetime="<?php echo $gdatetime[0]; ?>"
                                                                data-glkey = "<?php echo $glkey[0]; ?>"
                                                                data-rate="<?php echo $rate[$i]; ?>";
                                                                data-level="<?php echo $_SESSION['S_Level'];?>"
                                                                data-gnum="<?php echo $num[0]; ?>"
                                                                data-selected="<?php echo $team[($i)]; ?>"
                                                                data-selected-eng="<?php echo $i;?>"
                                                                data-allrate="<?php echo $rate[$i];?>"
                                                            >
                                                                <div class=" _rate">
                                                                    <span class="_text_left"><img src="/img/dog/Greyhound<?php echo ($i+1); ?>.png" style="height:25px;"></span>
                                                                    <span class="_text_left"><?php echo $team[$i]; ?></span>
                                                                    <span class="_text_right"><?php echo $rate[$i];?></span>
                                                                </div>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                </table>
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


                                                $where = " 1 AND c.GL_Key = 38920 AND a.M_Key = {$_SESSION['S_Key']} AND BG_Visible = '1' ";
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

                                                        if(in_array($arr['GL_Key'],array(119,38920))){

                                                            $betting_text = "<span style='color:#31ff39'>" .($arr['BGL_ResultChoice']+1)."번견</span>";
                                                            $betting_game_text = "가상개경주";
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
    var config_bet_finish_time = "<?php echo $gdatetime[0]; ?>";
    var game_code = 'dog';

    //타이머 시작
    function game_result_rollback(){
        swal('','배팅이 완료되었습니다.','success');
        setTimeout(function(){ location.reload();},1500);
    }
</script>
<script src="/js/soccer.js?t=<?php echo time(); ?>"></script>
<?php
include_once $root_path.'/include/footer.php';
?>
