<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) {
        swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }

    $row = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");

    //echo $row[M_Type];
    if($row['M_Type']==2){
        swal_move('총판 및 테스트 아이디는 환전신청 하실 수 없습니다.','/m/main');
        exit;
    }

    if($lib24c->member_info['M_Money']<10000){
        swal_move('보유머니가 부족합니다.','/m/main/');
    }
?>
    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>보유머니 환전내역</span>
                <em>MONEY EXCHANGE LIST</em>
            </h1>
            <ul class="sub_title_category">
                <li onclick="location.href='<?php echo $mobile_path;?>/money/charge/'">충전하기</li>
                <li onclick="location.href='<?php echo $mobile_path;?>/money/charge/list/'">충전내역</li>
                <li onclick="location.href='<?php echo $mobile_path;?>/money/refund/'"  class="active">환전하기</li>
                <li onclick="location.href='<?php echo $mobile_path;?>/money/refund/list/'">환전내역</li>
            </ul>
        </div>

        <div class="sub_mypage_wrap">
            <dl class="cash_caution">
                <dt>필독사항</dt>
                <dd><var>⊙</var><span class="strong">환전 신청 시 현재 보유머니에서 차감되며, 회원님의 계좌번호로 입금됩니다.</span></dd>
                <dd><var>⊙</var><span>은행 점검시간을 확인하신 후 해당시간에는 입금이 지연될 수 있으니 점검시간을 피해 신청해 주시기 바랍니다.</span></dd>
                <dd><var>⊙</var><span>자세한 문의사항은 고객센터를 이용해 주시기 바랍니다.</span></dd>
            </dl>
            <div class="sub_cash_box account_info">
                <h1>
                    <span>환전금액 입력</span>
                </h1>
                <h2>
                    <div>
                        <span>현재보유금액</span>
                        <span id="my_money"><?php echo number_format($row['M_Money']); ?></span><BR /><BR />
                        <span>환전금액입력</span>
                        <span><input type="text" name="refund_money" id="refund_money" value="0" /></span><BR /><BR />
                        <span>환전암호</span>
                        <span><input type="text" name="bank_pass" id="bank_pass" value="" style="margin-left:25px;" /></span>
                        <ul>
                            <li onclick="writemoney(10000)">1만</li>
                            <var></var>
                            <li onclick="writemoney(50000)">5만</li>
                            <var></var>
                            <li onclick="writemoney(100000)" class="green">10만</li>
                            <var></var>
                            <li onclick="writemoney(300000)" class="green">30만</li>
                            <var></var>
                            <li onclick="writemoney(500000)" class="green">50만</li>
                            <var></var>
                            <li onclick="writemoney(1000000)" class="green">100만</li>
                            <var></var>
                            <li onclick="clearmoney(0)" class="yellow">정정</li>
                        </ul>
                    </div>
                </h2>
            </div>
            <BR>
            <div class="cash_submit">
                <div class="cash_submit_btn" onclick="Action_Write();">보유머니 환전 신청하기</div>
            </div>
        </div>

        <!--<div class="bank_info">
            <ul>
                <li>
                    <span><img src="/mobile/img/img_bank1.png" /></span>
                    <em>21:30 ~ 00:05</em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank2.png" /></span>
                    <em>21:30 ~ 00:05</em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank3.png" /></span>
                    <em>
                        00:00 ~ 00:10<BR />
                        (금 00:00 ~ 00:40)
                    </em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank4.png" /></span>
                    <em>
                        00:00 ~ 01:00<BR />
                        (일 00:00 ~ 08:00)
                    </em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank5.png" /></span>
                    <em>
                        23:00 ~ 24:00<BR />
                        (타행이체불가)
                    </em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank6.png" /></span>
                    <em>00:00 ~ 01:30</em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank7.png" /></span>
                    <em>
                        00:00 ~ 01:00<BR />
                        (일 00:00 ~ 08:00)
                    </em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank8.png" /></span>
                    <em>23:50 ~ 00:10</em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank9.png" /></span>
                    <em>00:00 ~ 00:30</em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank10.png" /></span>
                    <em>00:00 ~ 00:20</em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank11.png" /></span>
                    <em>23:40 ~ 00:05</em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank12.png" /></span>
                    <em>00:00 ~ 00:30</em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank13.png" /></span>
                    <em>00:00 ~ 01:00</em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank14.png" /></span>
                    <em>
                        23:50 ~ 00:00<BR />
                        (04:00 ~ 05:00)
                    </em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank15.png" /></span>
                    <em>00:00 ~ 00:10</em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank16.png" /></span>
                    <em>23:50 ~ 00:30</em>
                </li>
                <li>
                    <span><img src="/mobile/img/img_bank17.png" /></span>
                    <em>23:50 ~ 00:05</em>
                </li>
            </ul>
        </div>-->

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
                            f.action = "/m/action/money_action.php";
                            f.submit();
                        }
                    });
                }
            }
        }
    </script>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>