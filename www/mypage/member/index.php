<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

// 로그인 체크
if ( !$_SESSION['S_Key'] ) {
    swal_move('로그인이 필요한 페이지 입니다.', 'login');
}



?>


    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">회원정보수정</div>
                <div class="title2">MY INFORMATION</div>
            </div>
            <div class="sub-box">

                <div class="mypage_menu1">
                    <a href="/mypage/betlist/">베팅내역</a>
                    <a href="/mypage/charge/">충전내역</a>
                    <a href="/mypage/refund/">환전내역</a>
                    <a href="/mypage/point/exchangelist/">포인트내역</a>
                    <!--<a href="/mypage/recom/">총판관리</a>-->
                    <a href="/mypage/message/">쪽지관리</a>
                    <a href="/mypage/member/modify/" class="active">회원정보수정</a>
                </div>
                <div class="mypage_menu2">
                    <a href="/_go/renewal/mypage/member_confirm.html" class="active">회원정보수정</a>
                </div>

                <div class="board_wrap">

                    <div class="b_title2">
                        <span>회원정보 수정</span> | 회원 정보를 수정할 수 있습니다.
                    </div>
                    <div style="border-top:2px solid #606060"></div>

                    <form>
                        <div class="mypage-meminfo">
                            <dl>
                                <dt>아이디</dt>
                                <dd><input type="text" class="mypage-meminfo-input type2" readonly value="<?php echo $_SESSION['S_ID']; ?>" /></dd>
                            </dl>
                            <dl>
                                <dt>비밀번호</dt>
                                <dd>
                                    <input type="password" class="mypage-meminfo-input" name="user_pass" id="user_pass" />

                                </dd>
                            </dl>
                            <dl>
                                <dt>비밀번호 확인</dt>
                                <dd>
                                    <input type="password" class="mypage-meminfo-input" name="user_re_pass" id="user_re_pass" />
                                    <span>※'비밀번호'를 6~10자리로 입력해주세요.(영문대,소문자 및 숫자만 허용)</span>
                                </dd>

                            </dl>
                        </div>
                    </form>

                </div> <!-- board_wrap -->
                <div class="member_btn">
                    <a href="javascript:;" onclick="Action_Write();">회원정보수정</a>
                </div>
            </div> <!-- sub-box -->

        </div> <!-- sub_wrap -->

    </div> <!-- sub_bg -->

    <script>
        function Action_Write() {
            var frm = document.HiddenActionForm;
            var user_pass     = document.getElementById( "user_pass" );
            var user_re_pass  = document.getElementById( "user_re_pass" );
            if ( user_pass.value.length < 3 )	{
                swal('',"'비밀번호'를 6~10자리로 입력해주세요.(영문대,소문자 및 숫자만 허용)",'warning');
                user_pass.focus();
                return false;
            } else if( user_pass.value != user_re_pass.value) {
                swal('','비밀번호와 비밀번호 확인이틀립니다.','warning');
                user_re_pass.focus();
            } else {
                frm.HAF_Value_0.value = "MemberModify";
                frm.HAF_Value_3.value = user_pass.value;
                frm.method = "POST";
                frm.action = "./proc/";
                frm.submit();
            }
        }

        function callback(res){
            if(res == true){
                swal('','비밀번호가 변경되었습니다.','success');
                setTimeout(function(){ location.href = '/main/';},2000);
            } else {
                swal('','비밀번호 변경시 오류가 발생 되었습니다.','warning');
            }
        }
    </script>
<?php
include_once $root_path.'/include/footer.php';
?>