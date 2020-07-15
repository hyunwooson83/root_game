<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    if ( !$_SESSION['S_Key'] ) {
        swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }


    if(empty($startDate)) $startDate = date("Y-m-d");
    if(empty($endDate))   $endDate = date("Y-m-d");


    $tb = "buygame a LEFT JOIN buygamelist b ON a.BG_Key = b.BG_Key LEFT JOIN gamelist_other c ON b.G_Key = c.G_Key  ";

    $view_article = 8; // 한화면에 나타날 게시물의 총 개수
    if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
    $start = ($_GET['page']-1)*$view_article;
    $href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}";


    $where = " 1 AND c.GI_Key = {$gubun} AND a.M_Key = {$_SESSION['S_Key']} AND BG_Visible = '1' ";

    if(!empty($startDate)){
        $where .= " AND DATE_FORMAT(R_RegDate,'%Y-%m-%d') BETWEEN '{$startDate}' AND '{$endDate}' ";
    }
    
    #성명으로 정렬시
    $order_by = " ORDER BY BG_BuyDate DESC ";

    $cnt = 0;
    $betting_text = "";
    $betting_game_text = "";
    $query = "SELECT COUNT(*) FROM {$tb} WHERE {$where}   ";
    
    $row = getRow($query);


?>
    <script>
        $(document).ready(function(){
            $("ol.login_st > li:nth-child(4)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">파워볼게임 베팅내역</div>
                <div class="title2">POWERBALL GAME BETTING LIST</div>
            </div>

            <div class="sub-box">

                <div class="mypage_menu1">
                    <a href="/mypage/betlist/" class="active">베팅내역</a>
                    <a href="/mypage/charge/">충전내역</a>
                    <a href="/mypage/refund/">환전내역</a>
                    <a href="/mypage/point/exchange/list/">포인트내역</a>
                    <!--<a href="/mypage/recom/">총판관리</a>-->
                    <a href="/mypage/message/">쪽지관리</a>
                    <a href="/mypage/member/modify/">회원정보수정</a>
                </div>
                <div class="mypage_menu2">
                    <a href="/mypage/betlist/sports/">스포츠게임</a>
                    <a href="/mypage/betlist/casino/" >카지노</a>
                    <a href="/mypage/betlist/power/" class="active">미니게임</a>
                    <a href="/mypage/betlist/virtual/" >가상게임</a>
                </div>
                <div class="mypage_menu3">
                    <a href="/mypage/betlist/minigame/?gubun=1" class="<?php echo ($gubun==1)?'active':''; ?>">파워볼</a>
                    <a href="/mypage/betlist/minigame/?gubun=2" class="<?php echo ($gubun==2)?'active':''; ?>">파워사다리</a>
                    <!--<a href="/mypage/betlist/minigame/">스피드키노</a>-->
                    <a href="/mypage/betlist/minigame/?gubun=3" class="<?php echo ($gubun==3)?'active':''; ?>">키노사다리</a>
                </div>


                <div class="board_wrap">


                    <div class="mypage-day-search">
                        <div class="title">베팅기간</div>
                        <div class="input_box">
                            <span class="active" data-day="<?php echo date("Y-m-d"); ?>">오늘</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-7 day")); ?>">1주일</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-15 day")); ?>">15일</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-1 month")); ?>">1개월</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-3 month")); ?>">3개월</span> &nbsp;
                            <input type="text" name="startDate" id="startDate" value="<?php echo $startDate; ?>">&nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;<input type="text" name="endDate" id="endDate" value="<?php echo $endDate; ?>">&nbsp;
                            <code class="view">조회하기</code>
                        </div>
                    </div>

                    <table class="table-black minigame">
                        <thead>
                            <tr>
                                <td>선택</td>
                                <td>베팅번호</td>
                                <td>회차일</td>
                                <td>베팅일시</td>
                                <td>게임구분</td>
                                <td>베팅내역</td>
                                <td>배당률</td>
                                <td width="12%">베팅금액</td>
                                <td width="12%">적중/손실금액</td>
                                <td width="9%">결과</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함
                            $que = "SELECT  a.*, b.*, c.G_Num2, c.G_Num FROM {$tb} WHERE {$where} {$order_by}  LIMIT {$start},{$view_article}";

                            $arr = getArr($que);
                            if(count($arr)>0){
                                foreach($arr as $arr) {
                                if (in_array($arr['GL_Key'], array(1,11,16))) {
                                    if ($arr['BGL_ResultChoice'] == 'Odd') {
                                        $betting_text = "<span style='color:dodgerblue'>홀</span>";
                                    } else if ($arr['BGL_ResultChoice'] == 'Even') {
                                        $betting_text = "<span style='color:orangered'>짝</span>";
                                    }
                                    $betting_game_text = "일반볼 홀/짝";
                                } else if (in_array($arr['GL_Key'], array(12,17))) {
                                    if ($arr['BGL_ResultChoice'] == 'Odd') {
                                        $betting_text = "<span style='color:dodgerblue'>언더</span>";
                                    } else if ($arr['BGL_ResultChoice'] == 'Even') {
                                        $betting_text = "<span style='color:orangered'>오버</span>";
                                    }
                                    $betting_game_text = "좌/우";
                                } else if (in_array($arr['GL_Key'], array(13,18))) {
                                    if ($arr['BGL_ResultChoice'] == 'Odd') {
                                        $betting_text = "<span style='color:dodgerblue'>좌</span>";
                                    } else if ($arr['BGL_ResultChoice'] == 'Even') {
                                        $betting_text = "<span style='color:orangered'>우</span>";
                                    }
                                    $betting_game_text = "3줄/4줄";
                                } else if (in_array($arr['GL_Key'], array(14,19))) {
                                    if ($arr['BGL_ResultChoice'] == 'Odd') {
                                        $betting_text = "<span style='color:dodgerblue'>좌3짝</span>";
                                    } else if ($arr['BGL_ResultChoice'] == 'Even') {
                                        $betting_text = "<span style='color:orangered'>좌4홀</span>";
                                    }
                                    $betting_game_text = "좌출3/4";
                                } else if (in_array($arr['GL_Key'], array(15,20))) {
                                    if ($arr['BGL_ResultChoice'] == 'Odd') {
                                        $betting_text = "<span style='color:dodgerblue'>우3홀</span>";
                                    } else if ($arr['BGL_ResultChoice'] == 'Even') {
                                        $betting_text = "<span style='color:orangered'>우4짝</span>";
                                    }
                                    $betting_game_text = "우출3/4";
                                } else if (in_array($arr['GL_Key'], array(8))) {
                                    if ($arr['BGL_ResultChoice'] == 'Odd') {
                                        $betting_text = "<span style='color:dodgerblue'>3줄</span>";
                                    } else if ($arr['BGL_ResultChoice'] == 'Even') {
                                        $betting_text = "<span style='color:orangered'>4줄</span>";
                                    }
                                    $betting_game_text = "파워볼 홀/짝";
                                } else if (in_array($arr['GL_Key'], array(2, 9))) {
                                    if ($arr['BGL_ResultChoice'] == 'Under') {
                                        $betting_text = "<span style='color:dodgerblue'>언더</span>";
                                    } else if ($arr['BGL_ResultChoice'] == 'Over') {
                                        $betting_text = "<span style='color:orangered'>오버</span>";
                                    }
                                } else if (in_array($arr['GL_Key'], array(4))) {
                                    if ($arr['BGL_ResultChoice'] == 'Under') {
                                        $betting_text = "<span style='color:dodgerblue'>홀+언더</span>";
                                    } else if ($arr['BGL_ResultChoice'] == 'Over') {
                                        $betting_text = "<span style='color:orangered'>홀+오버</span>";
                                    }
                                    $betting_game_text = "일반볼 홀+조합";
                                } else if (in_array($arr['GL_Key'], array(5))) {
                                    if ($arr['BGL_ResultChoice'] == 'Under') {
                                        $betting_text = "<span style='color:dodgerblue'>짝+언더</span>";
                                    } else if ($arr['BGL_ResultChoice'] == 'Over') {
                                        $betting_text = "<span style='color:orangered'>짝+오버</span>";
                                    }
                                    $betting_game_text = "일반볼 짝+조합";
                                } else if ($arr['GL_Key'] == 3) {
                                    if ($arr['BGL_ResultChoice'] == 'Big') {
                                        $betting_text = "대";
                                    } else if ($arr['BGL_ResultChoice'] == 'Middle') {
                                        $betting_text = "중";
                                    } else if ($arr['BGL_ResultChoice'] == 'Small') {
                                        $betting_text = "소";
                                    }
                                    $betting_game_text = "일반볼 대/중/소";
                                } else {
                                    $betting_text = '숫자 ' . $arr['BGL_ResultChoice'];
                                    $betting_game_text = "파워볼 숫자";
                                }

                                $suc_css = "ing";


                                if ($arr['BG_Result'] == 'Success') {
                                    $suc_css = "shot";
                                } else if ($arr['BG_Result'] == 'Cancel') {
                                    $suc_css = "tk";
                                } else if ($arr['BG_Result'] == 'Fail') {
                                    $suc_css = "noshot";
                                }
                        ?>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td><?php echo date("m-d H:i:s",strtotime($arr['BG_BuyDate'])); ?></td>
                                <td>
                                    <?php
                                        if($gubun == '1'){
                                            echo $arr['G_Num2'];
                                        } else {
                                            echo $arr['G_Num'];
                                        }

                                    ?>
                                    회차 - <?php echo date("Y월 m일",strtotime($arr['G_Datetime']));?>
                                </td>
                                <td><?php echo date("m-d H:i:s",strtotime($arr['BG_BuyDate'])); ?></td>
                                <td><?php echo $betting_game_text; ?></td>
                                <td><font class="mypage-grnfont"><?php echo $betting_text; ?></font></td>
                                <td><?php echo $arr['BG_TotalQuota']; ?></td>
                                <td class="right"><?php echo number_format($arr['BG_BettingPrice']); ?>원</td>
                                <td class="right">
                                    <?php
                                    if($arr['BG_Result']!='Await') {
                                        echo ($arr['BG_Result'] == 'Success' || $arr['BG_Result']=='Cancel') ? ''.number_format($arr['BG_ForecastPrice']).'원' : '';
                                        echo ($arr['BG_Result'] == 'Fail') ? ' 0원' : '';
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td class="<?php echo $suc_css; ?>">
                                    <code>
                                        <?php
                                        if($arr['BG_Result']!='Await') {
                                            if($arr['BG_Result'] == 'Success'){
                                                echo '<span class="font-blue">적중</span>';
                                            } else if($arr['BG_Result'] == 'Cancel'){
                                                echo '<span class="font-blue">취소(적특)</span>';
                                            } else if($arr['BG_Result'] == 'Fail'){
                                                echo '<span class="font-blue">미적중</span>';
                                            }
                                        } else {
                                            echo '진행중';
                                        }
                                        ?>
                                    </code>
                                </td>
                            </tr>
                                    <?php $cnt++; }} else { ?>
                                <tr>
                                    <td colspan="10">현재 등록된 구매내역이 없습니다.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div> <!-- board_wrap -->
                <?php
                if($total_article>0) {
                    include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
                }
                ?>
                <div class="betlist_bottom">
                    <div class="left">
                        <a class="choose">전체선택</a>
                        <a class="del">선택삭제</a>
                    </div>
                    <div class="right">
                        <a>게시판에 베팅내역 올리기</a>
                    </div>
                </div>


            </div> <!-- sub-box -->

        </div> <!-- sub_wrap -->

    </div> <!-- sub_bg -->
    <script>
        $(document).ready(function(){
            $('.input_box > span').on('click',function(){
                var startDay  = $(this).data("day");
                $('input[name="startDate"]').val(startDay);
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