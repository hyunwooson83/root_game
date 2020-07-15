<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

if(empty($page)) {
    setQry("DELETE FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'");
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


$query = "SELECT COUNT(DISTINCT(inPlayMatchIdx)) FROM {$tb} WHERE {$where} ";
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


<div class="nmenu">
    <ul class="nmenu_cate sports">
        <li onclick="location.href='#'">라이브</li>
        <li onclick="location.href='/m/game/sports/cross/'" class="">크로스</li>
        <li onclick="location.href='/m/game/sports/special/'" class="active">스페셜</li>
        <li onclick="location.href='/m/game/sports/WDL/'" class="">승무패</li>
        <li onclick="location.href='/m/game/sports/handicap/'" class="">핸디캡</li>
        <li onclick="location.href='/m/game/sports/underover/'" class="">언오버</li>
    </ul>
</div>

<div id="sub_wrap">

    <div class="sub_title">
        <h1>
            <span>스페셜 베팅</span>
            <em>Special betting</em>
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


    <div class="sports_list">

        <dl class="sports_list_top">
            <dd>승(홈)오버 <var class="arr_up arr_wave">▲</var></dd>
            <dt>무/핸/합</dt>
            <dd>패(원정)언더 <var class="arr_down arr_wave">▼</var></dd>
        </dl>




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
                                        LIMIT {$start}, {$view_article}
                                    ";
             echo $que;
            $arr = getArr($que);

        foreach($arr as $rs){
        if($cur_gid != $rs['GL_Key'] || $cur_name != $rs['G_Team1'] ){
            if(empty($cur_gid))   $cur_gid = $rs['GL_Key'];
            if(empty($cur_name))  $cur_name = $rs['G_Team1'];
            ?>


            <!-- League start { -->
            <h1>
                <span><img src="/img/icon_<?php echo $ITEMICON[$rs['GI_Key']];?>.png" /></span><img src="/img/league/<?php echo (!empty($rs['GL_SrvName']))?$rs['GL_SrvName']:'noimage.png';?>" style="height:16px; margin-top:4px"/><?php echo $rs['GL_Type'];?>
                <var><?php echo date("m/d H:i",strtotime($rs['G_Datetime'])); ?></var>

            </h1>
            <?php $cur_gid = $rs['GL_Key']; $cur_name = $rs['G_Team1'];} ?>
            <ul>
                <!-- 한경기 부분 { -->
                <!-- 승무패 시작 -->
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
                    $over_icon = '<var class="arr_up arr_wave">▲</var>';
                    $under_icon = '<var class="arr_down arr_wave">▼</var>';
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
                //echo $lose_select_css[$ct];
                ?>
                <li style="border-bottom:none">
                    <dl>
                        <dd class="betting-btn <?php echo $win_select_css[$ct];?>" data-rate="<?php echo $win_rate[$ct];?>" data-bet="<?php echo $win_bet[$ct];?>" data-gkey="<?php echo $rs['G_Key']; ?>" data-glist="<?php echo $rs['inPlayMatchIdx']; ?>" data-gtype="Special">
                            <div>
                                <span>
                                        <?php

                                        $gm = ($rs['G_MarketNameKor']!='')?$rs['G_MarketNameKor']:$rs['G_MarketName'];
                                        $gm = explode(" ",$gm);
                                        echo "[".$gm[1]."]";
                                        ?>
                                        <?php echo mb_substr($rs['G_Team1'],0,15,'utf-8');  ?>
                                </span>
                                <em><?php echo $over_icon;?>&nbsp;<?php echo number_format($win_rate[$ct],2); ?></em>
                            </div>
                        </dd>
                        <dd class="space"></dd>
                        <dd class="center">
                            <div>
                                <em>
                                    <?php
                                    echo (!empty($draw_rate[$ct]))?$draw_rate[$ct]:'VS';
                                    ?>
                                </em>
                            </div>
                        </dd>
                        <dd class="space"></dd>
                        <dd class=" betting-btn <?php echo $lose_select_css[$ct];?>" data-bet="<?php echo $lose_bet[$ct];?>" data-rate="<?php echo $lose_rate[$ct];?>" data-gkey="<?php echo $rs['G_Key']; ?>" data-glist="<?php echo $rs['inPlayMatchIdx']; ?>" data-gtype="Special">
                            <div class="right">
                                <em><?php echo number_format($lose_rate[$ct],2); ?><?php echo $under_icon;?></em>
                                <span><?php echo mb_substr($rs['G_Team2'],0,15,'utf-8'); ?></span>
                            </div>
                        </dd>
                    </dl>
                    <!-- 승무패 끝 -->
                </li>
                <!-- } 한경기 부분 -->
            </ul>
            <!-- } League end -->
        <?php $ct++;}} else {?>

            <script>swal('','검색(등록)된 데이터가 없습니다.','warning'); window.history.back();</script>

        <?php } ?>


        <div class="more_btn">
            <?php
            if($total_article>0) {
                include_once($_SERVER['DOCUMENT_ROOT'] . "/m/lib/page.php");
            }
            ?>
        </div>

    </div> <!-- sports_list -->


</div> <!-- Sub Wrap -->

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
    var config_bet_bound_min = parseInt("10000", 10);
    var config_bet_bound_max = parseInt("<?php echo $LEVELLIMITED['Sports_Max_Bet_Money']; ?>", 10);
    var config_bet_reward_max =  parseInt("<?php echo $LEVELLIMITED['Sports_Max_Hit_Mone']; ?>", 10);
    var config_max_bet_cnt = parseInt("<?php echo $LEVELLIMITED['Sports_Max_Bet_Cnt']; ?>", 10);
    var same_bet_cnt = parseInt("<?php echo $SITECONFIG['base_bet_cnt'];?>",10)-1;
    var same_bet_max = parseInt("<?php echo $SITECONFIG['base_bet_max'];?>",10);
    var same_hit_max = parseInt("<?php echo $SITECONFIG['base_hit_max'];?>",10);
    var one_folder_yn = '<?php echo $meminfo['M_One_Stop'];?>';
    var two_folder_yn = '<?php echo $meminfo['M_Two_Stop'];?>';
    var config_max_bet_rate = parseInt("<?php echo $SITECONFIG['sport_max_rate']; ?>", 10);
</script>
<script>


    /* Betcart */
    var betcart_state = 0;

    function betcart() {
        if (betcart_state == 0){
            $("#menu_cart").slideDown('fast');
            $("#footer").hide();
            $(".cart_title > span").text('베팅카트 닫기');
            $('html,body').animate({scrollTop:9999}, 400);
            betcart_state = 1;
        } else {
            $("#menu_cart").slideUp('fast');
            $("#footer").show();
            $(".cart_title > span").text('베팅카트 열기');
            $('html,body').animate({scrollTop:0}, 400);
            betcart_state = 0;
        }

    }
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
    function cartDel(cglkey){
        $.ajax({
            type : 'get',
            url : '/include/ajax.php',
            dataType : 'json',
            data : {'mode':'delCart','cartKey':cglkey},
            success : function(data){
                if(data.flag == true){
                    loadingCart();
                    //swal('','카트 경기가 삭제되었습니다.','success');
                } else {
                    swal('','카트 경기가 삭제시 오류 발생했습니다..','warning');
                }
            }
        });
    }

    $(document).ready(function(){

        $('.sub-game-toggle').on('click',function(){
            console.log('a')
            var glkey = $(this).data('gid');
            if($('.sub_game_'+glkey).hasClass('d-hide') == true){
                $('.sub_game_'+glkey).removeClass('d-hide').addClass('d-show');
            } else {
                $('.sub_game_'+glkey).removeClass('d-show').addClass('d-hide');
            }
        });
        $('.search-league').on('click',function(){
            var glkey = $(this).data('glkey');
            var gikey = $(this).data('gikey');
            $('#glkey').val(glkey);
            $('#search_item').val(gikey);
            document.sf.submit();
        });

    });
    loadingCart();

</script>


<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>
