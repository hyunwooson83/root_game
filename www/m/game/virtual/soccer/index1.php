<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';



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
                    a.GL_Key IN (38918)
                
                ORDER BY a.G_Datetime ASC
                LIMIT 2
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
    if($rows['G_Type2']=='WDL'){
        $win[] = $rows['G_QuotaWin'];
        $draw[] = $rows['G_QuotaDraw'];
        $lose[] = $rows['G_QuotaLose'];
    } else {
        $under[] = $rows['G_QuotaUnder'];
        $base[] = $rows['G_QuotaUnderOver'];
        $over[] = $rows['G_QuotaOver'];
    }
}



$endtimeint = strtotime($gdatetime[0])-60;
$game_type_gubun = "가상축구";
?>
    <style>
        .active {
            background: linear-gradient( to top, #4d6d2a, #709c3f ) !important;
            color: #fefffb !important;
            border-top: 1px solid #a7cb7f !important;
        }
    </style>
    <div class="nmenu">
        <ul class="nmenu_cate nmenu_cate3">
            <li onclick="location.href='/m/game/virtual/soccer/'" class="active" style="width: 33.3%;">가상축구컵</li>
            <li onclick="location.href='/m/game/virtual/horse/'" style="width: 33.3%;">가상경마</li>
            <li onclick="location.href='/m/game/virtual/dog/'" style="width: 33.3%;">가상개경주</li>
        </ul>
    </div>

    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>가상축구컵</span>
                <em>Virtual cup</em>
            </h1>
            <span>게임 : <b id="virtual_soccer">00:00</b></span>
            <span class="timer-time-text" style="display:none;"><?php echo $real_num[0];?></span>
        </div>

        <div class="game_display">
            <iframe src="https://b1.nusub365.com?vn=3&sw=320&sh=240" scrolling="no" width="320" height="240"></iframe>
        </div>

        <div class="sports_list">

            <dl class="sports_list_top">
                <dd>승(홈)오버 <var class="arr_down arr_wave blink">▼</var></dd>
                <dt>무/핸/합</dt>
                <dd>패(원정)언더 <var class="arr_up arr_wave">▲</var></dd>
            </dl>

            <!-- League start { -->
            <h1>
                <span><img src="/mobile/img/sub/icon_soccer.png" /></span>가상축구
                <var><?php echo date("m/d H:i",strtotime($rows['G_Datetime'])); ?></var>

            </h1>
            <ul>
                <!-- 한경기 부분 { -->
                <li style="border-bottom:none">
                    <dl>
                        <dd class="type-wdl btn-box"
                            data-gkey="<?php echo $gkey[0]; ?>"
                            data-datetime="<?php echo $gdatetime[0]; ?>"
                            data-glkey = "<?php echo $glkey[0]; ?>"
                            data-rate="<?php echo $win[0]; ?>";
                            data-level="<?php echo $_SESSION['S_Level'];?>"
                            data-gnum="<?php echo $num[0]; ?>"
                            data-selected="<?php echo $team1[0]; ?>"
                            data-selected-eng="Win"
                            data-allrate="<?php echo $win[0]."|".$draw[0]."|".$lose[0];?>">
                            <div>
                                <span><?php echo $team1[0]; ?></span>
                                <em><?php echo $win[0];?></em>
                            </div>
                        </dd>
                        <dd class="space"></dd>
                        <dd class="center btn-box"
                            data-gkey="<?php echo $gkey[0]; ?>"
                            data-datetime="<?php echo $gdatetime[0]; ?>"
                            data-glkey = "<?php echo $glkey[0]; ?>"
                            data-rate="<?php echo $draw[0]; ?>";
                            data-level="<?php echo $_SESSION['S_Level'];?>"
                            data-gnum="<?php echo $num[0]; ?>"
                            data-selected="무"
                            data-selected-eng="Draw"
                            data-allrate="<?php echo $win[0]."|".$draw[0]."|".$lose[0];?>">
                            <div>
                                <em><?php echo $draw[0];?></em>
                            </div>
                        </dd>
                        <dd class="space"></dd>
                        <dd class="type-wdl btn-box"
                            data-gkey="<?php echo $gkey[0]; ?>"
                            data-datetime="<?php echo $gdatetime[0]; ?>"
                            data-glkey = "<?php echo $glkey[0]; ?>"
                            data-rate="<?php echo $lose[0]; ?>";
                            data-level="<?php echo $_SESSION['S_Level'];?>"
                            data-gnum="<?php echo $num[0]; ?>"
                            data-selected="<?php echo $team2[0]; ?>"
                            data-selected-eng="Lose"
                            data-allrate="<?php echo $win[0]."|".$draw[0]."|".$lose[0];?>">
                            <div class="right">
                                <em><?php echo $lose[0];?></em>
                                <span><?php echo $team2[0]; ?></span>
                            </div>
                        </dd>
                    </dl>
                </li>
                <!-- } 한경기 부분 -->

                <!-- 한경기 부분 { -->
                <li style="border-top:none">
                    <dl>
                        <dd class="type-wdl btn-box"
                            data-gkey="<?php echo $gkey[0]; ?>"
                            data-datetime="<?php echo $gdatetime[0]; ?>"
                            data-glkey = "<?php echo $glkey[0]; ?>"
                            data-rate="<?php echo $over[0]; ?>";
                            data-level="<?php echo $_SESSION['S_Level'];?>"
                            data-gnum="<?php echo $num[0]; ?>"
                            data-selected="오버"
                            data-selected-eng="Over"
                            data-allrate="<?php echo $over[0]."|".$under[0];?>">
                            <div>
                                <span>오버</span>
                                <em><var class="arr_up arr_wave">▲</var><?php echo $over[0]; ?></em>
                            </div>
                        </dd>
                        <dd class="space"></dd>
                        <dd class="center">
                            <div>
                                <em><?php echo $base[0]; ?></em>
                            </div>
                        </dd>
                        <dd class="space"></dd>
                        <dd class="type-wdl btn-box"
                            data-gkey="<?php echo $gkey[0]; ?>"
                            data-datetime="<?php echo $gdatetime[0]; ?>"
                            data-glkey = "<?php echo $glkey[0]; ?>"
                            data-rate="<?php echo $under[0]; ?>";
                            data-level="<?php echo $_SESSION['S_Level'];?>"
                            data-gnum="<?php echo $num[0]; ?>"
                            data-selected="언더"
                            data-selected-eng="Under"
                            data-allrate="<?php echo $over[0]."|".$under[0];?>">
                            <div class="right">
                                <em><var class="arr_down arr_wave">▼</var><?php echo $under[0]; ?></em>
                                <span>언더</span>
                            </div>
                        </dd>
                    </dl>
                </li>
                <!-- } 한경기 부분 -->
            </ul>
            <!-- } League end -->



        </div> <!-- sports_list -->


    </div> <!-- Sub Wrap -->

<?php include_once "../include/money_box.php"; ?>

    <script>
        var config_bet_bound_min = parseInt("5000", 10);
        var config_bet_bound_max = parseInt("1000000", 10);
        var config_bet_reward_max =  parseInt("1000000", 10);
        var config_bet_finish_time = "<?php echo $gdatetime[0]; ?>";
        var game_code = 'soccerw';

        //타이머 시작
        function game_result_rollback(){
            swal('','배팅이 완료되었습니다.','success');
            setTimeout(function(){ location.reload();},1500);
        }
    </script>
    <script src="/m/js/soccer.js?t=<?php echo time(); ?>"></script>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>