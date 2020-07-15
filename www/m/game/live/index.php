<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
include_once $root_path.'/m/include/header.php';
?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(1)").addClass('active');
        });
    </script>
    <div class="sub_wrap">
        <div class="sub_con">
            <div class="center_con">
                <div class="score_board_category">
                    <ul>
                        <li onclick="location.href='/live_list.html'">라이브<var></var></li>
                        <li onclick="location.href='/cross_list.html'" class="active">크로스<var></var></li>
                        <li>스페셜<var></var></li>
                        <li>승무패<var></var></li>
                        <li>핸디캡<var></var></li>
                        <li>언오버<var></var></li>
                        <li>전반전<var></var></li>
                        <li>코너킥<var></var></li>
                    </ul>
                </div>

                <div class="score_board_contents">
                    <div class="root_display">
                        <font>라이브</font><B>></B> 축구(100)
                        <ul>
                            <li class="active">마감순정렬</li>
                            <li>리그별정렬</li>
                            <li>국가별정렬</li>
                        </ul>
                    </div> <!-- Root Display -->

                    <div class="score_board_sub_category">
                        <table class="five">
                            <tr>
                                <td class="active">
                                    <img src="/img/icon_all.png" />
                                    <span>전체</span>
                                    <em>(200)</em>
                                    <var></var>
                                </td>
                                <td>
                                    <img src="/img/icon_soccer.png" />
                                    <span>축구</span>
                                    <em>(100)</em>
                                    <var></var>
                                </td>
                                <td>
                                    <img src="/img/icon_basketball.png" />
                                    <span>농구</span>
                                    <em>(30)</em>
                                    <var></var>
                                </td>
                                <td>
                                    <img src="/img/icon_baseball.png" />
                                    <span>야구</span>
                                    <em>(30)</em>
                                    <var></var>
                                </td>
                                <td>
                                    <img src="/img/icon_volley.png" />
                                    <span>배구</span>
                                    <em>(10)</em>
                                    <var></var>
                                </td>
                            </tr>
                        </table>

                    </div> <!-- Score Board Sub Category -->




                    <div class="bl_live_betting_middle cross">
                        <table>

                            <tbody>
                            <!-- 경기목록 시작 -->
                            <tr> <!-- 리그시작  -->
                                <th colspan="9">
                                    <img src="/img/live_game.jpg" alt="">
                                </th>
                            </tr> <!-- 리그 끝 -->



                            <!-- 경기목록 끝 -->
                            </tbody>
                        </table>
                    </div> <!-- Live Betting Middle -->
                </div> <!-- Bet List Left -->
            </div> <!-- Center Container -->

            <div class="right_con">

                <div class="bl_betting_slip_box">
                    <div class="bl_right_title">
                        <h1>Betting Slip</h1>
                        <h2>
                            <span>고정</span>
                            <input type="checkbox" id="betting_slip_chk" />
                            <label for="betting_slip_chk"></label>
                        </h2>
                    </div>
                    <ul class="betting_cart">
                        <!--<div class="empty">베팅카트가 비었습니다.</div>-->
                        <li>
                            <span title="삭제"><img src="/img/icon_betting_cart_close.png" /></span>
                            <!--<code>
                                <span title="삭제"><img src="/img/icon_betting_cart_close.png" /></span>
                                <!--<em>
                                    <input type="checkbox" id="betting_cart_chk" />
                                    <label for="betting_cart_chk"></label>
                                    <var>축구 - Total(경기)</var>
                                </em>
                            </code>-->
                            <h1 class="first select">[홈팀] 맨체스터 유나이티드</h1>
                            <h1>[원정] 첼시</h1>
                            <!--<h1>International - Friendlies Women</h1>-->
                            <h2>
                                <!--<span>TOTAL : 3.50</span>-->
                                <em>승 <font>@</font> 2.29</em>
                                <!--<var>※ 라이브는 단폴만 가능합니다</var>-->
                            </h2>
                        </li>
                    </ul>


                    <!-- Betting Cart -->
                    <div class="betting_slip_text">
                        <ul class="betting_slip_top">
                            <li>
                                <span>보유금액</span>
                                <em><font>2,320,000</font></em>
                            </li>
                            <li>
                                <span>최소베팅금액</span>
                                <em>5,000</em>
                            </li>
                            <li>
                                <span>최고베팅금액</span>
                                <em>2,000,000</em>
                            </li>
                            <li>
                                <span>최대적중금액</span>
                                <em>10,000,000</em>
                            </li>
                            <li>
                                <span>배당률합계</span>
                                <em><B>3.00</B></em>
                            </li>
                            <li>
                                <span>베팅금액</span>
                                <em><input type="text" value="100,000"></em>
                            </li>
                            <li>
                                <span>적중예상금액</span>
                                <em>300,000</em>
                            </li>
                        </ul>
                        <div class="betting_slip_btn">
                            <ol>
                                <li>5,000</li>
                                <li>10,000</li>
                                <li>50,000</li>
                                <li>100,000</li>
                                <li>300,000</li>
                                <li>500,000</li>
                                <li class="orange">HALF</li>
                                <li class="orange">MAX</li>
                                <li class="orange">RESET</li>
                            </ol>
                            <h2>베팅하기</h2>
                        </div>
                    </div>
                </div>
                <!-- Betting Slip Box -->


            </div> <!-- Right Container -->

        </div> <!-- Sub Container -->
    </div>
    <div class="wrap_mask"></div>
    <div class="live_popup">
        <div class="live_wrap">
            <div class="live_head">
                <span class="top_logo"><img src="./img/top_logo.png" onclick="location.href='./gobet.html'"></span>
                <span class="close" onclick="$('.wrap_mask').hide();$('.live_popup').hide();"><img src="/img/pop_close_icon.png" /></span>
            </div>
            <div class="live_con">
                <div class="live_left">
                    <div class="live_box">
                        <div class="top">LIVE | [축구] 멘체스터 유나이티드 VS 첼시</div>
                        <div class="con"></div>
                    </div>
                    <div class="live_list_subject">
                        <span><img src="/img/pop_live_icon2.png"></span>
                        맨체스터 유나이티드 VS 첼시
                        <em>경기일시 : 03-21 18:00</em>
                    </div>
                    <div class="bl_live_betting_middle live_list_content">
                        <table>
                            <thead>
                            <tr>
                                <td width="66">종목</td>
                                <td width="66">구분</td>
                                <td width="318">승 (홈팀) <img src="/img/icon_up.png" /></td>
                                <td>무</td>
                                <td width="318">패 (원정) <img src="/img/icon_down.png" /></td>
                                <td width="60" style="min-width:60px">베팅</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr> <!-- TR START -->
                                <td title="종목">축구</td>
                                <td title="구분"><div class="flag">승무패</div></td>
                                <td title="승/홈/오버">
                                    <div class="bl_btn bl_text_btn long">
                                        <span class="left">맨체스터 유나이티드</span>
                                        <span class="right">2.39</span>
                                    </div>
                                </td>

                                <td title="무">
                                    <div class="bl_btn bl_text_btn middle">4.12</div>
                                </td>

                                <td title="패/원정/언더">
                                    <div class="bl_btn bl_text_btn long">
                                        <span class="left">2.39</span>
                                        <span class="right">첼시</span>
                                    </div>
                                </td>
                                <td class="bl_last_box">
                                    <div class="bl_btn bl_text_btn stay_btn active">베팅</div>
                                </td>
                            </tr>
                            <tr> <!-- TR START -->
                                <td title="종목">축구</td>
                                <td title="구분"><div class="flag">핸디캡</div></td>
                                <td title="승/홈/오버">
                                    <div class="bl_btn bl_text_btn long">
                                        <span class="left">맨체스터 유나이티드</span>
                                        <span class="right"><em><img src="/img/pop_live_icon3.png"></em>2.39</span>
                                    </div>
                                </td>

                                <td title="무">
                                    <div class="bl_btn bl_text_btn middle">4.12</div>
                                </td>

                                <td title="패/원정/언더">
                                    <div class="bl_btn bl_text_btn long">
                                        <span class="left">2.39<em><img src="/img/pop_live_icon4.png"></em></span>
                                        <span class="right">첼시</span>
                                    </div>
                                </td>
                                <td class="bl_last_box">
                                    <div class="bl_btn bl_text_btn stay_btn">베팅</div>
                                </td>
                            </tr>
                            <tr> <!-- TR START -->
                                <td title="종목">축구</td>
                                <td title="구분"><div class="flag">언오버</div></td>
                                <td title="승/홈/오버">
                                    <div class="bl_btn bl_text_btn long">
                                        <span class="left">맨체스터 유나이티드</span>
                                        <span class="right">2.39</span>
                                    </div>
                                </td>

                                <td title="무">
                                    <div class="bl_btn bl_text_btn middle">4.12</div>
                                </td>

                                <td title="패/원정/언더">
                                    <div class="bl_btn bl_text_btn long">
                                        <span class="left">2.39</span>
                                        <span class="right">첼시</span>
                                    </div>
                                </td>
                                <td class="bl_last_box">
                                    <div class="bl_btn bl_text_btn stay_btn">베팅</div>
                                </td>
                            </tr>
                            <tr> <!-- TR START -->
                                <td title="종목">축구</td>
                                <td title="구분"><div class="flag">기타1</div></td>
                                <td title="승/홈/오버">
                                    <div class="bl_btn bl_text_btn long">
                                        <span class="left">맨체스터 유나이티드</span>
                                        <span class="right">2.39</span>
                                    </div>
                                </td>

                                <td title="무">
                                    <div class="bl_btn bl_text_btn middle">4.12</div>
                                </td>

                                <td title="패/원정/언더">
                                    <div class="bl_btn bl_text_btn long">
                                        <span class="left">2.39</span>
                                        <span class="right">첼시</span>
                                    </div>
                                </td>
                                <td class="bl_last_box">
                                    <div class="bl_btn bl_text_btn stay_btn">베팅</div>
                                </td>
                            </tr>
                            <tr> <!-- TR START -->
                                <td title="종목">축구</td>
                                <td title="구분"><div class="flag">기타2</div></td>
                                <td title="승/홈/오버">
                                    <div class="bl_btn bl_text_btn long">
                                        <span class="left">맨체스터 유나이티드</span>
                                        <span class="right">2.39</span>
                                    </div>
                                </td>

                                <td title="무">
                                    <div class="bl_btn bl_text_btn middle">4.12</div>
                                </td>

                                <td title="패/원정/언더">
                                    <div class="bl_btn bl_text_btn long">
                                        <span class="left">2.39</span>
                                        <span class="right">첼시</span>
                                    </div>
                                </td>
                                <td class="bl_last_box">
                                    <div class="bl_btn bl_text_btn stay_btn">베팅</div>
                                </td>
                            </tr>
                            <tr> <!-- TR START -->
                                <td title="종목">축구</td>
                                <td title="구분"><div class="flag">승무패</div></td>
                                <td title="승/홈/오버">
                                    <div class="bl_btn bl_text_btn long">
                                        <span class="left">맨체스터 유나이티드</span>
                                        <span class="right">2.39</span>
                                    </div>
                                </td>

                                <td title="무">
                                    <div class="bl_btn bl_text_btn middle">4.12</div>
                                </td>

                                <td title="패/원정/언더">
                                    <div class="bl_btn bl_text_btn long">
                                        <span class="left">2.39</span>
                                        <span class="right">첼시</span>
                                    </div>
                                </td>
                                <td class="bl_last_box">
                                    <div class="bl_btn bl_text_btn stay_btn">베팅</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="live_right">
                    <div class="bl_betting_slip_box">
                        <div class="bl_right_title">
                            <h1>Betting Slip</h1>
                            <h2>
                                <span>고정</span>
                                <input type="checkbox" id="betting_slip_chk" />
                                <label for="betting_slip_chk"></label>
                            </h2>
                        </div>
                        <ul class="betting_cart">
                            <!--<div class="empty">베팅카트가 비었습니다.</div>-->
                            <li>
                                <code>
                                    <span title="삭제"><img src="/img/icon_betting_cart_close.png" /></span>
                                    <em>
                                        <input type="checkbox" id="betting_cart_chk" />
                                        <label for="betting_cart_chk"></label>
                                        <var>축구 - Total(경기)</var>
                                    </em>
                                </code>
                                <h1 class="first">[홈팀] 맨체스터 유나이티드</h1>
                                <h1>[원정] 첼시</h1>
                                <h1>International - Friendlies Women</h1>
                                <h2>
                                    <span>TOTAL : 3.50</span>
                                    <em>언더 <font>@</font> 2.29</em>
                                    <var>※ 라이브는 단폴만 가능합니다</var>
                                </h2>
                            </li>
                        </ul> <!-- Betting Cart -->
                        <div class="betting_slip_text">
                            <ul class="betting_slip_top">
                                <li>
                                    <span>보유금액</span>
                                    <em><font>2,320,000</font></em>
                                </li>
                                <li>
                                    <span>최소베팅금액</span>
                                    <em>5,000</em>
                                </li>
                                <li>
                                    <span>최고베팅금액</span>
                                    <em>2,000,000</em>
                                </li>
                                <li>
                                    <span>최대당첨금</span>
                                    <em>10,000,000</em>
                                </li>
                                <li>
                                    <span>예상배당률</span>
                                    <em><B>3.00</B></em>
                                </li>
                                <li>
                                    <span>베팅금액</span>
                                    <em><input type="text" value="100,000"></em>
                                </li>
                                <li>
                                    <span>당첨금</span>
                                    <em>300,000</em>
                                </li>
                            </ul>
                            <div class="betting_slip_btn">
                                <ol>
                                    <li>HALF</li>
                                    <li>MAX</li>
                                    <li>RESET</li>
                                </ol>
                                <h2>베팅하기</h2>
                            </div>
                        </div>
                    </div> <!-- Betting Slip Box -->

                </div>
            </div>

        </div>
    </div>


<?php include_once($root_path.'/include/footer.php'); ?>