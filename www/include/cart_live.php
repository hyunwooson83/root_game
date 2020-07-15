
<input type="hidden" value="1" id="cartCnt" name="cartCnt" />
<input type="hidden" id="gtg" name="gtg" value="1" />
<div class="right_con" style="position: relative; right:0px; top:0px;">
    <div class="bl_betting_slip_box">
        <div class="bl_right_title">
            <h1>Betting Slip</h1>
            <h2 style="padding-right:0px !important;">

                <span id="_position_">카트이동</span>
                <input type="checkbox" id="betting_slip_chk" style="display: block; margin-top: 14px;" />
                <label for="betting_slip_chk"></label>
            </h2>
        </div>
        <ul class="betting_cart">
            <div class="empty">베팅카트가 비었습니다.</div>
        </ul>


        <!-- Betting Cart -->
        <div class="betting_slip_text">
            <ul class="betting_slip_top">
                <li>
                    <span>보유금액</span>
                    <em><font><?php echo number_format($meminfo['M_Money']);?></font></em>
                </li>
                <li>
                    <span>최소베팅금액</span>
                    <em>10,000</em>
                </li>
                <li>
                    <span>최고베팅금액</span>
                    <em><?php echo number_format($LEVELLIMITED['Sports_Max_Bet_Money']); ?></em>
                </li>
                <li>
                    <span>최대적중금액</span>
                    <em><?php echo number_format($LEVELLIMITED['Sports_Max_Hit_Mone']); ?></em>
                </li>
                <li>
                    <span>배당률합계</span>
                    <em><B id="BettingQuota" class="cart-rate-over cart-rate-under cart-rate-hwin cart-rate-hlose cart-rate-win cart-rate-draw cart-rate-lose">1.00</B></em>
                </li>
                <li>
                    <span>베팅금액</span>
                    <em><input type="text" id="BettingMoney" value="10,000"></em>
                </li>
                <li>
                    <span>적중예상금액</span>
                    <em id="BettingQuotaMoney">0</em>
                </li>
            </ul>

            <div class="betting_slip_btn">
                <ol>
                    <li class="cart-price1" data-money="10000">10,000</li>
                    <li class="cart-price1" data-money="50000">50,000</li>
                    <li class="cart-price1" data-money="100000">100,000</li>
                    <li class="cart-price1" data-money="250000">250,000</li>
                    <li class="cart-price1" data-money="500000">500,000</li>
                    <li class="cart-price1" data-money="1000000">1,000,000</li>
                    <li class="orange cart-price1" data-money="<?php echo ($meminfo['M_Money']/2);?>" data-text="half">HALF</li>
                    <li class="orange cart-price1" data-money="<?php echo $meminfo['M_Money'];?>" data-text="max">MAX</li>
                    <li class="orange cart-price1" data-money="0">RESET</li>
                </ol>
                <h2 id="buyGameBtnLive" data-max_bet="<?php echo $LEVELLIMITED['Sports_Max_Bet_Money'];?>"  data-max_hit="<?php echo $LEVELLIMITED['Sports_Max_Hit_Mone'];?>">베팅하기</h2>

            </div>
        </div>
    </div>
    <!-- Betting Slip Box -->
</div>

<script>
    $(document).ready(function(){
        $(window).scroll(function() {
            var position = $(window).scrollTop();
            if(position>177){
                pos = position-170;
                $(".right_con").stop().animate({"top":pos+"px"},0);
            }
            if(position <= 177){
                $(".right_con").stop().animate({"top":0+"px"},0);
            }
        });
    });
    $('#_position_').on('click',function(){
        var text = $(this).text();
        var pos = 0;
        if(text == '고정'){
            $(this).text('카트이동');
            $('#betting_slip_chk').attr('checked',false);
            $(window).scroll(function() {
                var position = $(window).scrollTop();
                if(position>177){
                    pos = position-170;
                    $(".right_con").stop().animate({"top":pos+"px"},0);
                }
                if(position <= 177){
                    $(".right_con").stop().animate({"top":0+"px"},0);
                }
            });
        } else {
            $(this).text('카트고정');
            $('#betting_slip_chk').attr('checked',true);
            $(window).scroll(function() {
                $(".right_con").stop().animate({"top":0+"px"},0);
            });
        }
    });

    $('#betting_slip_chk').on('click',function(){
        if($(this).is(':checked')==true){
            $('#_position_').text('고정');
            $(window).scroll(function() {
                $(".right_con").stop().animate({"top":0+"px"},0);
            });
        } else {
            $('#_position_').text('이동');
            $(window).scroll(function() {
                var position = $(window).scrollTop();
                if(position>177){
                    pos = position-170;
                    $(".right_con").stop().animate({"top":pos+"px"},0);
                }
                if(position <= 177){
                    $(".right_con").stop().animate({"top":0+"px"},0);
                }
            });
        }
    });
</script>