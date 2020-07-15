<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

    //setQry("DELETE FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'");

    //축구 : 6046 농구 : 48242 야구 : 154914 배구 : 154830 아이스하키 : 35232


    $tb = "gamelist a LEFT JOIN gameitem c ON a.GI_Key = c.GI_Key LEFT JOIN gameleague b ON a.GL_Key = b.GL_Key_IDX";

    $view_article = 30; // 한화면에 나타날 게시물의 총 개수
    if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
    $start = ($_GET['page']-1)*$view_article;
    $href = "&teamName={$_GET['teamName']}&tb={$_GET['tb']}&search_item={$search_item}&gameType={$gameType}&glkey={$glkey}";
    $where = " 1  AND G_ResultScoreWin IS NOT NULL AND G_ResultScoreLose IS NOT NULL   ";

    if(!empty($glkey)){
        $where .= " AND a.GL_Key  = '{$glkey}' ";
    }
    if(!empty($search_item)){
        $where .= " AND a.GI_Key  = '{$search_item}' ";
    } else {
        $where .= " AND a.GI_Key IN (6046,35232,48242,154830,154914) ";
    }

    switch($gameType){
        case 'WDL':
        case 'Handicap':
        case 'UnderOver':
            $where .= " AND a.G_Type1 = 'Full' AND a.G_Type2 = '{$gameType}' ";
            break;
        case 'Special':
            $where .= " AND a.G_Type1 = 'Special' ";
            break;
        case 'FirstTime':
            $where .= " AND a.G_Type1 = 'Special' AND a.G_OType2 IN ('H1') ";
            break;

    }

    if(empty($startDate)) $startDate = date("Y-m-d",strtotime("-1 day"));
    if(empty($endDate))   $endDate = date("Y-m-d");

    $where .= " ";
    if(!empty($search_item)){
        $where .= " AND a.GI_Key  = '{$search_item}' ";
    } else {
        $where .= " AND a.GI_Key IN (6046,35232,48242,154830,154914) ";
    }


    if(!empty($search_order)){
        $where .= " ORDER BY '{$search_order}' ASC ";
    } else {
        $order_by = " ORDER BY G_Datetime DESC, inPlayMatchIdx ASC, G_Type2 DESC  ";
    }


    $query = "SELECT COUNT(*) FROM {$tb} WHERE {$where} ";
    //echo $query;
    $row = getRow($query);
    $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함

    //축구 : 6046 농구 : 48242 야구 : 154914 배구 : 154830 아이스하키 : 35232
    $total_item = 0;
    $que = "SELECT GI_Key, COUNT(DISTINCT(inPlayMatchIdx)) AS cnt FROM gamelist WHERE G_ResultScoreWin IS NOT NULL AND G_ResultScoreLose IS NOT NULL AND G_OType1= 'G' GROUP BY GI_Key";

    $item_cnt = getArr($que);
    foreach($item_cnt AS $ic){

        $item_info['cnt'][$ic['GI_Key']] = $ic['cnt'];
        $total_item += $ic['cnt'];
    }


?>
    <div id="sub_wrap">
        <div class="sub_title">
            <ul class="sub_title_category">
                <li onClick="location.href='/m/result/sports/'" class="active">스포츠</li>
                <li onClick="location.href='/m/result/minigame/power/'">미니게임</li>
                <li onClick="location.href='/m/result/virtual/'">가상게임</li>
            </ul>
            <h1>
                <span>스포츠 경기결과</span>
                <em>SPORTS RESULT</em>
            </h1>
        </div>
        <div id="tgame_type">
            <div class="tgame_slide">
                <ul class="tgame_type_sports">
                    <li class="active">
                        <em><img src="/mobile/img/sub/icon_all_category.png"></em>
                        <div>전체종목</div>
                        <span><?php echo number_format($total_item);?></span>
                        <var></var>
                    </li>
                    <li onclick="location.href = './?search_item=6046';">
                        <em><img src="/mobile/img/sub/icon_soccer.png"></em>
                        <div>축구</div>
                        <span><?php echo number_format($item_info['cnt'][6046]);?></span>
                        <var></var>
                    </li>
                    <li onclick="location.href = './?search_item=154914';">
                        <em><img src="/mobile/img/sub/icon_baseball.png"></em>
                        <div>야구</div>
                        <span><?php echo number_format($item_info['cnt'][154914]);?></span>
                        <var></var>
                    </li>
                    <li onclick="location.href = './?search_item=48242';">
                        <em><img src="/mobile/img/sub/icon_basketball.png"></em>
                        <div>농구</div>
                        <span><?php echo number_format($item_info['cnt'][48242]);?></span>
                        <var></var>
                    </li>
                    <li onclick="location.href = './?search_item=154830';">
                        <em><img src="/mobile/img/sub/icon_volleyball.png"></em>
                        <div>배구</div>
                        <span><?php echo number_format($item_info['cnt'][154830]);?></span>
                        <var></var>
                    </li>
                    <li onclick="location.href = './?search_item=35232';">
                        <em><img src="/mobile/img/sub/icon_hockey.png"></em>
                        <div>하키</div>
                        <span><?php echo number_format($item_info['cnt'][35232]);?></span>
                        <var></var>
                    </li>

                </ul>
            </div>
            <div class="tgame_guide" style="display: none;">
                <div><img src="/mobile/img/sports_type_select.png"></div>
            </div>
        </div>


        <div class="sports_list result">

            <dl class="sports_list_top">
                <dd>승(홈) <var class="arr_down arr_wave blink">▼</var></dd>
                <dt>무</dt>
                <dd>패(원정) <var class="arr_up arr_wave">▲</var></dd>
                <dt>정보</dt>
            </dl>
            <?php
            $cur_gid = "";

            if($total_article > 0){
            $cnt = 0;
            $que = "
                                        SELECT 
                                            *
                                        FROM 
                                            {$tb} 
                                        WHERE 
                                             {$where}                                    
                                             {$order_by}
                                        LIMIT 
                                            {$start}, {$view_article}
                                        
                                    ";
            //echo $que;
            $arr = getArr($que);
            foreach($arr as $rs){
            $win_bet = $lose_bet = $draw_bet = '';
            $over_icon = '';
            $under_icon = '';
            if (in_array($rs['G_SubType'], array(60461, 352321, 1549181, 482461, 352361, 482462, 352362, 482463, 352363, 482464, 352364, 60501, 60502))) {//승무패
                //echo "승무패";
                $win_rate[$ct] = $rs['G_QuotaWin'];
                $draw_rate[$ct] = $rs['G_QuotaDraw'];
                $lose_rate[$ct] = $rs['G_QuotaLose'];
                if($rs['G_ResultWDL']=='Win') {
                    $win_bet[$ct] = 'on';
                    $gresult = "홈승";
                    $result_css = "on";
                } else if($rs['G_ResultWDL']=='Draw') {
                    $draw_bet[$ct] = 'on';
                    $gresult = "무";
                    $result_css = "state";
                } else if($rs['G_ResultWDL']=='Lose') {
                    $lose_bet[$ct] = 'on';
                    $gresult = "원승";
                    $result_css = "off";
                }

            } else if (in_array($rs['G_SubType'], array(482646, 1548352, 6878952, 1549366, 1548502, 1548503, 1548504, 1549375))) {//승패
                //echo "승패";
                $win_rate[$ct] = $rs['G_QuotaWin'];
                $draw_rate[$ct] = 'VS';
                $lose_rate[$ct] = $rs['G_QuotaLose'];
                if($rs['G_ResultWDL']=='Win') {
                    $win_bet[$ct] = 'on';
                    $gresult = "홈승";
                    $result_css = "on";
                } else if($rs['G_ResultWDL']=='Lose') {
                    $lose_bet[$ct] = 'on';
                    $gresult = "원승";
                    $result_css = "off";
                }

            } else if (in_array($rs['G_SubType'], array(352322, 1548302, 1549168, 60462, 482448, 1549161, 482441, 352341, 1548345, 482465, 352365, 1548346, 482466, 352366, 482467, 1549376, 1548465, 1549360, 482522, 60562, 60481, 1549361, 482521, 60561, 60505))) {//언더오버
                //echo "오버/언더";
                $draw_bet = '';
                $win_rate[$ct] = $rs['G_QuotaOver'];
                $draw_rate[$ct] = $rs['G_QuotaUnderOver'];
                $lose_rate[$ct] = $rs['G_QuotaUnder'];
                if($rs['G_ResultUnderOver']=='Over') {
                    $win_bet[$ct] = 'on';
                    $gresult = "오버";
                    $result_css = "on";
                } else if($rs['G_ResultUnderOver']=='Under') {
                    $lose_bet[$ct] = 'on';
                    $gresult = "언더";
                    $result_css = "off";
                }

                $over_icon = '<var class="arr_up arr_wave">▲</var>';
                $under_icon = '<var class="arr_down arr_wave blink">▼</var>';
            } else {
                $draw_bet = '';
                //echo "핸디캡";
                $win_rate[$ct] = $rs['G_QuotaHandiWin'];
                $draw_rate[$ct] = $rs['G_QuotaHandicap'];
                $lose_rate[$ct] = $rs['G_QuotaHandiLose'];
                if($rs['G_ResultHandicap']=='HandiWin') {
                    $win_bet[$ct] = 'on';
                    $gresult = "핸승";
                    $result_css = "on";
                } else if($rs['G_ResultHandicap']=='HandiLose') {
                    $lose_bet[$ct] = 'on';
                    $gresult = "핸패";
                }
            }
            if($cur_gid != $rs['GL_Key'] || $cur_name != $rs['G_Team1'] ){
            if(empty($cur_gid))   $cur_gid = $rs['GL_Key'];
            if(empty($cur_name))  $cur_name = $rs['G_Team1'];
            ?>
            <!-- League start { -->
            <h1>
                <span><img src="/img/icon_<?php echo $ITEMICON[$rs['GI_Key']];?>.png" /></span><img src="/img/league/<?php echo $rs['GL_SrvName'];?>" />프리미어리그
                <var><?php echo date("m/d H:i",strtotime($rs['G_Datetime'])); ?></var>
            </h1>
                <?php $cur_gid = $rs['GL_Key']; $cur_name = $rs['G_Team1'];} ?>
            <ul>
                <!-- 한경기 부분 { -->
                <li style="border-bottom:none">
                    <dl>
                        <dd class="<?php echo $win_bet[$ct]; ?>">
                            <div>
                                <span><?php echo mb_substr($rs['G_Team1'], 0, 30, 'utf-8'); ?></span>
                                <em><?php echo $over_icon;?><?php echo number_format($win_rate[$ct], 2); ?></em>
                            </div>
                        </dd>
                        <dd class="space"></dd>
                        <dd class="center <?php echo $draw_bet[$ct]; ?>">
                            <div>
                                <em><?php
                                    echo $draw_rate[$ct];
                                    ?></em>
                            </div>
                        </dd>
                        <dd class="space"></dd>
                        <dd>
                            <div class="right <?php echo $lose_bet[$ct]; ?>">
                                <em><?php echo number_format($lose_rate[$ct], 2); ?><?php echo $under_icon;?></em>
                                <span><?php echo mb_substr($rs['G_Team2'], 0, 30, 'utf-8'); ?></span>
                            </div>
                        </dd>
                        <dd class="space"></dd>
                        <dd class="info"><div><code class="<?php echo $result_css;?>"><?php echo $gresult; ?></code><?php echo $rs['G_ResultScoreWin']; ?> : <?php echo $rs['G_ResultScoreLose']; ?></div></dd>
                    </dl>
                </li>
                <!-- } 한경기 부분 -->


            </ul>
            <?php }} else { ?>
            <ul><li colspan="8" class="text-center" style="color:#fff; font-size:14px; padding:10px;">등록된 경기결과가 없습니다.</li></ul>
            <?php } ?>
            <!-- } League end -->



        </div> <!-- sports_list -->

        <div class="sub_board">

            <?php
            if($total_article>0) {
                include_once($_SERVER['DOCUMENT_ROOT'] . "/m/lib/page.php");
            }
            ?>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('code.view').on('click',function(){
                location.href = './?startDate='+$('#startDate').val()+'&endDate='+$('#endDate').val();
            });
            $('.input_box > span').on('click',function(){
                var startDay  = $(this).data("day");
                $('input[name="startDate"]').val(startDay);
            });
            $.datepicker.setDefaults({
                dateFormat: 'yy-mm-dd',
                prevText: '이전 달',
                nextText: '다음 달',
                monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                dayNames: ['일', '월', '화', '수', '목', '금', '토'],
                dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
                dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
                showMonthAfterYear: true,
                yearSuffix: '년'
            });
            $( '#startDate,#endDate').datepicker();
        });
    </script>
<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php';
?>