<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

    if ( !$_SESSION['S_Key'] ) {
        swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }

    $row = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");

    #총판 타입이 루징이라면

    if ($row['M_Shop_Level'] > 0 && $row['M_ShopPayType'] == 'L') {
        //if($_SESSION['S_ID']!='bin01') {
            //$day = date('d');
            //if(!in_array($day,array(1,16))){
            swal_move('루징 총판회원은 매월 1일과 16일 관리자 확인 후 포인트 머니 전환이 가능합니다.', '../../../main');
            exit;
            //}
        //}
    }

    //echo $row[M_Type];
    /*if($row['M_Type']==2){
        swal_move('총판 및 테스트 아이디는 환전신청 하실 수 없습니다.','/game/cross/');
        exit;
    }

    //echo $row['M_Refund_YN'];
    if($row['M_Refund_YN']=='N'){
        swal_move('환전신청 하실 수 없습니다.','/game/cross/');
        exit;
    }

    if($lib24c->member_info['M_Point']<10000){
        msgMove('보유포인트가 부족합니다.','/game/cross/');
    }*/
?>

    <input type="hidden" name="my_point" id="my_point" value="<?=$lib24c->member_info['M_Point'];?>" />
    <input type="hidden" name="charge_name" id="charge_name" value="<?=$row1['M_BankOwner']?>"  />
    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>포인트 전환 신청내역</span>
                <em>POINT EXCHANGE LIST</em>
            </h1>
            <ul class="sub_title_category">
                <li onclick="location.href='/m/mypage/point/exchange/'" class="active">포인트전환신청</li>
                <li onclick="location.href='/m/mypage/point/list/'">포인트적립내역</li>
                <li onclick="location.href='/m/mypage/point/exchangelist/'">포인트전환내역</li>
            </ul>
        </div>

        <div class="sub_mypage_wrap">
            <dl class="cash_caution">
                <dt>필독사항</dt>
                <dd><var>⊙</var><span class="strong">포인트 전환 시 보유머니로 충전됩니다.</span></dd>
                <dd><var>⊙</var><span>최소 10,000 포인트 이상 신청가능합니다.</span></dd>
                <dd><var>⊙</var><span>자세한 문의사항은 고객센터를 이용해 주시기 바랍니다.</span></dd>
            </dl>
            <div class="sub_cash_box">
                <h1>
                    <span>전환 포인트 입력</span>
                </h1>
                <h2>
                    <span>현재보유포인트</span>
                    <label><?php echo number_format($meminfo['M_Point']);?> P</label><BR /><BR />
                    <!--<span>전환예정포인트</span>
                    <label class="yellow">100,000 P</label><BR /><BR />
                    <span>전환이후포인트</span>
                    <label class="red">50,000 P</label><BR /><BR />-->
                    <div>
                        <span>전환포인트</span>
                        <span><input type="text" name="charge_price" id="charge_price" class="i1" placeholder="직접입력"><span>원</span></span>
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
                <div class="cash_submit_btn" onclick="Action_Write();">보유포인트 전환 신청하기</div>
            </div>
        </div>

    </div>


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

            //alert(charge_type);
            if ( !charge_price.value.trim() || !checkPointorMoney(charge_price.value) ) {
                alert("전환은 10,000원 단위로 하실수 있습니다.");
                charge_price.focus();
            } else if ( charge_type == "Point" && parseInt(my_point.innerHTML) < parseInt(charge_price.value) ) {
                alert("보유포인트를 초과해서 충전요청을 하실수 없습니다.");
                charge_price.focus();
            } else {
                if ( confirm("포인트 전환 하시겠습니까?") ) {
                    f.HAF_Value_0.value = "RequestMoneyCharge";
                    f.HAF_Value_1.value = charge_type;
                    f.HAF_Value_2.value = charge_price.value;
                    f.HAF_Value_3.value = charge_name.value.trim();

                    f.method = "POST";
                    f.action = "/m/action/money_action.php";
                    f.submit();
                };
            };
        };
    </script>
<?php
include_once $root_path.'/include/footer.php';
?>