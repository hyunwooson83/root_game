<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';
   

    $tb = " gamelist_other  ";
    $view_article = 15; // 한화면에 나타날 게시물의 총 개수
    if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
    $start = ($_GET['page']-1)*$view_article;
    $href = "&startDate={$_GET['startDate']}&turn={$_GET['turn']}";

    $where = " 1 AND GI_Key = 4 AND G_State IN ('Stop','End') ";

    if(empty($startDate)){
        $startDate = date("Y-m-d");
    }

    $where .= " AND DATE_FORMAT(G_Datetime,'%Y-%m-%d') = '{$startDate}'";
    if(!empty($turn)){
        $where .= " AND G_Num2 = '{$turn}' ";
    }

    #성명으로 정렬시
    $order_by = " ORDER BY G_Datetime DESC, GL_Key ASC  ";

    $query = "SELECT COUNT(DISTINCT G_Num2) FROM {$tb} WHERE {$where} {$order_by} ";
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
                        미니게임 결과
                        <ul>
                            <li onChange="location.href='/result/sports/'+this.value">

                                <select>
                                    <option value="">스포츠게임선택</option>
                                    <option value="6046" <?php echo ($search_item==6046)?'selected':''; ?> onclick="location.href='./?search_item=6046';">축구</option>
                                    <option value="48242" <?php echo ($search_item==48242)?'selected':''; ?> onclick="location.href='./?search_item=48242';">농구</option>
                                    <option value="154914" <?php echo ($search_item==154914)?'selected':''; ?> onclick="location.href='./?search_item=154914';">야구</option>
                                    <option value="154830" <?php echo ($search_item==154830)?'selected':''; ?> onclick="location.href='./?search_item=154830';">배구</option>
                                    <option value="35232" <?php echo ($search_item==35232)?'selected':''; ?> onclick="location.href='./?search_item=35232';">아이스스하키</option>
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

                    <ul class="result_s_list2">
                        <li class="active" onclick="location.href='/result/minigame/power/'">파워볼게임</li>
                        <li onclick="location.href='/result/minigame/pwladder/'">파워사다리</li>
                        <!--<li onclick="location.href='/_go/renewal/game_result_mini_sk.html'">스피드키노</li>-->
                        <li onclick="location.href='/result/minigame/kenoladder/'">키노사다리</li>
                    </ul>
                </div>
                <form action="./" method="get">
                <div class="result_search mini">

                    <div class="line3">
                        <ol>
                            <li>
                                <input type="text" name="startDate" id="startDate" value="<?php echo $startDate;?>" class="date">
                            </li>
                            <li>
                                <select name="turn">
                                    <option value="">전체회차</option>
                                    <?php
                                        $que = "SELECT G_Num2, G_Num
FROM (SELECT * FROM gamelist_other
WHERE G_State
IN (
'Stop', 'End'
)
AND DATE_FORMAT( G_Datetime, '%Y-%m-%d' ) = '{$startDate}'
AND GI_Key =4 ORDER BY G_Num DESC) A
GROUP BY G_Num2";
                                        echo $que;
                                        $arr = getArr($que);
                                        foreach($arr as $rs){
                                    ?>
                                    <option value="<?php echo $rs['G_Num2'];?>" <?php echo ($rs['G_Num2']==$turn)?'selected':'';?>><?php echo $rs['G_Num']; ?></option>
                                    <?php } ?>
                                </select>
                            </li>
                            <li><input type="submit" value="검색하기" class="input6"></li>
                        </ol>
                    </div>

                </div>
                </form>
                <div class="result_list">
                    <div class="title">
                        <span>경기결과</span><var>|</var><em>파워볼게임</em>
                        <select>
                            <option>전체보기</option>
                            <option>전체보기</option>
                            <option>전체보기</option>
                            <option>전체보기</option>
                        </select>
                    </div> <!-- Live Betting Top -->
                    <div class="bl_live_betting_middle">
                        <table class="table-black minigame normal">
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
                            //echo $que;
                            $arr = getArr($que);
                            foreach($arr as $rs){
                            ?>
                            <thead>
                                <tr> <!-- TITLE -->
                                    <th colspan="4">
                                        <!--<img src="/_go/renewal/img/icon_minigame.png" />--><span>파워볼게임</span><code><?php echo date("m-d",strtotime($rs['G_Datetime'])); ?><strong><?php echo date("H-i",strtotime($rs['G_Datetime'])); ?></strong></code>
                                    </th>
                                </tr>
                                <tr>
                                    <td>게임일시</td>
                                    <td>회차</td>
                                    <td>결과</td>
                                    <td>배당률</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $sql = "SELECT * FROM gamelist_other WHERE G_Num2 = '{$rs['G_Num2']}' ORDER BY GL_Key ASC LIMIT 8";
                                //echo $sql."<br>";
                                $list = getArr($sql);
                                foreach($list as $list){
                            ?>
                                <tr>
                                    <td><?php echo date("m-d H:i",strtotime($rs['G_Datetime'])); ?></td>
                                    <td><?php echo $rs['G_Num']; ?>회차 (<?php echo $rs['G_Num2']; ?>)</td>
                                    <?php if($list['GL_Key']==1){ ?>
                                    <td><font class="mypage-grnfont">일반볼 (<?php echo ($list['G_ResultOddEven']=='Odd')?'홀':'짝';?>)</font></td>
                                    <td><?php echo $list['G_QuotaOdd'];?></td>
                                    <?php } else if($list['GL_Key']==2){ ?>
                                        <td><font class="mypage-grnfont">일반볼언오버 (<?php echo ($list['G_ResultUnderOver']=='Under')?'언더':'오버';?>)</font></td>
                                        <td><?php echo $list['G_QuotaUnder'];?></td>
                                    <?php } else if($list['GL_Key']==3){ ?>
                                        <td><font class="mypage-grnfont">일반볼대중소 (
                                                <?php
                                                    if($list['G_ResultSMB']=='Small'){
                                                        echo '소';
                                                        $rate = $list['G_QuotaSmall'];
                                                    } else if($list['G_ResultSMB']=='Middle') {
                                                        echo '중';
                                                        $rate = $list['G_QuotaMiddle'];
                                                    } else {
                                                        echo '대';
                                                        $rate = $list['G_QuotaBig'];
                                                    }
                                                ?>
                                                )</font></td>
                                        <td><?php echo $rate;?></td>
                                    <?php } else if($list['GL_Key']==4 || $list['GL_Key']==5){ ?>
                                        <td><font class="mypage-grnfont">일반볼조합 (
                                                <?php
                                                    if($list['G_ResultUnderOver'] != 'UnderOver' && $list['GL_Key']==4){
                                                        if($list['G_ResultUnderOver']=='Under') echo '홀+언더';
                                                        else                                    echo '홀+오버';
                                                    } else if($list['G_ResultUnderOver'] != 'UnderOver' && $list['GL_Key']==5) {
                                                        if($list['G_ResultUnderOver']=='Under') echo '짝+언더';
                                                        else                                    echo '짝+오버';
                                                    } else {
                                                        echo '-';
                                                    }
                                                ?>
                                                )</font></td>
                                        <td><?php echo $list['G_QuotaUnder'];?></td>
                                    <?php } else if($list['GL_Key']==8){ ?>
                                        <td><font class="mypage-grnfont">파워볼 홀/짝 (<?php echo ($list['G_ResultOddEven']=='Odd')?'홀':'짝';?>)</font></td>
                                        <td><?php echo $list['G_QuotaOdd'];?></td>
                                    <?php } else if($list['GL_Key']==9){ ?>
                                        <td><font class="mypage-grnfont">파워볼 언오버 (<?php echo ($list['G_ResultUnderOver']=='Under')?'언더':'오버';?>)</font></td>
                                        <td><?php echo $list['G_QuotaUnder'];?></td>
                                    <?php } else if($list['GL_Key']==10){ ?>
                                        <td><font class="mypage-grnfont">파워볼 구간 (숫자 <?php echo $list['G_ResultSection'];?>)</font></td>
                                        <td><?php echo $list['G_Section0'];?></td>
                                    <?php } ?>

                                </tr>
                            <?php } ?>
                            </tbody>
                                <?php
                                $cnt++;
                            }} else {
                                ?>
                                <tr><td colspan="7" class="text-center">등록된 결과가 없습니다.</td></tr>
                            <?php } ?>
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

<?php
include_once $root_path.'/include/footer.php';
?>