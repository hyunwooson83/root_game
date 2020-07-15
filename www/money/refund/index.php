<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) {
        swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }

    $row = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");

    //echo $row[M_Type];
    if($row['M_Type']==2){
        swal_move('총판 및 테스트 아이디는 환전신청 하실 수 없습니다.','cross/');
        exit;
    }

    if($lib24c->member_info['M_Money']<10000){
        swal_move('보유머니가 부족합니다.','../money/charge');
    }
?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(13)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">보유머니 환전</div>
                <div class="title2">MONEY EXCHANGE</div>
            </div>
            <ul class="sub_menu">
                <li class="on" onclick="location.href='/money/refund/'">보유머니 환전</li>
                <li class="" onclick="location.href='/mypage/refund/'">보유머니 환전목록</li>
            </ul>
            <div class="sub_box money_box" style="width:1040px; margin-left:30px; ">
                <div class="money_caution">
                    <h1><img src="/img/img_caution.png" />필독사항</h1>
                    <ul>
                        <li class="point"><strong>⊙</strong> 환전 신청 시 현재 보유머니에서 차감되며, 회원님의 계좌번호로 입금됩니다.</li>
                        <li><strong>⊙</strong> 은행 점검시간을 확인하여 해당시간에는 입금이 지연될 수 있으니 점검시간을 피해 신청해 주시기 바랍니다.</li>
                        <li><strong>⊙</strong> 자세한 문의사항은 고객센터를 이용해 주시기 바랍니다.</li>
                    </ul>
                </div>
                <!--<ul class="money_con1">
                    <li>
                        <div class="text1">현재 보유머니</div>
                        <var></var>
                        <div class="text2">150,000원</div>
                    </li>
                    <li>
                        <div class="text1">환전 예정금액</div>
                        <var></var>
                        <div class="text2 t_green">50,000원</div>
                    </li>
                    <li>
                        <div class="text1">환전 후 보유머니</div>
                        <var></var>
                        <div class="text2">100,000원</div>
                    </li>
                </ul>-->
                <div class="money_con3">
                    <div class="money_title">
                        <span class="t_white">환전신청하기</span><var>|</var>환전완료 시 현재 보유머니에서 차감되며, 고객님의 계좌번호로 입금됩니다.
                    </div>
                    <div class="content">
                        <div class="con1">
                            <div>현재 보유머니<span class="t_green"><span id="my_money"><?php echo number_format($row['M_Money']); ?></span>원</span></div>
                        </div>
                        <div class="money_choice">
                            <div class="left">머니환전암호</div>
                            <div class="right">
                                <div class="input">
                                    <input type="password" name="bank_pass" id="bank_pass"  class="i1" style="width:140px;" placeholder="암호입력">
                                </div>
                            </div>
                        </div>
                        <div class="money_choice">
                            <div class="left">신청금액선택</div>
                            <div class="right">
                                <div class="input">
                                    <input type="text" name="refund_money" id="refund_money"  class="i1" placeholder="직접입력"><span>원</span>
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="money_btn_wrap">
                    <span onclick="Action_Write();">신청하기</span>
                </div>
                <div class="time_info">
                    <div class="text t_green">은행별 점검시간 안내<var>|</var><span>은행 점검 시간을 확인하신 후 충전 및 환전 신청 바랍니다.</span></div>
                    <div class="img"><img src="/img/cash_notice_bank.png"></div>
                </div>

            </div>
        </div><!-- sub_wrap -->
    </div><!-- sub_bg -->


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