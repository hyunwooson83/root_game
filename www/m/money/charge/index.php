<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/header.php');

    if ($meminfo['M_Charge_YN'] == 'N') {
        swal_move('충전이 불가능한 회원 입니다. 관리자에게 문의해주세요.', '/main');
    }
?>

    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>보유머니 충전하기</span>
                <em>MONEY CHARGE</em>
            </h1>
            <ul class="sub_title_category">
                <li onclick="location.href='<?php echo $mobile_path;?>/money/charge/'" class="active">충전하기</li>
                <li onclick="location.href='<?php echo $mobile_path;?>/money/charge/list/'">충전내역</li>
                <li onclick="location.href='<?php echo $mobile_path;?>/money/refund/'">환전하기</li>
                <li onclick="location.href='<?php echo $mobile_path;?>/money/refund/list/'">환전내역</li>
            </ul>
        </div>

        <div class="sub_mypage_wrap">
            <dl class="cash_caution">
                <dt>필독사항</dt>
                <dd><var>⊙</var><span class="strong">입금 시 반드시 회원님 성함으로 입금바랍니다.</span></dd>
                <dd><var>⊙</var><span>은행 점검시간을 확인하신 후 해당시간에는 입금이 지연될 수 있으니 점검시간을 피해 신청해 주시기 바랍니다.</span></dd>
                <dd><var>⊙</var><span>입금계좌는 수시로 변경되오니 반드시 계좌번호문의 신청을 통해 계좌번호를 확인 후 입금하여 주시기 바랍니다.</span></dd>
                <dd><var>⊙</var><span>자세한 문의사항은 고객센터를 이용해 주시기 바랍니다.</span></dd>
                <!--<dd><var>⊙</var><span>보너스 지급 유의 사항입니다.</span></dd>
                <dd><var>⊙</var><span>5% 받을 경우 [스포츠 단폴 200% | 두폴 100% | 세폴더 이상 100%]</span></dd>
                <dd><var>⊙</var><span>5% 받을 경우 [카지노 250% | 미니게임 200%]</span></dd>
                <dd><var>⊙</var><span>10% 받을 경우 [스포츠 단폴 300% | 두폴 200% | 세폴더 이상 100%]</span></dd>
                <dd><var>⊙</var><span>105% 받을 경우 [카지노 200% | 미니게임 300%]</span></dd>-->
            </dl>
            <div class="sub_cash_box account_info">
                <h1>
                    <span>충전금액 입력</span>
                </h1>
                <h2>
                    <span>입금계좌 문의</span>
                    <em class="request-bank">계좌번호 신청</em><BR /><BR />
                    <!--<var style="margin-bottom:1em">※ 입금 계좌번호는 쪽지로 발송되며, 계좌번호로 선 입금 바랍니다.</var>-->

                    <span>현재 보유머니</span>
                    <label><?=$lib24c->member_info['M_Money'];?>원</label><BR />
                    <div>
                        <span>입금자명</span>
                        <span><input type="text" name="name" id="name"  style="width:100px; margin-left:25px;" value="<?php echo $meminfo['M_BankOwner'];?>" readonly /></span>
                    </div>
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
                <div class="cash_submit_btn" onclick="Action_Write();">보유머니 충전 신청하기</div>
            </div>
        </div>

        <div class="bank_info">
            <ul>
                <li>
                    <span><img src="/m/img/img_bank1.png" /></span>
                    <em>21:30 ~ 00:05</em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank2.png" /></span>
                    <em>21:30 ~ 00:05</em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank3.png" /></span>
                    <em>
                        00:00 ~ 00:10<BR />
                        (금 00:00 ~ 00:40)
                    </em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank4.png" /></span>
                    <em>
                        00:00 ~ 01:00<BR />
                        (일 00:00 ~ 08:00)
                    </em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank5.png" /></span>
                    <em>
                        23:00 ~ 24:00<BR />
                        (타행이체불가)
                    </em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank6.png" /></span>
                    <em>00:00 ~ 01:30</em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank7.png" /></span>
                    <em>
                        00:00 ~ 01:00<BR />
                        (일 00:00 ~ 08:00)
                    </em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank8.png" /></span>
                    <em>23:50 ~ 00:10</em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank9.png" /></span>
                    <em>00:00 ~ 00:30</em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank10.png" /></span>
                    <em>00:00 ~ 00:20</em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank11.png" /></span>
                    <em>23:40 ~ 00:05</em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank12.png" /></span>
                    <em>00:00 ~ 00:30</em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank13.png" /></span>
                    <em>00:00 ~ 01:00</em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank14.png" /></span>
                    <em>
                        23:50 ~ 00:00<BR />
                        (04:00 ~ 05:00)
                    </em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank15.png" /></span>
                    <em>00:00 ~ 00:10</em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank16.png" /></span>
                    <em>23:50 ~ 00:30</em>
                </li>
                <li>
                    <span><img src="/m/img/img_bank17.png" /></span>
                    <em>23:50 ~ 00:05</em>
                </li>
            </ul>
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
        function Action_Write() {
            var f = document.HiddenActionForm;

            var my_point      = document.getElementById( "my_point" );
            var charge_type   = null;
            var charge_type1  = document.getElementById( "charge_type_money" );
            var charge_type2  = document.getElementById( "charge_type_point" );
            var charge_price  = document.getElementById( "charge_price" );
            var bonus_price   = document.getElementById( "bonus_price" );
            var rolling       = $('input[name="bonus"]:checked').val();
            var name          = $('#name').val();
            var charge_type = "Money";
            if(name == ''){
                swal('','입금자명을 입력해주세요.','warning');
                $('#name').focus();
                return false;
            }
            //alert(charge_type);
            if ( !charge_price.value.trim() || !checkPointorMoney(charge_price.value) ) {
                swal('',"충전은 10,000원 단위로 하실수 있습니다.",'warning');
                charge_price.focus();
            } else {
                if ( confirm("충전 요청을 하시겠습니까?") ) {
                    f.HAF_Value_0.value = "RequestMoneyCharge";
                    f.HAF_Value_1.value = 'Money';
                    f.HAF_Value_2.value = removeComma(charge_price.value);
                    /*f.HAF_Value_3.value = removeComma(bonus_price.value);
                    f.HAF_Value_4.value = rolling;*/
                    f.HAF_Value_5.value = name;

                    f.method = "POST";
                    f.action = "<?php echo $mobile_path;?>/action/money_action.php";
                    f.submit();
                };
            };
        };
    </script>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>