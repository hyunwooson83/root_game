<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    if ( !$_SESSION['S_Key'] ) {
        swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }

    //하위총판들이 가지고 있는 회원들을 구한다.
    $smember = get_branch_sub_id($meminfo['M_ID']);

    if(empty($startDate)) $startDate = date("Y-m-d");
    if(empty($endDate))   $endDate = date("Y-m-d");

    $tb = "buygame a LEFT JOIN members b ON a.M_Key = b.M_Key ";

    $view_article = 20; // 한화면에 나타날 게시물의 총 개수
    if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
    $start = ($_GET['page']-1)*$view_article;
    $href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}&startDate={$_GET['startDate']}&endDate={$_GET['endDate']}";


    $where = " 1 AND a.M_Key IN ({$smember}) AND BG_Visible = '1'  ";

    if(!empty($startDate)){
        $where .= " AND DATE_FORMAT(BG_BuyDate,'%Y-%m-%d') BETWEEN '{$startDate}' AND '{$endDate}' ";
    }

    #성명으로 정렬시
    $order_by = " ORDER BY BG_BuyDate DESC ";

    $cnt = 0;
    $query = "SELECT COUNT(*) FROM {$tb} WHERE {$where}   ";
    //echo $query;
    $row = getRow($query);

    $game = get_recom_game_type($_SESSION['S_Key']);

?>
    <script>
        $(document).ready(function(){
            $("ol.login_st > li:nth-child(4)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">총판정산내역</div>
                <div class="title2">BRANCH PAY REPORT</div>
            </div>
            <div class="sub-box">
                <div class="mypage_menu1">
                    <a href="/mypage/branch/">총판현황</a>
                    <a href="/mypage/branch/bet/sports/">배팅내역</a>
                    <a href="/mypage/branch/pay/" class="active">정산내역</a>
                </div>
                <div class="mypage_menu2">
                    <a href="/mypage/branch/bet/sports/" class="active">스포츠게임</a>
                    <!--<a href="/_go/renewal/mypage/betlist_evolution.html" >에볼루션카지노</a>-->
                    <a href="/mypage/branch/bet/minigame/power/">미니게임</a>
                    <a href="/mypage/branch/bet/virtual/" >가상게임</a>
                </div>



                <div class="board_wrap">
                    <ul class="recom_state">
                        <li style="width: 32%">
                            <div class="text1">스포츠</div>
                            <var></var>
                            <div class="text2"><?php echo number_format($game['sports']);?></div>
                        </li>
                        <li style="width: 32%;">
                            <div class="text1">미니게임</div>
                            <var></var>
                            <div class="text2"><?php echo number_format($game['minigame']); ?></div>
                        </li>
                        <li style="width: 32%;">
                            <div class="text1">가상게임</div>
                            <var></var>
                            <div class="text2"><?php echo number_format($game['virtual'])?></div>
                        </li>
                    </ul>
                    <form name="f" id="f" method="get" action="./">
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
                    </form>
                    <table class="table-black mypage">
                        <thead>
                        <tr>
                            <td>일자</td>
                            <td>베팅구분</td>
                            <td>아이디[닉네임]</td>
                            <td>배팅금액</td>
                            <td>적중금액</td>
                            <td>지급포인트</td>                            
                            <td>처리일자</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $cur_gid = "";
                        $game_state = 0;
                        $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함
                        $que = "SELECT  * FROM {$tb} WHERE {$where} {$order_by}  LIMIT {$start},{$view_article}";
                        //echo $que;
                        $arr = getArr($que);
                        if(count($arr)>0){
                            foreach($arr as $rs) {
                                $sql = "SELECT * FROM buygamelist a LEFT JOIN gamelist b ON a.G_Key = b.G_Key WHERE  a.BG_Key = '{$rs['BG_Key']}'";
                                //echo $sql;
                                $ar = getArr($sql);
                                $betting_gubun = "스포츠";
                                switch($betting_gubun){
                                    case 'prematch':
                                    case 'live':
                                        $betting_gubun = "스포츠";
                                        break;
                                    case 'PB':
                                    case 'PB:':
                                    case 'KNL':
                                    $betting_gubun = "미니게임";
                                        break;
                                    case 'dog':
                                    case 'horse:':
                                    case 'soccer':
                                        $betting_gubun = "가상게임";
                                        break;
                                }

                                $q = "SELECT PI_RegDate FROM pointinfo WHERE M_Key = {$_SESSION['S_Key']} AND BG_Key = {$rs['BG_Key']}";
                                $pi = getRow($q);

                                 ?>
                                <tr>
                                    <td><?php echo date("Y-m-d H:i:s",strtotime($rs['BG_BuyDate'])); ?></td>
                                    <td><?php echo $betting_gubun;?></td>
                                    <td><?php echo $rs['M_ID']; ?>[<?php echo $rs['M_NICK']; ?>]</td>
                                    <td><?php echo number_format($rs['BG_BettingPrice']); ?>원</td>
                                    <td><?php echo number_format($pay_result); ?>원</td>
                                    <td>
                                        <?php
                                        if($rs['M_ShopPayType'] == 'R') {//롤링일 경우
                                            process_recom_point($rs['M_Key'], $rs['BG_Key'], $rs['BG_BettingPrice'],'Rolling');
                                        } else if ($rs['M_ShopPayType'] == 'L') {//루징일 경우
                                            if (empty($pay_result) || $pay_result == 0) {
                                                process_recom_point($rs['M_Key'], $rs['BG_Key'], $rs['BG_BettingPrice'],'Loseing');
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $pi['PI_RegDate'];?></td>
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
                <!--<div class="betlist_bottom">
                    <div class="left">
                        <a class="choose selectAll">전체선택</a>
                        <a class="del" data-game_state="<?php /*echo $game_state;*/?>">선택삭제</a>
                    </div>
                    <div class="right">
                        <a class="betting-upload">게시판에 베팅내역 올리기</a>
                    </div>
                </div>
-->
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
            $('.view').on('click',function(){
                $('#f').submit();
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

        $('.selectAll').on('click',function(){
            if($(this).text()=='전체선택'){
                $('.bgkey').prop('checked',true);
                $(this).text('선택해제');
            } else {
                $('.bgkey').prop('checked',false);
                $(this).text('전체선택');
            }
        });

        $('.del').on('click',function(){
            var cnt = 0;
            var bgkey = new Array();
            $('.bgkey').each(function(){
                if($(this).is(':checked')==true){
                    bgkey[cnt] = $(this).val();
                    cnt++;
                }
            });
            if(!cnt){
                swal('','삭제할 배팅내역을 선택해주세요.','warning');
                return false;
            }

            $.ajax({
                type : 'post',
                url : './proc/',
                dataType : 'json',
                data : 'HAF_Value_0=deleteBetList&bgkey='+bgkey,
                success : function(data){
                    if(data.flag == true){
                        swal('','배팅내역이 정상적으로 삭제되었습니다.','success');
                        setTimeout(function(){location.reload()},3000);
                    } else {
                        swal('',data.error,'warning');
                    }
                }
            });
        });

        $('.betting-upload').on('click',function(){
            var cnt = 0;
            var bgkey = new Array();
            $('.bgkey').each(function(){
                if($(this).is(':checked')==true){
                    bgkey[cnt] = $(this).val();
                    cnt++;
                }
            });
            if(!cnt){
                swal('','등록할 배팅내역을 선택해주세요.','warning');
                return false;
            }

            swal({
                text: "베팅정보를 등록하시겠습니까?",
                type: "success",
                confirmButtonText: "확인",
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type : 'post',
                        url : '/action/board_action.php',
                        dataType : 'json',
                        data : 'HAF_Value_0=BoardGameResultWrite&HAF_Value_1='+bgkey+'&HAF_Value_0=BoardGameResultWrite',
                        success : function(data){
                            if(data.flag == true){
                                location.href = '/mypage/board/modify/?&tn=betting&b_key='+data.bkey;
                            } else {
                                swal('',data.error,'warning');
                            }
                        }
                    });
                }
            });

        });
    </script>
<?php
include_once $root_path.'/include/footer.php';
?>