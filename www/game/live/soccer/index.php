<?php
    $root_path = $_SERVER['DOCUMENT_ROOT'];
    include_once $root_path.'/include/header_new.php';
    include_once($root_path . "/include/Snoopy.class.php");

    if($SITECONFIG['Live_Stop_YN'] == 'Y'){
        echo "<script>swal('','라이브 게임이 점검중입니다.','warning');
            setTimeout(function(){location.href = '/main/';},2000);</script>
            ";
    }

    setQry("DELETE FROM cartgamelist_live WHERE M_Key = '{$_SESSION['S_Key']}'");

    $total = 0;
    $que = "SELECT COUNT(G_ID) as item_cnt, GI_Key FROM gamelist_live WHERE 1 AND status = 1 AND G_State = 'Await' AND make_yn = 'y' GROUP BY GI_Key ";
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
                                    <em>(<?php echo (!empty($rm['6046']['cnt']) && $rm[6046]['cnt']>0)?$rm['6046']['cnt']:0;?>)</em>
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
                                    <em>(<?php echo (!empty($rm['154914']['cnt']) && $rm[154914]['cnt']>0)?$rm['154914']['cnt']:0;?>)</em>
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

                            $que = "SELECT * FROM gamelist_live  WHERE G_State = 'Await' AND status = '1' AND matchDateTime < NOW() AND GI_Key = '6046' AND make_yn = 'y'";
                            //echo $que;
                            $arr = getArr($que);
                            $arr_cnt = count($arr);
                            if($arr_cnt>0){
                                foreach($arr as $rs){
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
                                                    <span class="live-game-count_<?php echo $rs['G_ID'];?>" style="margin-left:5px;" data-gid="<?php echo $rs['G_ID'];?>">+9</span>
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
                        <input type="hidden" name="set_gid" id="set_gid" value="">
                        <input type="hidden" name="set_home" id="set_home" value="">
                        <input type="hidden" name="set_away" id="set_away" value="">
                        <input type="hidden" name="set_code1" id="set_code1" value="">
                        <input type="hidden" name="set_code2" id="set_code2" value="">
                        <input type="hidden" name="set_code3" id="set_code3" value="">
                        <input type="hidden" name="set_code4" id="set_code4" value="">
                        <input type="hidden" name="set_code5" id="set_code5" value="">
                        <input type="hidden" name="set_gkey1" id="set_gkey1" value="">
                        <input type="hidden" name="set_gkey2" id="set_gkey2" value="">
                        <input type="hidden" name="set_gkey3" id="set_gkey3" value="">
                        <input type="hidden" name="set_gkey4" id="set_gkey4" value="">
                        <input type="hidden" name="set_gkey5" id="set_gkey5" value="">
                        <input type="hidden" name="set_handi0" id="set_handi0" value="">
                        <input type="hidden" name="set_handi1" id="set_handi1" value="">
                        <input type="hidden" name="set_handi2" id="set_handi2" value="">
                        <input type="hidden" name="set_handi3" id="set_handi3" value="">
                        <input type="hidden" name="set_handi4" id="set_handi4" value="">
                        <input type="hidden" name="set_ou0" id="set_ou0" value="">
                        <input type="hidden" name="set_ou1" id="set_ou1" value="">
                        <input type="hidden" name="set_ou2" id="set_ou2" value="">
                        <input type="hidden" name="set_ou3" id="set_ou3" value="">
                        <input type="hidden" name="set_ou4" id="set_ou4" value="">

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
                                    <!--승무패-->
                                    <tr style="display:none;" class="markets-wdl">
                                        <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">
                                            <table>
                                                <tr>
                                                    <td colspan="3" style="width: 100%; text-align: center;  height:30px;">최종 승무패</td>
                                                </tr>
                                                <tr>

                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Win" data-type1="Full" data-type2="WDL" data-bet="Win" data-market="wdl">
                                                            <span class="live-left home-team-name">홈팀명</span>
                                                            <span class="live-right"><span  class="win-rate" data-gid=""></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Draw" data-type1="Full" data-type2="WDL" data-bet="Draw" data-market="wdl">
                                                            <span class="live-left">기준</span>
                                                            <span class="live-right draw-rate" data-gid=""></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Lose" data-type1="Full" data-type2="WDL" data-bet="Lose" data-market="wdl" style="margin-right:3px;">
                                                            <span class="live-left away-team-name">원정팀명</span>
                                                            <span class="live-right"><span class="away-rate" data-gid=""></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <!--승무패 전반-->
                                    <tr style="display:none ;" class="markets-wdl-1st">
                                        <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">
                                            <table>
                                                <tr>
                                                    <td colspan="3" style="width: 100%; text-align: center;  height:30px;">승무패 전반전</td>
                                                </tr>
                                                <tr>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Win1" data-type1="Special" data-type2="WDL" data-bet="Win1" data-market="wdl1st" data-grade="1">
                                                            <span class="live-left home-team-name">홈팀명</span>
                                                            <span class="live-right"><span></span> <span  class="win-1st-rate"></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Draw1" data-type1="Special" data-type2="WDL" data-bet="Draw1" data-market="wdl1st" data-grade="1">
                                                            <span class="live-left">기준</span>
                                                            <span class="live-right draw-1st-rate"></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Lose1" data-type1="Special" data-type2="WDL" data-bet="Lose1" data-market="wdl1st" data-grade="1" style="margin-right:3px;">
                                                            <span class="live-left away-team-name">원정팀명</span>
                                                            <span class="live-right"><span></span><span class="away-1st-rate">1.45</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <!--승무패 후반-->
                                    <tr style="display:none ;" class="markets-wdl-2st">
                                        <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">
                                            <table>
                                                <tr>
                                                    <td colspan="3" style="width: 100%; text-align: center;  height:30px;">승무패 후반전</td>
                                                </tr>
                                                <tr>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Win2" data-type1="Special" data-type2="WDL" data-bet="Win2" data-market="wdl2nd" data-grade="2">
                                                            <span class="live-left home-team-name">홈팀명</span>
                                                            <span class="live-right"><span></span> <span  class="win-2st-rate"></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Draw2" data-type1="Special" data-type2="WDL" data-bet="Draw2" data-market="wdl2nd" data-grade="2">
                                                            <span class="live-left">기준</span>
                                                            <span class="live-right draw-2st-rate"></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Lose2" data-type1="Special" data-type2="WDL" data-bet="Lose2" data-market="wdl2nd" data-grade="2" style="margin-right:3px;">
                                                            <span class="live-left away-team-name">원정팀명</span>
                                                            <span class="live-right"><span></span><span class="away-2st-rate"></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <!-- 핸디캡-->
                                    <tr style="display:none ;" class="markets-handicap">
                                        <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">
                                            <table>
                                                <tr><td colspan="2" style="width: 100%; text-align: center;  height:30px;">최종 핸디캡</td></tr>
                                                <tr>
                                                    <td class="two-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet HandiWin" data-type1="Full" data-type2="Handicap" data-bet="HandiWin" data-market="handicap">
                                                            <span class="live-left home-team-name">홈팀명</span>
                                                            <span class="live-right" >[<span class="home-handicap-option"></span>] <span class="home-handicap-rate">1.45</span></span>
                                                        </div>
                                                    </td>
                                                    <td class="two-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet HandiLose" data-type1="Full" data-type2="Handicap" data-bet="HandiLose" data-market="handicap" style="margin-right:3px;">
                                                            <span class="live-left away-team-name">원정팀명</span>
                                                            <span class="live-right" >[<span class="away-handicap-option"></span>] <span class="away-handicap-rate">1.98</span></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <!-- 핸디캡-->
                                    <tr style="display:none ;" class="markets-handicap-other">
                                        <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">
                                            <table>
                                                <tr><td colspan="2" style="width: 100%; text-align: center;  height:30px;">핸디캡 추가기준점<!--<span class="market-other-name">전반전</span>--></td></tr>
                                                <tr class="handicap-other-0" style="display: none;">
                                                    <td class="two-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet HandiWin-0" data-type1="Full" data-type2="Handicap" data-bet="HandiWin" data-market="handicap" data-other="Y" data-grade="0">
                                                            <span class="live-left home-team-name">홈팀명</span>
                                                            <span class="live-right" >[<span class="HandiWin-option-0"></span>] <span class="HandiWin-rate-0"></span></span>
                                                        </div>
                                                    </td>
                                                    <td class="two-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet HandiLose-0" data-type1="Full" data-type2="Handicap" data-bet="HandiLose" data-market="handicap" data-other="Y" data-grade="0" style="margin-right:3px;">
                                                            <span class="live-left away-team-name">원정팀명</span>
                                                            <span class="live-right" >[<span class="HandiLose-option-0"></span>] <span class="HandiLose-rate-0"></span></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="handicap-other-0" style="display: none;"><td>&nbsp;</td></tr>
                                                <tr class="handicap-other-1" style="display: none;">
                                                    <td class="two-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet  HandiWin-1" data-type1="Full" data-type2="Handicap" data-bet="HandiWin" data-market="handicap" data-other="Y" data-grade="1">
                                                            <span class="live-left home-team-name">홈팀명</span>
                                                            <span class="live-right" >[<span class="HandiWin-option-1"></span>] <span class="HandiWin-rate-1"></span></span>
                                                        </div>
                                                    </td>
                                                    <td class="two-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet  HandiLose-1" data-type1="Full" data-type2="Handicap" data-bet="HandiLose" data-market="handicap" data-other="Y" data-grade="1" style="margin-right:3px;">
                                                            <span class="live-left away-team-name">원정팀명</span>
                                                            <span class="live-right" >[<span class="HandiLose-option-1"></span>] <span class="HandiLose-rate-1"></span></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="handicap-other-1" style="display: none;"><td>&nbsp;</td></tr>
                                                <tr class="handicap-other-2" style="display: none;">
                                                    <td class="two-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet  HandiWin-2" data-type1="Full" data-type2="Handicap" data-bet="HandiWin" data-market="handicap" data-other="Y" data-grade="2">
                                                            <span class="live-left home-team-name">홈팀명</span>
                                                            <span class="live-right" >[<span class="HandiWin-option-2"></span>] <span class="HandiWin-rate-2"></span></span>
                                                        </div>
                                                    </td>
                                                    <td class="two-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet  HandiLose-2" data-type1="Full" data-type2="Handicap" data-bet="HandiLose" data-market="handicap" data-other="Y" data-grade="2" style="margin-right:3px;">
                                                            <span class="live-left away-team-name">원정팀명</span>
                                                            <span class="live-right" >[<span class="HandiLose-option-2"></span>] <span class="HandiLose-rate-2"></span></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="handicap-other-2" style="display: none;"><td>&nbsp;</td></tr>
                                                <tr class="handicap-other-3" style="display: none;">
                                                    <td class="two-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet  HandiWin-3" data-type1="Full" data-type2="Handicap" data-bet="HandiWin" data-market="handicap" data-other="Y" data-grade="3">
                                                            <span class="live-left home-team-name">홈팀명</span>
                                                            <span class="live-right" >[<span class="HandiWin-option-3"></span>] <span class="HandiWin-rate-3"></span></span>
                                                        </div>
                                                    </td>
                                                    <td class="two-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet  HandiLose-3" data-type1="Full" data-type2="Handicap" data-bet="HandiLose" data-market="handicap" data-other="Y" data-grade="3" style="margin-right:3px;">
                                                            <span class="live-left away-team-name">원정팀명</span>
                                                            <span class="live-right" >[<span class="HandiLose-option-3"></span>] <span class="HandiLose-rate-3"></span></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="handicap-other-3" style="display: none;"><td>&nbsp;</td></tr>

                                            </table>
                                        </td>
                                    </tr>
                                    <!--언더오버-->
                                    <tr style="display:none ;" class="markets-underover">
                                        <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">
                                            <table>
                                                <tr>
                                                    <td colspan="3" style="width: 100%; text-align: center;  height:30px;">최종 오버/언더</td>
                                                </tr>
                                                <tr>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Over" data-type1="Full" data-type2="UnderOver" data-bet="Over" data-market="underover">
                                                            <span class="live-left home-team-name">홈팀명</span>
                                                            <span class="live-right"><span><img src="/img/pop_live_icon3.png"></span> <span  class="over-rate">2.11</span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long live-bet ">
                                                            <span class="live-left">기준</span>
                                                            <span class="live-right overunder-option"></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Under" data-type1="Full" data-type2="UnderOver" data-bet="Under" data-market="underover" style="margin-right:3px;">
                                                            <span class="live-left away-team-name">원정팀명</span>
                                                            <span class="live-right"><span><img src="/img/pop_live_icon4.png"></span><span class="under-rate">1.45</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <!--언더오버 기타기준점-->
                                    <tr style="display:none ;" class="markets-underover-other">
                                        <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">
                                            <table>
                                                <tr>
                                                    <td colspan="3" style="width: 100%; text-align: center;  height:30px;">오버/언더 추가기준점<!--<span class="market-other-name">전반전</span>--></td>
                                                </tr>
                                                <tr style="display:none ;" class="underover-other-0">
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Over-0" data-type1="Full" data-type2="UnderOver" data-bet="Over" data-market="underover" data-other="Y" data-grade="0">
                                                            <span class="live-left home-team-name">홈팀명</span>
                                                            <span class="live-right"><span><img src="/img/pop_live_icon3.png"></span> <span  class="over-rate-0"></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long live-bet">
                                                            <span class="live-left">기준</span>
                                                            <span class="live-right overunder-option-0"></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live  live-bet Under-0" data-type1="Full" data-type2="UnderOver" data-bet="Under" data-market="underover" data-other="Y" data-grade="0" style="margin-right:3px;">
                                                            <span class="live-left away-team-name">원정팀명</span>
                                                            <span class="live-right"><span><img src="/img/pop_live_icon4.png"></span><span class="under-rate-0"></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr style="display:none ;" class="underover-other-0"><td>&nbsp;</td></tr>
                                                <tr style="display:none ;" class="underover-other-1">
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live  live-bet Over-1" data-type1="Full" data-type2="UnderOver" data-bet="Over" data-market="underover" data-other="Y" data-grade="1">
                                                            <span class="live-left home-team-name">홈팀명</span>
                                                            <span class="live-right"><span><img src="/img/pop_live_icon3.png"></span> <span  class="over-rate-1"></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live  live-bet">
                                                            <span class="live-left">기준</span>
                                                            <span class="live-right overunder-option-1"></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live  live-bet Under-1" data-type1="Full" data-type2="UnderOver" data-bet="Under" data-market="underover" data-other="Y" data-grade="1" style="margin-right:3px;">
                                                            <span class="live-left away-team-name">원정팀명</span>
                                                            <span class="live-right"><span><img src="/img/pop_live_icon4.png"></span><span class="under-rate-1"></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr style="display:none ;" class="underover-other-1"><td>&nbsp;</td></tr>
                                                <tr style="display:none ;" class="underover-other-2">
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Over-2" data-type1="Full" data-type2="UnderOver" data-bet="Over" data-market="underover" data-other="Y" data-grade="2">
                                                            <span class="live-left home-team-name">홈팀명</span>
                                                            <span class="live-right"><span><img src="/img/pop_live_icon3.png"></span> <span  class="over-rate-2"></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live
                                                        <span class="live-left">기준</span>
                                                            <span class="live-right overunder-option-2"></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Under-2" data-type1="Full" data-type2="UnderOver" data-bet="Under" data-market="underover" data-other="Y" data-grade="2" style="margin-right:3px;">
                                                            <span class="live-left away-team-name">원정팀명</span>
                                                            <span class="live-right"><span><img src="/img/pop_live_icon4.png"></span><span class="under-rate-2"></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr style="display:none ;" class="underover-other-2"><td>&nbsp;</td></tr>
                                                <tr style="display:none ;" class="underover-other-3">
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Over-3" data-type1="Full" data-type2="UnderOver" data-bet="Over" data-market="underover" data-other="Y" data-grade="3">
                                                            <span class="live-left home-team-name">홈팀명</span>
                                                            <span class="live-right"><span><img src="/img/pop_live_icon3.png"></span> <span  class="over-rate-3"></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet ">
                                                            <span class="live-left">기준</span>
                                                            <span class="live-right overunder-option-3"></span>
                                                        </div>
                                                    </td>
                                                    <td class="three-bet-btn">
                                                        <div class="bl_btn bl_text_btn long betting-btn-live live-bet Under-3" data-type1="Full" data-type2="UnderOver" data-bet="Under" data-market="underover" data-other="Y" data-grade="3" style="margin-right:3px;">
                                                            <span class="live-left away-team-name">원정팀명</span>
                                                            <span class="live-right"><span><img src="/img/pop_live_icon4.png"></span><span class="under-rate-3"></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr style="display:none ;" class="underover-other-3"><td>&nbsp;</td></tr>
                                            </table>
                                        </td>
                                    </tr>
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
        //setTimeout(function(){ location.reload(); },1000*60*5)
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
                get_data(gid);
                $('div.cross').find('div').removeClass('active');
                loadingCartLive(gid);
                clearInterval(timer);
                timer = setInterval(function(){
                    get_data(gid);
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
                    if(loading_flag1 == false) {
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
        var loading_flag1  = false;
        function get_data(gid){
            //console.log(gkey)
            $.ajax({
                type : 'post',
                url : '../proc/soccer.php',
                async: true,
                dataType : 'json',
                data : 'gid='+gid+'&item=soccer',
                    beforeSend : function(){
                        if(loading_flag == false) {
                            $('.loading').show();
                        }
                    },
                success : function(data){
                     console.log(data)
                    $('#set_gid').val(gid);//선택한 게임 아이디를 입력한다.
                    $('#set_home').val(data['homeName']);//선택한 게임 아이디를 입력한다.
                    $('#set_away').val(data['awayName']);//선택한 게임 아이디를 입력한다.
                    $('#set_gkey1').val(data['gkey'][0]);//선택한 게임 아이디를 입력한다.
                    $('#set_gkey2').val(data['gkey'][1]);//선택한 게임 아이디를 입력한다.
                    $('#set_gkey3').val(data['gkey'][2]);//선택한 게임 아이디를 입력한다.
                    $('#set_gkey4').val(data['gkey'][3]);//선택한 게임 아이디를 입력한다.
                    $('#set_gkey5').val(data['gkey'][4]);//선택한 게임 아이디를 입력한다.
                    $('#set_code1').val(data['code'][0]);//선택한 게임 아이디를 입력한다.
                    $('#set_code2').val(data['code'][1]);//선택한 게임 아이디를 입력한다.
                    $('#set_code3').val(data['code'][2]);//선택한 게임 아이디를 입력한다.
                    $('#set_code4').val(data['code'][3]);//선택한 게임 아이디를 입력한다.
                    $('#set_code5').val(data['code'][4]);//선택한 게임 아이디를 입력한다.


                    //승무패
                    if(data['WDL']['suspended']==false){
                        var cur_home_rate = cur_draw_rate = cur_away_rate = 1;
                        cur_home_rate = $('.win-rate').text();
                        cur_draw_rate = $('.draw-rate').text();
                        cur_away_rate = $('.away-rate').text();
                        //console.log(cur_home_rate +':'+ cur_draw_rate +':'+ cur_away_rate)
                        if(data['WDL']['homeRate'] > 1.03 && data['WDL']['drawRate'] > 1.03 && data['WDL']['awayRate'] > 1.03) {
                            $('.home-team-name').text(data['homeName']);
                            $('.away-team-name').text(data['awayName']);
                            $('.win-rate').text(data['WDL']['homeRate']);
                            $('.draw-rate').text(data['WDL']['drawRate']);
                            $('.away-rate').text(data['WDL']['awayRate']);
                            $('#wdl_gkey').val(data['WDL']['gkey']);
                            $('.markets-wdl').show();
                            if (cur_home_rate != data['WDL']['homeRate']) {
                                change_text_color('text-red-blink', $('.win-rate'));
                            }
                            if (cur_draw_rate != data['WDL']['drawRate']) {
                                change_text_color('text-yellow-blink', $('.draw-rate'));
                            }
                            if (cur_away_rate != data['WDL']['awayRate']) {
                                change_text_color('text-blue-blink', $('.away-rate'));
                            }
                        } else {
                            $('.markets-wdl').hide();
                        }
                    } else {
                        $('.markets-wdl').hide();
                    }
                    //전반

                    if(data['WDL1st']['suspended']==false){
                        var cur_home_rate = cur_draw_rate = cur_away_rate = 1;
                        cur_home_rate = $('.win-1st-rate').text();
                        cur_draw_rate = $('.draw-1st-rate').text();
                        cur_away_rate = $('.away-1st-rate').text();
                        if(data['WDL']['homeRate'] > 1.03 && data['WDL']['drawRate'] > 1.03 && data['WDL']['awayRate'] > 1.03) {
                            $('.home-team-name').text(data['homeName']);
                            $('.away-team-name').text(data['awayName']);
                            $('.win-1st-rate').text(data['WDL1st']['homeRate']);
                            $('.draw-1st-rate').text(data['WDL1st']['drawRate']);
                            $('.away-1st-rate').text(data['WDL1st']['awayRate']);
                            $('.markets-wdl-1st').show();
                            if (cur_home_rate != data['WDL1st']['homeRate']) {
                                change_text_color('text-red-blink', $('.win-1st-rate'));
                            }
                            if (cur_draw_rate != data['WDL1st']['drawRate']) {
                                change_text_color('text-yellow-blink', $('.draw-1st-rate'));
                            }
                            if (cur_away_rate != data['WDL1st']['awayRate']) {
                                change_text_color('text-blue-blink', $('.away-1st-rate'));
                            }
                        } else {
                            $('.markets-wdl-1st').hide();
                        }
                    } else {
                        $('.markets-wdl-1st').hide();
                    }

                    //후반
                    if(data['WDL2nd']['suspended']==false){
                        var cur_home_rate = cur_draw_rate = cur_away_rate = 1;
                        cur_home_rate = $('.win-2st-rate').text();
                        cur_draw_rate = $('.draw-2st-rate').text();
                        cur_away_rate = $('.away-2st-rate').text();
                        if(data['WDL']['homeRate'] > 1.03 && data['WDL']['drawRate'] > 1.03 && data['WDL']['awayRate'] > 1.03) {
                            $('.home-team-name').text(data['homeName']);
                            $('.away-team-name').text(data['awayName']);
                            $('.win-2st-rate').text(data['WDL2nd']['homeRate']);
                            $('.draw-2st-rate').text(data['WDL2nd']['drawRate']);
                            $('.away-2st-rate').text(data['WDL2nd']['awayRate']);
                            $('.markets-wdl-2st').show();


                            if (cur_home_rate != data['WDL2nd']['homeRate']) {

                                change_text_color('text-red-blink', $('.win-2st-rate'));
                            }
                            if (cur_draw_rate != data['WDL2nd']['drawRate']) {
                                change_text_color('text-yellow-blink', $('.draw-2st-rate'));
                            }
                            if (cur_away_rate != data['WDL2nd']['awayRate']) {
                                change_text_color('text-blue-blink', $('.away-2st-rate'));
                            }
                        } else {
                            $('.markets-wdl-2st').hide();
                        }
                    } else {
                        $('.markets-wdl-2st').hide();

                    }

                    //핸디캡
                    if(data['Handicap']['suspended']==false){
                        var cur_home_rate = cur_draw_rate = cur_away_rate = 1;
                        cur_home_rate = $('.home-handicap-rate').text();
                        cur_away_rate = $('.away-handicap-rate').text();
                        $('.markets-handicap').show();
                        $('.home-team-name').text(data['homeName']);
                        $('.away-team-name').text(data['awayName']);
                        $('.home-handicap-rate').text(data['Handicap']['handiWin']);
                        $('.home-handicap-option').text(data['Handicap']['handiWinOption']);
                        $('.away-handicap-rate').text(data['Handicap']['handiLose']);
                        $('.away-handicap-option').text(data['Handicap']['handiLoseOption']);
                        if(cur_home_rate != data['Handicap']['handiWin']) {
                            change_text_color('text-red-blink', $('.home-handicap-rate'));
                        }
                        if(cur_away_rate != data['Handicap']['handiLose']) {
                            change_text_color('text-blue-blink', $('.away-handicap-rate'));
                        }
                    } else {
                        $('.markets-handicap').hide();

                    }

                    //핸디캡 기타기준점
                    if(data['HandicapOther'] != null) {
                        if (data['HandicapOther'].length > 0) {
                            
                            $('.markets-handicap-other').show();
                            if(data['TM'] == '1st'){
                                $('.market-other-name').text('전반전');
                            } else if(data['TM']['2nd']){
                                $('.market-other-name').text('후반전');
                            }
                            var cur_home_rate0 = cur_away_rate0 = cur_home_rate1 = cur_away_rate1 = cur_home_rate2 = cur_home_rate3 = cur_away_rate2 = cur_away_rate3 = 1;
                            var cur_home_rate = new Array();
                            var cur_away_rate = new Array();
                            for (var i = 0; i < data['HandicapOther'].length; i++) {
                                if (data['HandicapOther'][i]['suspended'] == false) {
                                    $('#set_handi'+i).val(data['HandicapOther'][i]['id']);
                                    cur_home_rate[i] = $('.HandiWin-rate-'+i).text();
                                    cur_away_rate[i] = $('.HandiLose-rate-'+i).text();
                                    $('.handicap-other-' + i).show();
                                    $('.home-team-name').text(data['homeName']);
                                    $('.away-team-name').text(data['awayName']);
                                    $('.HandiWin-rate-' + i).text(data['HandicapOther'][i]['handiWin']);
                                    $('.HandiWin-option-' + i).text(data['HandicapOther'][i]['handiWinOption']);
                                    $('.HandiLose-rate-' + i).text(data['HandicapOther'][i]['handiLose']);
                                    $('.HandiLose-option-' + i).text(data['HandicapOther'][i]['handiLoseOption']);
                                    if(cur_home_rate[i] != data['HandicapOther'][i]['handiWin']) {
                                        change_text_color('text-red-blink', $('.HandiWin-rate-'+i));
                                    }
                                    if(cur_away_rate[i] != data['HandicapOther'][i]['handiLose']) {
                                        change_text_color('text-blue-blink', $('.HandiLose-rate-'+i));
                                    }
                                } else {
                                    $('.handicap-other-' + i).hide();
                                }
                            }
                        } else {
                            $('.markets-handicap-other').hide();
                        }
                    } else {
                        $('.markets-handicap-other').hide();
                    }

                    //언더오버
                    if(data['UnderOver']['suspended']==false){
                        var cur_home_rate = cur_draw_rate = cur_away_rate = 1;
                        cur_home_rate = $('.over-rate').text();
                        cur_draw_rate = $('.overunder-option').text();
                        cur_away_rate = $('.under-rate').text();
                        $('.markets-underover').show();
                        $('.home-team-name').text(data['homeName']);
                        $('.away-team-name').text(data['awayName']);
                        $('.over-rate').text(data['UnderOver']['over']);
                        $('.overunder-option').text(data['UnderOver']['overOption']);
                        $('.under-rate').text(data['UnderOver']['under']);
                        if(cur_home_rate != data['UnderOver']['over']) {
                            change_text_color('text-red-blink', $('.over-rate'));
                        }
                        /*if(cur_draw_rate != data['UnderOver']['overOption']) {
                            change_text_color('text-yellow-blink', $('.overunder-option'));
                        }*/
                        if(cur_away_rate != data['UnderOver']['under']) {
                            change_text_color('text-blue-blink', $('.under-rate'));
                        }
                    } else {
                        $('.markets-underover').hide();

                    }

                    //언오버버 기타기준점
                    if(data['ouOther'] != null) {
                        if (data['ouOther'].length > 0) {
                            $('.markets-underover-other').show();
                            var cur_home_rate = new Array();
                            var cur_away_rate = new Array();
                            for (var i = 0; i < data['ouOther'].length; i++) {
                                if (data['ouOther'][i]['suspended'] == false) {
                                    $('#set_ou'+i).val(data['ouOther'][i]['id']);
                                    cur_home_rate[i] = $('.over-rate-'+i).text();
                                    cur_away_rate[i] = $('.under-rate-'+i).text();
                                    $('.underover-other-' + i).show();
                                    $('.home-team-name').text(data['homeName']);
                                    $('.away-team-name').text(data['awayName']);
                                    $('.over-rate-' + i).text(data['ouOther'][i]['over']);
                                    $('.overunder-option-'+i).text(data['ouOther'][i]['overOption']);
                                    $('.under-rate-' + i).text(data['ouOther'][i]['under']);
                                    if(cur_home_rate[i] != data['ouOther'][i]['over']) {
                                        change_text_color('text-red-blink', $('.over-rate-' + i));
                                    }
                                    if(cur_away_rate[i] != data['ouOther'][i]['under']) {
                                        change_text_color('text-blue-blink', $('.under-rate-' + i));
                                    }
                                } else {
                                    $('.underover-other-' + i).hide();
                                }
                            }
                        } else {
                            $('.markets-underover-other').hide();
                        }
                    } else {
                        $('.markets-underover-other').hide();
                    }

                    $('.loading').hide();
                    loading_flag1 = true;
                }
            });
        }


    </script>


<?php include_once($root_path.'/include/live_footer.php'); ?>




