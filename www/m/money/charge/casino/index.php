<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/header.php');

if ($meminfo['M_Charge_YN'] == 'N') {
    swal_move('충전이 불가능한 회원 입니다. 관리자에게 문의해주세요.', '/main');
}
/*if($meminfo['M_Money']<10000){
    swal_move('카지노 머니 충전을 위한 보유머니가 부족합니다.', '/main');
}*/
?>

    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>카지노머니 충전/환전하기</span>
                <em>CASINO CHARGE</em>
            </h1>
            <ul class="sub_title_category">
                <li onclick="location.href='<?php echo $mobile_path;?>/money/charge/casino'" class="active">충전/환전하기</li>
                <li onclick="location.href='<?php echo $mobile_path;?>/money/charge/casino/list/'">충전/환전내역</li>
                <!--<li onclick="location.href='<?php /*echo $mobile_path;*/?>/money/refund/casino/'">환전하기</li>
                <li onclick="location.href='<?php /*echo $mobile_path;*/?>/money/refund/casino/list/'">환전내역</li>-->
            </ul>
        </div>

        <div class="sub_mypage_wrap">
            
            <div class="sub_cash_box account_info">
                <h1>
                    <span>충전/환전금액 입력</span>
                </h1>
                <h2>
                    <span>현재 보유머니</span>
                    <label><?=$lib24c->member_info['M_Money'];?>원</label><BR />
                    <span>현재 카지노머니</span>
                    <label><em id="casino_money" style="font-size:1em;">0</em>원</label><BR />
                    <div>
                        <span>신청금액선택</span>
                        <span><input type="text" name="charge_price" id="charge_price" OnKeyUp="javascript:is_onlynumeric( this.value, this );" value="0" /></span>
                        <ul>
                            <li onclick="writemoney(10000)">1만</li>
                            <var></var>
                            <li onclick="writemoney(50000)">5만</li>
                            <var></var>
                            <li onclick="writemoney(100000)" class="green">10만</li>
                            <var></var>
                            <li onclick="writemoney(200000)" class="green">20만</li>
                            <var></var>
                            <li onclick="writemoney(500000)" class="green">50만</li>
                            <var></var>
                            <li onclick="writemoney(1000000)" class="green">100만</li>
                            <var></var>
                            <li onclick="writemoney(2000000)" class="green">200만</li>
                            <var></var>
                            <li onclick="clearmoney(0)" class="yellow">정정</li>
                        </ul>
                    </div>
                    <!--<div>
                        <span>보너스</span>
                        <ul>
                            <input type="radio" class="bonus-class" name="bonus" value="0" checked> 0
                            <input type="radio" class="bonus-class" name="bonus" value="5"> 5%
                            <input type="radio" class="bonus-class" name="bonus" value="10"> 10%
                        </ul>
                    </div>
                    <span>보너스 충전금</span>
                    <label class="bonus"><input type="text" style="background-color: #222; border: none; text-align: right; font-size:14px; color:#fff;" name="bonus_price" id="bonus_price" value="0" class="t_orange" readonly></label>
                    <dl>
                        <dd class="on bonus-save">보너스받기</dd>
                        <dt>&nbsp;</dt>
                        <dd class="btn1 no-bonus">받지않기</dd>
                    </dl>-->
                </h2>
            </div>
            <div class="cash_submit">
                <div class="cash_submit_btn" style="margin-bottom:10px;" onclick="Action_Write('Charge');">카지노머니 충전 신청하기</div>
                <div class="cash_submit_btn" STYLE="background-color: #de6e19" onclick="Action_Write('Refund');" sty>카지노머니 환전 신청하기</div>
            </div>
        </div>



    </div>
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

            $('.bonus-save').on('click',function(){
                var charge_price = parseInt(removeComma($.trim($('#charge_price').val())));
                if(charge_price>0) {
                    var type = parseInt($('input[name="bonus"]:checked').val());
                    var bonus_price = 0;
                    var percent = type * 0.01;
                    if (type > 0) {
                        bonus_price = charge_price * percent;
                        $('#bonus_price').val(comma(bonus_price));
                    } else {
                        $('#bonus_price').val(0);
                    }
                } else {
                    swal('','신청하실 금액을 선택(입력)해주세요.','warning');
                }
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


            $('.no-bonus').on('click',function(){
                $('#bonus_price').val(0);
            })

            $('.request-bank').on('click',function(){
                swal({
                    title: "계좌번호",
                    text: "계좌번호를 요청 하시겠습니까?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "확인",
                    cancelButtonText: "취소"
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url : './proc/',
                            type : 'post',
                            dataType : 'json',
                            data : {'HAF_Value_0' : 'requestBank'},
                            success : function(res){
                                if(res.flag == true){
                                    swal('','완료되었습니다. 쪽지를 확인해주세요.','success');
                                } else {
                                    swal('','계좌요청시 오류가 발생했습니다. 잠시후에 다시 시도해주세요.'+res.error,'warning');
                                }
                                //setTimeout(function(){ location.reload();},3000);
                            },complete:function(data){
                            },error:function(request, status, error){
                                console.log(request);
                                console.log(status);
                                console.log(error);
                            }
                        });
                    }
                });
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
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>