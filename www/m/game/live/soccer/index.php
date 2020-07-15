<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

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
<style>
    .sports_list > ul > li > div.sub_detail_info > h1 > dl > dd > span {
        margin: 0 0.2em;
        padding: 0.1em 0.5em;
        color: #eebd04;
        border: 1px solid #eebd04;
    }
    .sports_list > ul > li > div.sub_detail_info > h1 > dl > dt {
        display: table-cell;
        width: 36%;
        padding: 0.5em 0;
        text-align: right;
        font-size: 1.15em;
        color: #FFF;
    }
    .sports_list > ul > li > div.sub_detail_info > h1 > dl > dd {
        display: table-cell;
        width: 30%;
        text-align: center;
        color: #999;
    }
    .sports_list > ul > li > div.sub_detail_info {
        border: 0px solid #6d8b34;
        background: #1b1b1b;
        padding: 0 0.5em 0 0.5em !important;
        border-radius: 0.5em;
        font-size: 0.8em;
        margin-top: 0.7em;
    }

    .text-red-blink { color: #f57442 !important; font-weight: bold;}
    .text-blue-blink { color: #33b2ee !important; font-weight: bold;}
    .text-yellow-blink { color:yellow !important; font-weight: bold;}
    .on{ border:#fff002 solid 1px;}
    .loading{ width:958px; height: 818px; position: absolute; left:342px; background-color: #000; opacity: .7;}
    .loading_text {position: relative; margin-top:300px; height:300px; margin-left:430px; font-size:2em;}
    .hide { display: none;}
    .show { display:'';}
</style>

<div class="nmenu">
    <ul class="nmenu_cate sports">
        <li onclick="location.href='#'">라이브</li>
        <li onclick="location.href='/m/game/sports/cross/'" class="active">크로스</li>
        <li onclick="location.href='/m/game/sports/special/'" class="">스페셜</li>
        <li onclick="location.href='/m/game/sports/WDL/'" class="">승무패</li>
        <li onclick="location.href='/m/game/sports/handicap/'" class="">핸디캡</li>
        <li onclick="location.href='/m/game/sports/underover/'" class="">언오버</li>

    </ul>
</div>

<div id="sub_wrap">

    <div class="sub_title">
        <h1>
            <span>크로스 베팅</span>
            <em>Cross betting</em>
        </h1>
        <!--<span><strong>마감순</strong><var>|</var>리그별<var>|</var>국가별</span>-->
    </div>
    <!-- 상단 종목 선택 { -->
    <div id="tgame_type">
        <?php echo include_once "../item.php"; ?>
    </div> <!-- tgame_type -->

    <script>
        $(".tgame_type_sports > li").click(function(){
            $(".tgame_type_sports > li").removeClass("active");
            $(this).addClass("active");
        });

        setTimeout(function(){
            $(".tgame_guide").fadeOut();
        },3000);
    </script>
    <!-- } 상단 종목 선택 -->


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

   
    <div class="sports_list">

        <dl class="sports_list_top">
            <dd>승(홈)오버 <var class="arr_up arr_wave">▲</var></dd>
            <dt>무/핸/합</dt>
            <dd>패(원정)언더 <var class="arr_down arr_wave">▼</var></dd>
        </dl>

            <!-- 승무패 -->
            <table class="live-list-box markets-wdl" style="display: none;">
                <tbody>
                <tr>
                    <th class="point">최종 승무패</th>
                </tr>
                <tr>
                    <td>
                        <table class="three">
                            <tbody>
                            <tr>
                                <td class="center betting-btn betting-btn-live live-bet Win" data-type1="Full" data-type2="WDL" data-bet="Win" data-market="wdl" style="width:32%; text-align: left;">
                                    <span class="home-team-name"></span>
                                    <em class="win-rate"></em>
                                </td>
                                <td class="center betting-btn betting-btn-live live-bet Win" data-type1="Full" data-type2="WDL" data-bet="Win" data-market="wdl" style="width:32%; text-align: left;">
                                    <span style="padding-left:10px; width:60%;">무</span>
                                    <em class="draw-rate" style="padding-right:10px;"></em>
                                </td>
                                <td class="betting-btn live-bet Lose" data-type1="Full" data-type2="WDL" data-bet="Lose" data-market="wdl" style="width:32%; text-align: left;">
                                    <em style="width: 80%; color:#bababa;" class="live-left away-team-name"></em>
                                    <span style="width:20%; color:#fff" class="away-rate"></span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <!-- 승무패 끝-->
            <!-- 승무패 -->
            <table class="live-list-box markets-wdl-1st" style="display: none;">
                <tbody>
                <tr>
                    <th class="point">승무패 전반전</th>
                </tr>
                <tr>
                    <td>
                        <table class="three">
                            <tbody>
                            <tr>
                                <td class="center betting-btn betting-btn-live  live-bet Win1" data-type1="Special" data-type2="WDL" data-bet="Win1" data-market="wdl1st" data-grade="1" style="width:32%; text-align: left;">
                                    <span class="home-team-name"></span>
                                    <em class="win-1st-rate"></em>
                                </td>
                                <td class="center betting-btn betting-btn-live live-bet Draw1" data-type1="Special" data-type2="WDL" data-bet="Draw1" data-market="wdl1st" data-grade="1" style="width:32%; text-align: left;">
                                    <span style="padding-left:10px; width:60%;">무</span>
                                    <em class=" draw-1st-rate" style="padding-right:10px;"></em>
                                </td>
                                <td class="betting-btn live-bet  Lose1" data-type1="Special" data-type2="WDL" data-bet="Lose1" data-market="wdl1st" data-grade="1" style="width:32%; text-align: left;">
                                    <em style="width: 80%; color:#bababa;" class="live-left away-team-name"></em>
                                    <span style="width:20%; color:#fff" class="away-1st-rate"></span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <!-- 승무패 끝-->
            <!-- 승무패 -->
            <table class="live-list-box markets-wdl-2st" style="display: none;">
                <tbody>
                <tr>
                    <th class="point">승무패 후반전</th>
                </tr>
                <tr>
                    <td>
                        <table class="three">
                            <tbody>
                            <tr>
                                <td class="center betting-btn betting-btn-live live-bet Win2" data-type1="Special" data-type2="WDL" data-bet="Win2" data-market="wdl2nd" data-grade="2" style="width:32%; text-align: left;">
                                    <span class="home-team-name"></span>
                                    <em class="win-2st-rate"></em>
                                </td>
                                <td class="center betting-btn betting-btn-live live-bet Draw2" data-type1="Special" data-type2="WDL" data-bet="Draw2" data-market="wdl2nd" data-grade="2" style="width:32%; text-align: left;">
                                    <span style="padding-left:10px; width:60%;">무</span>
                                    <em class=" draw-2st-rate" style="padding-right:10px;"></em>
                                </td>
                                <td class="betting-btn live-bet Lose2" data-type1="Special" data-type2="WDL" data-bet="Lose2" data-market="wdl2nd" data-grade="2" style="width:32%; text-align: left;">
                                    <em style="width: 80%; color:#bababa;" class="live-left away-team-name"></em>
                                    <span style="width:20%; color:#fff" class="away-2st-rate"></span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <!-- 승무패 끝-->
            <!-- 핸디캡 -->
            <table class="live-list-box markets-handicap" style="display: none;">
                <tbody>
                <tr>
                    <th class="point">최종 핸디캡</th>
                </tr>
                <tr>
                    <td>
                        <table class="three">
                            <tbody>
                            <tr>
                                <td class="betting-btn HandiWin" data-type1="Full" data-type2="Handicap" data-bet="HandiWin" data-market="handicap" style="border-right: 1px solid rgba(0,0,0,0.5);">
                                    <span style="width: 70%;" class="home-team-name"></span>
                                    <em style="width:30%; color:#fff">[<span class="home-handicap-option"></span>] <span class="home-handicap-rate"></span></em>
                                </td>
                                <td class="betting-btn HandiLose" data-type1="Full" data-type2="Handicap" data-bet="HandiLose" data-market="handicap">
                                    <em style="width: 70%; color:#bababa;" class="live-left away-team-name"></em>
                                    <span style="width:30%; color:#fff">[<span class="away-handicap-option"></span>] <span class="away-handicap-rate"></span></span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <!-- 핸디캡 끝-->
            <!-- 핸디캡 기타 기준점-->
            <table class="live-list-box markets-handicap-other" style="display: none;">
                <tbody>
                <tr>
                    <th class="point">핸디캡 기타 기준점</th>
                </tr>
                <?php for($i=0;$i<5;$i++){ ?>
                    <tr class="handicap-other-<?php echo $i;?>" style="display: none;">
                        <td>
                            <table class="three">
                                <tbody>
                                <tr>
                                    <td class="betting-btn HandiWin-<?php echo $i;?>" data-type1="Full" data-type2="Handicap" data-bet="HandiWin" data-market="handicap" data-other="Y" data-grade="<?php echo $i;?>" style="border-right: 1px solid rgba(0,0,0,0.5);">
                                        <span style="width: 70%;" class="home-team-name"></span>
                                        <em style="width:30%; color:#fff">[<span class="HandiWin-option-<?php echo $i;?>"></span>] <span class="HandiWin-rate-<?php echo $i;?>"></span></em>
                                    </td>
                                    <td class="betting-btn HandiLose-<?php echo $i;?>" data-type1="Full" data-type2="Handicap" data-bet="HandiLose" data-market="handicap" data-other="Y" data-grade="<?php echo $i;?>">
                                        <em style="width: 70%; color:#bababa;" class="live-left away-team-name"></em>
                                        <span style="width:30%; color:#fff">[<span class="HandiLose-option-<?php echo $i;?>"></span>] <span class="HandiLose-rate-<?php echo $i;?>"></span></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <!-- 핸디캡 기타 기준점 끝-->
            <!-- 오버언더 -->
            <table class="live-list-box markets-underover" style="display: none;">
                <tbody>
                <tr>
                    <th class="point">오버언더</th>
                </tr>
                <tr>
                    <td>
                        <table class="three">
                            <tbody>
                            <tr>
                                <td class="betting-btn Over" data-type1="Full" data-type2="UnderOver" data-bet="Over" data-market="underover">
                                    <span class="home-team-name"></span>
                                    <em class="over-rate"></em>
                                </td>
                                <td class="center" data-rate="-1.5" data-bet="" data-gkey="407959" data-glist="5588159" data-bet_cnt="0">
                                    <span class="live-right overunder-option"></span>
                                </td>
                                <td class="betting-btn Under" data-type1="Full" data-type2="UnderOver" data-bet="Under" data-market="underover">
                                    <em style="width: 80%; color:#bababa;" class="live-left away-team-name"></em>
                                    <span style="width:20%; color:#fff" class="under-rate"></span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <!-- 오버언더 끝-->

            <!-- 오버언더 기타 기준점 -->
            <table class="live-list-box markets-underover-other" style="display: none;">
                <tbody>
                <tr>
                    <th class="point">오버언더 기타기준점</th>
                </tr>
                <?php for($i=0;$i<5;$i++){ ?>
                    <tr class="underover-other-<?php echo $i;?>" style="display: none;">
                        <td>
                            <table class="three">
                                <tbody>
                                <tr>
                                    <td class="betting-btn Over-<?php echo $i;?>" data-type1="Full" data-type2="UnderOver" data-bet="Over" data-market="underover" data-other="Y" data-grade="<?php echo $i;?>" >
                                        <span class="home-team-name"></span>
                                        <em class="over-rate-<?php echo $i;?>"></em>
                                    </td>
                                    <td class="center">
                                        <span class="live-right overunder-option-<?php echo $i;?>"></span>
                                    </td>
                                    <td class="betting-btn Under-<?php echo $i;?>" data-type1="Full" data-type2="UnderOver" data-bet="Under" data-market="underover" data-other="Y" data-grade="<?php echo $i;?>" >
                                        <em style="width: 80%; color:#bababa;" class="live-left away-team-name"></em>
                                        <span style="width:20%; color:#fff" class="under-rate-<?php echo $i;?>"></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <!-- 오버언더 기타기준점 끝-->

        <!--보너스 시작 -->

        <?php

            $box_cnt = 0;
            $que = "SELECT * FROM gamelist_live  WHERE G_State = 'Await' AND status = '1' AND matchDateTime < NOW() AND GI_Key = '6046' AND make_yn = 'y'";
            //echo $que;
            $arr = getArr($que);
            $arr_cnt = count($arr);
            if($arr_cnt>0){
                foreach($arr as $rs){
        ?>

        <h1>
            <span><img src="/img/icon_soccer.png"></span> <?php echo mb_substr($rs['league_name'],0,22,'utf-8');?>

            <var><?php echo substr($rs['matchDateTime'],5,11);?></var>
            <span id="remind_time_<?php echo $rs['G_ID'];?>">00:00</span>
            <em class="play_btn sub-game-toggle" data-gid="55967291100">
                <label>+9</label>
            </em>
        </h1>

        <ul>
            <li>
                <div class="sub_detail_info left-game-list-box" id="left_game_box_<?php echo $rs['G_ID'];?>" data-gid="<?php echo $rs['G_ID'];?>" data-gkey="<?php echo $rs['G_Key'];?>" data-wdl="<?php echo $market_code_wdl;?>" data-handicap="<?php echo $market_code_handi;?>" data-ou="<?php echo $market_code_ou;?>" id="game_list_<?php echo $rs['G_ID'];?>">
                    <h1 style="height:50px; display: flex; flex-direction: column; align-items: center; justify-content: center; ">
                        <dl>
                            <dt style="text-align: left; padding-left:10px;" id="home_team_name" ><?php echo (!empty($rs['home_korName']))?$rs['home_korName']:$rs['home_name'];?></dt>
                            <dd><span id="home_score_<?php echo $rs['G_ID'];?>">0</span> : <span id="away_score_<?php echo $rs['G_ID'];?>">0</span></dd>
                            <dt style="text-align: right; padding-right: 5px;" id="away_team_name" ><?php echo (!empty($rs['away_korName']))?$rs['away_korName']:$rs['away_name']?></dt>
                        </dl>
                    </h1>
                    <h1 class="line2">
                        <dl>
                            <dd style="height:30px; width:100%; display: flex; align-items: center; justify-content: flex-end;"></dd>
                        </dl>
                    </h1>
                </div>
            </li>
        </ul>
        <?php }} ?>

        <div class="more_btn">
            <?php
            if($total_article>0) {
                //include_once($_SERVER['DOCUMENT_ROOT'] . "/m/lib/page.php");
            }
            ?>
        </div>

    </div> <!-- sports_list -->


</div> <!-- Sub Wrap -->

<script>
    var item_name = 'soccer';
    var mkey = <?php echo $_SESSION['S_Key'];?>
</script>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/betting_cart.php'); ?>

<div class="betcart_btn" onClick="betcart();">
    <h6></h6>
    <div class="cart_title">
        <span>베팅카트 열기</span>
        <ul>
            <li>선택경기<font><span id="select_game_cnt">0</span>건</font></li>
            <li>예상배당<font id="select_game_rate">1.00</font></li>
        </ul>
    </div>
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
            get_data(gid);
            $('div.cross').find('div').removeClass('active');
            //loadingCartLive(gid);
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
    var game_box = '';
    //진행중인 경기의 라이브데이터
    function get_live_score(){
        $.ajax({
            type : 'post',
            url : '/game/live/proc/liveScore.php',
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
            url : '/game/live/proc/soccer.php',
            async: true,
            dataType : 'json',
            data : 'gid='+gid+'&item=soccer',
            beforeSend : function(){
                if(loading_flag == false) {
                    $('.loading').show();
                }
            },
            success : function(data){
                game_box = $('.live-list-box').detach();
                $('#left_game_box_'+gid).append(game_box);
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


                $('.bet_box_wrap')
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
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>
