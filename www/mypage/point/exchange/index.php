<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

if ( !$_SESSION['S_Key'] ) {
    swal_move('로그인이 필요한 페이지 입니다.', 'login');
}

    $row = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");

    #총판 타입이 루징이라면

    if ($row['M_Shop_Level'] > 0 && $row['M_ShopPayType'] == 'L') {
        //if($_SESSION['S_ID']!='bin01') {
            /*$day = date('d');
            if(!in_array($day,array(1,16))){*/
            //swal_move('루징 총판회원은 1일과 16일만 포인트 머니 전환이 가능합니다.','../../../main');
            swal_move('루징 총판회원은 관리자 확인 후 포인트 머니 전환이 가능합니다.', '../../../main');
            exit;
            //}
       //}
    }
    //echo $row[M_Type];
    /*if($row['M_Type']==2){
        swal_move('총판 및 테스트 아이디는 환전신청 하실 수 없습니다.','/game/cross/');
        exit;
    }*/

    //echo $row['M_Refund_YN'];
    /*if($row['M_Refund_YN']=='N'){
        swal_move('환전신청 하실 수 없습니다.','/game/cross/');
        exit;
    }*/

    /*if($lib24c->member_info['M_Point']<10000){
        msgMove('보유포인트가 부족합니다.','/game/cross/');
    }*/
?>

    <input type="hidden" name="my_point" id="my_point" value="<?=$lib24c->member_info['M_Point'];?>" />
    <input type="hidden" name="charge_name" id="charge_name" value="<?=$row1['M_BankOwner']?>"  />
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">포인트 전환</div>
                <div class="title2">POINT EXCHANGE</div>
            </div>
            <div class="sub_box money_box">
                <div class="mypage_menu1">
                    <a href="/mypage/betlist/">베팅내역</a>
                    <a href="/money/charge/list/">충전내역</a>
                    <a href="/money/refund/list/">환전내역</a>
                    <a href="/mypage/point/exchange/list/" class="active">포인트내역</a>
                    <!--<a href="/mypage/recom/">총판관리</a>-->
                    <a href="/mypage/message/">쪽지관리</a>
                    <a href="/mypage/member/modify/">회원정보수정</a>
                </div>
                <div class="mypage_menu2">
                    <a href="/mypage/point/exchange/" class="active">포인트전환신청</a>
                    <a href="/mypage/point/list/">포인트적립내역</a>
                    <a href="/mypage/point/exchangelist/">포인트전환내역</a>
                </div>
                <div class="money_caution">
                    <h1><img src="/img/img_caution.png" />필독사항</h1>
                    <ul>
                        <li class="point"><strong>⊙</strong> 포인트 전환 시 보유머니로 충전됩니다.</li>
                        <li><strong>⊙</strong> 최소 10,000 포인트 이상 신청가능합니다.</li>
                        <li><strong>⊙</strong> 자세한 문의사항은 고객센터를 이용해 주시기 바랍니다.</li>
                    </ul>
                </div>

                <div class="money_con3">
                    <div class="money_title">
                        <span class="t_white">전환신청하기</span><var>|</var>보유머니로 충전됩니다.
                    </div>
                    <div class="content">
                        <div class="con1">
                            <div>현재 잔여포인트<span class="t_green"><?php echo $lib24c->member_info['M_Point']; ?> P</span></div>
                            <!--<div>전환예정포인트<span class="t_yellow">100,000 P</span></div>
                            <div>전환후 남은 포인트<span class="t_orange">50,000 P</span></div>-->
                        </div>
                        <div class="money_choice">
                            <div class="left">신청금액선택</div>
                            <div class="right">
                                <div class="input">
                                    <input type="text" name="charge_price" id="charge_price" class="i1" placeholder="직접입력"><span>원</span>
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
                                <div class="ment t_yellow">※최소 일만포인트 이상 신청가능합니다.</div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="money_btn_wrap">
                    <span onclick="Action_Write();">신청하기</span>
                </div>

            </div>
        </div><!-- sub_wrap -->
    </div><!-- sub_bg -->


    <script>
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
            var charge_name   = document.getElementById( "charge_name" );

            var charge_type = "Point";

            
            if ( !checkPointorMoney(charge_price.value.trim()) || charge_price.value < 10000 ) {
                swal("","전환은 10,000원 부터 10,000원 단위로만 하실수 있습니다.","warning");
                charge_price.focus();
            } else if ( charge_type == "Point" && parseInt(my_point.innerHTML) < parseInt(charge_price.value) ) {
                swal("","보유포인트를 초과해서 충전요청을 하실수 없습니다.","warning");
                charge_price.focus();
            } else {
                if ( confirm("포인트 전환 하시겠습니까?") ) {
                    f.HAF_Value_0.value = "RequestMoneyCharge";
                    f.HAF_Value_1.value = charge_type;
                    f.HAF_Value_2.value = charge_price.value;
                    f.HAF_Value_3.value = charge_name.value.trim();

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