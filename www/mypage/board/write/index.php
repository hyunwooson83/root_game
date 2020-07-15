<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';
    
    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) {
        //swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }

    if($SITECONFIG['M_Board_YN']!='Y'){
        msg('정상적인 방법으로 접근하세요.'); back();
    }

    $board_title = $lib24c->Check_Board('board');
?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(10)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">게시판</div>
                <div class="title2">TREND BOARD</div>
            </div>

            <div class="sub-box">

                <div class="board_wrap">

                    <div class="qna_cate">
                        <a class="none on">전체</a>
                        <a>스포츠북</a>
                        <a>가상게임</a>
                        <a class="none">미니게임</a>
                        <a>회원문의</a>
                        <a>정산문의</a>
                        <a>기타문의</a>
                    </div>
                    <input type="text" name="b_subject" id="b_subject" class="sub_board_write_title" placeholder="제목을 입력해주세요." />
                    <h2 class="sub_board_write_info add_btn" onClick="$(this).removeClass('add_btn'); $('table.hide').removeClass('hide'); $('.popup_box').removeClass('hide');">베팅내역 첨부</h2>

                    <div class="sub_board_view_con">
                        <table class="table-black mypage hide">
                            <thead>
                            <tr>
                                <td>베팅구분</td>
                                <td width="260">승(홈)<img src="/_go/renewal/img/icon_up.png" class="blink"></td>
                                <td>무</td>
                                <td width="260">패(원정)<img src="/_go/renewal/img/icon_down.png" class="blink"></td>
                                <td>점수</td>
                                <td>선택</td>
                                <td>결과</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="7" class="league_title">
                                    <img src="/_go/renewal/img/icon_soccer.png" /><img src="/_go/renewal/img/icon_pic.png" /> &nbsp; 프리미어리그 &nbsp; - &nbsp; 12/09 15:00
                                </td>
                            </tr>
                            <tr>
                                <td>승무패</td>
                                <td class="selected01">
									<span>
										<span>멘체스터유나이티드</span>
										<em>2.20</em>
									</span>
                                </td>
                                <td><span class="margin">3.10</span></td>
                                <td>
									<span>
										<span>1.20</span>
										<em>아스날</em>
									</span>
                                </td>
                                <td>0:2</td>
                                <td>홈팀 승</td>
                                <td class="noshot"><code>미적중</code></td>
                            </tr>
                            <tr>
                                <td>승무패</td>
                                <td>
									<span>
										<span>멘체스터유나이티드</span>
										<em>2.20</em>
									</span>
                                </td>
                                <td><span class="margin">3.10</span></td>
                                <td class="selected01">
									<span>
										<span>1.20</span>
										<em>아스날</em>
									</span>
                                </td>
                                <td>0:2</td>
                                <td>홈팀 패</td>
                                <td class="shot"><code>적중</code></td>
                            </tr>
                            <tr>
                                <td colspan="7" class="table-left">
                                    <div>
										<span>
											베팅일시&nbsp; : &nbsp;<font>2016-12-09(금) 15:20</font>&nbsp; / &nbsp;베팅금액&nbsp; : &nbsp;<font>100,000원</font><BR/>
											배당률&nbsp; : &nbsp;<font>2.20</font>&nbsp; / &nbsp;적중예상금액&nbsp; : &nbsp;<font>220,000원</font>&nbsp; / &nbsp;당첨금&nbsp; : &nbsp;<B>220,000</B>
										</span>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <h2 class="sub_board_write_info">
                            글내용 입력
                            <font>
                                욕설, 상대방 비방글, 타사이트 언급, 홍보 등은 경고없이 삭제되며 사이트 이용에 제한을 받을 수 있습니다.
                            </font>
                        </h2>
                        <textarea class="board_write_contents" name="b_content" id="summernote" placeholder="내용을 입력해주세요."></textarea>
                    </div>

                    <div class="btn_box btn_box_center">
                        <a href="javascript:;" class="btn_green"  OnClick="javascript:Action_Write();">등록</a>
                        <a href="../list/" class="btn_gray">취소</a>
                    </div>

                </div> <!-- board_wrap -->

            </div> <!-- sub-box -->

            <div class="popup_box hide">
                <div class="popup_box_bg"></div>
                <div class="sub_wrap">
                    <div class="mypage-title">베팅내역<em onClick="$('.popup_box').addClass('hide');"><img src="/img/btn_close(big).png" /></em></div>

                    <div class="mypage-type write" style="padding-bottom:25px; border-bottom:1px solid rgba(255, 255, 255, 0.15)">
                        <a href="" class="on">스포츠</a>
                        <dfn></dfn>
                        <a href="">파워볼게임</a>
                        <dfn></dfn>
                        <a href="#">파워사다리</a>
                        <dfn></dfn>
                        <a href="#">스피드키노</a>
                        <dfn></dfn>
                        <a href="#">키노사다리</a>
                        <dfn></dfn>
                        <a href="#">가상게임</a>
                    </div>

                    <table class="table-black mypage">
                        <thead>
                        <tr>
                            <td rowspan="2">선택</td>
                            <td>베팅구분</td>
                            <td width="260">승(홈)<img src="/_go/renewal/img/icon_up.png" class="blink"></td>
                            <td>무</td>
                            <td width="260">패(원정)<img src="/_go/renewal/img/icon_down.png" class="blink"></td>
                            <td>점수</td>
                            <td>선택</td>
                            <td>결과</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="8" class="league_title">
                                <img src="/_go/renewal/img/icon_soccer.png" /><img src="/_go/renewal/img/icon_pic.png" /> &nbsp; 프리미어리그 &nbsp; - &nbsp; 12/09 15:00
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="ckb_td"><input type="checkbox"></td>
                            <td>핸디캡</td>
                            <td class="selected01">
									<span>
										<span>바르셀로나</span>
										<em>2.20</em>
									</span>
                            </td>
                            <td><span class="margin">3.10</span></td>
                            <td>
									<span>
										<span>1.20</span>
										<em>레알마드리드</em>
									</span>
                            </td>
                            <td>3 : 1</td>
                            <td>홈팀 승</td>
                            <td class="wait"><code>대기</code></td>
                        </tr>
                        <tr>
                            <td>승무패</td>
                            <td>
									<span>
										<span>바르셀로나</span>
										<em>2.20</em>
									</span>
                            </td>
                            <td><span class="margin">3.10</span></td>
                            <td class="selected01">
									<span>
										<span>1.20</span>
										<em>레알마드리드</em>
									</span>
                            </td>
                            <td>3 : 1</td>
                            <td>홈팀 패</td>
                            <td class="wait"><code>대기</code></td>
                        </tr>

                        <tr>
                            <td colspan="8" class="league_title">
                                <img src="/_go/renewal/img/icon_soccer.png" /><img src="/_go/renewal/img/icon_pic.png" /> &nbsp; 프리미어리그 &nbsp; - &nbsp; 12/09 15:00
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="3" class="ckb_td"><input type="checkbox"></td>
                            <td>승무패</td>
                            <td class="selected01">
									<span>
										<span>멘체스터유나이티드</span>
										<em>2.20</em>
									</span>
                            </td>
                            <td><span class="margin">3.10</span></td>
                            <td>
									<span>
										<span>1.20</span>
										<em>아스날</em>
									</span>
                            </td>
                            <td>0:2</td>
                            <td>홈팀 승</td>
                            <td class="noshot"><code>미적중</code></td>
                        </tr>
                        <tr>
                            <td>승무패</td>
                            <td>
									<span>
										<span>멘체스터유나이티드</span>
										<em>2.20</em>
									</span>
                            </td>
                            <td><span class="margin">3.10</span></td>
                            <td class="selected01">
									<span>
										<span>1.20</span>
										<em>아스날</em>
									</span>
                            </td>
                            <td>0:2</td>
                            <td>홈팀 패</td>
                            <td class="shot"><code>적중</code></td>
                        </tr>
                        <tr>
                            <td colspan="7" class="table-left">
                                <div>
										<span>
											베팅일시&nbsp; : &nbsp;<font>2016-12-09(금) 15:20</font>&nbsp; / &nbsp;베팅금액&nbsp; : &nbsp;<font>100,000원</font><BR/>
											배당률&nbsp; : &nbsp;<font>2.20</font>&nbsp; / &nbsp;적중예상금액&nbsp; : &nbsp;<font>220,000원</font>&nbsp; / &nbsp;당첨금&nbsp; : &nbsp;<B>220,000</B>
										</span>
                                    <em>
                                        <B>베팅취소</B>
                                        <B>베팅삭제</B>
                                        <font>내역올리기</font>
                                    </em>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <table class="table-black mypage">
                        <thead>
                        <tr>
                            <td rowspan="2">선택</td>
                            <td>베팅구분</td>
                            <td width="260">승(홈)<img src="/_go/renewal/img/icon_up.png" class="blink"></td>
                            <td>무</td>
                            <td width="260">패(원정)<img src="/_go/renewal/img/icon_down.png" class="blink"></td>
                            <td>점수</td>
                            <td>선택</td>
                            <td>상태</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="8" class="league_title">
                                <img src="/_go/renewal/img/icon_soccer.png" /><img src="/_go/renewal/img/icon_pic.png" /> &nbsp; 프리미어리그 &nbsp; - &nbsp; 12/09 15:00
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="ckb_td"><input type="checkbox"></td>
                            <td>핸디캡</td>
                            <td class="selected01">
									<span>
										<span>비아레알</span>
										<em>2.20</em>
									</span>
                            </td>
                            <td><span class="margin">3.10</span></td>
                            <td>
									<span>
										<span>1.20</span>
										<em>세비야</em>
									</span>
                            </td>
                            <td>3 : 1</td>
                            <td>홈팀 승</td>
                            <td class="shot"><code>적중</code></td>
                        </tr>
                        <tr>
                            <td>핸디캡</td>
                            <td>
									<span>
										<span>비아레알</span>
										<em>2.20</em>
									</span>
                            </td>
                            <td><span class="margin">3.10</span></td>
                            <td class="selected01">
									<span>
										<span>1.20</span>
										<em>세비야</em>
									</span>
                            </td>
                            <td>3 : 1</td>
                            <td>홈팀 패</td>
                            <td class="noshot"><code>미적중</code></td>
                        </tr>

                        <tr>
                            <td colspan="8" class="league_title">
                                <img src="/_go/renewal/img/icon_soccer.png" /><img src="/_go/renewal/img/icon_pic.png" /> &nbsp; 프리미어리그 &nbsp; - &nbsp; 12/09 15:00
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="3" class="ckb_td"><input type="checkbox"></td>
                            <td>승무패</td>
                            <td class="selected01">
									<span>
										<span>첼시</span>
										<em>2.20</em>
									</span>
                            </td>
                            <td><span class="margin">3.10</span></td>
                            <td>
									<span>
										<span>1.20</span>
										<em>리버풀</em>
									</span>
                            </td>
                            <td>1:0</td>
                            <td>홈팀 승</td>
                            <td class="ing"><code>경기중</code></td>
                        </tr>
                        <tr>
                            <td>승무패</td>
                            <td>
									<span>
										<span>첼시</span>
										<em>2.20</em>
									</span>
                            </td>
                            <td><span class="margin">3.10</span></td>
                            <td class="selected01">
									<span>
										<span>1.20</span>
										<em>리버풀</em>
									</span>
                            </td>
                            <td>1:0</td>
                            <td>홈팀 패</td>
                            <td class="ing"><code>경기중</code></td>
                        </tr>
                        <tr>
                            <td colspan="7" class="table-left">
                                <div>
										<span>
											베팅일시&nbsp; : &nbsp;<font>2016-12-09(금) 15:20</font>&nbsp; / &nbsp;베팅금액&nbsp; : &nbsp;<font>100,000원</font><BR/>
											배당률&nbsp; : &nbsp;<font>2.20</font>&nbsp; / &nbsp;적중예상금액&nbsp; : &nbsp;<font>220,000원</font>&nbsp; / &nbsp;당첨금&nbsp; : &nbsp;<B>220,000</B>
										</span>
                                    <em>
                                        <B>베팅취소</B>
                                        <B>베팅삭제</B>
                                        <font>내역올리기</font>
                                    </em>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="paging_box view_box">
                        <a href="">◀</a><a href="">1</a><a href="" class="hit">2</a><a href="">3</a><a href="">4</a><a href="">5</a><a href="">6</a><a href="">7</a><a href="">8</a><a href="">9</a><a href="">10</a><a href="">▶</a>
                    </div>

                    <div class="board_searchbox">
                        <input type="text" class="input_style1" style="width:150px" placeholder="검색어를 입력해주세요." />&nbsp;
                        <input type="submit" class='btn_green' value="검색">
                        <span class="btn_float_left"><a class="btn_green">전체선택</a>&nbsp;<a href="" class="btn_gray">선택삭제</a></span>
                        <span class="btn_float_right btn_orange" onClick="$('.popup_box').addClass('hide');">게시판에 베팅내역 올리기</span>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- sub_wrap -->
    </div> <!-- sub_bg -->

    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#summernote').summernote({
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                ],
                lang: 'ko-KR',
                placeholder: '내용을 입력하세요.',
                tabsize: 2,
                height: 300
            });
        });
    </script>
    <script>

        function Action_Write() {
            var f = document.HiddenActionForm;

            var _subject  = document.getElementById( "b_subject" );
            var _type     = document.getElementById( "b_type" );
            var _content = document.getElementById( "summernote" );

            if ( _subject.value.trim() == "" ) {
                swal("","제목을 입력해 주세요.","warning");
                _subject.focus();
                return;
            }

            if(_content.value.trim() == ''){
                swal('','내용을 입력해주세요.','warning');
                _content.focus();
                return;
            }

            if ( confirm("게시물을 등록 하시겠습니까?") ) {
                f.HAF_Value_0.value = "BoardWrite";
                f.HAF_Value_1.value = "board";

                f.HAF_Value_2.value = _subject.value;
                f.HAF_Value_3.value = _content.value;
                f.HAF_Value_4.value = "<?=$_SERVER['HTTP_REFERER'];?>";

                f.method = "POST";
                f.action = "/action/board_action.php";
                f.submit();

            };
        }
    </script>
<?php
include_once $root_path.'/include/footer.php';
?>