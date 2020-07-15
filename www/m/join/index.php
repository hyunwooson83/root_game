<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="HandheldFriendly" content="true">
    <meta name="format-detection" content="telephone=no">
    <link rel="shortcut icon" href="/m/img/favicon.png">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=10,user-scalable=no">
    <title>TEXAS</title>
    <link rel="stylesheet" href="/m/css/login_style.css" />
    <link rel="stylesheet" href="/css/sweetalert.css" />
    <script src="/m/js/jquery-1.11.1.min.js"></script>
    <script src="/m/js/sweetalert.js"></script>
</head>

<style>
    body:before {content: ""; display: block;  position:fixed; width:100%; height:100%; left:0; top:0;  background:url('/mobile/img/login_bg.jpg') no-repeat; background-size:cover; z-index: -10;}


</style>
<body>
<form name="f" method="post">
    <input type="hidden" name="id_use_yn" id="id_use_yn" value="y" />
    <input type="hidden" name="nick_use_yn" id="nick_use_yn" value="y" />
    <input type="hidden" name="hp_use_yn" id="hp_use_yn" value="y" />
    <input type="hidden" name="mode" value="join" />
    <div class="join_wrap">

        <em><img src="/img/logo_b4.png" /></em>
        <span><img src="/mobile/img/login_text.png" /></span>
        <ul class="login_menu">
            <li><a href="/m/login/">LOGIN</a></li>
            <li class="on"><a href="/m/join/">JOIN</a></li>
        </ul>
        <div>
            <div><input type="text" class="style_input essential" name="user_id" id="user_id" placeholder="아이디" /></div>
            <span id="id_chk_text">영문,숫자만 입력 가능. 최소4 자이상</span>
        </div>
        <div>
            <div><input type="password" class="style_input essential" name="user_pass" id="user_pass" placeholder="비밀번호" /></div>
        </div>
        <div>
            <div><input type="password" class="style_input essential" name="user_pass" id="user_re_pass" placeholder="비밀번호 확인" /></div>
            <span id="pass_chk_text">영문,숫자,특수문자를 한 자 이상 반드시 포하한 6 ~ 16자</span>
        </div>
        <div>
            <div><input type="text" class="style_input essential" name="user_nick" id="user_nick" placeholder="닉네임" /></div>
            <span id="chk_nick">영문 또는 한글 4~12자</span>
        </div>
        <div>
            <div><input type="number" class="style_input essential" name="hp" id="hp" placeholder="휴대폰번호(숫자만 입력)" /></div>
        </div>
        <div>
            <input type="text" class="style_input essential" name="recjoin" id="recjoin" placeholder="추천인 아이디" />
        </div>
        <br>
        <div class="join_acc">
            <div>
                <select class="style_select type1" id="bank" name="bank">
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
                <em>
                    <input type="text" name="bank_owner" id="bank_owner" class="style_input host" placeholder="예금주" />
                </em>

            </div>
            <div><input type="text" name="bank_num" id="bank_num" class="style_input essential" placeholder="계좌번호 (숫자만입력)" /></div>
            <div><input type="text" name="bank_pass" id="bank_pass" class="style_input essential" placeholder="환전암호" /></div>
        </div>

        <div class="sub_board_btn">
            <a href="javascript:;" onClick="Action_Write();">가입하기</a>
            <a href="javascript:;" onClick="member_cancel();" class="btn_gray">취소</a>
        </div>
        <br />
        <div class="join_foot">
            <div class="telegram">
                <em><img src="/img/login/icon_kakaotalk.png"></em><span>TEXAS</span> &nbsp &nbsp &nbsp
                <em><img src="/mobile/img/icon_telegram.png"></em><span>TEXAS</span>
            </div>
            <h1><span onclick="location.href = '/login/pcmode=Y';">PC버전으로 보기</span></h1>
            <div class="text1">Copyright TEXAS Corp⒞. All Rights Reserved.</div>
        </div>

    </div>
</form>

<form name="HiddenActionForm" style="display:none;" target="HIddenActionFrame" >
    <input type="text" name="HAF_Value_0">
    <input type="text" name="HAF_Value_1">
    <input type="text" name="HAF_Value_2">
    <input type="text" name="HAF_Value_3">
    <input type="text" name="HAF_Value_4">
    <input type="text" name="HAF_Value_5">
    <input type="text" name="HAF_Value_6">
    <input type="text" name="HAF_Value_7">
    <input type="text" name="HAF_Value_8">
    <input type="text" name="HAF_Value_9">
    <input type="text" name="HAF_Value_10">
    <input type="text" name="HAF_Value_11">
    <input type="text" name="HAF_Value_12">
    <input type="text" name="HAF_Value_13">
    <input type="text" name="HAF_Value_14">
    <input type="text" name="HAF_Value_15">
    <input type="text" name="HAF_Value_16">
    <input type="text" name="HAF_Value_17">
    <input type="text" name="HAF_Value_18">
    <input type="text" name="HAF_Value_19">
    <input type="text" name="HAF_Value_20">
</form>
<iframe src="about:blank" style="display:none;width:600px;height:500px;" name="HIddenActionFrame" id="HIddenActionFrame" ></iframe>


<script>
    $(document).ready(function(){

        $('#user_id').keyup(function(){
            var txt = $(this);
            var flag = fc_chk_byte(txt,5,10);

            if(flag == false)	{
                $('#id_use_yn').val('y');
            } else {
                $.ajax({
                    type: "POST",
                    url: "./proc/",
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

        $('#user_nick').keyup(function(){
            var nick = $(this);
            var flag = fc_chk_byte(nick,2,10);
            var pattern_spc = /[~!@#$%^&*()_+|<>?:{}]/; // 특수문자



            if(flag == false)	{
                $('#nick_use_yn').val('y');
            } else	{
                $.ajax(	{
                    type : "POST",
                    url : "./proc/",
                    data : "mode=nick&userNick="+nick.val(),
                    success : function(res)
                    {
                        if($.trim(res) == 'y')	{
                            $('#nick_use_yn').val('n');
                            $('#chk_nick').val('사용가능한 회원 닉네임 입니다.');
                        } else	{
                            $('#nick_use_yn').val('y');
                            $('#chk_nick').val('이미 사용중인 회원 닉네임 입니다.');
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
        /*console.log($(".login_box").hasClass("active"))
        if($(".login_box").hasClass("active")==false){
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
        }*/
        location.href = '/m/login/';
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
                url : "./proc/",
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

    function distinct_chk1(txt, obj)
    {
        //alert(obj);
        var flag = fc_chk_byte(obj,2,10);

        if(flag == false)	{
            $('#nick_use_yn').val('y');
        } else	{
            $.ajax(	{
                type : "POST",
                url : "./ajax.php",
                data : "mode=nick&userNick="+txt,
                success : function(res)
                {
                    //$('#server').html(res);
                    //alert(res);
                    if($.trim(res) == 'y')	{
                        $('#nick_use_yn').val('n');
                        alert('사용가능한 회원 닉네임 입니다.');
                    } else	{
                        $('#nick_use_yn').val('y');
                        alert('이미 사용중인 회원 닉네임 입니다.');
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
            url : "./proc/",
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
    function Action_Write()	{
        var frm = document.HiddenActionForm;

        var user_id       = document.getElementById( "user_id" );
        var user_id_check = document.getElementById( "id_use_yn" );
        var user_nick     = document.getElementById( "user_nick" );
        var user_nick_check = document.getElementById( "nick_use_yn" );
        var user_pass     = document.getElementById( "user_pass" );
        var user_re_pass  = document.getElementById( "user_re_pass" );
        var cp            = $('#hp').val();
        var cp_check      = document.getElementById( "cp_check" );
        var cp_auth       = document.getElementById( "cp_auth" );
        var bank          = $("#bank").val();
        var bank_num      = document.getElementById( "bank_num" );
        var bank_owner    = document.getElementById( "bank_owner" );
        var bank_pass     = document.getElementById( "bank_pass" );
        var recjoin 	  = document.getElementById("recjoin");


        recjoin.value = $.trim(recjoin.value);
        if ( user_id.value == "" )	{
            swal('',"'회원아이디'를 6~10자리로 입력해주세요.(영문대,소문자 및 숫자만 허용)",'warning');
            user_id.focus();
        } else if ( user_id_check.value == "y")	{
            swal('',"아이디 중복확인을 실행해 주세요.",'warning');
            user_id.focus();
        } else 	if ( user_pass.value == "") 	{
            swal('',"'비밀번호'를 6~10자리로 입력해주세요.(영문대,소문자 및 숫자만 허용)",'warning');
            user_pass.focus();

        } else if( user_pass.value != user_re_pass.value)	{
            swal('','비밀번호와 비밀번호 확인이틀립니다.','warning');
            user_re_pass.focus();
        } else if ( user_nick.value == "")	{
            swal('',"'회원닉네임'을 입력해주세요.",'warning');
            user_nick.focus();
        } else 	if ( user_nick_check.value == "y") {
            swal('', "닉네임 중복확인을 실행해 주세요.", 'warning');
            user_nick.focus();
        } else if(charChk(user_nick,'hanengnum')){
            swal('',"닉네임에 특수문자를 사용하실 수 없습니다.",'warning');
            user_nick.focus();
            return false;
        } else if ( $('#hp').val() == '' )	{
            swal('',"정상적인 핸드폰 번호를 입력해주세요.",'warning');
            $('#hp').focus();
        } else if ( $('#recjoin').val() == '' )	{
            swal('',"추천인 아이디를 입력해주세요.",'warning');
            $('#hp').focus();
        } else if ( $("#bank").val() == "" )	{
            swal('',"'은행명'을 선택해주세요.",'warning');
            $("#bank").focus();
        } else if ( bank_num.value == "" )	{
            swal('',"'계좌번호'를 입력해주세요.",'warning');
            bank_num.focus();
        } else if ( bank_owner.value == "" )	{
            swal('',"'예금주'를 입력해주세요.",'warning');
            bank_owner.focus();
        } else if ( recjoin.value == "" )	{
            swal('',"'추천인'를 입력해주세요.",'warning');
            recjoin.focus();
        } else if ( bank_pass == "" )	{
            swal('',"'환전암호'를 입력해주세요.",'warning');
            bank_pass.focus();
        } else	{
            if ( confirm("회원가입을 하시겠습니까?") )	{
                frm.HAF_Value_0.value = "MemberJoin";
                frm.HAF_Value_1.value = user_id.value;
                frm.HAF_Value_2.value = user_nick.value;
                frm.HAF_Value_3.value = user_pass.value;
                frm.HAF_Value_4.value = cp;
                frm.HAF_Value_5.value = 0;
                frm.HAF_Value_9.value = bank;
                frm.HAF_Value_10.value = bank_num.value;
                frm.HAF_Value_11.value = bank_owner.value;
                frm.HAF_Value_12.value = bank_pass.value;
                frm.HAF_Value_14.value = recjoin.value;

                frm.method = "POST";
                frm.action = "/m/action/member_action.php";
                frm.submit();
            }
        }
    }
</script>
</body>
</html>