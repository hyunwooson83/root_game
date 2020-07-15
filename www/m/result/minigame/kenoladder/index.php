<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';
// 로그인 체크
if ( !$_SESSION['S_Key'] ) {
    swal_move('로그인이 필요한 페이지 입니다.', 'login');
}


// 로그인 체크
if ( !$_SESSION['S_Key'] ) $lib->AlertMSG( "로그인이 필요한 페이지 입니다.","/" );

$tb = " gamelist_other  ";
$view_article = 15; // 한화면에 나타날 게시물의 총 개수
if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
$start = ($_GET['page']-1)*$view_article;
$href = "&startDate={$_GET['startDate']}&turn={$_GET['turn']}";

$where = " 1 AND GI_Key = 3 AND G_State IN ('Stop','End') ";

if(empty($startDate))   $startDate = date("Y-m-d");

if(!empty($turn)){
    $where .= " AND G_Num2 = '{$turn}' ";
}

#성명으로 정렬시
$order_by = " ORDER BY G_Datetime DESC, GL_Key ASC  ";

$query = "SELECT COUNT(DISTINCT G_Num2) FROM {$tb} WHERE {$where} {$order_by} ";
$row = getRow($query);
$total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함


?>
    <div id="sub_wrap">
        <div class="sub_title">
            <ul class="sub_title_category">
                <li onClick="location.href='/mobile/game_result.html'">스포츠</li>
                <li onClick="location.href='/mobile/game_result_mini.html'" class="active">미니게임</li>
                <li onClick="location.href='/mobile/game_result_virtual.html'">가상게임</li>
            </ul>
            <h1>
                <span>키노사다리 경기결과</span>
                <em>KINO LADDER RESULT</em>
            </h1>
        </div>
        <div class="sub_mypage_wrap">
            <div class="type_select" onclick="slide_type();">
                <em>키노사다리</em>
                <div><span id="type_select_arr"><img src="/mobile/img/type_select_arr.png"></span></div>
            </div>
            <ul id="type_select_l" class="type_select_list">
                <li onclick="location.href='/m/result/minigame/power/'">파워볼</li>
                <li onclick="location.href='/m/result/minigame/pwladder/'">파워사다리</li>
                <!--<li onclick="location.href='/m/result/minigame/power/'">스피드키노</li>-->
                <li onclick="location.href='/m/result/minigame/kenoladder/'" class="active">키노사다리</li>
            </ul>
            <!-- 경기검색 { -->
            <form action="./" method="get" name="f">
                <div class="sub_searchbox">
                    <div class="search">
                        <div>
                            <input type="text" name="startDate" id="startDate" value="<?php echo $startDate;?>" class="date" style="width: 70% !important;">
                            <select class="style_select" style="width:35%" name="turn">
                                <option value="">전체회차</option>
                                <?php
                                $que = "SELECT G_Num2, G_Num
FROM (SELECT * FROM gamelist_other
WHERE G_State
IN (
'Stop', 'End'
)
AND DATE_FORMAT( G_Datetime, '%Y-%m-%d' ) = '{$startDate}'
AND GI_Key =3 ORDER BY G_Num DESC) A
GROUP BY G_Num2";
                                echo $que;
                                $arr = getArr($que);
                                if(count($arr)>0){
                                    foreach($arr as $rs){
                                        ?>
                                        <option value="<?php echo $rs['G_Num2'];?>" <?php echo ($rs['G_Num2']==$turn)?'selected':'';?>><?php echo $rs['G_Num']; ?></option>
                                    <?php }} ?>
                            </select>
                            <a href="javascript:;" class="style_btn_confirm blue" style="padding:0.5em 1em " onclick="document.f.submit();">검색하기</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <table class="sub_board_list">
            <thead>
            <tr>
                <td>키노사다리 경기 결과리스트 <dfn>1,274</dfn></td>
            </tr>
            </thead>
            <tbody>
            <?php
            if($total_article > 0){
                $cnt = 0;
                $que = "
                                            SELECT 
                                                DISTINCT G_Num2, G_Datetime, G_Num
                                            FROM 
                                                {$tb} 
                                            WHERE 
                                                 {$where}
                                                 {$order_by}
                                            LIMIT 
                                                $start, $view_article 
                                           
                                        ";

                $arr = getArr($que);
                foreach($arr as $rs){
                    ?>
                    <tr>
                        <td style="padding:0">

                            <h1 class="sub_board_bet">
                                <span><img src="/mobile/img/sub/icon_mini.png"></span> 키노사다리
                                <var><?php echo date("m-d H:i",strtotime($rs['G_Datetime'])); ?></var>
                            </h1>
                            <!-- 경기결과 { -->
                            <table class="sub_board_bet pb">
                                <thead>
                                <tr>
                                    <td>게임일시</td>
                                    <td>회차</td>
                                    <td>결과</td>
                                    <td>배당률</td>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                $sql = "SELECT * FROM gamelist_other WHERE G_Num2 = '{$rs['G_Num2']}' AND DATE_FORMAT(G_Datetime,'%Y-%m-%d') = '{$startDate}'  ORDER BY GL_Key ASC LIMIT 5";
                                $list = getArr($sql);
                                foreach($list as $list){
                                    ?>
                                    <tr>
                                        <td><?php echo date("m-d H:i",strtotime($rs['G_Datetime'])); ?></td>
                                        <td><?php echo $rs['G_Num']; ?>회차 </td>
                                        <?php if($list['GL_Key']==11){ ?>
                                            <td><font class="mypage-grnfont">홀/짝 (<?php echo ($list['G_ResultOddEven']=='Odd')?'홀':'짝';?>)</font></td>
                                            <td><?php echo $list['G_QuotaOdd'];?></td>
                                        <?php } else if($list['GL_Key']==12){ ?>
                                            <td><font class="mypage-grnfont">출발점 (<?php echo ($list['G_ResultOddEven']=='Odd')?'좌':'우';?>)</font></td>
                                            <td><?php echo $list['G_QuotaOdd'];?></td>
                                        <?php } else if($list['GL_Key']==13){ ?>
                                            <td><font class="mypage-grnfont">출발점 (<?php echo ($list['G_ResultOddEven']=='Odd')?'3줄':'4줄';?>)</font></td>
                                            <td><?php echo $list['G_QuotaOdd'];?></td>
                                            <td><?php echo $rate;?></td>
                                        <?php } else if($list['GL_Key']==14 || $list['GL_Key']==15){ ?>
                                            <td><font class="mypage-grnfont">일반볼조합 (
                                                    <?php
                                                    if ($list['G_ResultOddEven'] != '' && $list['GL_Key'] == 14) {
                                                        if ($list['G_ResultOddEven'] == 'Even') echo '좌4홀';
                                                        else                                    echo '좌3짝';
                                                    } else if ($list['G_ResultOddEven'] != '' && $list['GL_Key'] == 15) {
                                                        if ($list['G_ResultOddEven'] == 'Even') echo '우4짝';
                                                        else                                    echo '우3홀';
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                    )</font></td>
                                            <td><?php echo $list['G_QuotaOdd'];?></td>
                                        <?php } ?>

                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <!-- } 경기결과 -->
                        </td>
                    </tr>
                <?php }} ?>
            </tbody>
        </table>

        <div class="sub_board" style="border-top:none">
            <?php
            if($total_article>0) {
                include_once($_SERVER['DOCUMENT_ROOT'] . "/m/lib/page.php");
            }
            ?>

        </div>

    </div>

<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php';
?>