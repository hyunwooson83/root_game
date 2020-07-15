<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/header_none.php';

    $slv = $_SESSION['S_ShopLevel']+1;

    /*$que = "SELECT M_ShopTop FROM members WHERE M_Shop_Level = '{$slv}' ORDER BY M_ShopTop DESC LIMIT 1";
    echo $que;
    $row = getRow($que);
    if(!$row['M_ShopTop']){
        $grade = 'a';
    } else {
        $grade = chr(ord($row['M_ShopTop'])+1);
    }*/

    if($slv == 1){
        $level = 3;
    } else if($slv == 2){
        $level = 4;
    } else if($slv == 3){
        $level = 5;
    } else if($slv == 4){
        $level = 6;
    }

    #상위목록
    $sql = "SELECT * FROM members WHERE M_ID = '{$_SESSION['S_ID']}' ";
    //echo $sql."<br>";
    $up = getRow($sql);
    $grade = $up['M_ShopTop'];
    $up_link = '';

    $len = strlen($grade)-1;
    $upgrade = substr($grade,0,$len);
    $up_link = './?branch_id='.$up['M_ShopParentID'].'&branch_lv='.($branch_lv-1).'&grade='.$upgrade;





    $que = "SELECT M_ShopTop FROM members WHERE M_Shop_Level = '{$slv}' AND M_ShopParentID = '{$_SESSION['S_ID']}' AND LENGTH(M_ShopTop) = {$slv} ORDER BY M_ShopTop DESC LIMIT 1";
    //echo $que."<br>";
    $row = getRow($que);
    if(!$row['M_ShopTop']){
        $grade = $grade.'a';
    } else {
        $prev = substr($row['M_ShopTop'],-1);
        $grade = $up['M_ShopTop'].chr(ord($prev)+1);
    }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="imagetoolbar" content="no">
    <meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">
    <link rel="shortcut icon" href="/img/favicon.png">
    <title>TEXAS - 텍사스</title>
    <link rel="stylesheet" href="/css/login.css" />
    <link rel="stylesheet" type="text/css" href="/css/sweetalert.css">
    <script src="/js/jquery-1.11.1.min.js"></script>
    <script src="/js/jquery.easing.1.3.js"></script>
    <script src="/js/login.js"></script>
    <script src="/js/placeholders.min.js"></script>
    <script type="text/javascript" src="/js/_lib.js?time=<?php echo time();?>"></script>
    <script type="text/javascript" src="/js/sweetalert.js"></script>
    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body>

<div id="fullpage_wrap">

    <div class="join_area" style="display: block;" >
        <form name="f" id="f" method="post">
            <input type="hidden" name="id_use_yn" id="id_use_yn" value="y" />
            <input type="hidden" name="nick_use_yn" id="nick_use_yn" value="y" />
            <input type="hidden" name="hp_use_yn" id="hp_use_yn" value="y" />
            <input type="hidden" name="mode" value="shopJoin" />
            <input type="hidden" name="shopLevel" value="<?php echo $slv;?>" />
            <input type="hidden" name="grade" value="<?php echo $grade;?>" />
            <input type="hidden" name="level" value="<?php echo $level;?>" />
            <div class="join_line top active"></div>
            <div class="join_line bottom active"></div>
            <div class="join_box active">
                <!--<h1><img src="/img/login/login_logo.png"></h1>-->
                
                <ul>
                    <li class="gap">
                        <em>
                            <input type="text" name="user_id" id="user_id" maxlength="10" placeholder="아이디" style="width:265px;">
                            <var style="width:80px; height:26px; border:#fff solid 1px;" class="confirm" id="id_chk">중복확인</var>
                            <code id="id_chk_text">영문, 숫자만 입력가능하며 최소 2자이상</code>
                        </em>
                    </li>
                    <li>
                        <em>
                            <input type="password" name="user_pass" id="user_pass" placeholder="비밀번호">
                        </em>
                    </li>
                    <li class="gap">
                        <em>
                            <input type="password" name="re_user_pass" id="user_re_pass" placeholder="비밀번호 확인">
                            <code class="line2" id="pass_chk_text">6~10자리로 입력해주세요.(영문대,소문자 및 숫자만 허용)</code>
                        </em>
                    </li>
                    <li>
                        <em>
                            <input type="text" name="user_nick" id="user_nick" placeholder=" 닉네임" style="width:265px;">
                            <var style="width:80px; height:26px; border:#fff solid 1px;" class="confirm" id="nick_chk">중복확인</var>
                            <code class="line2" id="chk_nick">영문 또는 한글 4 ~ 12자</code>
                        </em>
                    </li>
                    <li>
                        <em>
                            <input type="text" name="hp" id="hp" placeholder="휴대폰 번호">
                            <code id="hp_chk_text" style="display: none;"></code>
                        </em>
                    </li>
                    <li>
                        <em>
                            <select class="middle" id="pay_type" name="pay_type">
                                <?php if($type == 'R'){ ?>
                                <option value="R">롤링</option>
                                <?php } else { ?>
                                <option value="L">루징</option>
                                <?php } ?>
                            </select>

                                <input type="text" name="shop_percent" id="shop_percent" class="small" placeholder="요율입력">
                                <code class="line2" >요율은 <?php echo $percent;?>% 이하로만 입력해주세요.</code>


                        </em>
                    </li>

                    <li>
                        <em>
                            <select class="middle" id="bank" name="">
                                <option value="">은행선택</option>
                                <option value="하나은행">하나은행</option>
                                <option value="제주은행">제주은행</option>
                                <option value="전북은행">전북은행</option>
                                <option value="우체국">우체국</option>
                                <option value="우리은행">우리은행</option>
                                <option value="외환은행">외환은행</option>
                                <option value="아메리카">아메리카</option>
                                <option value="씨티은행">씨티은행</option>
                                <option value="신협">신협</option>
                                <option value="신한은행">신한은행</option>
                                <option value="수협">수협</option>
                                <option value="새마을금고">새마을금고</option>
                                <option value="상호저축은행">상호저축은행</option>
                                <option value="케이뱅크">케이뱅크</option>
                                <option value="카카오뱅크">카카오뱅크</option>
                                <option value="산업은행">산업은행</option>
                                <option value="부산은행">부산은행</option>
                                <option value="미즈호">미즈호</option>
                                <option value="미쓰비시">미쓰비시</option>
                                <option value="도이치">도이치</option>
                                <option value="대구은행">대구은행</option>
                                <option value="농협">농협</option>
                                <option value="기업은행">기업은행</option>
                                <option value="국민은행">국민은행</option>
                                <option value="광주은행">광주은행</option>
                                <option value="경남은행">경남은행</option>
                                <option value="SC제일은행">SC제일은행</option>
                                <option value="JS모간">JS모간</option>
                                <option value="HSBS">HSBS</option>
                                <option value="ANB암로">ANB암로</option>
                            </select>
                            <input type="text" name="bank_owner" id="bank_owner" class="small" placeholder="예금주">
                        </em>
                    </li>
                    <li>
                        <em>
                            <input type="text" name="bank_num" id="bank_num" placeholder="계좌번호 (숫자만 입력)">
                        </em>
                    </li>
                    <li>
                        <em>
                            <input type="text" name="bank_pass" id="bank_pass" placeholder="환전암호">
                        </em>
                    </li>
                    <li class="btn">
                        <em>
                            <var class="confirm" onClick="Action_Write();">추가하기</var>
                            <var onClick="window.close();">닫기</var>
                        </em>
                    </li>
                   
                </ul>
            </div>
        </form>
    </div>

</div> <!-- fullpage_wrap -->

<script>
    function Action_Write()	{
        var f = document.f;
        var percent = <?php echo $percent;?>

        if($('#user_id').val().length<4){
            swal('','아이디는 2자이상 15자 이하로 입력해주세요.','warning');
            $('#user_id').focus();
            return false;
        }
        if(f.id_use_yn.value == 'y'){
            swal('','사용중인 회원 아이디 입니다.','warning');
            $('#user_id').focus();
            return false;
        }
        /*if($('#user_nick').val().length<4){
            swal('',' 닉네임은 2자이상 12자 이하로 입력해주세요.','warning');
            $('#user_nick').focus();
            return false;
        }*/
        if(f.nick_use_yn.value == 'y'){
            swal('','사용중인 닉네임 입니다.','warning');
            $('#user_nick').focus();
            return false;
        }
        if(f.user_pass.value == ''){
            swal('','비밀번호를 입력해주세요.','warning');
            f.user_pass.focus();
            return false;
        }
        if(f.re_user_pass.value == ''){
            swal('','비밀번호를 입력해주세요.','warning');
            f.re_user_pass.focus();
            return false;
        }
        if(f.hp.value == ''){
            swal('','휴대폰번호를 입력해주세요.','warning');
            f.hp.focus();
            return false;
        }
        if(f.shop_percent.value == ''){
            swal('','페센트를 입력해주세요.','warning');
            f.shop_percent.focus();
            return false;
        }

        if(percent < f.shop_percent.value){
            swal('','요율은 '+percent+' 이하로 입력해주세요.','warning');
            f.shop_percent.focus();
            return false;
        }
        var formData = $('#f').serialize();

        $.ajax({
            type : 'post',
            url : './proc/',
            dataType : 'json',
            data : formData,
            success : function(data){
                if(data.flag == true){
                    alert('정상적으로 등록 되었습니다.');
                    opener.location.reload();
                    window.close();
                } else {
                    alert('등록시 오류가 발생했습니다.'+data.error);
                }
            }
        });
    }


    $(document).ready(function(){
        $("body").height($(window).height());


        $('#id_chk').on('click',function(){
            var txt = $('#user_id');
            var flag = fc_chk_byte(txt,2,10);

            if(flag == false)	{
                $('#id_use_yn').val('y');
            } else {
                $.ajax({
                    type: "POST",
                    url: "/login/proc/",
                    data: "mode=join&userId=" + txt.val(),
                    success: function (res) {
                        if ($.trim(res) == 'y') {
                            $('#id_use_yn').val('n');
                            //swal('', '사용가능한 회원 아이디 입니다.', 'success');
                            $('#id_chk_text').text('사용가능한 회원 아이디 입니다.');
                        } else {
                            $('#id_use_yn').val('y');
                            $('#id_chk_text').text('이미 사용중인 회원 아이디 입니다.');
                            //swal('', '이미 사용중인 회원 아이디 입니다.', 'warning');
                        }
                    },
                    error: function (xhr, textStatus) {
                        alert(xhr.status);
                    }
                });
            }
        });

        $('#nick_chk').on('click',function(){
            var nick = $('#user_nick');
            var flag = fc_chk_byte(nick,2,10);

            if(flag == false)	{
                $('#nick_use_yn').val('y');
            } else	{
                $.ajax(	{
                    type : "POST",
                    url : "/login/proc/",
                    data : "mode=nick&userNick="+nick.val(),
                    success : function(res)
                    {
                        if($.trim(res) == 'y')	{
                            $('#nick_use_yn').val('n');
                            $('#chk_nick').text('사용가능한 회원 닉네임 입니다.');
                        } else	{
                            $('#nick_use_yn').val('y');
                            $('#chk_nick').text('이미 사용중인 회원 닉네임 입니다.');
                        }
                    },
                    error : function(xhr,textStatus)
                    {
                        alert(xhr.status);
                    }
                });
            }
        });
    });


    setTimeout(function(){
        $(".login_line").addClass("active");
    },1000);
    setTimeout(function(){
        $(".login_box").addClass("active");
    },2000);

    function member_join(){
        $(".login_line").removeClass("active");
        $(".login_box").removeClass("active");
        setTimeout(function(){
            $(".login_box").hide();
            $(".join_area").show();
        },2000);
        setTimeout(function(){
            $(".join_line").addClass("active");
            $(".join_box").addClass("active");
        },2200);
    }

    function member_cancel(){
        if(!$(".login_box").hasClass("active")){
            $(".complete_line").removeClass("active");
            $(".complete_box").removeClass("active");
            $(".join_line").removeClass("active");
            $(".join_box").removeClass("active");
            setTimeout(function(){
                $(".complete_area").hide();
                $(".join_area").hide();
                $(".login_box").show();
            },2000);
            setTimeout(function(){
                $(".login_line").addClass("active");
                $(".login_box").addClass("active");
            },2200);
        }

    }

    function member_complete(){
        $(".join_line").removeClass("active");
        $(".join_box").removeClass("active");
        setTimeout(function(){
            $(".join_area").hide();
            $(".complete_area").show();
        },2000);
        setTimeout(function(){
            $(".complete_line").addClass("active");
            $(".complete_box").addClass("active");
        },2200);
    }


    function distinct_chk(txt, obj)
    {
        var flag = fc_chk_byte(obj,5,10);
        if(flag == false)	{
            $('#id_use_yn').val('y');
        } else	{
            $.ajax(	{
                type : "POST",
                url : "/login/proc/",
                data : "mode=join&userId="+txt,
                success : function(res)
                {
                    if($.trim(res) == 'y')	{
                        $('#id_use_yn').val('n');
                        swal('','사용가능한 회원 아이디 입니다.','success');
                    } else	{
                        $('#id_use_yn').val('y');
                        swal('','이미 사용중인 회원 아이디 입니다.','warning');
                    }
                },
                error : function(xhr,textStatus)
                {
                    alert(xhr.status);
                }
            });
        }
    }




    function distinct_chk2()
    {
        $.ajax(	{
            type : "POST",
            url : "/login/proc/",
            data : "mode=hp&hp="+$('#hp').val(),
            success : function(res)
            {
                if($.trim(res) == 'y')	{
                    $('#hpk_use_yn').val('n');
                    $('#hp_chk_text').val('사용가능한 휴대폰번호 입니다.');
                } else	{
                    $('#hp_use_yn').val('y');
                    $('#hp_chk_text').val('이미 사용중인 휴대폰번호 입니다.');
                }
            },
            error : function(xhr,textStatus)
            {
                alert(xhr.status);
            }
        });
    }

    function Trim(string)	{
        for(;string.indexOf(" ") != -1;)	{
            string=string.replace(" ","")
        }
        return string;
    }

    function charChk(obj, type)	{
        var str = Trim(obj.value);

        for(i=0;i<str.length;i++)	{
            ch = str.charAt(i);
            switch(type)
            {
                case 'han':
                    if((ch >= 'ㄱ' && ch <= '힣') || (ch >= 0 && ch <=9))	{
                    } else	{
                        alert("한글 및 숫자만 사용가능합니다.");
                        obj.value = str.substring(0,i);
                    }
                    break;

                case 'eng':
                    if((ch >= 'a' && ch <= 'z') || (ch=='A' && ch == 'Z'))	{
                    } else	{
                        alert("한글 및 숫자만 사용가능합니다.");
                        obj.value = str.substring(0,i);
                    }
                    break;

                case 'num':
                    if((ch >= 0 && ch <=9))	{
                    } else	{
                        alert("한글 및 숫자만 사용가능합니다.");
                        obj.value = str.substring(0,i);
                    }
                    break;

                case 'engnum':
                    if((ch >= 'a' && ch <= 'z') || (ch >= 0 && ch <=9) || (ch=='A' && ch == 'Z'))	{
                    } else	{
                        alert("영어 및 숫자만 사용가능합니다.");
                        obj.value = str.substring(0,i);
                    }
                    break;
                case 'hanengnum':
                    if((ch >= 'ㄱ' && ch <= '힣') ||(ch >= 'a' && ch <= 'z') || (ch >= 0 && ch <=9) || (ch=='A' && ch == 'Z'))	{
                    } else	{
                        alert("특수문자는 사용불가능 합니다.");
                        obj.value = str.substring(0,i);
                    }
                    break;
            }
        }
    }

    function fc_chk_byte(memo, mi, mx)
    {
        var ari_max=mx;
        var ari_min=mi;
        var ls_str = memo.val(); // 이벤트가 일어난 컨트롤의 value 값
        var li_str_len = ls_str.length; // 전체길이



        // 변수초기화
        var li_max = ari_max; // 제한할 글자수 크기
        var li_min = ari_min; // 제한할 글자수 크기
        var i = 0;     // for문에 사용
        var li_byte = 0;  // 한글일경우는 2 그밗에는 1을 더함
        var li_len = 0;  // substring하기 위해서 사용
        var ls_one_char = ""; // 한글자씩 검사한다
        var ls_str2 = ""; // 글자수를 초과하면 제한할수 글자전까지만 보여준다.

        for(i=0; i< li_str_len; i++)
        {
            // 한글자추출
            ls_one_char = ls_str.charAt(i);

            // 한글이면 2를 더한다.
            if (escape(ls_one_char).length > 4)	{
                li_byte += 2;
            }else	{   // 그밗의 경우는 1을 더한다.
                li_byte++;
            }
            // 전체 크기가 li_max를 넘지않으면
            if(li_byte <= li_max)	{
                li_len = i + 1;
            }
        }

        // 전체길이를 초과하면

        if(li_byte < li_min || li_byte > li_max)	{
            //alert(li_min+"자 이상 "+li_max+"이하로 입력해주세요.");
            $('#id_chk_text').text(li_min+"자 이상 "+li_max+"이하로 입력해주세요.");
            if(li_byte> li_max)	{
                ls_str2 = ls_str.substr(0, li_len);
            } else	{
                ls_str2 = ls_str;
            }
            memo.val(ls_str2);
            memo.focus();
            return false;
        } else	{
            return true;
        }
    }




</script>

</body>
</html>
