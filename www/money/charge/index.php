<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    $mem = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");

    if ($mem['M_Charge_YN'] == 'N') {
        swal_move('충전이 불가능한 회원 입니다. 관리자에게 문의해주세요.', 'game/cross');
    }

?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(12)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">보유머니 충전</div>
                <div class="title2">MONEY CHARGE</div>
            </div>
            <ul class="sub_menu">
                <li class="on" onclick="location.href='/money/charge/'">보유머니 충전</li>
                <li onclick="location.href='/mypage/charge/'">보유머니 충전내역</li>
                <!--<li onclick="location.href='/money/casino/charge/'">카지노 충전</li>
                <li onclick="location.href='/money/casino/charge/list/'">카지노 충전내역</li>-->
            </ul>
            <div class="sub_box money_box" style="width:1040px; margin-left:30px; ">
                <div class="money_caution">
                    <h1><img src="/img/img_caution.png" />필독사항</h1>
                    <ul>
                        <li class="point"><strong>⊙</strong> 입금 시 반드시 회원님 성함으로 입금바랍니다.</li>
                        <li><strong>⊙</strong> 은행 점검시간을 확인하신 후 해당시간에는 입금이 지연될 수 있으니 점검시간을 피해 신청해 주시기 바랍니다.</li>
                        <li><strong>⊙</strong> 입금계좌는 수시로 변경되오니 반드시 계좌번호문의 신청을 통해 계좌번호를 확인 후 입금하여 주시기 바랍니다.</li>
                        <li><strong>⊙</strong> 자세한 문의사항은 고객센터를 이용해 주시기 바랍니다.</li>
                    </ul>
                </div>

                <div class="money_con2">
                    <div class="money_title">
                        <span class="t_white">입금계좌정보</span><var>|</var>입금 계좌번호는 쪽지로 발송되며, 계좌번호로 선 입금 바랍니다.
                    </div>
                    <div class="content">
                        <div class="con1">
                            <div>입금계좌 문의<code class="btn1 request-bank">계좌번호 신청</code><var class="t_yellow">※계좌번호는 쪽지로 발송됩니다.</var></div>
                        </div>
                    </div>
                </div>
                <div class="money_con3">
                    <div class="money_title">
                        <span class="t_white">충전신청하기</span><var>|</var>선 입금하신 금액과 동일하게 신청하시기 바랍니다.
                    </div>
                    <div class="content">
                        <div class="con1">
                            <div>현재 보유머니<span class="t_green"><?=$lib24c->member_info['M_Money'];?>원</span></div>
                        </div>

                            <div class="con1">
                                <div>입금자명<span class="t_green" style="margin-left:45px;"><input type="text" name="name" id="name" value="<?php echo $meminfo['M_BankOwner'];?>" readonly style="height:30px;    width: 155px;
    border: 1px solid #898989;
    background: #555555;
    color: #fff;"></span></div>
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
                                    <span><a class="ui_btn_red" href="javascript:writemoney(200000);  ">200,000</a></span>
                                    <span><a class="ui_btn_red" href="javascript:writemoney(500000);  ">500,000</a></span>
                                    <span><a class="ui_btn_red" href="javascript:writemoney(1000000); ">1,000,000</a></span>
                                    <span><a class="ui_btn_red" href="javascript:writemoney(2000000); ">2,000,000</a></span>
                                    <span class="all money_reset"><a class="ui_btn_gray" href="javascript:clearmoney(0);      ">정정</a></span>
                                </div>
                                <!--<div class="ment t_yellow">※입금 시 고객님의 성함으로 반드시 입금해 주시기 바랍니다.</div>-->
                            </div>
                        </div>
                        <!--<div class="con1 con11">
                            <div>보너스 선택
                                <input type="radio" class="bonus-class" name="bonus" value="0" checked> 0
                                <input type="radio" class="bonus-class" name="bonus" value="5"> 5%
                                <input type="radio" class="bonus-class" name="bonus" value="10"> 10%
                            </div>
                        </div>-->
                        <style>
                            .rolling-title { font-size:15px; margin-right:10px;}
                            .rolling-box{ width:65%; }
                            .rolling-box > table { width:100%; border:#626262 solid 1px; margin-left: 10px; }
                            .rolling-box > table > thead > tr > th, td { height:35px; font-size:15px !important; text-align: center; border: #626262 solid 1px; }
                            #bonus_price { background-color: #0b0b0b; border:none; text-align: right; font-size:18px; padding-right:10px; }

                        </style>
                        <!--<div class="money_title">
                            <div class="t_white rolling-title">롤링조건 </div>

                            <div class="rolling-box">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>%</th>
                                            <th>스포츠</th>
                                            <th>카지노</th>
                                            <th>미니게임/가상스포츠/</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr id="percent" style="display: 'block';" class="percent-tr">
                                        <td>0%</td>
                                        <td colspan="3">기본 롤링 100%가 적용됩니다.</td>
                                    </tr>
                                    <tr id="5_percent" style="display: none;" class="percent-tr">
                                        <td>5%</td>
                                        <td>단폴 200%<br>두폴더 100%<br>새폴더 이상 100%</td>
                                        <td>250%</td>
                                        <td>200%</td>
                                    </tr>
                                    <tr id="10_percent" style="display: none;" class="percent-tr">
                                        <td>10%</td>
                                        <td>단폴 300%<br>두폴더 200%<br>새폴더 이상 100%</td>
                                        <td>200%</td>
                                        <td>350%</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="con1 con11">
                            <div>보너스 금액<span class="t_orange"><input type="text" name="bonus_price" id="bonus_price" value="" class="t_orange" readonly>원</span>
                                <code class="bonus-save">보너스받기</code>
                                <code class="btn1 no-bonus">보너스받지않기</code>
                            </div>
                        </div>-->

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
            /*var bonus_price   = document.getElementById( "bonus_price" );
            var rolling       = $('input[name="bonus"]:checked').val();*/
            var name          = $('#name').val();

            var charge_type = "Money";

            //alert(charge_type);
            if(name == ''){
                swal('','입금자명을 입력해주세요.','warning');
                $('#name').focus();
                return false;
            }
            if ( !charge_price.value.trim() || !checkPointorMoney(charge_price.value) ) {
                swal('',"충전은 10,000원 단위로 하실수 있습니다.",'warning');
                charge_price.focus();
            } else {
                if ( confirm("충전 요청을 하시겠습니까?") ) {
                    f.HAF_Value_0.value = "RequestMoneyCharge";
                    f.HAF_Value_1.value = 'Money';
                    f.HAF_Value_2.value = removeComma(charge_price.value);
                    /*f.HAF_Value_3.value = removeComma(bonus_price.value);*/
                    /*f.HAF_Value_4.value = rolling;*/
                    f.HAF_Value_5.value = name;


                    f.method = "POST";
                    f.action = "/action/money_action.php";
                    f.submit();
                };
            };
        };
    </script>

<?php
include_once $root_path.'/include/footer.php';
?>