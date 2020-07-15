<div class="minigame_info type2">
    <em>게임분류 <b><?php echo $game_type_gubun;?></b></em>
    <div id="selBet">
        게임선택 <span class="tx"></span>
    </div>
    <label>배당률 <b id="b"><input type="text" class="val-coin" id="betrate_sum" name="betrate_sum" value="" readonly="" style="background-color: transparent; font-size:16px; color:#fff; border:none; text-align: left;"></b></label>
</div>
<div class="minigame_cart">
    <div class="minigame_cart_input">
        <em>적중금액</em>
        <input class="bet-dividend-view mini_betting_input_hit" id="betexpect" name="betexpect" value="0" type="text" style="letter-spacing: 1px;" >
    </div>
    <div class="minigame_cart_input">
        <em>베팅금액</em>
        <input name="bet-amount-view" class="font_gobet2" id="betball" name="betball" value="0" type="text" style="letter-spacing: 1px;" >
    </div>
    <dl>
        <dd data-value="10,000" class="select-amount btn-select-coin" data-money="10000">10,000</dd>
        <dd data-value="30,000" class="select-amount btn-select-coin" data-money="30000">30,000</dd>
        <dd data-value="50,000" class="select-amount btn-select-coin" data-money="50000">50,000</dd>
        <dd data-value="100,000" class="select-amount btn-select-coin" data-money="100000">100,000</dd>
        <dd data-value="500,000" class="select-amount btn-select-coin" data-money="500000">500,000</dd>
        <dd data-value="1,000,000" class="select-amount btn-select-coin" data-money="1000000">1,000,000</dd>
    </dl>
    <!--<div class="minigame_cart_input">
        <em>직접입력</em>
        <input value="0" class="font_wred" type="number">
    </div>-->
    <dl>
        <dd class="blue select-amount" id="other-money">잔돈</dd>
        <dd data-value="1,000,000" class="select-amount btn-select-coin blue2" data-money="<?php echo $meminfo['M_Money'];?>">올인</dd>
        <dd class="red reset-selected-amount btn-select-coin" data-money="0">초기화</dd>
    </dl>
    <div class="minigame_cart_btn">
        <div id="btn_betting" data-login="<?php echo $_SESSION['S_Key']; ?>" data-datetime="<?php echo $gdatetime[0]; ?>" data-gkey="" data-glkey="" data-allrate="" data-bet-selected="">베팅하기</div>
        <!--<em onclick="result_open();">최근 회차별 통계</em>-->
        <span style="width: 99%;" onclick="location.href='/m/mypage/betlist/minigame/power/'">전체 베팅내역</span>
    </div>
</div>
<input type="hidden" name="other_money" id="other_money" value="100000">
<input type="hidden" name="other_money_use" id="other_money_use" value="n">