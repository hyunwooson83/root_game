<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

if($SITECONFIG['KL_Stop_YN'] == 'Y'){
    echo "<script>swal('','키노사다리 게임이 점검중입니다.','warning');
        setTimeout(function(){location.href = '/m/main/';});</script>,2000);
        ";
}


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

    $endtimeint = strtotime($gdatetime[0])-30;
    $game_type_gubun = "키노사다리";
    $pl = $MINIGAME_RATE['MG_KenoLadder'];
    $pl = explode("|",$pl);
?>

    <div id="sub_wrap">
        <div class="nmenu">
            <?php include_once "../include/time_box_top.php"; ?>

        </div>

        <!--<div class="sub_title">
            <h1>
                <span>키노사다리</span>
                <em>LadderKino</em>
            </h1>
        </div>-->

        <div class="minigame_wrap">

            <input type="hidden" name="ntry_power"  id="ntry_power" value="0">
            <input type="hidden" name="ntry_min" id="ntry_min" value="0">
            <input type="hidden" name="ntry_remind" id="ntry_remind" value="0">
            <div class="minigame_screen">
                <div><iframe id="game_frame" src="http://ntry.com/scores/powerball/main.php" frameborder="0" border="0" scrolling="no"></iframe></div>
            </div>

            <div class="sports_list2">
                <h1 style="padding:0.7em">
                    <em><?php echo date("m월 d일",strtotime($gdatetime[0])); ?>
                        <b>[<span id="play_num_view" class="timer-num-text"><?php echo $num[0]; ?>회차</span>]</b>
                        <span id="last_play_time"><?php echo substr($gdatetime[0],-8,5); ?>
                            <label id="remaind_time">00:00</label>
                            <span class="timer-time-text" style="display:none;">123456789</span>
                    </em>
                </h1>
            </div>

            <table class="minigame_btnbox">
                <tbody>
                <tr>
                    <th>
                        <b>1게임</b>
                        <font>홀/짝</font>
                    </th>
                    <td>
                        <dl class="mini_betbtn mini_betbtn_type2">
                            <dd class="game-odd-active pl-odd btn-box"
                                data-gkey="<?php echo $gkey[0]; ?>"
                                data-datetime="<?php echo $gdatetime[0]; ?>"
                                data-glkey = "<?php echo $glkey[0]; ?>"
                                data-rate="<?php echo $pl[0]; ?>";
                                data-level="<?php echo $_SESSION['S_Level'];?>"
                                data-gnum="<?php echo $num[0]; ?>"
                                data-selected="홀"
                                data-selected-eng="Odd"
                                data-allrate="<?php echo $pl[0]."|".$pl[1];?>">
                                <span class="betball ball_blue">홀</span>
                                <em><?php echo $pl[0]; ?></em>
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
                                data-allrate="<?php echo $pl[0]."|".$pl[1];?>">
                                <span class="betball ball_red">짝</span>
                                <em><?php echo $pl[1]; ?></em>
                            </dd>
                        </dl>
                    </td>
                </tr>
                <tr>
                    <th>
                        <b>2게임</b>
                        <font>출발점</font>
                    </th>
                    <td>
                        <dl class="mini_betbtn mini_betbtn_type2">
                            <dd class="game-odd-active pl-left btn-box"
                                data-gkey="<?php echo $gkey[1]; ?>"
                                data-datetime="<?php echo $gdatetime[1]; ?>"
                                data-glkey = "<?php echo $glkey[1]; ?>"
                                data-rate="<?php echo $pl[2]; ?>";
                                data-level="<?php echo $_SESSION['S_Level'];?>"
                                data-datetime="<?php echo $num[1]; ?>"
                                data-selected="좌"
                                data-selected-eng="Left"
                                data-allrate="<?php echo $pl[2]."|".$pl[3];?>">
                                <span class="betball ball_blue">좌</span>
                                <em><?php echo $pl[2]; ?></em>
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
                                data-allrate="<?php echo $pl[2]."|".$pl[3];?>">
                                <span class="betball ball_red">우</span>
                                <em><?php echo $pl[3]; ?></em>
                            </dd>
                        </dl>
                    </td>
                </tr>
                <tr>
                    <th>
                        <b>3게임</b>
                        <font>줄갯수</font>
                    </th>
                    <td>
                        <dl class="mini_betbtn mini_betbtn_type2">
                            <dd class="game-odd-active pl-line-3 btn-box"
                                data-gkey="<?php echo $gkey[2]; ?>"
                                data-datetime="<?php echo $gdatetime[2]; ?>"
                                data-glkey = "<?php echo $glkey[2]; ?>"
                                data-rate="<?php echo $pl[4]; ?>";
                                data-level="<?php echo $_SESSION['S_Level'];?>"
                                data-datetime="<?php echo $num[2]; ?>"
                                data-selected="3줄"
                                data-selected-eng="Line3"
                                data-allrate="<?php echo $pl[4]."|".$pl[5];?>">
                                <span class="betball ball_blue">3줄</span>
                                <em><?php echo $pl[4]; ?></em>
                            </dd>
                            <dd class="game-odd-active pl-line-4 btn-box"
                                data-gkey="<?php echo $gkey[2]; ?>"
                                data-datetime="<?php echo $gdatetime[2]; ?>"
                                data-glkey = "<?php echo $glkey[2]; ?>"
                                data-rate="<?php echo $pl[5]; ?>";
                                data-level="<?php echo $_SESSION['S_Level'];?>"
                                data-datetime="<?php echo $num[2]; ?>"
                                data-selected="4줄"
                                data-selected-eng="Line4"
                                data-allrate="<?php echo $pl[4]."|".$pl[5];?>">
                                <span class="betball ball_red">4줄</span>
                                <em><?php echo $pl[5]; ?></em>
                            </dd>
                        </dl>
                    </td>
                </tr>
                <tr>
                    <th>
                        <b>4게임</b>
                        <font>좌우출발<br>3/4줄</font>
                    </th>
                    <td>
                        <dl class="mini_betbtn mini_betbtn_type4">
                            <dd class="game-odd-active pl-line-even-left-3 btn-box"
                                data-gkey="<?php echo $gkey[3]; ?>"
                                data-datetime="<?php echo $gdatetime[3]; ?>"
                                data-glkey = "<?php echo $glkey[3]; ?>"
                                data-rate="<?php echo $pl[6]; ?>";
                                data-level="<?php echo $_SESSION['S_Level'];?>"
                                data-datetime="<?php echo $num[3]; ?>"
                                data-selected="좌3짝"
                                data-selected-eng="L3E"
                                data-allrate="<?php echo $pl[6]."|".$pl[7];?>">
                                <span class="betball ball_red">짝<code class="left">3</code></span>
                                <em><?php echo $pl[6]; ?></em>
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
                                data-allrate="<?php echo $pl[6]."|".$pl[7];?>">
                                <span class="betball ball_blue">홀<code class="right">3</code></span>
                                <em><?php echo $pl[7]; ?></em>
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
                                data-allrate="<?php echo $pl[8]."|".$pl[9];?>">
                                <span class="betball ball_blue">홀<code class="left">4</code></span>
                                <em><?php echo $pl[8]; ?></em>
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
                                data-allrate="<?php echo $pl[8]."|".$pl[9]; ?>">
                                <span class="betball ball_red">짝<code class="right">4</code></span>
                                <em><?php echo $pl[9]; ?></em>
                            </dd>
                        </dl>
                    </td>
                </tr>
                </tbody>
            </table>

            <?php include_once "../include/money_box.php"; ?>

            <dl class="mini_footinfo">
                <dt>알아두세요!</dt>
                <dd><code>·</code><font>나눔로또에서 제공하는 스피드 키노를 기본으로 진행하는 게임입니다.</font></dd>
                <dd><code>·</code><font>나눔로또 스피드키노기준 키노사다리 중계는 5분에 한번씩 매 4분 40초, 9분 40초에 게임이 실행됩니다.</font></dd>
                <dd><code>·</code><font>출발점은 일반볼 첫번째 숫자가 홀일 경우 좌출발, 짝일 경우 우출발입니다.</font></dd>
                <dd><code>·</code><font>줄갯수는 일반볼 첫번째 숫자가 1~14일 경우 3줄, 15~28일 경우 4줄입니다.</font></dd>
                <dd><code>·</code><font>한번 베팅한 게임은 베팅취소 및 베팅수정이 불가합니다.</font></dd>
                <dd><code>·</code><font>베팅은 본인의 보유 예치금 기준으로 베팅 가능하며, 추첨결과에 따라 명시된 배당률 기준으로 적립해드립니다.</font></dd>
                <dd><code>·</code><font>부적절한 방법(ID도용, 불법프로그램, 시스템 베팅 등)으로 베팅을 할 경우 무효처리되며, 전액 몰수 / 강제탈퇴 등 불이익을 받을 수 있습니다.</font></dd>
                <dd><code>·</code><font>모든 배당률은 당사의 운영정책에 따라 언제든지 상/하향 조정될 수 있습니다.</font></dd>
            </dl>

        </div> <!-- minigame_wrap -->
    </div>

    <div id="recom_bg" onclick="result_close();"></div>
    <div id="recom_add_setting" class="mini_result_list">
        <em>최근 회차별 통계</em>
        <fieldset class="mini_result_rows">
            <ul>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
                <li>07월 27일 [23]</li>
            </ul>
        </fieldset>
        <div style="padding:0">
            <div>
                <a href="javascript:result_close();" class="ggray">닫기</a>
            </div>
        </div>
    </div>

    <script>
        function result_open(){
            iScrollRefresh();
            $("html, body").bind('touchmove', function(e){e.preventDefault()});
            $("#recom_add_setting").fadeIn();
            $("#recom_bg").fadeIn();
        }
        function result_close(){
            iScrollRefresh();
            $("html, body").unbind('touchmove');
            $("#recom_add_setting").fadeOut();
            $("#recom_bg").fadeOut();
        }
    </script>
    <input type="hidden" id="bet_list" value="" style="width: 100%;">
    <script>
        var config_bet_bound_min = parseInt("5000", 10);
        var config_bet_bound_max = parseInt("1000000", 10);
        var config_bet_reward_max =  parseInt("1000000", 10);
        var config_bet_finish_time = parseInt("<?php echo $SITECONFIG['kenoladder_bet_time']; ?>");
        var game_code = 'KL';

        //타이머 시작
        //타이머 시작
        function game_result_rollback(){
            swal('','배팅이 완료되었습니다.','success');
            setTimeout(function(){ location.reload();},1500);
        }
    </script>
    <script src="/js/keno.js?t=<?php echo time(); ?>"></script>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>