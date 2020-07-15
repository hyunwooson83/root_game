<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
include_once $root_path.'/include/header.php';
include_once($root_path . "/include/Snoopy.class.php");

if($SITECONFIG['Live_Stop_YN'] == 'Y'){
    echo "<script>swal('','라이브 게임이 점검중입니다.','warning');
            setTimeout(function(){location.href = '/main/';},2000);</script>;
            ";
}


$snoopy = new Snoopy;
$key = 'e446082c-da71-4e2c-8457-9c3ae43c3c8f';
$snoopy->agent = $_SERVER['HTTP_USER_AGENT'];


$total = 0;
$que = "SELECT COUNT(DISTINCT(G_ID)) as item_cnt, GI_Key FROM gamelist_live WHERE 1 AND status = 1 AND make_yn = 'y' ";
$arr = getArr($que);
if(count($arr)>0){
    foreach($arr as $arr){
        $rm[$arr['GI_Key']]['cnt'] = $arr['item_cnt'];
        $total += $rm[$arr['GI_Key']]['cnt'];
    }
}




?>
<script>
    $(document).ready(function(){
        $(".sub_header > .top2 > li:nth-child(1)").addClass('active');
    });
</script>
<style>
    .live-left { float:left; padding-left:5px;  }
    .live-right { float: right; padding-right:5px;  }
    .live-bet { margin-left:3px; background-color: #404040; height:32px; line-height: 32px; border-radius: 3px;}
    .live-text-center { text-align: center;}
    .live-text-center-100 { text-align: center; width: 100%;}
    div.game-list { background-color:#232323; min-height: 130px; width: 409px; border-radius:5px; margin-top:5px; margin-left:3px; margin-bottom:10px; }
    span.live-game-count {text-align: center; background-color: #131313; padding-left: 5px; padding-right: 5px; padding-top: 2px; padding-bottom:2px; margin-left:5px; }
    #game_start_date { border:#484848 solid 1px; text-align: center; }
    div.live-game-disp { border:#d48e08 solid 1px; text-align: center }
    .right-game-list { width: 538px;background-color:#232323;}
    table.six > tbody > tr td {border:#000 solid 1px; background: linear-gradient(to top,#485539,#383838); width:89.6px; height: 40px; text-align: center; }
    td.three-bet-btn { width:179.3px; height: 32px; }
    td.two-bet-btn { width:269px; height: 32px; }
    td.four-bet-btn { width:134.5px; height: 32px; }
    td.five-bet-btn { width:134.5px; height: 32px; }
    .betting-btn-live.active {
        background: linear-gradient(to top,#50682a,#8ebb44);
        color:#fff;
    }
    .text-red-blink { color: #f57442; font-weight: bold;}
    .text-blue-blink { color: #33b2ee; font-weight: bold;}
    .text-yellow-blink { color:yellow; font-weight: bold;}
    .on{ border:#fff002 solid 1px;}
    .loading{ width:958px; height: 818px; position: absolute; left:342px; background-color: #000; opacity: .7;}
    .loading_text {position: relative; margin-top:300px; height:300px; margin-left:430px; font-size:2em;}
    .hide { display: none;}
    .show { display:'';}
</style>
<div class="sub_wrap" style="width:1220px;">
    <div class="sub_con">
        <div class="center_con" style="width:960px;">
            <!--<div class="score_board_category">
                <ul>
                    <li onclick="location.href='/live_list.html'">라이브<var></var></li>
                    <li onclick="location.href='/cross_list.html'" class="active">크로스<var></var></li>
                    <li>스페셜<var></var></li>
                    <li>승무패<var></var></li>
                    <li>핸디캡<var></var></li>
                    <li>언오버<var></var></li>
                    <li>전반전<var></var></li>
                    <li>코너킥<var></var></li>
                </ul>
            </div>-->

            <div class="score_board_contents">
                <div class="root_display" style="background: url('/img/bg_bl_title.jpg');">
                    <font>라이브</font><B>></B> 축구(0)
                    <ul>
                        <li class="active">마감순정렬</li>
                        <li>리그별정렬</li>
                        <li>국가별정렬</li>
                    </ul>
                </div> <!-- Root Display -->

                <div class="score_board_sub_category">
                    <table class="five">
                        <tr>
                            <td class="active" onclick="location.href = './';">
                                <img src="/img/icon_all.png" />
                                <span>전체</span>
                                <em>(<?php echo $total;?>)</em>
                                <var></var>
                            </td>
                            <td class="active" onclick="location.href = '../soccer/';">
                                <img src="/img/icon_soccer.png" />
                                <span>축구</span>
                                <em>(<?php echo $rm['6046']['cnt'];?>)</em>
                                <var></var>
                            </td>
                            <td onclick="location.href = '../basketball/';">
                                <img src="/img/icon_basketball.png" />
                                <span>농구</span>
                                <em>(0)</em>
                                <var></var>
                            </td>
                            <td onclick="location.href = '../baseball/';">
                                <img src="/img/icon_baseball.png" />
                                <span>야구</span>
                                <em>(<?php echo $rm['154914']['cnt'];?>)</em>
                                <var></var>
                            </td>
                            <td onclick="location.href = '../volleyball/';">
                                <img src="/img/icon_volley.png" />
                                <span>배구</span>
                                <em>(0)</em>
                                <var></var>
                            </td>
                        </tr>
                    </table>

                </div> <!-- Score Board Sub Category -->


                <style>
                    .left-game-list { width: 420px; min-height: 300px;}
                    .left-game-detail { width: 540px; min-height: 300px;}
                </style>

                <div class="bl_live_betting_middle cross" style="width: 960px; display: flex; justify-content: start;">

                    <div class="left-game-list">
                        <div class="game-title" style="background: url('/img/live/game_ing_title.jpg') no-repeat; width: 414px; height: 38px;"></div>
                        <!-- 경기 목록 시작 -->
                        <?php

                        $que = "SELECT * FROM gamelist_live  WHERE G_State = 'Await' AND status = '1' AND matchDateTime < NOW() AND GI_Key = '6046'";
                        //echo $que;
                        $arr = getArr($que);
                        $arr_cnt = count($arr);
                        if($arr_cnt>0){
                            foreach($arr as $rs){

                                $maeket_cnt = 0;
                                $sql = "SELECT * FROM gamelist_live_market WHERE G_ID = '{$rs['G_ID']}' ";
                                //echo $sql;
                                $list = getArr($sql);
                                if(count($list)>0){
                                    foreach($list as $list){

                                        if($list['type']=='01' && $list['subType'] == '90'){
                                            $market_code_wdl = $list['marketCode'];
                                            $wdl_other = explode("_",$market_code_wdl);
                                            $maeket_cnt++;
                                        }

                                        if($list['type']=='03' && $list['subType'] == '90'){
                                            $market_code_handi = $list['marketCode'];
                                            $maeket_cnt++;
                                            //핸디캡 기타 기준점
                                            $handicap_other = explode("_",$list['marketCode']);
                                            echo $handicap_etc = "_".$list['type']."_".$list['subType']."_".($handicap_other[3]+1);
                                        }
                                        if($list['type']=='06' && $list['subType'] == '90') $market_code_ou = $list['marketCode'];  $maeket_cnt++;


                                    }
                                }


                                //echo $rs['G_ID'];
                                if($maeket_cnt>0){
                                    ?>
                                    <div class="game-box game-list left-game-list-box" data-gid="<?php echo $rs['G_ID'];?>" data-gkey="<?php echo $rs['G_Key'];?>" data-wdl="<?php echo $market_code_wdl;?>" data-handicap="<?php echo $market_code_handi;?>" data-ou="<?php echo $market_code_ou;?>" id="game_list_<?php echo $rs['G_ID'];?>" >
                                        <table>
                                            <thead>
                                            <colgroup>
                                                <col width="5%">
                                                <col width="42%">
                                                <col width="17%">
                                                <col width="15%">
                                                <col width="7%">
                                            </colgroup>

                                            <tr>
                                                <td height="40"><img src="/img/icon_soccer.png" style="width: 17px; line-height: 40px; padding-left: 10px; "> </td>
                                                <td>축구 : <?php echo mb_substr($rs['league_name'],0,22,'utf-8');?></td>
                                                <td>
                                                    <div id="game_start_date"><?php echo substr($rs['matchDateTime'],5,11);?></div>
                                                </td>
                                                <td>
                                                    <div class="live-game-disp">
                                                        <span id="disp_game_half_text_<?php echo $rs['G_ID'];?>"></span> <span id="remind_time_<?php echo $rs['G_ID'];?>">00:00</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="live-game-count_<?php echo $rs['G_ID'];?>" style="margin-left:5px;" data-gid="<?php echo $rs['G_ID'];?>">+3</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" ><div style="width: 96%; border: #313131 solid 1px; margin:0 auto;"></div></td>
                                            </tr>
                                            <tr>
                                                <td colspan="10" style="width: 400px;" height="90">
                                                    <table>
                                                        <tr>
                                                            <td style="width: 160px;"><div id="home_team_name" class="live-text-center-100"><?php echo (!empty($rs['home_korName']))?$rs['home_korName']:$rs['home_name'];?></div></td>
                                                            <!--<td style="width: 5%;"><span id="home_team_img"><img src="/img/live/home_team_img.png" border="0"></span></td>-->
                                                            <td style="width:90px;">
                                                                <div style="width:100%; text-align: center; font-size:18px;">
                                                                    <span id="home_score_<?php echo $rs['G_ID'];?>">0</span> - <span id="away_score_<?php echo $rs['G_ID'];?>">0</span>
                                                                    <br>
                                                                    <span style="text-align: center; font-size: 13px; color:#ff6000;">VS</span>
                                                                </div>
                                                            </td>
                                                            <!--<td style="width: 5%;"><span id="away_team_img"><img src="/img/live/away_team_img.png"></span></td>-->
                                                            <td style="width: 150px;"><div id="away_team_name" class="live-text-center-100"><?php echo (!empty($rs['away_korName']))?$rs['away_korName']:$rs['away_name']?></div></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                <?php }}} else { ?>



                        <div class="game-box game-list" >
                            <table>
                                <thead>
                                <colgroup>
                                    <col width="5%">
                                    <col width="42%">
                                    <col width="17%">
                                    <col width="15%">
                                    <col width="7%">
                                </colgroup>


                                <tr>
                                    <td colspan="5" style="text-align: center;" >진행중인 경기가 없습니다.</div></td>
                        </tr>

                        </thead>
                        </table>
                    </div>
                    <?php } ?>
                    <!-- 경기 목록 끝 -->

                    <!-- 예정 경기 목록 시작 -->
                    <div class="game-title" style="background: url('/img/live/notyet.png') no-repeat; width: 414px; height: 38px;"></div>
                    <div class="game-box game-list" style="padding-bottom:20px;">
                        <table>
                            <thead>
                            <!--<tr>
                                <td height="40" style="width: 30px;"><img src="/img/icon_soccer.png" style="width: 17px; line-height: 40px; padding-left: 10px; "> </td>
                                <td>축구 : 프랑스 리그켭</td>
                            </tr>
                            <tr>
                                <td colspan="5" ><div style="width: 96%; border: #313131 solid 1px; margin:0 auto;"></div></td>
                            </tr>
                            <tr>
                                <td colspan="10" style="width: 409px; padding-left:15px; padding-right:15px; " height="60">
                                    <table style="width: 380px; margin-top:10px;">
                                        <tr style=" border: #000 solid 2px; height: 70px; border-radius: 5px;">
                                            <td style="width: 135px; "><div id="home_team_name" class="live-text-center-100">스타드브레스트29</div></td>

                                            <td style="width:90px;">
                                                <div style="width:100%; text-align: center; font-size:13px;">
                                                    <span id="home_yet_date">01-09</span> <span id="away_yet_time">09:00</span>
                                                    <br>
                                                    <span style="text-align: center; font-size: 16px; color:#ff6000;">VS</span>
                                                </div>
                                            </td>

                                            <td style="width: 135px;"><div id="away_team_name" class="live-text-center-100">스타드브레스트29</div></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>-->
                            <tr>
                                <td colspan="10" style="width: 409px; padding-left:15px; padding-right:15px; " height="60">
                                    <table style="width: 380px; margin-top:10px;">
                                        <tr style=" border: #000 solid 2px; height: 70px; border-radius: 5px;">
                                            <td style="width: 135px; "><div id="home_team_name" class="live-text-center-100">진행예정인 경기가 없습니다.</div></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            </thead>
                        </table>
                    </div>
                    <!-- 예정 경기 목록 끝 -->
                </div>

                <!-- 오른쪽 배팅 화면 시작 -->
                <div class="loading hide">
                    <div class="loading_text"><img src="/img/loading_bar2.gif"></div>
                </div>
                <div class="right-game-detail" style="width: 538px; background-color: #131313;">
                    <div class="right-game-list">
                        <!-- 경기 목록 시작 -->
                        <div class="game-box"  >
                            <table>
                                <thead>
                                <colgroup>
                                    <col width="5%">
                                    <col width="42%">
                                    <col width="3%">
                                    <col width="3%">
                                    <col width="3%">
                                </colgroup>

                                <tr>
                                    <td height="40"><!--<img src="/img/icon_soccer.png" style="width: 17px; line-height: 40px; padding-left: 10px; ">--> </td>
                                    <td><!--리옹 vs 스타드브레스트 29--></td>
                                    <td>
                                        <!--<img src="/img/icon_live_on.png" />-->
                                    </td>
                                    <td>
                                        <!--<img src="/img/icon_alert_on.png" />-->
                                    </td>
                                    <td>
                                        <!--<img src="/img/icon_ground_on.png" />-->
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" ><div style="width: 96%; border: #313131 solid 1px; margin:0 auto;"></div></td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="width: 409px;" height="40">
                                        <table class="six" style="width: 100%;">
                                            <tbody>
                                            <tr>
                                                <td class="active">
                                                    <span>전체</span>
                                                </td>
                                                <td>
                                                    <span>매치</span>
                                                </td>
                                                <td>
                                                    <span>오버언더</span>
                                                </td>
                                                <td>
                                                    <span>핸디캡</span>
                                                </td>
                                                <td>
                                                    <span>스페셜</span>
                                                </td>
                                                <td>
                                                    <span>기타</span>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr><td style="height: 10px;">&nbsp;</td></tr>
                                </thead>
                            </table>
                        </div>
                        <!-- 경기 목록 끝 -->
                    </div>
                    <!--<div class="right-game-list" style=" margin-top:20px; min-height: 500px;" id="game_rate_content">
                        <div class="game-type"></div>
                        <div class="FixtureID"></div>
                        <div class="FixtureID"></div>
                    </div>-->
                    <div class="right-game-list" style=" display: block;">
                        <!-- 경기 목록 시작 -->
                        <div class="game-box" >
                            <table>
                                <thead class="live-list-box wdl">
                                <colgroup>
                                    <col width="5%">
                                    <col width="42%">
                                    <col width="3%">
                                    <col width="3%">
                                    <col width="3%">
                                </colgroup>
                                </thead>
                                <thead class="live-list-box st1">
                                <colgroup>
                                    <col width="5%">
                                    <col width="42%">
                                    <col width="3%">
                                    <col width="3%">
                                    <col width="3%">
                                </colgroup>
                                </thead>
                                <thead class="live-list-box st2">
                                <colgroup>
                                    <col width="5%">
                                    <col width="42%">
                                    <col width="3%">
                                    <col width="3%">
                                    <col width="3%">
                                </colgroup>
                                </thead>
                                <thead class="live-list-box handicap">
                                <colgroup>
                                    <col width="5%">
                                    <col width="42%">
                                    <col width="3%">
                                    <col width="3%">
                                    <col width="3%">
                                </colgroup>
                                </thead>
                                <thead class="live-list-box handicap-etc">
                                <colgroup>
                                    <col width="5%">
                                    <col width="42%">
                                    <col width="3%">
                                    <col width="3%">
                                    <col width="3%">
                                </colgroup>
                                </thead>
                                <thead class="live-list-box ou">
                                <colgroup>
                                    <col width="5%">
                                    <col width="42%">
                                    <col width="3%">
                                    <col width="3%">
                                    <col width="3%">
                                </colgroup>
                                </thead>
                                <thead class="live-list-box ou-etc">
                                <colgroup>
                                    <col width="5%">
                                    <col width="42%">
                                    <col width="3%">
                                    <col width="3%">
                                    <col width="3%">
                                </colgroup>
                                </thead>
                            </table>
                        </div>
                        <!-- 경기 목록 끝 -->
                    </div>
                </div>
                <!-- 오른쪽 배팅 화면 끝 -->
            </div> <!-- Live Betting Middle -->
        </div> <!-- Bet List Left -->
    </div> <!-- Center Container -->
    <script>
        var item_name = 'soccer';
        var mkey = <?php echo $_SESSION['S_Key'];?>
    </script>
    <!-- 카트 시작 -->
    <?php include $_SERVER['DOCUMENT_ROOT']."/include/cart_live.php"; ?>
    <!-- Right Container -->

</div> <!-- Sub Container -->
</div>
<script>
    //5분에 한번 새로고침한다.
    setTimeout(function(){ location.reload(); },1000*60*5)
    text_blink('loading_text');
    var config_bet_bound_min = parseInt("10000", 10);
    var config_bet_bound_max = parseInt("<?php echo $LEVELLIMITED['Sports_Max_Bet_Money']; ?>", 10);
    var config_bet_reward_max =  parseInt("<?php echo $LEVELLIMITED['Sports_Max_Hit_Mone']; ?>", 10);
    var config_max_bet_cnt = parseInt("<?php echo $LEVELLIMITED['Sports_Max_Bet_Cnt']; ?>", 10);
    var config_max_bet_rate = parseInt("<?php echo $SITECONFIG['sport_max_rate']; ?>", 10);
    var same_bet_cnt = parseInt("<?php echo $SITECONFIG['base_bet_cnt'];?>",10)-1;
    var same_bet_max = parseInt("<?php echo $SITECONFIG['base_bet_max'];?>",10);
    var same_hit_max = parseInt("<?php echo $SITECONFIG['base_hit_max'];?>",10);
    var one_folder_yn = '<?php echo $meminfo['M_One_Stop'];?>';
    var two_folder_yn = '<?php echo $meminfo['M_Two_Stop'];?>';
    var item_name = 'soccer';
</script>
<script>
    var timer;
    $(document).ready(function(){
        $('.left-game-list-box').on('click',function(){
            var gid = $(this).data('gid');
            var wdl = $(this).data('wdl');
            var handicap = $(this).data('handicap');
            var ou = $(this).data('ou');
            var gkey = $(this).data('gkey');
            get_data(gid,wdl,handicap,ou,gkey);
            clearInterval(timer);
            timer = setInterval(function(){
                get_data(gid,wdl,handicap,ou, gkey);
            } , 3000);
        });
        <?php if($arr_cnt>0){ ?>
        get_live_score();
        setInterval(get_live_score,10000);
        <?php } ?>

        $('.left-game-list-box').on('click',function(){
            $('.left-game-list-box').each(function(){
                $(this).removeClass('on');
            })
            if($(this).hasClass('on')==true){
                $(this).removeClass('on');
            } else {
                $(this).addClass('on');
            }
        })
    });


    var loading_flag  = false;
    //진행중인 경기의 라이브데이터
    function get_live_score(){
        $.ajax({
            type : 'post',
            url : '../proc/liveScore.php',
            async: true,
            dataType : 'json',
            data : 'mode=gameLiveScore&item=soccer',
            beforeSend : function(){
                if(loading_flag == false) {
                    $('.loading').show();
                }
            },
            success : function(data) {

                for (var i = 0; i < data.length; i++) {

                    if (data[i]['timeStatus'] == 3) {
                        //location.reload();
                        $('#game_list_'+data[i]['gid']).hide();
                    } else {
                        if (data[i]['sportsId'] != '16') {
                            $('#remind_time_' + data[i]['gid']).text(data[i]['timeM'] + ':' + data[i]['timeS']);
                        } else {
                            $('#remind_time_' + data[i]['gid']).text('');
                        }
                        $('#disp_game_half_text_' + data[i]['gid']).text(data[i]['timeKorMark']);

                        var home_score = (data[i]['homeScore'] == '')?'':data[i]['homeScore'];
                        var away_score = (data[i]['awayScore'] == '')?'':data[i]['awayScore'];

                        $('#home_score_' + data[i]['gid']).text(data[i]['homeScore']);
                        $('#away_score_' + data[i]['gid']).text(data[i]['awayScore']);
                        $('#home_team_name').text(data[i]['homeName']);
                        $('#away_team_name').text(data[i]['awayName']);
                    }
                }
                $('.loading').hide();
                loading_flag = true;
            }
        });
    }

    //get_data();
    //setInterval('get_data()',3000);
    function get_data(gid, wdl, handicap, ou, gkey){

        //console.log(gkey)
        $.ajax({
            type : 'post',
            url : '../proc/getData.php',
            async: true,
            dataType : 'json',
            data : 'gid='+gid+'&mode=gameSelected&gkey='+gkey+'&item=soccer',
            success : function(data){
                var market_code_wdl = wdl;
                var market_code_handi = handicap;
                var market_code_ou = ou;
                var stop_flag = new Array('HT','FT');
                var minus_rate = 0.03;
                if (data[0]['timeStatus'] == 3) {
                    //location.reload();
                    $('#markets' + market_code_wdl + '_' + data[0]['gid']).hide();
                } else {




                    /* 승패 최종 결과 */
                    if(typeof data[0]['markets']['+market_code_wdl+'] != 'undefined') {
                        var home_team_name = data[0]['markets']['+market_code_wdl+']['matchOdds'][0]['oddsName'];
                        var away_team_name = data[0]['markets']['+market_code_wdl+']['matchOdds'][2]['oddsName'];
                        if(home_team_name.length>15){
                            home_team_name = home_team_name.substring(0,10)+'...';
                        }

                        if(away_team_name.length>15){
                            away_team_name = away_team_name.substring(0,10)+'...';
                        }

                        console.log(away_team_name)
                        if (data[0]['markets']['+market_code_wdl+']['suspended'] == false || inArray(data[0]['markets']['+market_code_wdl+']['matchOdds'][0]['option'],stop_flag) == false) {
                            //$('.live-list-box.wdl').empty();
                            var home_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_wdl;?>']['matchOdds'][0]['odds']))-minus_rate);
                            var draw_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_wdl;?>']['matchOdds'][1]['odds']))-minus_rate);
                            var away_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_wdl;?>']['matchOdds'][2]['odds']))-minus_rate);
                            var cur_home_rate = $('#home_rate_' + data[0]['gid']).text();
                            var cur_draw_rate = $('#draw_rate_' + data[0]['gid']).text();
                            var cur_away_rate = $('#away_rate_' + data[0]['gid']).text();

                            bet_three = '';


                            var wdl_box = $('#markets' + market_code_wdl + '_' + gid);
                            if (wdl_box.length == 0) {
                                bet_three += '<tr id="markets' + market_code_wdl + '_' + gid + '">';
                                bet_three += '    <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">';
                                bet_three += '        <table><tr><td colspan="3" style="width: 100%; text-align: center;  height:30px;">최종 승무패 결과</td></tr>';
                                bet_three += '            <tr>';
                                bet_three += '                <td class="three-bet-btn">';
                                bet_three += '                    <div class="bl_btn bl_text_btn long betting-btn-live win-'+gid+' live-bet home" data-code="<?php echo $market_code_wdl;?>" data-bet="Win" data-gid="'+gid+'" data-gkey="'+gkey+'" data-rate="'+home_rate+'" data-homeName="'+home_team_name+'" data-awayName="'+away_team_name+'">';
                                bet_three += '                        <span class="live-left">' + home_team_name + '</span>';
                                bet_three += '                        <span class="live-right" id="home_rate_' + gid + '">' + home_rate + '</span>';
                                bet_three += '                    </div>';
                                bet_three += '                </td>';
                                bet_three += '                <td class="three-bet-btn">';
                                bet_three += '                    <div class="bl_btn bl_text_btn long betting-btn-live draw-'+gid+' live-bet draw" data-code="<?php echo $market_code_wdl;?>" data-bet="Draw" data-gid="'+gid+'" data-gkey="'+gkey+'" data-rate="'+draw_rate+'" data-homeName="'+home_team_name+'" data-awayName="'+away_team_name+'">';
                                bet_three += '                        <span class="live-left">무승부</span>';
                                bet_three += '                        <span class="live-right" id="draw_rate_' + gid + '">' + draw_rate + '</span>';
                                bet_three += '                    </div>';
                                bet_three += '                </td>';
                                bet_three += '                <td class="three-bet-btn">';
                                bet_three += '                    <div class="bl_btn bl_text_btn long betting-btn-live lose-'+gid+' live-bet away" data-code="<?php echo $market_code_wdl;?>" data-bet="Lose" data-gid="'+gid+'" data-gkey="'+gkey+'" data-rate="'+away_rate+'" data-homeName="'+home_team_name+'" data-awayName="'+away_team_name+'" data-type="패" style="margin-right:3px;">';
                                bet_three += '                        <span class="live-left">' + away_team_name + '</span>';
                                bet_three += '                        <span class="live-right" id="away_rate_' + gid + '">' + away_rate + '</span>';
                                bet_three += '                    </div>';
                                bet_three += '                </td>';
                                bet_three += '            </tr>';
                                bet_three += '        </table>';
                                bet_three += '    </td>';
                                bet_three += '</tr>';

                                $('.live-list-box.wdl').empty().append(bet_three);
                            } else {
                                console.log('승무패 = '+data[0]['gid']+' [ '+home_rate+' : '+draw_rate+' : '+away_rate+']')
                                $('#home_rate_' + data[0]['gid']).text(home_rate);
                                if(cur_home_rate != home_rate) {
                                    change_text_color('text-red-blink', $('#home_rate_' + data[0]['gid']));
                                }
                                //$('.cart-rate-Win-'+gid).text(home_rate)
                                $('#draw_rate_' + data[0]['gid']).text(draw_rate);
                                if(cur_draw_rate != draw_rate) {
                                    change_text_color('text-yellow-blink', $('#draw_rate_' + data[0]['gid']));
                                }
                                //$('.cart-rate-Draw-'+gid).text(home_rate)
                                $('#away_rate_' + data[0]['gid']).text(away_rate);
                                //$('.cart-rate--'+gid).text(home_rate)
                                if(cur_away_rate != away_rate) {
                                    change_text_color('text-blue-blink', $('#away_rate_' + data[0]['gid']));
                                }

                                $('.live-list-box.wdl').show();
                            }

                            $('#markets' + market_code_wdl + '_' + data[0]['gid']).show();
                        } else {
                            $('#markets' + market_code_wdl + '_' + gid).empty();
                            $('.live-list-box.wdl').hide();
                        }
                    } else {
                        $('#markets' + market_code_wdl + '_' + gid).empty();
                        $('.live-list-box.wdl').hide();
                    }

                    /* 핸디캡 최종 결과*/

                    /* 핸디캡 최종 결과*/
                    var no_handicap = new Array('+0.25','+0.75','+1.25','+1.75','+2.25','+2.75','+3.25','+3.75','+4.25','+4.75','+5.25','+5.75','+6.25','+7.25','+8.25','+9.25','+10.25','+11.25','+12.25','+13.25','0.5','-0.5');
                    //$('.live-list-box.handicap').empty();
                    //console.log(data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][0]['option']);
                    if(typeof data[0]['markets']['<?php echo $market_code_handi;?>'] != 'undefined') {
                        if (data[0]['markets']['<?php echo $market_code_handi;?>']['suspended'] == false) {
                            var home_team_name = data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][0]['oddsName'];
                            var away_team_name = data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][1]['oddsName'];
                            if(home_team_name.length>15){
                                home_team_name = home_team_name.substring(0,10)+'...';
                            }

                            if(away_team_name.length>15){
                                away_team_name = away_team_name.substring(0,10)+'...';
                            }
                            if (inArray(data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][0]['option'],no_handicap) == false && inArray(data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][1]['option'],no_handicap) == false) {

                                var home_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][0]['odds']))-minus_rate);
                                var home_rate_option = data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][0]['option'];
                                var away_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][1]['odds']))-minus_rate);
                                var away_rate_option = data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][1]['option'];

                                var cur_home_rate = $('#home_handicap_rate_' + data[0]['gid']).text();
                                var cur_away_rate = $('#away_handicap_rate_' + data[0]['gid']).text();

                                bet_two = '';

                                console.log('handicap')
                                $('#markets' + market_code_handi + '_' + gid).show();
                                var handi_box = $('#markets' + market_code_handi + '_' + gid);
                                if (handi_box.length == 0) {
                                    bet_two += '<tr id="markets' + market_code_handi + '_' + gid + '">';
                                    bet_two += '    <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">';
                                    bet_two += '        <table>';
                                    bet_two += '            <tr><td colspan="2" style="width: 100%; text-align: center;  height:30px;">'+data[0]['markets']['<?php echo $market_code_handi;?>']['korName']+'</td></tr>';
                                    bet_two += '            <tr>';
                                    bet_two += '                <td class="two-bet-btn">';
                                    bet_two += '                    <div class="bl_btn bl_text_btn long betting-btn-live win-'+gid+' live-bet HandiWin" data-bet="HandiWin" data-code="<?php echo $market_code_handi;?>" data-gid="'+gid+'" data-gkey="'+gkey+'" data-rate="'+home_rate+'" data-homeName="'+home_team_name+'" data-awayName="'+away_team_name+'"  data-awayName="'+away_team_name+'">';
                                    bet_two += '                        <span class="live-left">' + home_team_name + '</span>';
                                    bet_two += '                        <span class="live-right" >' + '[<span id="home_handicap_option_' + gid + '">' + home_rate_option + '</span>] <span id="home_handicap_rate_' + gid + '">'+home_rate + '</span></span>';
                                    bet_two += '                    </div>';
                                    bet_two += '                </td>';
                                    bet_two += '                <td class="two-bet-btn">';
                                    bet_two += '                    <div class="bl_btn bl_text_btn long betting-btn-live lose-'+gid+' live-bet HandiLose" data-bet="HandiLose" data-code="<?php echo $market_code_handi;?>" data-gid="'+gid+'" data-gkey="'+gkey+'" data-rate="'+away_rate+'" data-homeName="'+home_team_name+'" data-awayName="'+away_team_name+'" data-type="패" style="margin-right:3px;">';
                                    bet_two += '                        <span class="live-left">' + away_team_name + '</span>';
                                    bet_two += '                        <span class="live-right" >' + '[<span id="away_handicap_option_' + gid + '">' + away_rate_option + '</span>] <span id="away_handicap_rate_' + gid + '">' + away_rate + '</span></span>';
                                    bet_two += '                    </div>';
                                    bet_two += '                </td>';
                                    bet_two += '            </tr>';
                                    bet_two += '        </table>';
                                    bet_two += '    </td>';
                                    bet_two += '</tr>';
                                    $('.live-list-box.handicap').empty().append(bet_two);
                                } else {
                                    console.log('핸디 = '+data[0]['gid']+' [ '+home_rate+' : '+away_rate+']')
                                    $('#home_handicap_rate_' + data[0]['gid']).text(home_rate);
                                    $('#away_handicap_rate_' + data[0]['gid']).text(away_rate);

                                    $('#home_handicap_option_' + data[0]['gid']).text(home_rate_option);
                                    $('#away_handicap_option_' + data[0]['gid']).text(away_rate_option);

                                    if(cur_home_rate != home_rate) {
                                        change_text_color('text-red-blink', $('#home_handicap_rate_' + data[0]['gid']));
                                    }
                                    if(cur_away_rate != away_rate) {
                                        change_text_color('text-blue-blink', $('#away_handicap_rate_' + data[0]['gid']));
                                    }
                                }
                                $('.live-list-box.handicap').show();
                            } else {
                                $('#markets' + market_code_handi + '_' + gid).hide();
                                $('.live-list-box.handicap').hide();
                            }
                        } else {
                            $('#markets' + market_code_handi + '_' + gid).hide();
                            $('.live-list-box.handicap').hide();
                        }
                    }


                    /* 핸디캠 최종 기타 기준점*/
                    var no_handicap_etc = new Array('+0.25','+0.75','+1.25','+1.75','+2.25','+2.75','+3.25','+3.75','+4.25','+4.75','+5.25','+5.75','+6.25','+7.25','+8.25','+9.25','+10.25','+11.25','+12.25','+13.25');
                    //$('.live-list-box.handicap').empty();


                    if(typeof data[0]['markets']['<?php echo $handicap_etc;?>'] != 'undefined') {
                        console.log(data[0]['markets']['<?php echo $market_code_handi;?>']['suspended'])
                        if (data[0]['markets']['<?php echo $handicap_etc;?>']['suspended'] == false) {

                            var handi_box_etc = $('#markets<?php echo $handicap_etc;?>_' + gid);

                            console.log(handi_box_etc)
                            if (handi_box_etc.length == 0) {
                                var bet_two = '';
                                bet_two += '<tr id="markets<?php echo $handicap_etc;?>_' + gid + '">';
                                bet_two += '    <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">';
                                bet_two += '        <table>';
                                bet_two += '            <tr><td colspan="2" style="width: 100%; text-align: center;  height:30px;">' + data[0]['markets']['<?php echo $handicap_etc;?>']['korName'] + '</td></tr>';
                                for (var k = 0; k < data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'].length; k+=2) {
                                    var home_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][k]['odds'])) - minus_rate);
                                    var home_rate_option = data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][k]['option'];
                                    var away_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][k + 1]['odds'])) - minus_rate);
                                    var away_rate_option = data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][(k+1)]['option'];

                                    var cur_home_rate = $('#home_handicap_rate_' + data[0]['gid'] + k).text();
                                    var cur_away_rate = $('#away_handicap_rate_' + data[0]['gid'] + k).text();



                                    if (inArray(data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][k]['option'], no_handicap_etc) == false && inArray(data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][k+1]['option'], no_handicap_etc) == false) {
                                        bet_two += '            <tr>';
                                        bet_two += '                <td class="two-bet-btn">';
                                        bet_two += '                    <div class="bl_btn bl_text_btn long betting-btn-live win-' + gid + ' live-bet HandiWin" data-bet="HandiWin" data-code="<?php echo $handicap_etc;?>" data-gid="' + gid + '" data-gkey="' + gkey + '" data-rate="' + home_rate + '" data-homeName="' + home_team_name + '" data-awayName="' + away_team_name + '"  data-awayName="' + away_team_name + '">';
                                        bet_two += '                        <span class="live-left">' + home_team_name + '</span>';
                                        bet_two += '                        <span class="live-right" >' + '[<span id="home_handicap_option_' + gid + '">' + home_rate_option + '</span>] <span id="home_handicap_rate_' + gid + k +'">' + home_rate + '</span></span>';
                                        bet_two += '                    </div>';
                                        bet_two += '                </td>';
                                        bet_two += '                <td class="two-bet-btn">';
                                        bet_two += '                    <div class="bl_btn bl_text_btn long betting-btn-live lose-' + gid + ' live-bet HandiLose" data-bet="HandiLose" data-code="<?php echo $handicap_etc;?>" data-gid="' + gid + '" data-gkey="' + gkey + '" data-rate="' + away_rate + '" data-homeName="' + home_team_name + '" data-awayName="' + away_team_name + '" data-type="패" style="margin-right:3px;">';
                                        bet_two += '                        <span class="live-left">' + away_team_name + '</span>';
                                        bet_two += '                        <span class="live-right" >' + '[<span id="away_handicap_option_' + gid + '">' + away_rate_option + '</span>] <span id="away_handicap_rate_' + gid + (k+1)+'">' + away_rate + '</span></span>';
                                        bet_two += '                    </div>';
                                        bet_two += '                </td>';
                                        bet_two += '            </tr>';
                                        bet_two += '            <tr><td>&nbsp;</td></tr>';
                                    }
                                }//for k
                                bet_two += '        </table>';
                                bet_two += '    </td>';
                                bet_two += '</tr>';

                                $('.live-list-box.handicap-etc').empty().append(bet_two);

                            } else {
                                console.log('핸디 = ' + data[0]['gid'] + ' [ ' + home_rate + ' : ' + away_rate + ']')
                                for (var k = 0; k < data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'].length; k+=2) {
                                    if (inArray(data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][k]['option'], no_handicap_etc) == false && inArray(data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][k+1]['option'], no_handicap_etc) == false) {

                                        console.log(cur_home_rate +'!='+ home_rate)
                                        $('#home_handicap_rate_' + data[0]['gid']+k).text(home_rate);
                                        $('#away_handicap_rate_' + data[0]['gid']+(k+1)).text(away_rate);

                                        $('#home_handicap_option_' + data[0]['gid']).text(home_rate_option);
                                        $('#away_handicap_option_' + data[0]['gid']).text(away_rate_option);

                                        if (cur_home_rate != home_rate) {
                                            change_text_color('text-red-blink', $('#home_handicap_rate_' + data[0]['gid'] + k));
                                        }
                                        if (cur_away_rate != away_rate) {
                                            change_text_color('text-blue-blink', $('#away_handicap_rate_' + data[0]['gid'] + (k+1)));
                                        }
                                    }
                                }
                            }
                            console.log('show')
                            $('.live-list-box.handicap-etc').show();
                            /*} else {
                                $('#markets' + market_code_handi + '_' + gid).hide();
                                $('.live-list-box.handicap-etc').hide();
                            }*/
                        } else {
                            $('.live-list-box.handicap-etc').hide();
                        }

                    } else {
                        $('.live-list-box.handicap-etc').hide();
                    }//end if

                    /* 언오버 최종 결과*/
                    //$('.live-list-box.ou').empty();
                    var no_point = new Array(0.25,0.75,1.25,1.75,2.25,2.75,3.25,3.75,4.25,4.75,5.25,5.75,6.25,6.75,7.25,7.75,8.25,8.75,9.25,9.75,10.25,10.75,11.25,11.75,12.25,12.75,13.25,13.75,14.25,15.75,15.25,15.75);

                    //console.log(data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][0]['option'])
                    if(typeof data[0]['markets']['<?php echo $market_code_ou;?>'] != 'undefined') {
                        var home_team_name = data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][0]['oddsName'];
                        var away_team_name = data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][1]['oddsName'];
                        if(home_team_name.length>15){
                            home_team_name = home_team_name.substring(0,10)+'...';
                        }

                        if(away_team_name.length>15){
                            away_team_name = away_team_name.substring(0,10)+'...';
                        }
                        if (data[0]['markets']['<?php echo $market_code_ou;?>']['suspended'] == false) {
                            console.log('언오버 = '+data[0]['gid']+' [ '+home_rate+' : '+away_rate+'] '+data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][0]['option'])
                            if(inArray(data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][0]['option'],no_point) == false) {

                                var home_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][0]['odds']))-minus_rate);
                                var away_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][1]['odds']))-minus_rate);
                                var base_rate = data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][0]['option'];

                                var cur_home_rate = $('#home_over_rate_' + data[0]['gid']).text();
                                var cur_away_rate = $('#away_under_rate_' + data[0]['gid']).text();

                                bet_three = '';
                                $('#markets' + market_code_ou + '_' + data[0]['gid']).show();
                                var wdl_box = $('#markets' + market_code_ou + '_' + gid);
                                if (wdl_box.length == 0) {
                                    bet_three += '<tr id="markets' + market_code_ou + '_' + gid + '">';
                                    bet_three += '    <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">';
                                    bet_three += '        <table><tr><td colspan="3" style="width: 100%; text-align: center;  height:30px;">'+data[0]['markets']['<?php echo $market_code_ou;?>']['korName']+'</td></tr>';
                                    bet_three += '            <tr>';
                                    bet_three += '                <td class="three-bet-btn">';
                                    bet_three += '                    <div class="bl_btn bl_text_btn long betting-btn-live over-' + gid + ' live-bet over" data-bet="Over" data-code="<?php echo $market_code_ou;?>" data-gid="' + gid + '" data-gkey="'+gkey+'" data-rate="' + home_rate + '" data-homeName="' + home_team_name + '" data-awayName="' + away_team_name + '"  data-awayName="' + away_team_name + '" data-option="' + base_rate + '">';
                                    bet_three += '                        <span class="live-left">' + home_team_name + '</span>';
                                    bet_three += '                        <span class="live-right"><span><img src="/img/pop_live_icon3.png"></span> <span  id="home_over_rate_' + gid + '">' + home_rate + '</span>';
                                    bet_three += '                    </div>';
                                    bet_three += '                </td>';
                                    bet_three += '                <td class="three-bet-btn">';
                                    bet_three += '                    <div class="bl_btn bl_text_btn long">';
                                    bet_three += '                        <span class="live-left">기준</span>';
                                    bet_three += '                        <span class="live-right" id="overunder_rate_' + gid + '">' + base_rate + '</span>';
                                    bet_three += '                    </div>';
                                    bet_three += '                </td>';
                                    bet_three += '                <td class="three-bet-btn">';
                                    bet_three += '                    <div class="bl_btn bl_text_btn long betting-btn-live under-' + gid + ' live-bet under" data-bet="Under" data-code="<?php echo $market_code_ou;?>" data-gid="' + gid + '" data-gkey="'+gkey+'" data-rate="' + home_rate + '" data-homeName="' + home_team_name + '" data-awayName="' + away_team_name + '"  data-awayName="' + away_team_name + '" data-option="' + base_rate + '" style="margin-right:3px;">';
                                    bet_three += '                        <span class="live-left">' + away_team_name + '</span>';
                                    bet_three += '                        <span class="live-right"><span><img src="/img/pop_live_icon4.png"></span><span id="away_under_rate_' + gid + '">' + away_rate + '</span>';
                                    bet_three += '                    </div>';
                                    bet_three += '                </td>';
                                    bet_three += '            </tr>';
                                    bet_three += '        </table>';
                                    bet_three += '    </td>';
                                    bet_three += '</tr>';
                                    $('.live-list-box.ou').empty().append(bet_three);
                                } else {
                                    console.log('언오버 = '+data[0]['gid']+' [ '+home_rate+' : '+away_rate+']'+base_rate)
                                    $('#home_over_rate_' + data[0]['gid']).text(home_rate);

                                    $('.cart-rate-Over-'+gid ).text(home_rate);
                                    $('#away_under_rate_' + data[0]['gid']).text(away_rate);
                                    $('.cart-rate-Under-'+gid).text(away_rate);
                                    $('#overunder_rate_' + data[0]['gid']).text(base_rate);

                                    if(cur_home_rate != home_rate) {
                                        change_text_color('text-red-blink', $('#home_over_rate_' + data[0]['gid']));
                                    }
                                    if(cur_away_rate != away_rate) {
                                        change_text_color('text-blue-blink', $('#away_under_rate_' + data[0]['gid']));
                                    }
                                }
                                $('.live-list-box.ou').show();
                            } else {
                                $('#markets' + market_code_ou + '_' + data[0]['gid']).hide();
                                $('.live-list-box.ou').hide();
                            }
                        } else {
                            $('#markets' + market_code_ou + '_' + gid).hide();
                            $('.live-list-box.ou').hide();
                        }
                    } else {
                        $('#markets' + market_code_ou + '_' + gid).empty();
                        $('.live-list-box.ou').hide();
                    }
                }
            }
        });
    }


</script>


<?php include_once($root_path.'/include/live_footer.php'); ?>




