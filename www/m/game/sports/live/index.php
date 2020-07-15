<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

setQry("DELETE FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'");

//축구 : 6046 농구 : 48242 야구 : 154914 배구 : 154830 아이스하키 : 35232
$total_item = 0;
$que = "SELECT GI_Key, COUNT(DISTINCT(inPlayMatchIdx)) AS cnt FROM gamelist WHERE G_Locked = 1 AND G_Datetime > NOW() AND GI_Key IN (6046,35232,48242,154830,154914) AND G_OType1= 'G' GROUP BY GI_Key";
//echo $que;
$item_cnt = getArr($que);
foreach($item_cnt AS $ic){
    $item_info['item'][] = $ic['GI_Key'];
    $item_info['cnt'][] = $ic['cnt'];
    $total_item += $ic['cnt'];
}

$tb = "gamelist a LEFT JOIN gameitem c ON a.GI_Key = c.GI_Key LEFT JOIN gameleague b ON a.GL_Key = b.GL_Key_IDX";

$view_article = 30; // 한화면에 나타날 게시물의 총 개수
if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
$start = ($_GET['page']-1)*$view_article;
$href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}&search_item={$search_item}";
$where = " 1 AND G_Locked =  '1' AND G_Datetime > NOW() AND G_QuotaWin > 0 AND G_SubType IN (60461,352321,482646,1549366,1548352) AND G_OType1 = 'G' ";
if(!empty($search_text)){
    $where .= " AND (G_Team1 LIKE '%{$search_text}%' OR G_Team2 LIKE '%{$search_text}%') ";
}

if(!empty($search_item)){
    $where .= " AND a.GI_Key  = '{$search_item}' ";
} else {
    $where .= " AND a.GI_Key IN (6046,35232,48242,154830,154914) ";
}

if(!empty($glkey)){
    $where .= " AND b.GL_Key_IDX = '{$glkey}' ";
}

if(!empty($search_order)){
    $where .= " ORDER BY '{$search_order}' ASC ";
} else {
    $order_by = " ORDER BY G_Datetime ASC, inPlayMatchIdx ASC, G_Type2 DESC  ";
}


$query = "SELECT COUNT(DISTINCT(inPlayMatchIdx)) FROM {$tb} WHERE {$where} ";
//echo $query;
$row = getRow($query);
$total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함



?>


<div class="nmenu">
    <ul class="nmenu_cate sports">
        <li onclick="location.href='/m/game/sports/live/'" class="active">라이브</li>
        <li onclick="location.href='/m/game/sports/cross/'">크로스</li>
        <li onclick="location.href='/m/game/sports/special/'" class="">스페셜</li>
        <li onclick="location.href='/m/game/sports/WDL/'" class="">승무패</li>
        <li onclick="location.href='/m/game/sports/handicap/'" class="">핸디캡</li>
        <li onclick="location.href='/m/game/sports/underover/'" class="">언오버</li>

    </ul>
</div>



<div id="sub_wrap">

    <div class="sub_title">
        <h1>
            <span>라이브 베팅</span>
            <em>Live betting</em>
        </h1>
        <span><strong>마감순</strong><var>|</var>리그별<var>|</var>국가별</span>
    </div>
    <!-- 상단 종목 선택 { -->


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
            <dd>승(홈)오버 <var class="arr_down arr_wave blink">▼</var></dd>
            <dt>무/핸/합</dt>
            <dd>패(원정)언더 <var class="arr_up arr_wave">▲</var></dd>
        </dl>




        <!-- League start { -->
        <h1>
           현재 진행중인 라이브 경기가 없습니다.

        </h1>
        <ul>

            <!-- } 한경기 부분 -->


            <!-- } 한경기 부분 -->
        </ul>
        <!-- } League end -->




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
    var config_bet_bound_min = parseInt("5000", 10);
    var config_bet_bound_max = parseInt("<?php echo $LEVELLIMITED['Sports_Max_Bet_Money']; ?>", 10);
    var config_bet_reward_max =  parseInt("<?php echo $LEVELLIMITED['Sports_Max_Hit_Mone']; ?>", 10);
</script>



<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>
