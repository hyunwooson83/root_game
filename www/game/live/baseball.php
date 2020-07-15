<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
include_once $root_path.'/include/header.php';

?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(1)").addClass('active');
        });
    </script>
    <style>
        .live-left { float:left; padding-left:5px;  }
        .live-right { float: right; padding-right:5px; color:#84be44; }
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
                                <td class="active">
                                    <img src="/img/icon_all.png" />
                                    <span>전체</span>
                                    <em>(0)</em>
                                    <var></var>
                                </td>
                                <td>
                                    <img src="/img/icon_soccer.png" />
                                    <span>축구</span>
                                    <em>(0)</em>
                                    <var></var>
                                </td>
                                <td>
                                    <img src="/img/icon_basketball.png" />
                                    <span>농구</span>
                                    <em>(0)</em>
                                    <var></var>
                                </td>
                                <td>
                                    <img src="/img/icon_baseball.png" />
                                    <span>야구</span>
                                    <em>(0)</em>
                                    <var></var>
                                </td>
                                <td>
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

                            $que = "SELECT * FROM gamelist_live  WHERE G_State = 'Await' AND status = 1";
                            $arr = getArr($que);
                            if(count($arr)>0){
                                foreach($arr as $rs){
                                    ?>
                                    <div class="game-box game-list" id="game_list_<?php echo $rs['G_ID'];?>" >
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
                                                    <span class="live-game-count_<?php echo $rs['G_ID'];?>">+54</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" ><div style="width: 96%; border: #313131 solid 1px; margin:0 auto;"></div></td>
                                            </tr>
                                            <tr>
                                                <td colspan="10" style="width: 400px;" height="90">
                                                    <table>
                                                        <tr>
                                                            <td style="width: 160px;"><div id="home_team_name" class="live-text-center-100"><?php echo (!empty($rs['home_KorName']))?$rs['home_KorName']:$rs['home_name'];?></div></td>
                                                            <!--<td style="width: 5%;"><span id="home_team_img"><img src="/img/live/home_team_img.png" border="0"></span></td>-->
                                                            <td style="width:90px;">
                                                                <div style="width:100%; text-align: center; font-size:18px;">
                                                                    <span id="home_score_<?php echo $rs['G_ID'];?>">0</span> - <span id="away_score_<?php echo $rs['G_ID'];?>">0</span>
                                                                    <br>
                                                                    <span style="text-align: center; font-size: 13px; color:#ff6000;">VS</span>
                                                                </div>
                                                            </td>
                                                            <!--<td style="width: 5%;"><span id="away_team_img"><img src="/img/live/away_team_img.png"></span></td>-->
                                                            <td style="width: 150px;"><div id="away_team_name" class="live-text-center-100"><?php echo (!empty($rs['away_KorName']))?$rs['away_KorName']:$rs['away_name'];?></div></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                <?php }} else { ?>



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
                    <div class="right-game-detail" style="width: 538px; background-color: #131313;">
                        <div class="right-game-list">
                            <!-- 경기 목록 시작 -->
                            <div class="game-box" >
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
                                        <td><!--리옹 vs 스타드브레스트 29-->진행중인 게임이 생성되면 자동으로 화면에 보여집니다.</td>
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
                                    <thead>
                                    <colgroup>
                                        <col width="5%">
                                        <col width="42%">
                                        <col width="3%">
                                        <col width="3%">
                                        <col width="3%">
                                    </colgroup>

                                    <?php

                                    ?>


                                    <!-- 배팅판 2개 시작 -->
                                    <tr>
                                        <td colspan="5" style="width: 409px; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">
                                            <table>
                                                <tr><td colspan="2" style="width: 100%; text-align: center;  height:30px;">최종승패</td></tr>
                                                <tr>
                                                    <td class="two-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn win-65486 live-bet home">
                                                            <span class="live-left">Kale 1957...</span>
                                                            <span class="live-right">1.16</span>
                                                        </div>
                                                    </td>
                                                    <td class="two-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn win-65486 live-bet away" style="margin-right:3px;">
                                                            <span class="live-left">Kale 1957...</span>
                                                            <span class="live-right">1.16</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <!-- 배팅판 2개 끝 -->

                                    <tr><td style="height: 10px;">&nbsp;</td></tr>
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

        <!-- 카트 시작 -->
        <?php include $_SERVER['DOCUMENT_ROOT']."/include/cart.php"; ?>
        <!-- Right Container -->

    </div> <!-- Sub Container -->
    </div>

    <script>
        get_data();
        setInterval('get_data()',5000);
        function get_data(){
            $.ajax({
                type : 'post',
                url : './proc/',
                async: true,
                dataType : 'json',
                success : function(data){

                    for(var i=0 ; i<data.length;i++){
                        if(data[i]['sportsId']!='16'){
                            $('#remind_time_'+data[i]['gid']).text(data[i]['timeM']+':'+data[i]['timeS']);
                        } else {
                            $('#remind_time_'+data[i]['gid']).text('');
                        }
                        $('#disp_game_half_text_'+data[i]['gid']).text(data[i]['timeKorMark']);

                        $('#home_score_'+data[i]['gid']).text(data[i]['homeScore']);
                        $('#away_score_'+data[i]['gid']).text(data[i]['awayScore']);

                        console.log(data[i]['markets']['_00_90_34']);

                    }
                }
            });
        }

    </script>

<?php include_once($root_path.'/include/live_footer.php'); ?>