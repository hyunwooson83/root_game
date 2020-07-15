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
                <div class="title1">회원정보확인</div>
                <div class="title2">MY INFORMATION CONFIRM</div>
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
                    <a href="/mypage/member/modify/" class="active">회원정보수정</a>
                </div>

                <div class="board_wrap">

                    <div class="b_title2">
                        <span>회원정보 확인</span> | 회원정보를 안전하게 보호하기 위해 비밀번호를 다시 한 번 확인합니다.
                    </div>
                    <div style="border-top:2px solid #606060"></div>

                    <form>
                        <div class="mypage-meminfo">
                            <dl>
                                <dt>아이디</dt>
                                <dd><input type="text" name="id" id="id" class="mypage-meminfo-input type2" value="" placeholder="아이디" /></dd>
                            </dl>
                            <dl>
                                <dt>비밀번호</dt>
                                <dd>
                                    <input type="password" name="pw" id="pw" class="mypage-meminfo-input" placeholder="비밀번호" />
                                </dd>
                            </dl>
                        </div>
                    </form>

                </div> <!-- board_wrap -->
                <div class="member_btn">
                    <a href="javascript:;" id="confirm_id">확인</a>
                </div>
            </div> <!-- sub-box -->

        </div> <!-- sub_wrap -->

    </div> <!-- sub_bg -->
<script>
    $(document).ready(function(){
       $('#confirm_id').on('click',function(){
           var id = $.trim($('#id').val());
           var pw = $.trim($('#pw').val());
           if(id != '' && pw != '') {
               $.ajax({
                   type: 'post',
                   url: '../proc/',
                   dataType: 'json',
                   data: 'HAF_Value_0=MemberLogin&HAF_Value_1=' + id + '&HAF_Value_2=' + pw,
                   success: function (data) {
                        if(data.flag == true){
                            location.href = '../';
                        } else {
                            swal('',data.error,'warning');
                        }
                   }
               });
           } else {
               swal('','아이디 또는 비밀번호를 확인해주세요.','warning');
               return false;
           }
       });
    });
</script>
<?php
include_once $root_path.'/include/footer.php';
?>