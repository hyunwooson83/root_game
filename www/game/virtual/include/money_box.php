<dl class="mini_bettingslip" id="games-betslip">
    <input type="hidden" id="odd-id" value="0" autocomplete="off">
    <input type="hidden" id="odd-value" value="0" autocomplete="off">
    <input type="hidden" id="amount-placed" value="0" autocomplete="off">

    <dt class="col full cart_info">
        <div>
            <em>게임분류</em>
            <span class="f_yellow"><?php echo $game_type_gubun;?></span>
        </div>
        <div id="selBet">
            <em>게임선택</em>
            <span class="tx"></span>
        </div>
        <div id="betRate">
            <em>배당률</em>
            <span id="b"><input type="text" class="val-coin" id="betrate_sum" name="betrate_sum" value="" readonly="" style="background-color: transparent; font-size:16px; color:#fff; border:none; text-align: left;"></span>
        </div>
    </dt>
    <dd>
        <h2>베팅 금액을 선택하세요</h2>
        <ul>
            <li>
                <div class="bet-money">
                    <input name="bet-amount-view" class="bet-amount" id="betball" name="betball" value="0" type="text" style="letter-spacing: 1px;" >
                    <span>베팅 금액</span>
                </div>
                <div>
                    <input class="bet-dividend-view mini_betting_input_hit" id="betexpect" name="betexpect" value="0" type="text" style="letter-spacing: 1px;" >
                    <span>적중 예상금액</span>
                </div>
            </li>
            <li>
                <code data-value="10,000" class="select-amount btn-select-coin" data-money="10000">10,000</code>
                <code data-value="50,000" class="select-amount btn-select-coin" data-money="50000">50,000</code>
                <code data-value="100,000" class="select-amount btn-select-coin" data-money="100000">100,000</code>
                <code data-value="250,000" class="select-amount btn-select-coin" data-money="250000">250,000</code>
                <code data-value="500,000" class="select-amount btn-select-coin" data-money="500000">500,000</code>
                <code data-value="1,000,000" class="select-amount btn-select-coin" data-money="1000000">1,000,000</code>
            </li>
            <li>
                <code class="mini_betcalc_btn2 select-amount" id="other-money">잔돈</code>
                <code class="mini_betcalc_btn3 reset-selected-amount btn-select-coin" data-money="0">초기화</code>
                <div>
                    <input name="betExp4" id="direct_input_money"  value="" class="select-amount-text" type="text" placeholder="" style="width:283px; letter-spacing: 1px;" onKeyup="this.value=this.value.replace(/[^0-9+,]/g,'');">
                    <span>직접 입력</span>
                </div>
            </li>
        </ul>
        <div class="mini_betting_confirm place-bet" id="btn_betting" data-login="<?php echo $_SESSION['S_Key']; ?>" data-datetime="<?php echo $gdatetime[0]; ?>" data-gkey="" data-glkey="" data-allrate="" data-bet-selected="">베팅하기</div>
    </dd>
    <div class="bet-disable" id="preloader" >
        <div style="position: absolute; font-size:30px; left: 30%;" id="disable-text">경기를 생성중입니다. 잠시만 기댜려주세요.</div>
    </div>
    <input type="hidden" name="other_money" id="other_money" value="100000">
    <input type="hidden" name="other_money_use" id="other_money_use" value="n">
</dl>