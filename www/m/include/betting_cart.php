<input type="hidden" value="1" id="cartCnt" name="cartCnt" />
<input type="hidden" id="gtg" name="gtg" value="1" />
<div class="menu cart" id="menu_cart" data-menu="cart">
    <div>
        <ul class="cart_pick">
            <li>
                배팅카트가 비었습니다.
            </li>
        </ul>

        <ul class="cart_info">
            <li>
                <em>보유금액</em>
                <span class="cart_info_hit"><?php echo number_format($meminfo['M_Money']);?></span>
            </li>
            <li>
                <em>베팅최소금액</em>
                <span>5,000</span>
            </li>
            <li>
                <em>베팅최고금액</em>
                <span><?php echo number_format($LEVELLIMITED['Sports_Max_Bet_Money']); ?></span>
            </li>
            <li>
                <em>적중최고금액</em>
                <span><?php echo number_format($LEVELLIMITED['Sports_Max_Hit_Mone']); ?></span>
            </li>
        </ul>

        <div class="cart_bet">
            <div>
                <em>배당률 합계</em>
                <span><font id="BettingQuota">1.00</font></span>
            </div>
            <div>
                <em>적중 예상금액</em>
                <span><font id="BettingQuotaMoney">0</font></span>
            </div>
            <div>
                <em><var>베팅금액</var></em>
                <span><input type="text" class="cart_bet_won" id="BettingMoney" value="10,000"></input>
			</span></div>

            <dl class="cash_num_btn" id="number_box99">
                <dd>1</dd>
                <dd>2</dd>
                <dd>3</dd>
                <dd>4</dd>
                <dd>5</dd>
                <dd>6</dd>
                <dt></dt>
                <dd>7</dd>
                <dd>8</dd>
                <dd>9</dd>
                <dd>0</dd>
                <dd class="type2"><img src="/mobile/img/icon_left_arrow.png"></dd>
                <dd class="type3" onclick="numberbox_btn(99)"><span>닫기</span></dd>
            </dl>
            <ol>
                <li class="cart-price" data-money="10000">10,000</li>
                <li class="right cart-price" data-money="50000">50,000</li>
                <li class="right cart-price"  data-money="100000">100,000</li>
                <li class="cart-price"  data-money="250000">250,000</li>
                <li class="cart-price"  data-money="500000">500,000</li>
                <li data-money="1000000" class="right cart-price">1,000,000</li>
                <li class="blue cart-price" data-money="<?php echo ($meminfo['M_Money']/2);?>" data-text="half">HALF</li>
                <li class="blue2 cart-price" data-money="<?php echo $meminfo['M_Money'];?>" data-text="max">MAX</li>
                <li class="red cart-price" data-money="0">다시입력</li>
            </ol>
            <code id="buyGameBtn" data-max_bet="<?php echo $LEVELLIMITED['Sports_Max_Bet_Money'];?>"  data-max_hit="<?php echo $LEVELLIMITED['Sports_Max_Hit_Mone'];?>">베팅하기</code>
        </div>

    </div>
</div>
