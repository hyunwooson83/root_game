<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    //setQry("DELETE FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'");

    //축구 : 6046 농구 : 48242 야구 : 154914 배구 : 154830 아이스하키 : 35232


    $tb = "gamelist a LEFT JOIN gameitem c ON a.GI_Key = c.GI_Key LEFT JOIN gameleague b ON a.GL_Key = b.GL_Key_IDX";

    $view_article = 30; // 한화면에 나타날 게시물의 총 개수
    if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
    $start = ($_GET['page']-1)*$view_article;
    $href = "&teamName={$_GET['teamName']}&tb={$_GET['tb']}&search_item={$search_item}&gameType={$gameType}&glkey={$glkey}";
    $where = " 1 AND G_Locked =  '3' AND G_ResultScoreWin IS NOT NULL AND G_ResultScoreLose IS NOT NULL   ";

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

    $where .= " AND DATE_FORMAT(G_Datetime,'%Y-%m-%d') BETWEEN '{$startDate}' AND '{$endDate}' ";
    if(!empty($teamName)){
        $where .= " AND (a.G_Team1 LIKE '%{$teamName}%' OR a.G_Team2 LIKE '%{$teamName}%') ";
    }
    if(!empty($search_order)){
        $where .= " ORDER BY '{$search_order}' ASC ";
    } else {
        $order_by = " ORDER BY G_Datetime DESC, inPlayMatchIdx ASC, G_Type1 ASC,G_Type2 DESC  ";
    }


    $query = "SELECT COUNT(*) FROM {$tb} WHERE {$where} ";
    /*echo $query;*/
    $row = getRow($query);
    $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함



?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(8)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">경기결과</div>
                <div class="title2">GAME RESULT</div>
            </div>
            <div class="game_result_wrap">
                <div class="result_s_choice">
                    <div class="title">
                        스포츠게임 결과
                        <ul>
                            <li onChange="location.href='/result/sports/'+this.value">
                                <select>
                                    <option onclick="location.href='./?search_item=';">스포츠게임선택</option>
                                    <option value="6046" <?php echo ($search_item=6046)?'selected':''; ?> onclick="location.href='./?search_item=6046';">축구</option>
                                    <option value="48242" <?php echo ($search_item=48242)?'selected':''; ?> onclick="location.href='./?search_item=48242';">농구</option>
                                    <option value="154914" <?php echo ($search_item=154914)?'selected':''; ?> onclick="location.href='./?search_item=154914';">야구</option>
                                    <option value="154830" <?php echo ($search_item=154830)?'selected':''; ?> onclick="location.href='./?search_item=154830';">배구</option>
                                    <option value="35232" <?php echo ($search_item=35232)?'selected':''; ?> onclick="location.href='./?search_item=35232';">아이스스하키</option>
                                </select>
                            </li>
                            <li>
                                <select name="minigame" onChange="location.href='/result/minigame/'+this.value">
                                    <option>미니게임선택</option>
                                    <option value="power" <?php echo ($minigame=='power')?'selected':''; ?>>파워볼게임</option>
                                    <option value="pwladder" <?php echo ($minigame=='pwladder')?'selected':''; ?>>파워사다리</option>
                                    <!--<option>스피드키노</option>-->
                                    <option value="kenoladder" <?php echo ($minigame=='kenoladder')?'selected':''; ?>>키노사다리</option>
                                </select>
                            </li>
                            <li>
                                <select onChange="location.href='/result/virtual/'+this.value">
                                    <option>가상게임선택</option>
                                    <option value="soccer" <?php echo ($minigame=='soccer')?'selected':''; ?>>가상축구</option>
                                    <option value="horse" <?php echo ($minigame=='horse')?'selected':''; ?>>가상경마</option>
                                    <option value="dog" <?php echo ($minigame=='dog')?'selected':''; ?>>가상개경주</option>
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="score_board_category">
                    <ul>
                        <li class="<?php echo ($_REQUEST['gameType']=='WDL')?'active':'';?>" onclick="location.href = './?gameType=WDL';">승무패<var></var></li>
                        <li class="<?php echo ($_REQUEST['gameType']=='Handicap')?'active':'';?>" onclick="location.href = './?gameType=Handicap';">핸디캡<var></var></li>
                        <li class="<?php echo ($_REQUEST['gameType']=='UnderOver')?'active':'';?>" onclick="location.href = './?gameType=UnderOver';">언오버<var></var></li>
                        <li class="<?php echo ($_REQUEST['gameType']=='Special')?'active':'';?>" onclick="location.href = './?gameType=Special';">스페셜<var></var></li>
                        <li class="<?php echo ($_REQUEST['gameType']=='FirstTime')?'active':'';?>" onclick="location.href = './?gameType=FirstTime';">전반전<var></var></li>
                        <!--<li>코너킥<var></var></li>-->
                    </ul>
                </div>

                <div class="result_s_choice">

                    <ul class="result_s_list">
                        <li title="ALL" class="e1 <?php echo ($_REQUEST['search_item']=='')?'on':'';?>" onclick="location.href='./';"></li>
                        <li title="축구" class="e2 <?php echo ($_REQUEST['search_item']=='6046')?'on':'';?>" onclick="location.href='./?search_item=6046';"></li>
                        <li title="농구" class="e3 <?php echo ($_REQUEST['search_item']==48242)?'on':'';?>" onclick="location.href='./?search_item=48242';"></li>
                        <li title="야구" class="e4 <?php echo ($_REQUEST['search_item']==154914)?'on':'';?>" onclick="location.href='./?search_item=154914';"></li>
                        <li title="배구" class="e5 <?php echo ($_REQUEST['search_item']==154830)?'on':'';?>" onclick="location.href='./?search_item=154830';"></li>
                        <li title="아이스하키" class="e7 <?php echo ($_REQUEST['search_item']==35232)?'':'';?>" onclick="location.href='./?search_item=35232';"></li>
                        <li title="테니스" class="e6"></li>
                        <li title="미식축구" class="e9 "></li>
                        <li title="E스포츠" class="e10 "></li>

                    </ul>
                </div>

                <form name="f" id="f" action="./" method="get">
                <div class="result_search">
                    <div class="line1">
                        <span>리그선택</span>
                        <select class="input1" name="glkey">
                            <option value="" selected>리그선택</option>
                            <?php
                                $sql = "SELECT * FROM gameleague WHERE GL_Key_IDX < 99999 ORDER BY GL_Type ASC";
                                //echo $sql;
                                $arr = getArr($sql);
                                foreach($arr as $list){
                            ?>
                            <option value="<?php echo $list['GL_Key_IDX']; ?>" <?php echo ($list['GL_Key']==$glkey)?'selected':'';?>><?php echo $list['GL_Type']; ?></option>
                            <?php } ?>
                        </select>
                        <span>팀명</span>
                        <input type="text" name="teamName" placeholder="텍스트검색"  class="input2" value="<?php echo $teamName;?>">
                    </div>
                    <div class="line2">
                        <span>기간선택</span>
                        <input type="text" name="startDate" id="startDate" value="<?php echo $startDate; ?>" class="input3"> ~ <input type="text" name="endDate" id="endDate" value="<?php echo $endDate; ?>" class="input3">
                        <ul>
                            <li class="on" data-day="<?php echo date("Y-m-d"); ?>">오늘</li>
                            <li data-day="<?php echo date("Y-m-d",strtotime("-7 day")); ?>">1주일</li>
                            <li data-day="<?php echo date("Y-m-d",strtotime("-15 day")); ?>">15일</li>
                            <li data-day="<?php echo date("Y-m-d",strtotime("-1 month")); ?>">1개월</li>
                            <li data-day="<?php echo date("Y-m-d",strtotime("-3 month")); ?>">3개월</li> &nbsp;
                        </ul>
                    </div>
                    <input type="submit" value="검색하기" class="input4">
                </div>
                </form>
                <div class="result_list">
                    <div class="title">
                        <span>경기결과</span><var>|</var><em>축구</em>
                        <select>
                            <option>전체보기</option>
                            <option>전체보기</option>
                            <option>전체보기</option>
                            <option>전체보기</option>
                        </select>
                    </div> <!-- Live Betting Top -->


                    <div class="bl_live_betting_middle">
                        <table>
                            <thead>
                            <tr>
                                <td width="109">경기일시</td>
                                <td width="70">구분</td>
                                <td width="90">마켓명</td>
                                <td width="290">승 (홈팀) <img src="/img/icon_up.png"  class="blink"/></td>
                                <td class="min_space"></td>
                                <td>무/핸/합</td>
                                <td class="min_space"></td>
                                <td width="290">패 (원정) <img src="/img/icon_down.png"  class="blink"/></td>
                                <td width="60">점수</td>
                                <td width="100" style="min-width:100px">결과</td>
                            </tr>
                            </thead>
                            <tbody>
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
                                        $win_bet[$ct] = 'active';
                                        $gresult = "홈팀승";
                                        $result_css = "on";
                                    } else if($rs['G_ResultWDL']=='Draw') {
                                        $draw_bet[$ct] = 'active';
                                        $gresult = "무승부";
                                        $result_css = "state";
                                    } else if($rs['G_ResultWDL']=='Lose') {
                                        $lose_bet[$ct] = 'active';
                                        $gresult = "원정승";
                                        $result_css = "off";
                                    }

                                } else if (in_array($rs['G_SubType'], array(482646, 1548352, 6878952, 1549366, 1548502, 1548503, 1548504, 1549375))) {//승패
                                    //echo "승패";
                                    $win_rate[$ct] = $rs['G_QuotaWin'];
                                    $draw_rate[$ct] = 'VS';
                                    $lose_rate[$ct] = $rs['G_QuotaLose'];
                                    if($rs['G_ResultWDL']=='Win') {
                                        $win_bet[$ct] = 'active';
                                        $gresult = "홈팀승";
                                        $result_css = "on";
                                    } else if($rs['G_ResultWDL']=='Lose') {
                                        $lose_bet[$ct] = 'active';
                                        $gresult = "원정승";
                                        $result_css = "off";
                                    }

                                } else if (in_array($rs['G_SubType'], array(352322, 1548302, 1549168, 60462, 482448, 1549161, 482441, 352341, 1548345, 482465, 352365, 1548346, 482466, 352366, 482467, 1549376, 1548465, 1549360, 482522, 60562, 60481, 1549361, 482521, 60561, 60505))) {//언더오버
                                    //echo "오버/언더";
                                    $draw_bet = '';
                                    $win_rate[$ct] = $rs['G_QuotaOver'];
                                    $draw_rate[$ct] = $rs['G_QuotaUnderOver'];
                                    $lose_rate[$ct] = $rs['G_QuotaUnder'];

                                    if($rs['G_ResultUnderOver']=='Over') {
                                        $win_bet[$ct] = 'active';
                                        $gresult = "오버";
                                        $result_css = "on";
                                    } else if($rs['G_ResultUnderOver']=='Under') {
                                        $lose_bet[$ct] = 'active';
                                        $gresult = "언더";
                                        $result_css = "off";
                                    }

                                    $over_icon = '<em class="blink"><img src="/img/pop_live_icon3.png"></em>';
                                    $under_icon = '<em class="blink"><img src="/img/pop_live_icon4.png"></em>';
                                } else {
                                    $draw_bet = '';
                                    //echo "핸디캡";
                                    $win_rate[$ct] = $rs['G_QuotaHandiWin'];
                                    $draw_rate[$ct] = $rs['G_QuotaHandicap'];
                                    $lose_rate[$ct] = $rs['G_QuotaHandiLose'];
                                    if($rs['G_ResultHandicap']=='HandiWin') {
                                        $win_bet[$ct] = 'active';
                                        $gresult = "핸승";
                                        $result_css = "on";
                                    } else if($rs['G_ResultHandicap']=='HandiLose') {
                                        $lose_bet[$ct] = 'active';
                                        $gresult = "핸패";
                                    }
                                }
                                if($cur_gid != $rs['GL_Key'] || $cur_name != $rs['G_Team1'] ){
                                    if(empty($cur_gid))   $cur_gid = $rs['GL_Key'];
                                    if(empty($cur_name))  $cur_name = $rs['G_Team1'];

                            ?>
                                <tr> <!-- TITLE -->
                                    <th colspan="10">
                                        <h1>
                                            &nbsp; <img src="/img/icon_<?php echo $ITEMICON[$rs['GI_Key']];?>.png" style="width:22px; margin-top:1px" /><img src="/img/league/<?php echo $rs['GL_SrvName'];?>" style="height:16px; margin-top:4px"/>&nbsp;<span style="color:#fff;"><?php echo $rs['GL_Type'];?></span>
                                        </h1>
                                    </th>
                                </tr>
                                    <?php $cur_gid = $rs['GL_Key']; $cur_name = $rs['G_Team1'];} ?>
                            <tr> <!-- LINE -->
                                <td title="경기일시" class="time"><?php echo date("m/d H:i",strtotime($rs['G_Datetime'])); ?></td>
                                <td title="경기일시" class="time"><?php echo ($rs['G_Type1']=='Special')?'스페셜':'풀타임'; ?></td>
                                <td title="경기일시" class="time"><?php echo $rs['G_MarketNameKor']; ?></td>
                                <td title="승/홈/오버">
                                    <div class="bl_btn bl_text_btn long <?php echo $win_bet[$ct]; ?>">
                                        <span class="left"><?php echo mb_substr($rs['G_Team1'], 0, 30, 'utf-8'); ?></span>
                                        <span class="right"><?php echo $over_icon;?><?php echo number_format($win_rate[$ct], 2); ?></span>
                                    </div>
                                </td>
                                <td class="min_space"></td>
                                <td title="무/핸/합">
                                    <div class="bl_btn bl_text_btn middle <?php echo $draw_bet[$ct]; ?>">
                                        <?php
                                        echo $draw_rate[$ct];
                                        ?>
                                    </div>
                                </td>
                                <td class="min_space"></td>
                                <td title="패/원정/언더">
                                    <div class="bl_btn bl_text_btn long <?php echo $lose_bet[$ct]; ?>">
                                        <span class="left"><?php echo number_format($lose_rate[$ct], 2); ?><?php echo $under_icon;?></span>
                                        <span class="right"><?php echo mb_substr($rs['G_Team2'], 0, 30, 'utf-8'); ?></span>
                                    </div>
                                </td>
                                <td class="point" style="color:#fff;"><?php echo $rs['G_ResultScoreWin']; ?> : <?php echo $rs['G_ResultScoreLose']; ?></td>
                                <td class="state"><div class="<?php echo $result_css;?>"><?php echo $gresult; ?></div></td>
                            </tr>

                            <?php }} else { ?>
                                <tr><td colspan="8" class="text-center" style="color:#fff; font-size:14px; padding:10px;">등록된 경기결과가 없습니다.</td></tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                    <?php
                    if($total_article>0) {
                        include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
                    }
                    ?>
                </div>

            </div>
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
    include_once $root_path.'/include/footer.php';
?>