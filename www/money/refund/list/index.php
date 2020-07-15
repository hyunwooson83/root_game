<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) {
        swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }

    $row = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");


    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) $lib->AlertMSG( "로그인이 필요한 페이지 입니다.","/" );
    if($_GET['tn']=='customer')	move('./board_list2.php?tn=customer');
    $mem = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");

    $tb = "requests a LEFT JOIN members b ON a.M_Key = b.M_Key ";

    $view_article = 15; // 한화면에 나타날 게시물의 총 개수
    if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
    $start = ($_GET['page']-1)*$view_article;
    $href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}";
    $where = " 1 AND R_Type1 = 'Refund' AND R_Type2 = 'Money' AND M_Key = '{$_SESSION['S_Key']}' ";
    if(!empty($search_text)){
        $where .= " AND (B_Subject LIKE '%{$search_text}%' OR B_Content LIKE '%{$search_text}%') ";
    }

    #성명으로 정렬시
    $order_by = " ORDER BY R_RegDate DESC ";

    $query = "SELECT COUNT(*) FROM {$tb} WHERE {$where} ";

    $row = getRow($query);
    $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함

?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(13)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">보유머니 환전목록</div>
                <div class="title2">MONEY EXCHANGE LIST</div>
            </div>
            <div class="board_wrap board2">
                <div class="line_top"></div>
                <table class="qna_list">
                    <thead>
                    <tr>
                        <th>번호</th>                        
                        <th style="width:55%">요청금액</th>
                        <th>요청일자</th>
                        <th>완료일자</th>
                        <th>결과</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    if($total_article > 0){
                        $cnt = 0;
                        $que = "
                                    SELECT 
                                        R_Money, R_RegDate, R_ResultDate, R_State
                                    FROM 
                                        {$tb} 
                                    WHERE 
                                         {$where}                                    
                                    ORDER BY R_RegDate DESC
                                    LIMIT 
                                        {$start}, {$view_article}
                                    
                                ";
echo $que;
                        $arr = getArr($que);
                        foreach($arr as $list){

                            ?>
                            <tr>
                                <td><?=($total_article-$cnt-(($_GET['page']-1)*$view_article))?></td>
                                <td><div style="text-align: right;"><?php echo number_format($list['R_Money']); ?>원</div></td>
                                <td><?php echo $list['R_RegDate']; ?></td>
                                <td><?php echo $list['R_ResultDate']; ?></td>
                                <td class="state">

                                    <?php
                                        switch($list['R_State']){
                                            case 'Await':
                                                echo '<span class="off">확인중</span>';
                                                break;
                                            case 'Done':
                                                echo '<span class="on">환전완료</span>';
                                                break;
                                        }
                                    ?>
                                </td>
                            </tr>
                            <!--<tr>
                                <td>2222</td>
                                <td>스포츠</td>
                                <td class="subject" onclick="location.href='/_go/renewal/qna_view.html'"><div>문의드립니다.</div></td>
                                <td>홍길동</td>
                                <td class="state"><span class="on">답변완료</span></td>
                                <td>2017-07-07</td>
                            </tr>-->
                            <?php
                            $cnt++;
                        }} else {
                        ?>
                        <tr><td colspan="7" class="text-center">등록된 게시물이 없습니다.</td></tr>
                    <?php } ?>
                    </tbody>

                </table>
                <div class="line_bottom"></div>
                <div class="btn_wrap">
                    <a href="/_go/renewal/qna_write.html">글쓰기</a>
                </div>
                <?php
                if($total_article>0) {
                    include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
                }
                ?>
                <!--<div class="paging_box">
                    <a href="">◀</a><a href="">1</a><a href="" class="hit">2</a><a href="">3</a><a href="">4</a><a href="">5</a><a href="">6</a><a href="">7</a><a href="">8</a><a href="">9</a><a href="">10</a><a href="">▶</a>
                </div>-->
               
            </div>

        </div>
    </div>


    <script>
        function writemoney(v)
        {
            var charge_price  = document.getElementById( "refund_money" );
            if(!charge_price.value) charge_price.value = 0;
            charge_price.value = parseInt(charge_price.value)+parseInt(v);
        }
        function clearmoney()
        {
            var charge_price  = document.getElementById( "refund_money" );
            charge_price.value = 0;
        }

        function Action_Write() {

            var f = document.HiddenActionForm;

            var my_money      = $.trim(remove_comma($( "#my_money" ).val()));
            var refund_money  = document.getElementById( "refund_money" );
            var bank_pass  = document.getElementById( "bank_pass" );

            if(refund_money.value<1){
                swal('','환전금액은 1만원 이상만 가능합니다.','warning');
                return;
            } else {
                if ( !refund_money.value.trim() || !checkPointorMoney(refund_money.value) ) {
                    swal("","환전은 10,000원 단위로 하실수 있습니다.","warning");
                    refund_money.focus();
                } else if ( parseInt(my_money.innerHTML) < parseInt(refund_money.value) ) {
                    swal("","보유금액을 초과해서 환전요청을 하실수 없습니다.","warning");
                    refund_money.focus();
                } else if ( bank_pass.value == "") {
                    swal("","환전 암호를 입력해주세요.","warning");
                    bank_pass.focus();
                } else {
                    swal({
                        title: "환전요청",
                        text: "환전요청을 진행 하시겠습니까?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "확인",
                        cancelButtonText: "취소"
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            f.HAF_Value_0.value = "RequestMoneyRefund";
                            f.HAF_Value_1.value = refund_money.value;
                            f.HAF_Value_2.value = bank_pass.value;

                            f.method = "POST";
                            f.action = "/action/money_action.php";
                            f.submit();
                        }
                    });
                }
            }
        }
    </script>
<?php
include_once $root_path.'/include/footer.php';
?>