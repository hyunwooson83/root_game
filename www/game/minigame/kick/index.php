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
                            gamelist a 
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
                            a.GL_Key IN (22,23,24,25,26)
                        
                        ORDER BY a.G_Datetime ASC, a.GL_Key asc
                        LIMIT 5
                ";

    $rows = getRow($que);


    $pl = $MINIGAME_RATE['MG_Kick'];
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
            <div class="title1">파워프리킥</div>
            <div class="title2">POWER FREE KICK GAME</div>
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
                                <input type="hidden" gid="4" id="game-id">
                                <input type="hidden" name="ntry_power"  id="ntry_power" value="0">
                                <input type="hidden" name="ntry_min" id="ntry_min" value="0">
                                <input type="hidden" name="ntry_remind" id="ntry_remind" value="0">

                                <div class="games-wrap" id="mini_boxwrap">
                                    <div id="mini_box_right">
                                        <div class="mini_gamemovie">
                                            <div class="power_ladder">
                                                <!--<iframe src="http://ntry.com/scores/powerball/live.php" scrolling="no"></iframe>-->
                                            </div>
                                        </div>
                                        <!-- 파워볼 베팅 타이머 -->
                                        <div class="mini_timecount">
                                            <span id="last-play-date"><?php echo substr($rows['G_Datetime'],0,10); ?></span>
                                            <font>[<span id="play_num_view" class="timer-num-text"><?php echo $rows['G_Num']; ?></span> 회차]</font>
                                            <span id="last_play_time" style="color:yellow;"><?php echo substr($rows['G_Datetime'],-8); ?></span>
                                            <b class="count" id="remaind_time">00:52</b>
                                            <em onclick="location.reload();">새로고침</em>
                                            <span id="endtime" style="display:none;"><?php echo $rows['G_Datetime']; ?></span>
                                            <span class="timer-time-text" style="display:none;">123456789</span>
                                        </div>

                                        <!--베팅판 시작 -->
                                        <ul class="mini_bettingbtn">
                                            <li>
                                                <dl>
                                                    <dd class="game-odd-active pl-odd btn-box"
                                                        data-gkey="<?php echo $rows['G_Key']; ?>"
                                                        data-datetime="<?php echo $rows['G_Datetime']; ?>"
                                                        data-glkey = "<?php echo $rows['GL_Key']; ?>"
                                                        data-rate="<?php echo $pl[0]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-gnum="<?php echo $rows['G_Num']; ?>"
                                                        data-selected="홀"
                                                        data-selected-eng="Odd"
                                                        data-allrate="<?php echo $pl[0]."|".$pl[1];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_1.png">
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[0]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active pl-even btn-box"
                                                        data-gkey="<?php echo $rows['G_Key']; ?>"
                                                        data-datetime="<?php echo $rows['G_Datetime']; ?>"
                                                        data-glkey = "<?php echo $rows['GL_Key']; ?>"
                                                        data-rate="<?php echo $pl[1]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-gnum="<?php echo $rows['G_Num']; ?>"
                                                        data-selected="짝"
                                                        data-selected-eng="Even"
                                                        data-allrate="<?php echo $pl[0]."|".$pl[1];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_2.png">
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[1]; ?></dfn>
                                                    </dd>

                                                </dl>
                                                <em>게임 1</em>
                                                <span>선수 1번/2번</span>
                                            </li>
                                            <li>
                                                <dl>
                                                    <dd class="game-odd-active pl-left btn-box"
                                                        data-gkey="<?php echo $rows['G_Key']; ?>"
                                                        data-datetime="<?php echo $rows['G_Datetime']; ?>"
                                                        data-glkey = "<?php echo $rows['GL_Key']; ?>"
                                                        data-rate="<?php echo $pl[2]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-gnum="<?php echo $rows['G_Num']; ?>"
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
                                                        data-gkey="<?php echo $rows['G_Key']; ?>"
                                                        data-datetime="<?php echo $rows['G_Datetime']; ?>"
                                                        data-glkey = "<?php echo $rows['GL_Key']; ?>"
                                                        data-rate="<?php echo $pl[3]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-gnum="<?php echo $rows['G_Num']; ?>"
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
                                                <span>방향 좌/우</span>
                                            </li>
                                            <li>
                                                <dl>
                                                    <dd class="game-odd-active pl-line-3 btn-box"
                                                        data-gkey="<?php echo $rows['G_Key']; ?>"
                                                        data-datetime="<?php echo $rows['G_Datetime']; ?>"
                                                        data-glkey = "<?php echo $rows['GL_Key']; ?>"
                                                        data-rate="<?php echo $pl[4]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-gnum="<?php echo $rows['G_Num']; ?>"
                                                        data-selected="언더"
                                                        data-selected-eng="Under"
                                                        data-allrate="<?php echo $pl[4]."|".$pl[5];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_goal.png">
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[4]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active pl-line-4 btn-box"
                                                        data-gkey="<?php echo $rows['G_Key']; ?>"
                                                        data-datetime="<?php echo $rows['G_Datetime']; ?>"
                                                        data-glkey = "<?php echo $rows['GL_Key']; ?>"
                                                        data-rate="<?php echo $pl[5]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-gnum="<?php echo $rows['G_Num']; ?>"
                                                        data-selected="오버"
                                                        data-selected-eng="Over"
                                                        data-allrate="<?php echo $pl[4]."|".$pl[5];?>"
                                                    >
                                                        <label>
                                                            <img src="/img/mini_btn_nogoal.png">
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[5]; ?></dfn>
                                                    </dd>

                                                </dl>
                                                <em>게임 3</em>
                                                <span>골인/노골</span>
                                            </li>
                                            <li>
                                                <dl class="type4">
                                                    <dd class="game-odd-active pl-line-even-left-3 btn-box"
                                                        data-gkey="<?php echo $rows['G_Key']; ?>"
                                                        data-datetime="<?php echo $rows['G_Datetime']; ?>"
                                                        data-glkey = "<?php echo $rows['GL_Key']; ?>"
                                                        data-rate="<?php echo $pl[6]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-gnum="<?php echo $rows['G_Num']; ?>"
                                                        data-selected="좌3짝"
                                                        data-selected-eng="L3E"
                                                        data-allrate="<?php echo $pl[6]."|".$pl[7];?>"
                                                    >
                                                        <label>
                                                            <div class="bet_circle_new">
                                                                <h1 class="blue">1번</h1>
                                                                <h4 class="blue">좌</h4>
                                                            </div>
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[6]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active pl-line-odd-right-3 btn-box"
                                                        data-gkey="<?php echo $rows['G_Key']; ?>"
                                                        data-datetime="<?php echo $rows['G_Datetime']; ?>"
                                                        data-glkey = "<?php echo $rows['GL_Key']; ?>"
                                                        data-rate="<?php echo $pl[7]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-gnum="<?php echo $rows['G_Num']; ?>"
                                                        data-selected="우3홀"
                                                        data-selected-eng="R3O"
                                                        data-allrate="<?php echo $pl[6]."|".$pl[7];?>"
                                                    >
                                                        <label>
                                                            <div class="bet_circle_new">
                                                                <h1 class="blue">1번</h1>
                                                                <h4 class="red">우</h4>
                                                            </div>
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[7]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active pl-line-odd-left-4 btn-box"
                                                        data-gkey="<?php echo $rows['G_Key']; ?>"
                                                        data-datetime="<?php echo $rows['G_Datetime']; ?>"
                                                        data-glkey = "<?php echo $rows['GL_Key']; ?>"
                                                        data-rate="<?php echo $pl[8]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-gnum="<?php echo $rows['G_Num']; ?>"
                                                        data-selected="좌4홀"
                                                        data-selected-eng="L4O"
                                                        data-allrate="<?php echo $pl[8]."|".$pl[9];?>"
                                                    >
                                                        <label>
                                                            <div class="bet_circle_new">
                                                                <h1 class="red">2번</h1>
                                                                <h4 class="blue">좌</h4>
                                                            </div>
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[8]; ?></dfn>
                                                    </dd>
                                                    <dd class="game-odd-active pl-line-even-right-4 btn-box"
                                                        data-gkey="<?php echo $rows['G_Key']; ?>"
                                                        data-datetime="<?php echo $rows['G_Datetime']; ?>"
                                                        data-glkey = "<?php echo $rows['GL_Key']; ?>"
                                                        data-rate="<?php echo $pl[9]; ?>";
                                                        data-level="<?php echo $_SESSION['S_Level'];?>"
                                                        data-gnum="<?php echo $rows['G_Num']; ?>"
                                                        data-selected="우4짝"
                                                        data-selected-eng="R4E"
                                                        data-allrate="<?php echo $pl[8]."|".$pl[9]; ?>"
                                                    >
                                                        <label>
                                                            <div class="bet_circle_new">
                                                                <h1 class="red">2번</h1>
                                                                <h4 class="red">우</h4>
                                                            </div>
                                                        </label>
                                                        <dfn class="rateA"><?php echo $pl[9]; ?></dfn>
                                                    </dd>

                                                </dl>
                                                <em>게임 4</em>
                                                <span>선수/방향</span>
                                            </li>
                                        </ul>

                                        <!-- 베팅판 끝 -->


                                        <!-- 미니게임 베팅 박스 -->
                                        <?php include_once "../include/money_box.php"; ?>

                                        <div class="betting-history mini_table betting-history">
                                            <h2>베팅내역</h2>
                                            <table class="palign">
                                                <!--<colgroup>
                                                    <col width="41px">
                                                    <col width="71px">
                                                    <col width="108px">
                                                    <col width="111px">
                                                    <col width="134px">
                                                    <col width="113px">
                                                    <col width="71px">
                                                    <col width="95px">
                                                    <col width="106px">
                                                    <col width="96px">
                                                </colgroup>-->
                                                <thead>
                                                <tr>
                                                    <td scope="col"><input type="checkbox"/></td>
                                                    <td scope="col">번호</td>
                                                    <td scope="col">회차</td>
                                                    <td scope="col">베팅시간</td>
                                                    <td scope="col">게임 분류</td>
                                                    <td scope="col">베팅내역</td>
                                                    <td scope="col">배당</td>
                                                    <td scope="col">베팅 금액</td>
                                                    <td scope="col">승/패</td>
                                                    <td scope="col">승리</td>
                                                </tr>
                                                </thead>
                                                <tbody class="betting_history">
                                                <tr>

                                                    <td class="chk"><input type="checkbox" name="chk[]" class="chkbox" value="" data-idx=""></td>
                                                    <td class="num">1322</td>

                                                    <td class="date">13:01<br>12341111회(123)</td>
                                                    <td class="time">03 : 29</td>

                                                    <td class="sort"><b>홀/짝</b></td>
                                                    <td class="state"><strong class=""></strong></td>
                                                    <td class="per">1.97</td>
                                                    <td class="money01 td_right">5,000</td>
                                                    <td class="money02 td_right">7,000원</td>
                                                    <td class="result">당첨</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="10">결과가 없습니다.</td>
                                                </tr>

                                                </tbody>
                                            </table>
                                            <h3>
                                                <code class="btn-history-delete-selected" id="all_delete">선택 삭제</code>
                                                <code class="btn-history-select-all" id="all_selected" value="1" data-chk="0">모두 선택</code>
                                                <code data-value="4" class="btn-view-allhistory" onclick="location.href='/betting-history/minigame/powerball/?game=PB'">모든 베팅 내역</code>
                                            </h3>            </div>
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
<input type="text" id="bet_list" value="" style="width: 100%;">
<script>
    var config_bet_bound_min = parseInt("5000", 10);
    var config_bet_bound_max = parseInt("1000000", 10);
    var config_bet_reward_max =  parseInt("1000000", 10);
    var config_bet_finish_time = parseInt("2020-01-22 09:00:00", 10);
    var game_code = 'KICK';

    //타이머 시작

</script>
<script src="/js/powerball.js"></script>
<?php
include_once $root_path.'/include/footer.php';
?>
