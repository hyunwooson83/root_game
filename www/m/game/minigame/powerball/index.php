<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) {
        swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }

if($meminfo['M_MiniYN']=='N'){
    echo '<script>swal("","미니게임을 이용하실 수 없습니다.","warning"); setTimeout(function(){ location.href="/main/";},2000);</script>';
}

    if($SITECONFIG['Power_Stop_YN'] == 'Y'){
        echo "<script>swal('','파워볼 게임이 점검중입니다.','warning');
        setTimeout(function(){location.href = '/m/main/';});</script>,2000);
        ";
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

    <div id="sub_wrap">
        <div class="nmenu">
            <?php include_once "../include/time_box_top.php"; ?>
            
        </div>

        <!--<div class="sub_title">
            <h1>
                <span>파워볼게임</span>
                <em>Powerball game</em>
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

            <!-- powerball_area -->
            <table class="minigame_btnbox_type2">
                <tbody>
                <tr>
                    <th>
                        <b>1게임</b>
                        <font>일반볼 홀/짝</font>
                    </th>
                    <th>
                        <b>2게임</b>
                        <font>일반볼 숫자합 언오버</font>
                    </th>
                </tr>
                <tr>
                    <td>
                        <dl class="mini_betbtn mini_betbtn_type5">
                            <dd class="p-odd btn-box"
                                data-gkey="<?php echo $gkey[0]; ?>"
                                data-datetime="<?php echo $gdatetime[0]; ?>"
                                data-glkey = "<?php echo $glkey[0]; ?>"
                                data-rate="<?php echo $po[0]; ?>";
                                data-level="<?php echo $_SESSION['S_Level'];?>"
                                data-gnum="<?php echo $num[0]; ?>"
                                data-selected="홀"
                                data-selected-eng="Odd"
                                data-allrate="<?php echo $po[0]."|".$po[1];?>">
                                <span><img src="/m/img/sub/mini_btn_pb_odd.png"></span>
                                <em><?php echo $po[0]; ?></em>
                            </dd>
                            <dd class=" p-even btn-box"
                                data-gkey="<?php echo $gkey[0]; ?>"
                                data-datetime="<?php echo $gdatetime[1]; ?>"
                                data-glkey = "<?php echo $glkey[0]; ?>"
                                data-rate="<?php echo $po[1]; ?>";
                                data-level="<?php echo $_SESSION['S_Level'];?>"
                                data-datetime="<?php echo $num[1]; ?>"
                                data-selected="짝"
                                data-selected-eng="Even"
                                data-allrate="<?php echo $po[0]."|".$po[1];?>">
                                <span><img src="/m/img/sub/mini_btn_pb_even.png"></span>
                                <em><?php echo $po[1]; ?></em>
                            </dd>
                        </dl>
                    </td>
                    <td>
                        <dl class="mini_betbtn mini_betbtn_type5">
                            <dd class="game-odd-active p-odd btn-box"
                                data-gkey="<?php echo $gkey[1]; ?>"
                                data-datetime="<?php echo $gdatetime[1]; ?>"
                                data-glkey = "<?php echo $glkey[1]; ?>"
                                data-rate="<?php echo $po[2]; ?>";
                                data-level="<?php echo $_SESSION['S_Level'];?>"
                                data-datetime="<?php echo $num[1]; ?>"
                                data-selected="언더"
                                data-selected-eng="Under"
                                data-allrate="<?php echo $po[2]."|".$po[3];?>">
                                <span><img src="/m/img/sub/under_72.png"></span>
                                <em><?php echo $po[2]; ?></em>
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
                                data-allrate="<?php echo $po[2]."|".$po[3];?>">
                                <span><img src="/m/img/sub/over_72.png"></span>
                                <em><?php echo $po[3]; ?></em>
                            </dd>
                        </dl>
                    </td>
                </tr>

                <tr>
                    <th colspan="2">
                        <b>3게임</b>
                        <font>일반볼 구간</font>
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <dl class="mini_betbtn mini_betbtn_type6">
                            <dd class="game-odd-active p-between-15 btn-box"
                                data-gkey="<?php echo $gkey[2]; ?>"
                                data-datetime="<?php echo $gdatetime[2]; ?>"
                                data-glkey = "<?php echo $glkey[2]; ?>"
                                data-rate="<?php echo $po[6]; ?>";
                                data-level="<?php echo $_SESSION['S_Level'];?>"
                                data-datetime="<?php echo $num[2]; ?>"
                                data-selected="소"
                                data-selected-eng="Small"
                                data-allrate="<?php echo $po[4]."|".$po[5]."|".$po[6];?>">
                                <span><img src="/m/img/sub/mini_btn_pb_small.png"></span>
                                <em><?php echo $po[4]; ?></em>
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
                                data-allrate="<?php echo $po[4]."|".$po[5]."|".$po[6];?>">
                                <span><img src="/m/img/sub/mini_btn_pb_middle.png"></span>
                                <em><?php echo $po[5]; ?></em>
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
                                data-allrate="<?php echo $po[4]."|".$po[5]."|".$po[6];?>">
                                <span><img src="/m/img/sub/mini_btn_pb_big.png"></span>
                                <em><?php echo $po[6]; ?></em>
                            </dd>
                        </dl>
                    </td>
                </tr>

                <tr>
                    <th colspan="2">
                        <b>4게임</b>
                        <font>일반볼 조합</font>
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <dl class="mini_betbtn mini_betbtn_type8">
                            <dd class="game-odd-active p-under-odd-4 btn-box"
                                data-gkey="<?php echo $gkey[3]; ?>"
                                data-datetime="<?php echo $gdatetime[3]; ?>"
                                data-glkey = "<?php echo $glkey[3]; ?>"
                                data-rate="<?php echo $po[7]; ?>";
                                data-level="<?php echo $_SESSION['S_Level'];?>"
                                data-datetime="<?php echo $num[3]; ?>"
                                data-selected="홀+언더"
                                data-selected-eng="OddUnder"
                                data-allrate="<?php echo $po[7]."|".$po[8];?>">
                                <span><img src="/m/img/sub/mini_btn_pb_odd_under.png"></span>
                                <em><?php echo $po[7]; ?></em>
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
                                data-allrate="<?php echo $po[9]."|".$po[10];?>">
                                <span><img src="/m/img/sub/mini_btn_pb_even_under.png"></span>
                                <em><?php echo $po[9]; ?></em>
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
                                data-allrate="<?php echo $po[7]."|".$po[8];?>">
                                <span><img src="/m/img/sub/mini_btn_pb_odd_over.png"></span>
                                <em><?php echo $po[8]; ?></em>
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
                                data-allrate="<?php echo $po[9]."|".$po[10];?>">
                                <span><img src="/m/img/sub/mini_btn_pb_even_over.png"></span>
                                <em><?php echo $po[10]; ?></em>
                            </dd>
                        </dl>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">
                        <b>5게임</b>
                        <font>파워볼 조합</font>
                    </th>
                </tr>
                <tr>
                    <td>
                        <dl class="mini_betbtn mini_betbtn_type5">
                            <dd class="game-odd-active p-under-odd-4 btn-box"
                                data-gkey="<?php echo $gkey[5]; ?>"
                                data-datetime="<?php echo $gdatetime[5]; ?>"
                                data-glkey = "<?php echo $glkey[5]; ?>"
                                data-rate="<?php echo $pop[0]; ?>";
                                data-level="<?php echo $_SESSION['S_Level'];?>"
                                data-datetime="<?php echo $num[5]; ?>"
                                data-selected="홀"
                                data-selected-eng="Odd"
                                data-allrate="<?php echo $pop[0]."|".$pop[1];?>">
                                <span><img src="/m/img/sub/mini_btn_pb_odd.png"></span>
                                <em><?php echo $pop[0]; ?></em>
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
                                data-allrate="<?php echo $pop[0]."|".$pop[1];?>">
                                <span><img src="/m/img/sub/mini_btn_pb_even.png"></span>
                                <em><?php echo $pop[1]; ?></em>
                            </dd>
                        </dl>
                    </td>
                    <td>
                        <dl class="mini_betbtn mini_betbtn_type5">
                            <dd class="game-odd-active p-over-odd-4 btn-box"
                                data-gkey="<?php echo $gkey[6]; ?>"
                                data-datetime="<?php echo $gdatetime[6]; ?>"
                                data-glkey = "<?php echo $glkey[6]; ?>"
                                data-rate="<?php echo $pop[2]; ?>";
                                data-level="<?php echo $_SESSION['S_Level'];?>"
                                data-datetime="<?php echo $num[1]; ?>"
                                data-selected="언더"
                                data-selected-eng="Under"
                                data-allrate="<?php echo $pop[2]."|".$pop[3];?>">
                                <span><img src="/m/img/sub/under_45.png"></span>
                                <em><?php echo $pop[2]; ?></em>
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
                                data-allrate="<?php echo $pop[2]."|".$pop[3];?>">
                                <span><img src="/m/img/sub/over_45.png"></span>
                                <em><?php echo $pop[3]; ?></em>
                            </dd>
                        </dl>
                    </td>
                </tr>

                <tr>
                    <th colspan="2">
                        <b>6게임</b>
                        <font>파워볼 숫자</font>
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <dl class="mini_betbtn mini_betbtn_type7">
                            <?php
                            $k = 4;
                            for($j=0;$j<=4;$j++){
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
                                data-allrate="<?php echo $pop[$k];?>">
                                <span><img src="/m/img/sub/mini_btn_pb_bg.png"><b><?php echo $j; ?></b></span>
                                <em><?php echo $pop[$k]; ?></em>
                            </dd>
                                <?php $k++; } ?>
                        </dl>
                        <dl class="mini_betbtn mini_betbtn_type7">
                            <?php
                            for($j=5;$j<=9;$j++){
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
                                data-allrate="<?php echo $pop[$k];?>">
                                <span><img src="/m/img/sub/mini_btn_pb_bg.png"><b><?php echo $j; ?></b></span>
                                <em><?php echo $pop[$k]; ?></em>
                            </dd>
                                <?php $k++; } ?>
                        </dl>
                    </td>
                </tr>


                </tbody>
            </table>

            <!-- 금액 설정 -->
            <?php include_once "../include/money_box.php"; ?>
            <!-- 금액 설정 끝 -->
            <dl class="mini_footinfo">
                <dt>알아두세요!</dt>
                <dd><code>·</code><font>본 서비스는 나눔로또의 파워볼 결과를 기준으로 정산처리하며, 파워볼의 결과와 무관합니다.</font></dd>
                <dd><code>·</code><font>한번 베팅한 게임은 베팅취소및 베팅수정이 불가합니다.</font></dd>
                <dd><code>·</code><font>베팅은 본인의 보유 예치금 기준으로 베팅 가능하며, 추첨결과에 따라 명시된 배당률 기준으로 적립해드립니다.</font></dd>
                <dd><code>·</code><font>부적절한 방법(ID도용, 불법프로그램, 시스템 베팅 등)으로 베팅을 할 경우 무효처리되며, 전액 몰수 / 강제탈퇴 등 불이익을 받을 수 있습니다.</font></dd>
                <dd><code>·</code><font>파워볼게임의 모든 배당률은 당사의 운영정책에 따라 언제든지 상/하향 조정될 수 있습니다.</font></dd>
                <dd><code>·</code><font>모든 배당률은 당사의 운영정책에 따라 언제든지 상/하향 조정될 수 있습니다.</font></dd>
            </dl>

        </div> <!-- minigame_wrap -->

    </div>

    <div id="recom_bg" onclick="result_close();"></div>

    <input name="bet_list" type="hidden" id="bet_list" value="" style="width: 100;">
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
    <script src="/m/js/powerball.js?t=<?php echo time(); ?>"></script>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>