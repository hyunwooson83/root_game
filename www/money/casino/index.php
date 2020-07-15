<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    $mem = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");

    if ($mem['M_Charge_YN'] == 'N') {
        swal_move('충전이 불가능한 회원 입니다. 관리자에게 문의해주세요.', 'game/cross');
    }

    /*if($mem['M_Money']<10000){
        swal_move('카지노 머니 충전을 위한 보유머니가 부족합니다.', '../money/charge');
    }*/

?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(12)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">카니노 머니 입출금</div>
                <div class="title2">MONEY CHARGE</div>
            </div>
            <ul class="sub_menu">
                <li class="on" onclick="location.href='/money/casino/'">카지노머니 충전/환전</li>
                <!--<li onclick="location.href='/mypage/charge/'">카지노머니 충전/환전내역</li>-->
                <!--<li onclick="location.href='/money/casino/charge/'">카지노 충전</li>
                <li onclick="location.href='/money/casino/charge/list/'">카지노 충전내역</li>-->
            </ul>
            <div class="sub_box money_box" style="width:1040px; margin-left:30px; ">
                <div class="money_caution">
                    <h1><img src="/img/img_caution.png" />필독사항</h1>
                    <ul>
                        <li class="point"><strong>⊙</strong> 카지노 머니는 보유머니에서만 충전이 가능합니다.</li>
                        <li><strong>⊙</strong> 보유머니에서 카지노 머니를 충전금액을 입금하시면 보유머니가 카지노 머니로 충전됩니다.</li>
                        <li><strong>⊙</strong> 카지노 머니 환전은 카지노 보유머니에서 환전을 하시면 보유머니로 이동되면 환전을 하실 수 있습니다.</li>
                        <li><strong>⊙</strong> 자세한 문의사항은 고객센터를 이용해 주시기 바랍니다.</li>
                    </ul>
                </div>
                <div class="money_con3">
                    <div class="money_title">
                        <span class="t_white">충전신청하기</span><var>|</var>
                    </div>
                    <div class="content">
                        <div class="con1">
                            <div>현재 보유머니<span class="t_green"><em id="current_money"><?=number_format($lib24c->member_info['M_Money']);?></em>원</span></div>
                        </div>
                        <div class="con1">
                            <div>카지노 보유머니<span class="t_orange"><em id="casino_money">0</em>원</span></div>
                        </div>
                        <div class="money_choice">
                            <div class="left">신청금액선택</div>
                            <div class="right">
                                <div class="input">
                                    <input type="text" class="i1" placeholder="직접입력" name="charge_price" id="charge_price" OnKeyUp="javascript:is_onlynumeric( this.value, this );"><span>원</span>
                                </div>
                                <div class="m_btn">
                                    <span><a class="ui_btn_red" href="javascript:writemoney(10000);   ">10,000</a></span>
                                    <span><a class="ui_btn_red" href="javascript:writemoney(30000);   ">30,000</a></span>
                                    <span><a class="ui_btn_red" href="javascript:writemoney(50000);   ">50,000</a></span>
                                    <span><a class="ui_btn_red" href="javascript:writemoney(100000);  ">100,000</a></span>
                                    <span><a class="ui_btn_red" href="javascript:writemoney(500000);  ">500,000</a></span>
                                    <span><a class="ui_btn_red" href="javascript:writemoney(1000000); ">1,000,000</a></span>
                                    <span class="all money_reset"><a class="ui_btn_gray" href="javascript:clearmoney(0);      ">정정</a></span>
                                </div>
                                <!--<div class="ment t_yellow">※입금 시 고객님의 성함으로 반드시 입금해 주시기 바랍니다.</div>-->
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    .btn-orange {
                        background: linear-gradient(to top, #be4e0e, #f26718) !important;
                        border: 1px solid #d96c2d !important;
                        border-top: 1px solid #fab58e !important;
                        color: #fff;
                    }
                </style>
                <div class="money_btn_wrap">
                    <span onclick="Action_Write('Charge');">충전하기</span>
                    <span onclick="Action_Write('Refund');" class="btn-orange">환전하기</span>
                </div>

                <table class="table-black table-mypage-moneylist big">
                    <thead>
                    <tr>
                        <td>번호</td>
                        <td>닉네임</td>
                        <td>충전/환전</td>
                        <td>요청일시</td>
                        <td width="20%">충전금액</td>
                        <td width="20%">처리일시</td>
                        <td>처리상태</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tb = "requests a LEFT JOIN members b ON a.M_Key = b.M_Key ";

                    $view_article = 10; // 한화면에 나타날 게시물의 총 개수
                    if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
                    $start = ($_GET['page']-1)*$view_article;
                    $href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}";
                    $where = " 1 AND R_Casino_YN = 'Y' AND R_Money > 0 AND a.M_Key = '{$_SESSION['S_Key']}'";

                    #성명으로 정렬시
                    $order_by = " ORDER BY R_RegDate DESC ";

                    $query = "SELECT COUNT(*) FROM {$tb} WHERE {$where} ";
                    //echo $query;
                    $row = getRow($query);
                    $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함
                    if($total_article > 0){
                        $cnt = 0;
                        $que = "
                                    SELECT 
                                        *
                                    FROM 
                                        {$tb} 
                                    WHERE 
                                         {$where}                                    
                                    ORDER BY R_RegDate DESC
                                    LIMIT 
                                        {$start}, {$view_article}
                                    
                                ";
                        //echo $que;
                        $arr = getArr($que);
                        foreach($arr as $list){
                            $rstate  = "확인중";
                            if($list['R_State']=='Done') {
                                $rstate = "처리완료";
                            } else if($list['R_State']=='Cancel'){
                                $rstate = "취소";
                            }

                            ?>
                            <tr>
                                <td><?=($total_article-$cnt-(($_GET['page']-1)*$view_article))?></td>
                                <td><?php echo $list['M_NICK']; ?></td>
                                <td><?php echo ($list['R_Type1']=='Charge')?'<font class="mypage-grnfont">충전</font>':'환전'; ?></td>
                                <td><?php echo $list['R_RegDate']; ?></td>
                                <td><font class="mypage-grnfont"><?php echo number_format($list['R_Money']); ?></font></td>
                                <td><?php echo $list['R_ResultDate']; ?></td>
                                <td><font class="mypage-grnfont"><?php echo $rstate; ?></font></td>
                            </tr>
                            <?php
                            $cnt++;
                        }} else {
                        ?>
                        <tr><td colspan="7" class="text-center">등록된 내역이 없습니다.</td></tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php
            if($total_article>0) {
                include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
            }
            ?>
        </div><!-- sub_wrap -->
    </div><!-- sub_bg -->

    <script>
        $(document).ready(function(){
           $('.money_reset').on('click',function(){
               $('#charge_price').val('0');
           });
            $('.bonus-class').on('click',function(){
                var type = parseInt($.trim($(this).val()));
                $('.percent-tr').hide();
                $('#'+type+'_percent').show();
            });


            $.ajax({
                url : './proc/',
                type : 'post',
                dataType : 'json',
                data : {'HAF_Value_0' : 'getCasinoMoney'},
                success : function(res){
                    if(res.returnCode == 0){
                        var casinoMoney = res.memberBalance;
                        $('#casino_money').text(comma(res.memberBalance));
                        /*if(casinoMoney < 10000){
                            swal('','카지노 머니 환전 최소 금액은 10,000원 입니다.','warning');
                            setTimeout(function(){ location.href = '/main/';},3000);
                        }*/
                    } else {
                        console.log(res)
                    }
                    //console.log(res.memberBalance);
                },complete:function(data){
                },error:function(request, status, error){
                    console.log(request);
                    console.log(status);
                    console.log(error);
                }
            });

        });
        function writemoney(v)
        {
            var charge_price  = document.getElementById( "charge_price" );
            if(!charge_price.value) charge_price.value = 0;
            charge_price.value = parseInt(charge_price.value)+parseInt(v);
        }
        function clearmoney()
        {
            var charge_price  = document.getElementById( "charge_price" );
            charge_price.value = 0;
        }
        
        function Action_Write(type) {
            var f = document.HiddenActionForm;
            var charge_type2  = document.getElementById( "charge_type_point" );
            var charge_price  = document.getElementById( "charge_price" );
            var charge_type = "Money";
            var type_text = (type=='Charge')?'충전':'환전';

            if(type == 'Charge'){
                var money = parseInt(removeComma($('#current_money').text()));
                if(money < 10000){
                    swal('','카지노 머니 충전 최소 금액은 10,000원 입니다.','warning');
                    return false;
                }
            } else {
                var money = parseInt(removeComma($('#casino_money').text()));
                if(money < 10000){
                    swal('','카지노 머니 환전 최소 금액은 10,000원 입니다.','warning');
                    return false;
                }
            }

            if(money < parseInt(removeComma(charge_price.value))){
                swal('',"보유머니가 부족합니다.", 'warning');
            } else {
                if (!charge_price.value.trim() || !checkPointorMoney(charge_price.value)) {
                    swal('', type_text + "은 10,000원 단위로 하실수 있습니다.", 'warning');
                    charge_price.focus();
                } else {
                    if (confirm("카지노 머니 " + type_text + " 하시겠습니까?")) {
                        if (type == 'Charge') {
                            f.HAF_Value_0.value = "RequestMoneyChargeCasino";
                        } else {
                            f.HAF_Value_0.value = "RequestMoneyRefundCasino";
                        }
                        f.HAF_Value_1.value = 'Money';
                        f.HAF_Value_2.value = removeComma(charge_price.value);
                        f.HAF_Value_3.value = type;


                        f.method = "POST";
                        f.action = "/action/money_action.php";
                        f.submit();
                    }
                    ;
                }
                ;
            }
        };

        function rollbackCharge(res){
            if(res == true){
                swal('','카지노 머니가 정상적으로 충전되었습니다. ','success');
                setTimeout(function(){ location.reload(); },3000);
            } else {
                swal('','카지노 머니가 충전시 오류가 발생되었습니다. ','warning');
                setTimeout(function(){ location.reload(); },3000);
            }
        }
        function rollbackRefund(res){
            if(res == true){
                swal('','카지노 머니가 정상적으로 환전되었습니다. ','success');
                setTimeout(function(){ location.reload(); },3000);
            } else {
                swal('','카지노 머니가 환전시 오류가 발생되었습니다. ','warning');
                setTimeout(function(){ location.reload(); },3000);
            }
        }
    </script>

<?php
include_once $root_path.'/include/footer.php';
?>