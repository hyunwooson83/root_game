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
                                        a.GL_Key IN (38919)
                                    
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
$game_type_gubun = "가상경마";
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
            <li onclick="location.href='/m/game/virtual/soccer/'" style="width: 33.3%;">가상축구컵</li>
            <li onclick="location.href='/m/game/virtual/horse/'" class="active" style="width: 33.3%;">가상경마</li>
            <li onclick="location.href='/m/game/virtual/dog/'" style="width: 33.3%;">가상개경주</li>
        </ul>
    </div>

    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>가상경마</span>
                <em>Virtual horse</em>
            </h1>
            <span>게임 : <b id="virtual_soccer">00:00</b></span>
            <span class="timer-time-text" style="display:none;"><?php echo $real_num[0];?></span>
        </div>

        <div class="game_display">
            <iframe src="https://b2.nusub365.com?vn=8&sw=520&sh=240" scrolling="no" width="320" height="240"></iframe>
        </div>

        <div class="sports_list">

            <dl class="sports_list_top">
                <dd>승(홈)오버 <var class="arr_down arr_wave blink">▼</var></dd>
                <dt>무/핸/합</dt>
                <dd>패(원정)언더 <var class="arr_up arr_wave">▲</var></dd>
            </dl>

            <!-- League start { -->
            <h1>
                <span><img src="/mobile/img/sub/icon_soccer.png" /></span>가상경마
                <var><?php echo date("m/d H:i",strtotime($rows['G_Datetime'])); ?></var>

            </h1>
            <ul>
                <!-- 한경기 부분 { -->
                <?php
                for($i=0;$i<count($rate);$i++){
                if($i%2==0) echo "</li><li>";
                ?>
                <li style="border-bottom:none">
                    <dl>
                        <dd class="type-wdl btn-box"
                            data-gkey="<?php echo $gkey[$i]; ?>"
                            data-datetime="<?php echo $gdatetime[$i]; ?>"
                            data-glkey = "<?php echo $glkey[$i]; ?>"
                            data-rate="<?php echo $rate[$i]; ?>";
                            data-level="<?php echo $_SESSION['S_Level'];?>"
                            data-gnum="<?php echo $num[$i]; ?>"
                            data-selected="<?php echo $team[$i]; ?>"
                            data-selected-eng="<?php echo $i;?>"
                            data-allrate="<?php echo $rate[$i];?>">
                            <div>
                                <span><?php echo $team[$i]; ?></span>
                                <em><?php echo $rate[$i];?></em>
                            </div>
                        </dd>
                        <dd class="space"></dd>
                    </dl>
                </li>
                <?php } ?>
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
        var game_code = 'horse';

        //타이머 시작
        function game_result_rollback(){
            swal('','배팅이 완료되었습니다.','success');
            setTimeout(function(){ location.reload();},1500);
        }
    </script>
    <script src="/m/js/soccer.js?t=<?php echo time(); ?>"></script>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>