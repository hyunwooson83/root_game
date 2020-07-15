<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

if(empty($page)) {
    setQry("DELETE FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'");
}

if($chkMobile == true){
    move('/m/main/?mobile=Y');
}


//축구 : 6046 농구 : 48242 야구 : 154914 배구 : 154830 아이스하키 : 35232
$total_item = 0;



$tb = "gamelist a LEFT JOIN gameitem c ON a.GI_Key = c.GI_Key  LEFT JOIN gameleague b ON a.GL_Key = b.GL_Key_IDX ";

$view_article = 30; // 한화면에 나타날 게시물의 총 개수
if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
$start = ($_GET['page']-1)*$view_article;
$href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}&search_item={$search_item}";
$where = " 1 AND G_State = 'Await' AND G_Datetime > NOW() AND G_OType1 = 'S' AND b.GL_State = 'Normal' AND b.GL_Key NOT IN (439)  ";

//$where .= " AND (G_QuotaHandiWin > {$SITECONFIG['sport_rate_base']} AND G_QuotaHandiLose > {$SITECONFIG['sport_rate_base']} ";
//$where .= " OR G_QuotaUnder > {$SITECONFIG['sport_rate_base']} AND G_QuotaOver > {$SITECONFIG['sport_rate_base']} OR G_QuotaWin > {$SITECONFIG['sport_rate_base']} AND G_QuotaLose > {$SITECONFIG['sport_rate_base']}  ) ";
if(!empty($search_text)){
    $where .= " AND (B_Subject LIKE '%{$search_text}%' OR B_Content LIKE '%{$search_text}%') ";
}

if(!empty($search_item)){
    $where .= " AND a.GI_Key  = '{$search_item}' ";
} else {
    //$where .= " AND a.GI_Key IN (6046,35232,48242,154830,154914) ";
}

if(!empty($search_order)){
    $where .= " ORDER BY '{$search_order}' ASC ";
} else {

    //$order_by = " ORDER BY G_Datetime ASC, inPlayMatchIdx ASC, G_Seq ASC  ";
    $order_by = " ORDER BY G_Datetime ASC, inPlayMatchIdx ASC, G_Seq ASC, G_MainType ASC  ";
}


$query = "SELECT COUNT(inPlayMatchIdx) FROM {$tb} WHERE {$where} ";
//echo $query;
$row = getRow($query);
$total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함


$que1 = "SELECT a.GI_Key, COUNT(DISTINCT(inPlayMatchIdx)) AS cnt  FROM {$tb} WHERE {$where}  GROUP BY a.GI_Key";
//echo $que1;
$item_cnt = getArr($que1);
if($item_cnt>0) {
    foreach ($item_cnt AS $ic) {
        $item_info['cnt'][$ic['GI_Key']] = $ic['cnt'];
        $total_item += $ic['cnt'];
    }
}

if(empty($LEVELLIMITED['Sports_Max_Bet_Money']) || empty($LEVELLIMITED['Sports_Max_Hit_Mone'])){
    $que = "SELECT * FROM level_limited WHERE L_Level = '{$_SESSION['S_Key']}'";
    $row1 = getRow($que);
    $LEVELLIMITED['Sports_Max_Bet_Money'] = $row1['Sports_Max_Bet_Money'];
    $LEVELLIMITED['Sports_Max_Hit_Mone'] = $row1['Sports_Max_Hit_Mone'];
}
?>

<script>
    $(document).ready(function(){
        $(".sub_header > .top2 > li:nth-child(2)").addClass('active');
    });
</script>

<div class="sub_wrap" style="width: 1300px;">
    <div class="sub_con">
        <?php include_once $_SERVER['DOCUMENT_ROOT']."/include/left_wing.php"; ?>
        <div class="center_con">
            <div class="score_board_category">
                <ul>
                    <!-  매뉴 페이지 연결 -->
                    <li onclick="swal('','라이브 게임은 준비중입니다.','success');">라이브<var></var></li>
                    <li onclick="location.href='/game/sports/cross/'">조합<var></var></li>
                    <li onclick="location.href='/game/sports/special/'" class="active">스페셜<var></var></li>
                    <li onclick="location.href='/game/sports/WDL/'">승무패<var></var></li>
                    <li onclick="location.href='/game/sports/handicap/'">핸디캡<var></var></li>
                    <li onclick="location.href='/game/sports/underover/'">언오버<var></var></li>
                    <!--<li onclick="location.href='/game/sports/firsttime/'">전반전<var></var></li>-->
                    <!--<li onclick="location.href='./coner.php'">코너킥<var></var></li>-->
                </ul>
            </div>

            <div class="score_board_contents">
                <div class="root_display">
                    <font>크로스</font>
                    <ul>
                        <li class="<?php echo ($search_order=='G_Datetime' || empty($search_order))?'active':'';?>" onclick="location.href = './?search_order=G_Datetime';">마감순정렬</li>
                        <!--<li class="<?php /*echo ($search_order=='GL_Key')?'active':'';*/?>" onclick="location.href = './';" onclick="location.href = './?search_order=GL_Key';">리그별정렬</li>-->
                    </ul>
                </div> <!-- Root Display -->
                <style>
                    div.score_board_sub_category > table.five > tr > td { width: 16.6% !important;}
                </style>
                <div class="score_board_sub_category">
                    <table class="five">
                        <tr>
                            <td class="<?php echo empty($search_item)?'active':'';?>" style="width:16.6%;" onclick="location.href = './';">
                                <img src="/img/icon_all.png" />
                                <span>전체</span>
                                <em id="item_total">(<?php echo $total_item;?>)</em>
                                <var></var>
                            </td>
                            <td class="<?php echo ($search_item==6046)?'active':'';?>" style="width:16.6%;" onclick="location.href = './?search_item=6046';">
                                <img src="/img/icon_soccer.png" />
                                <span>축구</span>
                                <em id="item_soccer">(<?php echo ($item_info['cnt'][6046]>0)?$item_info['cnt'][6046]:'0';?>)</em>
                                <var></var>
                            </td>
                            <td class="<?php echo ($search_item==48242)?'active':'';?>" style="width:16.6%;" onclick="location.href = './?search_item=48242';">
                                <img src="/img/icon_basketball.png" />
                                <span>농구</span>
                                <em id="item_basketball">(<?php echo ($item_info['cnt'][48242]>0)?$item_info['cnt'][48242]:'0';?>)</em>
                                <var></var>
                            </td>
                            <td class="<?php echo ($search_item==154914)?'active':'';?>" style="width:16.6%;" onclick="location.href = './?search_item=154914';">
                                <img src="/img/icon_baseball.png" />
                                <span id="item_baseball">야구</span>
                                <em>(<?php echo ($item_info['cnt'][154914]>0)?$item_info['cnt'][154914]:'0'?>)</em>
                                <var></var>
                            </td>
                            <td class="<?php echo ($search_item==154830)?'active':'';?>" style="width:16.6%;" onclick="location.href = './?search_item=154830';">
                                <img src="/img/icon_volley.png" />
                                <span>배구</span>
                                <em id="volleyball">(<?php echo ($item_info['cnt'][154830]>0)?$item_info['cnt'][154830]:'0';?>)</em>
                                <var></var>
                            </td>
                            <td class="<?php echo ($search_item==35232)?'active':'';?>" style="width:16.6%;" onclick="location.href = './?search_item=35232';">
                                <img src="/img/icon_hockey.png" />
                                <span>하키</span>
                                <em id="hockey">(<?php echo ($item_info['cnt'][35232]>0)?$item_info['cnt'][35232]:'0';?>)</em>
                                <var></var>
                            </td>
                        </tr>
                    </table>

                </div> <!-- Score Board Sub Category -->




                <div class="bl_live_betting_middle cross cross-bet-box">
                    <table>
                        <thead>
                        <tr>
                            <td width="25" class="bl_first_box"></td>
                            <td width="84">경기일시</td>
                            <td width="134" style="min-width:104px">정보</td>
                            <td width="274">승 (홈팀) <!--<img src="/img/icon_up.png"  class="blink"/>--></td>
                            <td class="min_space"></td>
                            <td>무/핸/합</td>
                            <td class="min_space"></td>
                            <td width="274">패 (원정) <!--<img src="/img/icon_down.png"  class="blink"/>--></td>

                        </tr>
                        </thead>

                        <tbody>

                        <!-- 경기목록 시작 -->
                        <?php
                        $cur_gid = "";
                        $cur_name = "";
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
                                        
                                        
                                    ";

                            //echo $que;
                            $arr = getArr($que);
                        if(count($arr)>0){
                        foreach($arr as $rs){
                        if($cur_gid != $rs['GL_Key'] || $cur_name != $rs['G_Team1'] ){
                            if(empty($cur_gid))   $cur_gid = $rs['GL_Key'];
                            if(empty($cur_name))  $cur_name = $rs['G_Team1'];
                            ?>
                            <tr>
                                <!-- 리그시작  -->
                                <th colspan="9">
                                    <h1>
                                        &nbsp; <img src="/img/icon_<?php echo $ITEMICON[$rs['GI_Key']];?>.png" style="width:22px; margin-top:1px" /><?php //echo $rs['GL_Key'];?><img src="/img/league/<?php echo (!empty($rs['GL_SrvName']))?$rs['GL_SrvName']:'noimage.png';?>" style="height:16px; margin-top:4px"/>&nbsp;<span style="color:#fff;"><?php echo $rs['GL_Type'];?></span>
                                        <ul class="bl_title_btn">

                                            <!--<li class="bl_btn short">
                                                <div class="alert_btn active">
                                                    <img src="/img/icon_alert_on.png" />
                                                </div>
                                            </li>
                                            <li class="bl_btn short">
                                                <div class="ground_btn">
                                                    <img src="/img/icon_ground_on.png" />
                                                </div>
                                            </li>-->
                                        </ul>
                                    </h1>
                                </th>
                            </tr>
                            <!-- 리그 끝 -->
                            <?php $cur_gid = $rs['GL_Key']; $cur_name = $rs['G_Team1'];} ?>

                            <tr>
                                <!-- LINE 승무패-->
                                <td title="즐겨찾기" class="bl_first_box">
                                    <div class="favorite_btn">
                                        <img src="/img/icon_favorite_on.png" style="width:20px;"/>
                                    </div>
                                </td>
                                <td title="경기일시" class="time"><?php echo date("m/d H:i",strtotime($rs['G_Datetime'])); ?> <?php //echo $rs['G_Key'];?></td>
                                <td>
                                    <div class=" short2">
                                        <div class="cross_btn">
                                                    <span class="left">
                                                        <?php
                                                        echo ($rs['G_MarketNameKor']!='')?$rs['G_MarketNameKor']:$rs['G_MarketName'];
                                                        ?>
                                                    </span>
                                            <span class="right"><?php incluce_extend($rs['G_SubType']); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <?php
                                $over_icon = '';
                                $under_icon = '';
                                if (in_array($rs['G_SubType'], array(60461, 352321, 1549181, 482461, 352361, 482462, 352362, 482463, 352363, 482464, 352364, 60501, 60502))) {//승무패
                                    //echo "승무패";
                                    $win_rate[$ct] = $rs['G_QuotaWin'];
                                    $draw_rate[$ct] = $rs['G_QuotaDraw'];
                                    $lose_rate[$ct] = $rs['G_QuotaLose'];
                                    $win_bet[$ct] = 'Win';
                                    $draw_bet[$ct] = 'Draw';
                                    $lose_bet[$ct] = 'Lose';
                                    $win_select_css[$ct] = 'win-'.$rs['G_Key'];
                                    $draw_select_css[$ct] = 'draw-'.$rs['G_Key'];
                                    $lose_select_css[$ct] = 'lose-'.$rs['G_Key'];
                                } else if (in_array($rs['G_SubType'], array(482646, 1548352, 6878952, 1549366, 1548502, 1548503, 1548504, 1549375))) {//승패
                                    //echo "승패";
                                    $win_rate[$ct] = $rs['G_QuotaWin'];
                                    $draw_rate[$ct] = 'VS';
                                    $lose_rate[$ct] = $rs['G_QuotaLose'];
                                    $win_bet[$ct] = 'Win';
                                    $draw_bet[$ct] = '';
                                    $lose_bet[$ct] = 'Lose';
                                    $win_select_css[$ct] = 'win-'.$rs['G_Key'];
                                    $draw_select_css[$ct] = '';
                                    $lose_select_css[$ct] = 'lose-'.$rs['G_Key'];
                                } else if (in_array($rs['G_SubType'], array(352322, 1548302, 1549168, 60462, 482448, 1549161, 482441, 352341, 1548345, 482465, 352365, 1548346, 482466, 352366, 482467, 1549376, 1548465, 1549360, 482522, 60562, 60481, 1549361, 482521, 60561, 60505))) {//언더오버
                                    //echo "오버/언더";
                                    $win_rate[$ct] = $rs['G_QuotaOver'];
                                    $draw_rate[$ct] = $rs['G_QuotaUnderOver'];
                                    $lose_rate[$ct] = $rs['G_QuotaUnder'];
                                    $win_bet[$ct] = 'Over';
                                    $draw_bet[$ct] = '';
                                    $lose_bet[$ct] = 'Under';
                                    $win_select_css[$ct] = 'over-'.$rs['G_Key'];
                                    $draw_select_css[$ct] = '';
                                    $lose_select_css[$ct] = 'under-'.$rs['G_Key'];
                                    $over_icon = '<em class="blink"><img src="/img/pop_live_icon3.png"></em>';
                                    $under_icon = '<em class="blink"><img src="/img/pop_live_icon4.png"></em>';
                                } else {
                                    //echo "핸디캡";
                                    $win_rate[$ct] = $rs['G_QuotaHandiWin'];
                                    $draw_rate[$ct] = $rs['G_QuotaHandicap'];
                                    $lose_rate[$ct] = $rs['G_QuotaHandiLose'];
                                    $win_bet[$ct] = 'HandiWin';
                                    $draw_bet[$ct] = '';
                                    $lose_bet[$ct] = 'HandiLose';
                                    $win_select_css[$ct] = 'hwin-'.$rs['G_Key'];
                                    $draw_select_css[$ct] = '';
                                    $lose_select_css[$ct] = 'hlose-'.$rs['G_Key'];
                                }
                                ?>
                                <td title="승/홈/오버">
                                    <div class="bl_btn bl_text_btn long betting-btn <?php echo $win_select_css[$ct];?>" data-bet="<?php echo $win_bet[$ct];?>" data-gkey="<?php echo $rs['G_Key']; ?>" data-glist="<?php echo $rs['inPlayMatchIdx']; ?>" data-gtype="<?php echo $rs['G_Type1']; ?>" data-bet_cnt="<?php echo member_game_bet_cnt($_SESSION['S_Key'],$rs['G_Key'],$win_bet[$ct]);?>">
                                        <span class="left"><?php echo mb_substr($rs['G_Team1'], 0, 30, 'utf-8'); ?></span>
                                        <span class="right"><?php echo $over_icon;?><?php echo number_format($win_rate[$ct], 2); ?></span>
                                    </div>
                                </td>
                                <td class="min_space"></td>
                                <td title="무/핸/합">
                                    <div class="bl_btn bl_text_btn middle <?php echo $draw_select_css[$ct];?> <?php echo ($draw_rate[$ct] > 0) ? 'betting-btn' : ''; ?>" data-bet="<?php echo $draw_bet[$ct];?>" data-gkey="<?php echo $rs['G_Key']; ?>" data-gtype="<?php echo $rs['G_Type1']; ?>" data-glist="<?php echo $rs['inPlayMatchIdx']; ?>" data-bet_cnt="<?php echo member_game_bet_cnt($_SESSION['S_Key'],$rs['G_Key'],'Draw');?>">
                                        <?php
                                        echo (!empty($draw_rate[$ct]))?$draw_rate[$ct]:'VS';
                                        ?>
                                    </div>
                                </td>
                                <td class="min_space"></td>
                                <td title="패/원정/언더" style="padding-right:8px;">
                                    <div class="bl_btn bl_text_btn long betting-btn <?php echo $lose_select_css[$ct];?>" data-bet="<?php echo $lose_bet[$ct];?>" data-gkey="<?php echo $rs['G_Key']; ?>" data-gtype="<?php echo $rs['G_Type1']; ?>" data-glist="<?php echo $rs['inPlayMatchIdx']; ?>" data-bet_cnt="<?php echo member_game_bet_cnt($_SESSION['S_Key'],$rs['G_Key'],$lose_bet[$ct]);?>">
                                        <span class="left"><?php echo number_format($lose_rate[$ct], 2); ?><?php echo $under_icon;?></span>
                                        <span class="right"><?php echo mb_substr($rs['G_Team2'], 0, 30, 'utf-8'); ?></span>
                                    </div>
                                </td>
                            </tr>



                            <!-- 추가 기준점 끝 -->

                        <?php $ct++;}}} else {
                        ?>
                            <script>swal('','등록된 경기가 없습니다.','warning'); setTimeout(function(){ location.href = './';}, 2000)</script>
                            <?
                        } ?>
                        <!-- 경기목록 끝 -->
                        </tbody>
                    </table>
                </div> <!-- Live Betting Middle -->
                <?php
                if($total_article>0) {
                    //include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
                }
                ?>
            </div> <!-- Bet List Left -->
        </div> <!-- Center Container -->

        <!-- 카트 시작 -->
        <?php include $_SERVER['DOCUMENT_ROOT']."/include/cart.php"; ?>
        <!-- Right Container -->
        <!-- 카트 끝 -->
    </div> <!-- Sub Container -->
</div>
<div class="wrap_mask"></div>
<script>
    var config_bet_bound_min = parseInt("10000", 10);
    var config_bet_bound_max = parseInt("<?php echo $LEVELLIMITED['Sports_Max_Bet_Money']; ?>", 10);
    var config_bet_reward_max =  parseInt("<?php echo $LEVELLIMITED['Sports_Max_Hit_Mone']; ?>", 10);
    var config_max_bet_cnt = parseInt("<?php echo $LEVELLIMITED['Sports_Max_Bet_Cnt']; ?>", 10);
    var same_bet_cnt = parseInt("<?php echo $SITECONFIG['base_bet_cnt'];?>",10)-1;
    var same_bet_max = parseInt("<?php echo $SITECONFIG['base_bet_max'];?>",10);
    var same_hit_max = parseInt("<?php echo $SITECONFIG['base_hit_max'];?>",10);
    var config_max_bet_rate = parseInt("<?php echo $SITECONFIG['sport_max_rate']; ?>", 10);
    var one_folder_yn = '<?php echo $meminfo['M_One_Stop'];?>';
    var two_folder_yn = '<?php echo $meminfo['M_Two_Stop'];?>';
</script>
<script>
    loadingCart();

    function call_back(){
        swal({
            text: "배팅이 정상적으로 완료되었습니다.",
            type: "success",
            confirmButtonText: "확인",
        }).then(function(isConfirm) {
            if (isConfirm) {
                location.reload();
            }
        });
    }


    $(document).ready(function(){

        $('.search-league').on('click',function(){
            var glkey = $(this).data('glkey');
            var gikey = $(this).data('gikey');
            $('#glkey').val(glkey);
            $('#search_item').val(gikey);
            document.sf.submit();
        });
        $('.show-sub-page').on('click',function(){
            var key = $(this).data('key');
            if($('#sub_page_'+key).hasClass('d-hide') == true){
                $('#sub_page_'+key).removeClass('d-hide').addClass('d-show');
            } else {
                $('#sub_page_'+key).removeClass('d-show').addClass('d-hide');
            }
        });
    });


</script>


<?php include_once($root_path.'/include/footer.php'); ?>
