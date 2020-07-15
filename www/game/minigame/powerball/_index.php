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
                    a.G_Datetime >= NOW()                    
                AND
                    a.GI_Key = 4
                ORDER BY a.G_Datetime ASC, a.GL_Key asc
                LIMIT 10
    ";

$rows = getArr($que);
foreach($rows as $rows){
    $real_num[]     = $rows['G_Num2'];
    $num[]          = $rows['G_Num'];
    $glkey[]        = $rows['GL_Key'];
    $gkey[]         = $rows['G_Key'];
    $gdatetime[]    = $rows['G_Datetime'];
}


$endtimeint = strtotime($gdatetime[0])-30;


$game_type_gubun = "파워볼";
$po = $MINIGAME_RATE['MG_Power'];
$po = explode("|",$po);

$pop = $MINIGAME_RATE['MG_PowerP'];
$pop = explode("|",$pop);


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
            <div class="title1">파워볼게임</div>
            <div class="title2">POWERBALL GAME</div>
        </div>
        <!--<ul class="sub_menu">
            <li class="on" onclick="location.href='/money/charge/'">보유머니 충전</li>
            <li onclick="location.href='/money/charge/list/'">보유머니 충전내역</li>
            <li onclick="location.href='/money/casino/charge/'">카지노 충전</li>
            <li onclick="location.href='/money/casino/charge/list/'">카지노 충전내역</li>
        </ul>-->
        <div class="sub_box money_box" style="width:1040px; margin-left:0px;">
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
                                                <iframe src="http://ntry.com/scores/powerball/live.php" scrolling="no" id="the_iframe"></iframe>
                                            </div>
                                        </div>
                                        <!-- 파워볼 베팅 타이머 -->
                                        <div class="mini_timecount">
                                            <span id="last-play-date"><?php echo substr($gdatetime[0],0,10); ?></span>
                                            <font>[<span id="play_num_view" class="timer-num-text"><?php echo $num[0]; ?></span> 회차]</font>
                                            <span id="last_play_time"><?php echo substr($gdatetime[0],-8,5); ?></span>
                                            <span class="count" id="remaind_time" style="margin-left: 10px; font-size:20px; color:yellowgreen; font-weight: bold;">00:00</span>
                                            <em onclick="location.reload();">새로고침</em>
                                            <span id="endtime" style="display:none;"><?php echo $gdatetime[0]; ?></span>
                                            <span class="timer-time-text" style="display:none;">123456789</span>
                                        </div>

                                        <!--베팅판 시작 -->
                                        <ul class="powerball_bettingbtn">
                                            <li class="">
                                                <dl>
                                                    <dd class="game-odd-active p-odd btn-box"
                                                        data-gkey="<?php echo $gkey[0]; ?>"
                                                        data-datetime="<?php echo $gdatetime[0]; ?>"
                                                        data-glkey = "<?php echo $glkey[0]; ?>"
                                                        data-rate="<?php echo $po[0]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-gnum="<?php echo $num[0]; ?>"
                                                        data-selected="홀"
                                                        data-selected-eng="Odd"
                                                        data-allrate="<?php echo $po[0]."|".$po[1];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_blue_.png">
                                                        </label>
                                                        <div class="mini-text">홀</div>
                                                        <dfn class="rateA"><?php echo $po[0]; ?></dfn>

                                                    </dd>
                                                    <dd class="game-odd-active p-even btn-box"
                                                        data-gkey="<?php echo $gkey[0]; ?>"
                                                        data-datetime="<?php echo $gdatetime[1]; ?>"
                                                        data-glkey = "<?php echo $glkey[0]; ?>"
                                                        data-rate="<?php echo $po[1]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[1]; ?>"
                                                        data-selected="짝"
                                                        data-selected-eng="Even"
                                                        data-allrate="<?php echo $po[0]."|".$po[1];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_red_.png">
                                                        </label>
                                                        <div class="mini-text">짝</div>
                                                        <dfn class="rateA"><?php echo $po[1]; ?></dfn>
                                                    </dd>
                                                </dl>
                                                <em>게임 1</em>
                                                <span>일반볼 숫자합 홀/짝</span>
                                            </li>
                                            <li class="">
                                                <dl>
                                                    <dd class="game-odd-active p-odd btn-box"
                                                        data-gkey="<?php echo $gkey[1]; ?>"
                                                        data-datetime="<?php echo $gdatetime[1]; ?>"
                                                        data-glkey = "<?php echo $glkey[1]; ?>"
                                                        data-rate="<?php echo $po[2]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[1]; ?>"
                                                        data-selected="언더"
                                                        data-selected-eng="Under"
                                                        data-allrate="<?php echo $po[2]."|".$po[3];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_blue_.png">
                                                        </label>
                                                        <div class="mini-base-text">언더</div>
                                                        <div class="combine-base-base">기준 72.5</div>
                                                        <dfn class="rateA"><?php echo $po[2]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active p-even btn-box"
                                                        data-gkey="<?php echo $gkey[1]; ?>"
                                                        data-datetime="<?php echo $gdatetime[1]; ?>"
                                                        data-glkey = "<?php echo $glkey[1]; ?>"
                                                        data-rate="<?php echo $po[3]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[1]; ?>"
                                                        data-selected="오버"
                                                        data-selected-eng="Over"
                                                        data-allrate="<?php echo $po[2]."|".$po[3];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_red_.png">
                                                        </label>
                                                        <div class="mini-base-text">오버</div>
                                                        <div class="combine-base-base">기준 72.5</div>
                                                        <dfn class="rateA"><?php echo $po[3]; ?></dfn>
                                                    </dd>
                                                </dl>
                                                <em>게임 2</em>
                                                <span>일반볼 숫자합 언더/오버</span>
                                            </li>
                                            <!--대중소-->
                                            <li class=" wide last ">
                                                <dl>
                                                    <dd class="game-odd-active p-between-15 btn-box"
                                                        data-gkey="<?php echo $gkey[2]; ?>"
                                                        data-datetime="<?php echo $gdatetime[2]; ?>"
                                                        data-glkey = "<?php echo $glkey[2]; ?>"
                                                        data-rate="<?php echo $po[6]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[2]; ?>"
                                                        data-selected="소"
                                                        data-selected-eng="Small"
                                                        data-allrate="<?php echo $po[4]."|".$po[5]."|".$po[6];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_pb_small.png">
                                                            <div></div>
                                                        </label>
                                                        <dfn class="rateA"><?php echo $po[4]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active p-between-65 btn-box"
                                                        data-gkey="<?php echo $gkey[2]; ?>"
                                                        data-datetime="<?php echo $gdatetime[2]; ?>"
                                                        data-glkey = "<?php echo $glkey[2]; ?>"
                                                        data-rate="<?php echo $po[5]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[2]; ?>"
                                                        data-selected="중"
                                                        data-selected-eng="Middle"
                                                        data-allrate="<?php echo $po[4]."|".$po[5]."|".$po[6];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_pb_middle.png">
                                                            <div></div>
                                                        </label>
                                                        <dfn class="rateA"><?php echo $po[5]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active p-between-81 btn-box"
                                                        data-gkey="<?php echo $gkey[2]; ?>"
                                                        data-datetime="<?php echo $gdatetime[2]; ?>"
                                                        data-glkey = "<?php echo $glkey[2]; ?>"
                                                        data-rate="<?php echo $po[4]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[2]; ?>"
                                                        data-selected="대"
                                                        data-selected-eng="Big"
                                                        data-allrate="<?php echo $po[4]."|".$po[5]."|".$po[6];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_pb_big.png">
                                                            <div></div>
                                                        </label>
                                                        <dfn class="rateA"><?php echo $po[6]; ?></dfn>
                                                    </dd>
                                                </dl>
                                                <em>게임 3</em>
                                                <span>일반볼 대/중/소</span>
                                            </li>
                                            <!--대중소 끝-->
                                        </ul>
                                        <ul class="powerball_bettingbtn">
                                            <li class=" two ">
                                                <dl>
                                                    <dd class="game-odd-active p-under-odd-4 btn-box"
                                                        data-gkey="<?php echo $gkey[3]; ?>"
                                                        data-datetime="<?php echo $gdatetime[3]; ?>"
                                                        data-glkey = "<?php echo $glkey[3]; ?>"
                                                        data-rate="<?php echo $po[7]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[3]; ?>"
                                                        data-selected="홀+언더"
                                                        data-selected-eng="OddUnder"
                                                        data-allrate="<?php echo $po[7]."|".$po[8];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_blue_.png">
                                                        </label>
                                                        <div class="combine-text">홀+언더</div>
                                                        <dfn class="rateA"><?php echo $po[7]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active p-under-even-4 btn-box"
                                                        data-gkey="<?php echo $gkey[3]; ?>"
                                                        data-datetime="<?php echo $gdatetime[3]; ?>"
                                                        data-glkey = "<?php echo $glkey[3]; ?>"
                                                        data-rate="<?php echo $po[8]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[3]; ?>"
                                                        data-selected="홀+오버"
                                                        data-selected-eng="OddOver"
                                                        data-allrate="<?php echo $po[7]."|".$po[8];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_red_.png">
                                                        </label>
                                                        <div class="combine-text">홀+오버</div>
                                                        <dfn class="rateA"><?php echo $po[8]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active p-over-odd-4 btn-box"
                                                        data-gkey="<?php echo $gkey[4]; ?>"
                                                        data-datetime="<?php echo $gdatetime[4]; ?>"
                                                        data-glkey = "<?php echo $glkey[4]; ?>"
                                                        data-rate="<?php echo $po[9]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[1]; ?>"
                                                        data-selected="짝+언더"
                                                        data-selected-eng="EvenUnder"
                                                        data-allrate="<?php echo $po[9]."|".$po[10];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_blue_.png">
                                                        </label>
                                                        <div class="combine-text">짝+언더</div>
                                                        <dfn class="rateA"><?php echo $po[9]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active p-over-even-4 btn-box"
                                                        data-gkey="<?php echo $gkey[4]; ?>"
                                                        data-datetime="<?php echo $gdatetime[4]; ?>"
                                                        data-glkey = "<?php echo $glkey[4]; ?>"
                                                        data-rate="<?php echo $po[10]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[4]; ?>"
                                                        data-selected="짝+오버"
                                                        data-selected-eng="EvenOver"
                                                        data-allrate="<?php echo $po[9]."|".$po[10];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_red_.png">
                                                        </label>
                                                        <div class="combine-text">짝+오버</div>
                                                        <dfn class="rateA"><?php echo $po[10]; ?></dfn>
                                                    </dd>
                                                </dl>
                                                <em>게임 4</em>
                                                <span>일반볼 조합</span>
                                            </li>
                                            <li class=" two ">
                                                <dl>
                                                    <dd class="game-odd-active p-under-odd-4 btn-box"
                                                        data-gkey="<?php echo $gkey[5]; ?>"
                                                        data-datetime="<?php echo $gdatetime[5]; ?>"
                                                        data-glkey = "<?php echo $glkey[5]; ?>"
                                                        data-rate="<?php echo $pop[0]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[5]; ?>"
                                                        data-selected="홀"
                                                        data-selected-eng="Odd"
                                                        data-allrate="<?php echo $pop[0]."|".$pop[1];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_blue_.png">
                                                        </label>
                                                        <div class="mini-text">홀</div>
                                                        <dfn class="rateA"><?php echo $pop[0]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active p-under-even-4 btn-box"
                                                        data-gkey="<?php echo $gkey[5]; ?>"
                                                        data-datetime="<?php echo $gdatetime[5]; ?>"
                                                        data-glkey = "<?php echo $glkey[5]; ?>"
                                                        data-rate="<?php echo $pop[1]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[5]; ?>"
                                                        data-selected="짝"
                                                        data-selected-eng="Even"
                                                        data-allrate="<?php echo $pop[0]."|".$pop[1];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_red_.png">
                                                        </label>
                                                        <div class="mini-text">짝</div>
                                                        <dfn class="rateA"><?php echo $pop[1]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active p-over-odd-4 btn-box"
                                                        data-gkey="<?php echo $gkey[6]; ?>"
                                                        data-datetime="<?php echo $gdatetime[6]; ?>"
                                                        data-glkey = "<?php echo $glkey[6]; ?>"
                                                        data-rate="<?php echo $pop[2]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[1]; ?>"
                                                        data-selected="언더"
                                                        data-selected-eng="Under"
                                                        data-allrate="<?php echo $pop[2]."|".$pop[3];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_blue_.png">
                                                        </label>
                                                        <div class="mini-base-text">언더</div>
                                                        <div class="combine-base-base">기준 4.5</div>
                                                        <dfn class="rateA"><?php echo $pop[2]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active p-over-even-4 btn-box"
                                                        data-gkey="<?php echo $gkey[6]; ?>"
                                                        data-datetime="<?php echo $gdatetime[6]; ?>"
                                                        data-glkey = "<?php echo $glkey[6]; ?>"
                                                        data-rate="<?php echo $pop[3]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-datetime="<?php echo $num[6]; ?>"
                                                        data-selected="오버"
                                                        data-selected-eng="Over"
                                                        data-allrate="<?php echo $pop[2]."|".$pop[3];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_red_.png">
                                                        </label>
                                                        <div class="mini-base-text">오버</div>
                                                        <div class="combine-base-base">기준 4.5</div>
                                                        <dfn class="rateA"><?php echo $pop[3]; ?></dfn>
                                                    </dd>
                                                </dl>
                                                <em>게임 5</em>
                                                <span>파워볼 홀/짝 | 언더/오버</span>
                                            </li>
                                        </ul>
                                        <ul class="powerball_bettingbtn">
                                            <!--파워볼구간-->
                                            <li class=" one ">
                                                <dl>
                                                    <?php
                                                    $k = 4;
                                                    for($j=0;$j<=9;$j++){
                                                        ?>
                                                        <dd class="game-odd-active p-ball_option-<?php echo $j;?> btn-box"
                                                            data-gkey="<?php echo $gkey[7]; ?>"
                                                            data-datetime="<?php echo $gdatetime[7]; ?>"
                                                            data-glkey = "<?php echo $glkey[7]; ?>"
                                                            data-rate="<?php echo $pop[$j]; ?>";
                                                            data-level="<?php echo $_SESSION['S_Level'];?>"
                                                            data-datetime="<?php echo $num[7]; ?>"
                                                            data-selected="<?php echo $j; ?>"
                                                            data-selected-eng="<?php echo $j; ?>"
                                                            data-allrate="<?php echo $pop[$k];?>"
                                                        >
                                                            <label style="color:#222; font-weight:500;">
                                                                <?php echo $j; ?>
                                                                <div></div>
                                                            </label>
                                                            <dfn class="rateA" style="color:#222; font-weight:500;"><?php echo $pop[$k]; ?></dfn>
                                                        </dd>
                                                        <?php $k++; } ?>
                                                </dl>
                                                <em>게임 6</em>
                                                <span>파워볼 숫자</span>
                                            </li>
                                            <!--파워볼 구간끝-->
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


                                                $where = " 1 AND c.GI_Key = 4 AND a.M_Key = {$_SESSION['S_Key']} AND BG_Visible = '1' ";
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


                                                        if(in_array($arr['GL_Key'],array(1))){
                                                            if($arr['BGL_ResultChoice']=='Odd'){
                                                                $betting_text = "<span style='color:dodgerblue'>홀</span>";
                                                            } else if($arr['BGL_ResultChoice']=='Even'){
                                                                $betting_text = "<span style='color:orangered'>짝</span>";
                                                            }
                                                            $betting_game_text = "일반볼 홀/짝";
                                                        } else if(in_array($arr['GL_Key'],array(9))){
                                                            if($arr['BGL_ResultChoice']=='Odd'){
                                                                $betting_text = "<span style='color:dodgerblue'>언더</span>";
                                                            } else if($arr['BGL_ResultChoice']=='Even'){
                                                                $betting_text = "<span style='color:orangered'>오버</span>";
                                                            } else if($arr['BGL_ResultChoice']=='Under'){
                                                                $betting_text = "<span style='color:dodgerblue'>언더</span>";
                                                            } else if($arr['BGL_ResultChoice']=='Over'){
                                                                $betting_text = "<span style='color:orangered'>오버</span>";
                                                            }
                                                            $betting_game_text = "파워볼 언더/오버";
                                                        } else if(in_array($arr['GL_Key'],array(8))){
                                                            if($arr['BGL_ResultChoice']=='Odd'){
                                                                $betting_text = "<span style='color:dodgerblue'>홀</span>";
                                                            } else if($arr['BGL_ResultChoice']=='Even'){
                                                                $betting_text = "<span style='color:orangered'>짝</span>";
                                                            }
                                                            $betting_game_text = "파워볼 홀/짝";
                                                        } else if(in_array($arr['GL_Key'],array(2,9))){
                                                            if($arr['BGL_ResultChoice']=='Under'){
                                                                $betting_text = "<span style='color:dodgerblue'>언더</span>";
                                                            } else if($arr['BGL_ResultChoice']=='Over'){
                                                                $betting_text = "<span style='color:orangered'>오버</span>";
                                                            }
                                                        } else if(in_array($arr['GL_Key'],array(4))){
                                                            if($arr['BGL_ResultChoice']=='Under'){
                                                                $betting_text = "<span style='color:dodgerblue'>홀+언더</span>";
                                                            } else if($arr['BGL_ResultChoice']=='Over'){
                                                                $betting_text = "<span style='color:orangered'>홀+오버</span>";
                                                            }
                                                            $betting_game_text = "일반볼 홀+조합";
                                                        } else if(in_array($arr['GL_Key'],array(5))){
                                                            if($arr['BGL_ResultChoice']=='Under'){
                                                                $betting_text = "<span style='color:dodgerblue'>짝+언더</span>";
                                                            } else if($arr['BGL_ResultChoice']=='Over'){
                                                                $betting_text = "<span style='color:orangered'>짝+오버</span>";
                                                            }
                                                            $betting_game_text = "일반볼 짝+조합";
                                                        } else if($arr['GL_Key']==3){
                                                            if($arr['BGL_ResultChoice']=='Big'){
                                                                $betting_text = "대";
                                                            } else if($arr['BGL_ResultChoice']=='Middle'){
                                                                $betting_text = "중";
                                                            } else if($arr['BGL_ResultChoice']=='Small'){
                                                                $betting_text = "소";
                                                            }
                                                            $betting_game_text = "일반볼 대/중/소";
                                                        } else {
                                                            $betting_text = '숫자 '.$arr['BGL_ResultChoice'];
                                                            $betting_game_text = "파워볼 숫자";
                                                        }

                                                        ?>
                                                        <tr>
                                                            <td scope="col" class="num"><?=($total_article-$cnt-(($_GET['page']-1)*$view_article))?></td>
                                                            <td scope="col" class="date"><?php echo $arr['G_Num']; ?>회차<br><?php echo $arr['G_Num2']; ?></td>
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





                <input name="bet_list" type="hidden" id="bet_list" value="" style="width: 100;">



            </div>
        </div>
    </div><!-- sub_wrap -->
</div><!-- sub_bg -->
<script>
    var config_bet_bound_min = parseInt("5000", 10);
    var config_bet_bound_max = parseInt("1000000", 10);
    var config_bet_reward_max =  parseInt("1000000", 10);
    var config_bet_finish_time = parseInt("<?php echo $SITECONFIG['power_bet_time']; ?>");
    var game_code = 'PB';
</script>

<script>
    $(document).ready(function(){
        $('.del-list').on('click',function(){
            var bgkey = $(this).data('bgkey');
            if(confirm('배팅내역을 삭제하시겠습니까?')==true){
                $.ajax({
                    type : 'post',
                    url : './proc/',
                    dataType : 'json',
                    data : {'HAF_Value_0' : 'deleteBetList', 'bgkey':bgkey},
                    success : function(data){
                        if(data.flag == true){
                            swal('','배팅내역이 삭제되었습니다.','success');
                            setTimeout(function(){ location.reload(); }, 2000);
                        } else {
                            swal('','배팅내역이 삭제시 오류가 발생 되었습니다. ['+data.error+']','warning');
                        }
                    }
                });
            }
        });
    });
    function game_result_rollback(){
        swal('','배팅이 완료되었습니다.','success');
        setTimeout(function(){ location.reload();},1500);
    }
</script>
<script src="/js/powerball.js?t=<?php echo time(); ?>"></script>

<?php
include_once $root_path.'/include/footer.php';
?>
