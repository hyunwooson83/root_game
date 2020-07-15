<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="imagetoolbar" content="no">
    <meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">
    <link rel="shortcut icon" href="/img/favicon.png">
    <title>TREND</title>
    <link rel="stylesheet" href="/css/login.css" />
    <link rel="stylesheet" type="text/css" href="/css/sweetalert.css">
    <script src="/js/jquery-1.11.1.min.js"></script>
    <script src="/js/jquery.easing.1.3.js"></script>
    <script src="/js/login.js"></script>
    <script src="/js/placeholders.min.js"></script>
    <script type="text/javascript" src="/js/_lib.js"></script>
    <script type="text/javascript" src="/js/sweetalert.js"></script>
</head>
<body>

<div id="fullpage_wrap">

    <div class="join_area">
        <form name="f" method="post">
            <input type="hidden" name="id_use_yn" id="id_use_yn" value="y" />
            <input type="hidden" name="nick_use_yn" id="nick_use_yn" value="y" />
            <input type="hidden" name="hp_use_yn" id="hp_use_yn" value="y" />
            <input type="hidden" name="mode" value="join" />
            <div class="join_line top"></div>
            <div class="join_line bottom"></div>
            <div class="join_box">
                <!--<h1><img src="/img/login/login_logo.png"></h1>-->
                <ul class="login_menu">
                    <li onClick="member_cancel();">LOGIN</li>
                    <li class="on"  onClick="member_join();">JOIN</li>
                </ul>
                <ul>
                    <li class="gap">
                        <em>
                            <input type="text" name="user_id" id="user_id" maxlength="10" placeholder="아이디"><code>영문, 숫자만 입력가능하며 최소 4자이상</code>
                        </em>
                    </li>
                    <li>
                        <em>
                            <input type="password" name="user_pass" id="user_pass" placeholder="비밀번호">
                        </em>
                    </li>
                    <li class="gap">
                        <em>
                            <input type="password" name="user_pass" id="user_re_pass" placeholder="비밀번호 확인">
                            <code class="line2">6 ~ 10 자리 영문 및 숫자</code>
                        </em>
                    </li>
                    <li>
                        <em>
                            <input type="text" name="user_nick" id="user_nick" placeholder=" 닉네임">
                            <code class="line2">(영문 2~10자 / 한글2~5자 이하)</code>
                        </em>
                    </li>
                    <li>
                        <em>
                            <input type="text" name="hp" id="hp" placeholder="휴대폰 번호">
                        </em>
                    </li>
                    <li class="gap" style="margin-bottom:10px">
                        <em>
                            <input type="text"name="recjoin" id="recjoin"  placeholder="추천인 아이디">
                        </em>
                    </li>
                    <li>
                        <em>
                            <select class="middle">
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
                            <var class="confirm" onClick="member_complete();">가입하기</var>
                            <var onClick="member_cancel();">취소</var>
                        </em>
                    </li>
                    <li>
                        <span><code>CS CENTER</code></span>
                        <em>
                            <ol>
                                <li>
                                    <img src="/img/login/icon_kakaotalk.png">
                                    <label>Trend</label>
                                </li>
                                <li>
                                    <img src="/img/login/icon_telegram.png">
                                    <label>Trend</label>
                                </li>
                            </ol>
                        </em>
                    </li>
                </ul>
            </div>
        </form>
    </div>

    <div class="complete_area">
        <div class="complete_line top"></div>
        <div class="complete_line bottom"></div>
        <div class="complete_box">
            <h1><img src="/_go/renewal/img/top_logo.png"></h1>
            <!--<h2><img src="/_go/renewal/img/login/text_title_word.png"></h2>-->
            <h3>축하합니다! 회원가입이 완료되었습니다.</h3>
            <h4>
                관리자 승인 후 로그인 하시면 정상적으로 서비스 이용이 가능합니다.<BR />
                고벳은 깨끗하고 안전한 운영을 자랑합니다.
            </h4>
            <ul>
                <li>
                    <img src="/_go/renewal/img/login/icon_complete1.png"><BR />
                    <span>회원님의 모든데이터는<BR />암호화 되어 안전하게<BR />보관됩니다.</span>
                </li>
                <li>
                    <img src="/_go/renewal/img/login/icon_complete2.png"><BR />
                    <span>아이디/비밀번호를<BR />분실되지 않도록<BR />보안에 신경써주십시오.</span>
                </li>
                <li>
                    <img src="/_go/renewal/img/login/icon_complete3.png"><BR />
                    <span>회원탈퇴 후<BR />회원님의 정보는<BR />완전히 삭제됩니다.</span>
                </li>
            </ul>
            <h5>
                <span class="main" onClick="member_cancel();">LOGIN</span>
            </h5>
        </div>
    </div>

</div> <!-- fullpage_wrap -->

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
    function Action_Write()	{
        var frm = document.HiddenActionForm;

        var user_id       = document.getElementById( "user_id" );
        var user_id_check = document.getElementById( "id_use_yn" );
        var user_nick     = document.getElementById( "user_nick" );
        var user_nick_check = document.getElementById( "nick_use_yn" );
        var user_pass     = document.getElementById( "user_pass" );
        var user_re_pass  = document.getElementById( "user_re_pass" );
        var cp1           = $("#cp1" ).html();
        var cp2           = document.getElementById( "cp2" );
        var cp3           = document.getElementById( "cp3" );
        var cp            = cp1 + "-" + cp2.value + "-" + cp3.value;
        var cp_check      = document.getElementById( "cp_check" );
        var cp_auth       = document.getElementById( "cp_auth" );        
        var bank          = $("#bank").html();
        var bank_num      = document.getElementById( "bank_num" );
        var bank_owner    = document.getElementById( "bank_owner" );
        var bank_pass     = document.getElementById( "bank_pass" );
        var recjoin 	  = document.getElementById("recjoin");
        var certyn	 	  = document.getElementById("hp_use_yn");
        

        recjoin.value = $.trim(recjoin.value);
        if ( user_id.value == "" || !checkIDPass(user_id.value) )	{
            alert("'회원아이디'를 6~10자리로 입력해주세요.(영문대,소문자 및 숫자만 허용)");
            user_id.focus();
        } else if ( user_id_check.value == "y")	{
            alert("아이디 중복확인을 실행해 주세요.");
            user_id.focus();
        } else 	if ( user_pass.value == "" || !checkIDPass(user_pass.value) )	{
            alert("'비밀번호'를 6~10자리로 입력해주세요.(영문대,소문자 및 숫자만 허용)");
            user_pass.focus();
            return false;
        } else if( user_pass.value != user_re_pass.value)	{
            alert('비밀번호와 비밀번호 확인이틀립니다.');
            user_re_pass.focus();
        } else if ( user_nick.value == "")	{
            alert("'회원닉네임'을 입력해주세요.");
            user_nick.focus();
        } else 	if ( user_nick_check.value == "y")	{
            alert("닉네임 중복확인을 실행해 주세요.");
            user_nick.focus();
        } else if ( isNaN(cp2.value) || cp2.value.length < 3 )	{
            alert("정상적인 핸드폰 번호를 입력해주세요.");
            cp2.focus();
        } else if ( isNaN(cp3.value) || cp3.value.length < 4 )	{
            alert("정상적인 핸드폰 번호를 입력해주세요.");
            cp3.focus();
            /*} else if ( certyn.value == 'y' )	{
              alert("휴대폰 인증을 받아주세요.");
              cp3.focus();  */
        } else if ( bank_num.value == "" )	{
            alert("'계좌번호'를 입력해주세요.");
            bank_num.focus();
        } else if ( bank_owner.value == "" )	{
            alert("'예금주'를 입력해주세요.");
            bank_owner.focus();
        } else if ( recjoin.value == "" )	{
            alert("'추천인'를 입력해주세요.");
            recjoin.focus();
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
                /*frm.HAF_Value_15.value = shop.value;*/

                frm.method = "POST";
                frm.action = "/action/member_action.php";
                frm.submit();
            };
        };
    };
</script>

<script>
    $(document).ready(function(){
        $("body").height($(window).height());
        $('.umMovie').height($(window).height());
        video_resize();

        /*$('.login').on('click',function(){

        });*/
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
                url : "./ajax.php",
                data : "mode=join&userId="+txt,
                success : function(res)
                {
                    //$('#server').html(res);

                    if($.trim(res) == 'y')	{
                        $('#id_use_yn').val('n');
                        alert('사용가능한 회원 아이디 입니다.');
                    } else	{
                        $('#id_use_yn').val('y');
                        alert('이미 사용중인 회원 아이디 입니다.');
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
            url : "./ajax.php",
            data : "mode=hp&hp="+$('#cp1').val()+'-'+$('#cp2').val()+'-'+$('#cp3').val(),
            success : function(res)
            {
                //$('#server').html(res);
                //alert(res);
                if($.trim(res) == 'y')	{
                    $('#hpk_use_yn').val('n');
                    alert('사용가능한 휴대폰번호 입니다.');
                } else	{
                    $('#hp_use_yn').val('y');
                    alert('이미 사용중인 휴대폰번호 입니다.');
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
        var ls_str = memo.value; // 이벤트가 일어난 컨트롤의 value 값
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
            alert(li_min+"자 이상 "+li_max+"이하로 입력해주세요.");
            if(li_byte> li_max)	{
                ls_str2 = ls_str.substr(0, li_len);
            } else	{
                ls_str2 = ls_str;
            }
            memo.value = ls_str2;
            memo.focus();
            return false;
        } else	{
            return true;
        }
    }

</script>

</body>
</html>
