function timeHandler(remind){

    var min = 0;
    var sec = 0;
    if(remind<60){
        if(remind == -1){
            gameRefresh();
        } else {
            min = 0;
            sec = remind;
        }
    } else {
        min = parseInt(remind/60);
        sec = remind%60;
    }

    var ct_sec1 = (parseInt(min)<10)?'0'+min:min;
    var ct_sec2 = (parseInt(sec)<10)?'0'+sec:sec;

    if(remind >= 60){
        console.log('c')
        $('#preloader').hide();
    } else {
        $('.disable-text').html('지금은 베팅하실 수 없습니다.');
        $('#preloader').show();
    }

    console.log*(ct_sec1+':'+ct_sec2)
    $('#remaind_time').text(ct_sec1+':'+ct_sec2)
    var rem = parseInt(remind)-1;
    $('#soccer_remind').text(rem);

    //console.log(remind+'- '+min+' : '+sec);
    //$('#ntry_remind').val(remind);
}

//로또사이트 시간 가져오기
function getServerTime(){
    var url = "./proxy.php";
    var promis = $.ajax({
        url: url,
        type: 'GET',
        dataType : 'json',
        cache : false,
        success : function(data){
            var end_time = data['remind_time'];
            $('#soccer_remind').text(end_time)
            setInterval(function(){
                timeHandler($('#soccer_remind').text());
            },1000);
        }
    });
}

//시간포맷변경
function getTimeStamp(ymdhis) {

    var d = new Date(ymdhis);
    var s1 =
        leadingZeros(d.getFullYear(), 4) + '-' +
        leadingZeros(d.getMonth() + 1, 2) + '-' +
        leadingZeros(d.getDate(), 2) + ' '+
        leadingZeros(d.getHours(), 2) + ':' +
        leadingZeros(d.getMinutes(), 2);
    var s2 =
        leadingZeros(d.getHours(), 2) + ':' +
        leadingZeros(d.getMinutes(), 2);

    //console.log(ymdhis+' : '+s2);
    //$('.grade-text').eq(2).html(s1);
    $('#endtime').text(s1);
    $('.timer-remind-text, #last_play_time').text(s2);

}

function leadingZeros(n, digits) {
    var zero = '';
    n = n.toString();

    if (n.length < digits) {
        for (i = 0; i < digits - n.length; i++)
            zero += '0';
    }
    return zero + n;
}

//경기마감 후 데이터를 새로만든다.
function gameRefresh(){
    var gd = '',
        re = '',
        next_datetime = '';


    gd = $('.timer-num-text').text().match(/[0-9]{1,3}/);
    re = $('.timer-time-text').text().match(/[0-9]{6,10}/);

    next_datetime = $('#endtime').text()+':44';

    var tDate = new Date(next_datetime);

    tDate.setFullYear(tDate.getFullYear());
    tDate.setMonth(tDate.getMonth());
    tDate.setDate(tDate.getDate());
    tDate.setHours(tDate.getHours());
    tDate.setMinutes(tDate.getMinutes()+5);
    tDate.setSeconds(tDate.getSeconds());

    getTimeStamp(tDate);


    $('.timer-num-text').text((parseInt(gd[0])+1));
    $('.timer-time-text').text((parseInt(re[0])+1));

    var last_turn = parseInt(gd[0])+1;
    if(last_turn == 299){
        location.reload(true);
    }
    //배당률 및 경기 선택 초기화
    //game_reset();
    //$('#preloader').fadeOut(2);

    location.reload();
}

//로또사이트 시간 가져오기
function getServerTime(){
    var url = "./proxy.php";
    var promis = $.ajax({
        url: url,
        type: 'GET',
        dataType : 'json',
        cache : false,
        success : function(data){
            var end_time = data['remind_time'];
            $('#soccer_remind').text(end_time);
            setInterval(function(){
                timeHandler($('#soccer_remind').text());
            },1000);
        }
    });
}

function countdown( elementName, minutes, seconds )
{
    var element, endTime, hours, mins, msLeft, time;

    function twoDigits( n )
    {
        return (n <= 9 ? "0" + n : n);
    }

    function updateTimer()
    {
        msLeft = endTime - (+new Date);
        if ( msLeft < 1000 ) {
            element.innerHTML = "00:00";
            location.reload();
        } else {
            time = new Date( msLeft );
            hours = time.getUTCHours();
            mins = time.getUTCMinutes();
            element.innerHTML = (hours ? hours + ':' + twoDigits( mins ) : mins) + ':' + twoDigits( time.getUTCSeconds() );
            setTimeout( updateTimer, time.getUTCMilliseconds() + 500 );
        }
    }

    element = document.getElementById( elementName );
    endTime = (+new Date) + 1000 * (60*minutes + seconds) + 500;
    updateTimer();
}

function change_time(){

}

$(document).ready(function(){
    getServerTime();
    var url = "../proc/";
    var promis = $.ajax({
        url: url,
        type: 'POST',
        dataType : 'json',
        data : {'HAF_Value_0':'virtualTimer'},
        success : function(data){
            console.log(data)
            var min = 0;
            var sec = 0;
            var soccer = parseInt(data['soccer_time']);
            var horse = parseInt(data['horse_time']);
            var dog = parseInt(data['dog_time']);

            if(soccer<60){
                min = 0;
                sec = soccer;
            } else {
                min = parseInt(soccer/60);
                sec = soccer%60;
            }

            var ct_sec1 = (parseInt(min)<10)?'0'+min:min;
            var ct_sec2 = (parseInt(sec)<10)?'0'+sec:sec;

            if(horse<60){
                min = 0;
                sec = horse;
            } else {
                min = parseInt(horse/60);
                sec = horse%60;
            }

            var ct_sec3 = (parseInt(min)<10)?'0'+min:min;
            var ct_sec4 = (parseInt(sec)<10)?'0'+sec:sec;

            if(dog<60){
                min = 0;
                sec = dog;
            } else {
                min = parseInt(dog/60);
                sec = dog%60;
            }

            var ct_sec5 = (parseInt(min)<10)?'0'+min:min;
            var ct_sec6 = (parseInt(sec)<10)?'0'+sec:sec;

            $('#virtual_soccer').text(ct_sec1+':'+ct_sec2);
            $('#virtual_soccer').text(ct_sec1+':'+ct_sec2);
            $('#virtual_soccer').text(ct_sec1+':'+ct_sec2);
            countdown( "virtual_soccer", ct_sec1, ct_sec2 );
            countdown( "virtual_horse", ct_sec3, ct_sec4 );
            countdown( "virtual_dog", ct_sec5, ct_sec6 );
        }
    });
    
    //
    
    $('#btn_betting').on('click',function(){

        var pickgame = 0,
            betball = parseInt(numberWithUnCommas($('#betball').val())),
            betexpect = parseInt(numberWithUnCommas($('#betexpect').val())),
            type = game_code,
            gubun = new Array(),
            num = $.trim($('#play_num_view').text());
            choice_rate = $.trim($('#betrate_sum').val()),
            loginchk = $(this).data('login');


        if($(this).data('login')==''){
            //swal_login();
            return false;
        }


        //선택한 경기가 있는지 체크
        var cnt = 0;
        pickgame = false;
        $('.btn-box').each(function(){
            if($(this).hasClass('active')){
                pickgame = true;
            }
        });

        if(!pickgame){
            swal("","게임을 선택하여 주십시요.","info");
            return false;
        }

        if(betball <  config_bet_bound_min){
            swal("","베팅금액을 선택하여 주십시오.","info");
            return false;
        }

        if(betball >  config_bet_bound_max){
            swal("","베팅금액을 " + numberWithCommas(config_bet_bound_max) + " 이하로 입력하세요","info");
            return false;
        }

        if(betexpect >  config_bet_reward_max){
            swal("","예상 당첨금액 허용최대치(" + numberWithCommas(config_bet_reward_max) + ")를 초과할 수 없습니다","info");
            return false;
        }


        if(!loginchk){
            var msg = "로그인 후 이용하세요";
            swal({
                title: '<span class="alert-text">' + msg+ '</span>',
                type: 'info',
                html: "<button type='button' class='swal2-styled go-to-login' style='background-color: rgb(48, 133, 214); border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);'>로그인</button><button type='button' class='swal2-styled go-to-register' style='display: inline-block; background-color: rgb(170, 170, 170);'>회원가입</button>",
                showConfirmButton: false
            }).catch(swal.noop);
            return false;
        }

        var gkey = $(this).data('gkey');
        var glkey = $(this).data('glkey');
        var datetime = $(this).data('datetime');
        var all_rate = $(this).data('allrate');
        var bet_selected = $(this).data('bet-selected');


        swal({
            text: numberWithCommas(betball)+"원을 베팅하시겠습니까?",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "확인",
            cancelButtonText: "취소",
            closeOnConfirm: true
        })
            .then((isConfirm)=>{
                if(isConfirm){
                    console.log('bet_selected -> '+bet_selected);
                    MinigameCartBuy(betball, betexpect, num, type, choice_rate, gkey, glkey, datetime, all_rate, bet_selected );
                }
            });
    });


    //베팅머니 선택시
    $('.btn-select-coin').on('click',function(){
        console.log('a')
        var bet_money = $(this).data('money'),
            total_money = parseInt(numberWithUnCommas($('#betball').val())),
            betsum = $('#betrate_sum').val();


        if(bet_money == 0 && $(this).text()=='초기화'){//reset
            $('#betball, #betexpect').prop('value','0');
            total_money = 0;
            $('#other_money_use').val('n');
            $('#direct_input_money').prop('value','');
        } else {
            total_money = total_money + bet_money;
        }

        $('#betball').prop('value',numberWithCommas(total_money));
        calcHitMoney();
    });

    $('#other-money').on('click',function(){
        var bet_money = 0,
            total_money = parseInt(numberWithUnCommas($('#betball').val())),
            betsum = $('#betrate_sum').val();
        //if($('#other_money_use').val()=='n'){
        bet_money = parseInt($('#other_money').val());
        $('#other_money_use').val('y');
        total_money = bet_money;
        //}

        $('#betball').prop('value',numberWithCommas(total_money));
        calcHitMoney();
    });


    // 베팅금액 직접 입력하기
    $('#direct_input_money').on('keyup',function(){
        var direct_money = parseInt(numberWithUnCommas($(this).val()));
        $(this).prop('value', numberWithCommas(direct_money));
        if (parseInt(numberWithUnCommas(direct_money)) > 0) {
            $('#betball').prop('value', numberWithCommas(direct_money));
            calcHitMoney();
        } else {
            $(this).prop('value', '');
        }
    });



    //게임 배당률 선택시
    $('.btn-box').on("click",function(e){
        var memberBetSelect = "";
        var real_turn = $('.timer-time-text').text(),
            fake_turn = $('.timer-num-text').text(),
            game_rate = $(this).data('rate'),
            game_type = $(this).data('type'),
            game_text = $(this).data('text'),
            game_select = $(this).data('selected');
            real_turn_num = new Array(),
            cur_selected = $(this).find('img'),
            cur_is_selected = '',
            img_selected = '',
            img_is_selected = '',
            gubun = new Array(),
            rate_gubun = 'P';

        /*gubun[0] = $(this).data('gkey');
        gubun[1] = $(this).data('glkey');
        gubun[2] = $(this).data('datetime');
        gubun[3] = $(this).data('type');
        gubun[4] = $(this).data('num');
        gubun[5] = game_rate;
        gubun[6] = $(this).data('betexpect');
        gubun[7] = $(this).data('betball');*/

        console.log($(this).data('gkey')+'-'+$(this).data('glkey'))
        $('#btn_betting').data('gkey',$(this).data('gkey'));
        $('#btn_betting').data('glkey',$(this).data('glkey'));
        $('#btn_betting').data('allrate',$(this).data('allrate'));
        console.log($(this).data('selected-eng'))
        $('#btn_betting').data('bet-selected',$(this).data('selected-eng'));

        $('#bet_list').val(gubun);





        //실제회차를 가져온다.
        real_turn_num = real_turn.match(/[0-9]{5,7}/);

        //selBg = $(this).attr('class').split(" ");


        //console.log(game_rate+' : '+real_turn_num[0]+' : '+game_type+' : '+game_text);
        //배당률, 회차, 경기구분, 경기명이 하나라도 없으면 오류 발생
        if(game_rate === undefined || real_turn_num[0] == '' || game_type == '' || game_text == '' ){
            alert('게임 선택시 문제가 발생했습니다. 다시 시도해주세요!');
            return false;
        } else {
            //이미 선택된 같은 베팅이라면
            if($(this).hasClass('active')){
                //$('.game-text:nth(1)').text('게임을선택하세요');
                $('#selBet>span.tx').text('-');
                $('#betrate_sum').prop('value','1.00');
                $('#betexpect').prop('value','0');
                $(this).removeClass('active');
                $('#selBet span.tx').removeClass().addClass('tx ');
                //$('#selBet span.tx').addClass('')
                /*img_selected = cur_selected.removeClass('active').replace('_on','');
                cur_selected.attr('src',img_selected);*/
            } else {
                //선택된게 있지만 같은 게임은 아니라면
                var cnt = 0;
                $('.btn-box').each(function(){
                    $(this).removeClass('active');
                });
                //카드에 배당률 넣어봅시다
                var img = $(this).find('img').attr('src');
                $('#betrate_sum').prop('value',game_rate);
                $('.game-text:nth(1)').find('img').attr('src',img);
                //$('.game-text:nth(2)').find('img').attr('src','/ko/assets/images/sub/games/mini_btn_pb_odd_under.png');

                $(this).addClass('active');

                $('#selBet span.tx').text(game_select);
                rate_gubun = 'P';
            }
            calcHitMoney();
        }

    });


    //지난회차 결과보기
    $("#lastbatting_bt").click(function(){
        $("#lastbatting_win").show();
    });
    $("#lastbatting_win .wclosebt").click(function(){
        $("#lastbatting_win").hide();
    });


    //베팅내역 전체선택
    $('#all_selected, .all_selected').on('click',function(){

        var chk = $(this).data('chk');
        if(chk==0){
            $('.chkbox').each(function () {
                $(this).prop('checked', true);
            });
            $(this).text('선택해제');
            $(this).data('chk','1');
        } else {
            $('.chkbox').each(function () {
                $(this).prop('checked', false);
            });
            $(this).text('전체선택');
            $(this).data('chk','0');
        }

    });

    //베팅내역 전체 삭제
    $('#all_delete').on('click',function(){
        var arr_data = new Array();
        var cnt = 0;

        $('.chkbox').each(function(){
            if($(this).is(':checked')==true) {
                arr_data[cnt] = $(this).data("idx");
                cnt++;
            }
        });


        if(!arr_data.length){
            swal('','삭제하실 베팅내역을 선택해주세요.','warning');
            return;
        }


        swal({
            text: "베팅내역을 삭제하시겠습니까?",
            type : "question",
            showCancelButton: true,
            confirmButtonText: "확인",
            cancelButtonText: "취소",
        })
            .then((isConfirm)=>{
                if(isConfirm){

                    var jsonurl =  "/games/prc/betting_list_delete.php";

                    $.ajax({
                        type: "POST",
                        url: jsonurl,
                        dataType: "JSON",
                        data: {
                            "idx": arr_data
                        },
                        success: function (data) {
                            if (data.flag) {
                                swal({
                                    text: "베팅내역을 성공적으로 삭제했습니다.",
                                    type : "success",
                                    confirmButtonText: "확인",
                                })
                                    .then((isConfirm)=> {
                                        if (isConfirm) {
                                            location.reload(true);
                                        }
                                    });


                                //location.reload(true);
                            } else {

                            }
                        },
                        complete: function (data) {
                        },
                        error: function (xhr, status, error) {
                            var err = status + ' \r\n' + error;
                        }
                    });
                }
            });
    });

});

$(document).on('click','#page_more',function(){
    var jsonurl =  LANGUAGE + "/games/prc/result_minigame_prc.php";

    $.ajax({
        type: "POST",
        url: jsonurl,
        dataType: "JSON",
        data: {
            "page": $('#pm').val(),
            "game_type" : game_code
        },
        success: function (data) {
            if (data.flag) {
                var html = '';
                var smb_range = '';
                for(var i=0;i<data.rs.length;i++){
                    if(data.rs[i]['smb']=='대'){
                        smb_range = '(81-130)';
                    } else if(data.rs[i]['smb']=='중'){
                        smb_range = '(65-80)';
                    } else {
                        smb_range = '(15-64)';
                    }
                    html += '<tr>';
                    html += '<td class="td-body">'+data.rs[i]['faketurn']+'</td>';
                    html += '<td class="td-body">'+data.rs[i]['lotteryNo']+'</td>';
                    html += '<td class="td-body">'+data.rs[i]['ballsum']+'</td>';
                    html += '<td class="td-body">'+data.rs[i]['power_eo']+'</td>';
                    html += '<td class="td-body">'+data.rs[i]['lotteryPowerBall']+'</td>';
                    html += '<td class="td-body">'+data.rs[i]['powerball_eo']+'</td>';
                    html += '<td class="td-body">'+data.rs[i]['smb']+smb_range+'</td>';
                    html += '<td class="td-body">'+data.rs[i]['power_range']+'</td>';
                    html += '</tr>';
                }
                $('.mgcdetC').find('tbody').append(html);
                $('#page_more').attr('data-page',data.next_num);
                $('#pm').val(data.next_num);
            } else {
                messagebox("더이상 수신된 결과가 없습니다.");
            }
        },
        complete: function (data) {
        },
        error: function (xhr, status, error) {
            var err = status + ' \r\n' + error;
        }
    });
});

$(document).on('click','#all_betting_list',function(){
    var jsonurl =  LANGUAGE + "/games/prc/betting_powerball_prc.php";

    $.ajax({
        type: "POST",
        url: jsonurl,
        dataType: "JSON",
        data: {
            "page": $('#pm').val(),
            "game_type" : game_code
        },
        success: function (data) {
            var html = '';
            if (data.flag) {
                $('.betting_history').empty();
                for(var i=0;i<data.rs.length;i++){
                    console.log(1);
                    html += '<tr>';
                    html += '<td class="chk"><input type="checkbox" name="chk[]" value=""></td>';
                    html += '<td class="num">'+(i+1)+'</td>';
                    html += '<td class="date">'+data.rs[i]['eventId']+'</td>';
                    html += '<td class="time">'+data.rs[i]['regdate']+'</td>';
                    html += '<td class="sort"><b>'+data.rs[i]['gamename']+'</b></td>';
                    html += '<td class="state"><strong class="">'+data.rs[i]['my_bet']+'</strong></td>';
                    html += '<td class="per">'+data.rs[i]['betrate']+'</td>';
                    html += '<td class="money01 td_right">'+data.rs[i]['betball']+'원</td>';
                    html += '<td class="money02 td_right"></td>';
                    html += '<td class="result ">'+data.rs[i]['result']+'</td>';
                    html += '</tr>';
                }
                $('.betting_history').append(html);
            } else {
                messagebox("더이상 수신된 결과가 없습니다.");
            }
        },
        complete: function (data) {
        },
        error: function (xhr, status, error) {
            var err = status + ' \r\n' + error;
        }
    });
});



$(document).on('click','#page_more_ladder',function(){
    var jsonurl =  LANGUAGE + "/games/prc/result_minigame_prc.php";

    $.ajax({
        type: "POST",
        url: jsonurl,
        dataType: "JSON",
        data: {
            "page": $('#pm').val(),
            "game_type" : game_code
        },
        success: function (data) {
            if (data.flag) {
                var html = '';
                var smb_range = '';
                for(var i=0;i<data.rs.length;i++){
                    if(data.rs[i]['smb']=='대'){
                        smb_range = '(81-130)';
                    } else if(data.rs[i]['smb']=='중'){
                        smb_range = '(65-80)';
                    } else {
                        smb_range = '(15-64)';
                    }
                    html += '<tr>';
                    html += '<td class="td-body">'+data.rs[i]['faketurn']+'</td>';
                    html += '<td class="td-body">'+data.rs[i]['start']+'</td>';
                    html += '<td class="td-body">'+data.rs[i]['line']+'</td>';
                    html += '<td class="td-body">'+data.rs[i]['pattern']+'</td>';
                    html += '</tr>';
                }
                $('.mgcdetC').find('tbody').append(html);
                $('#page_more').attr('data-page',data.next_num);
                $('#pm').val(data.next_num);
            } else {
                messagebox("더이상 수신된 결과가 없습니다.");
            }
        },
        complete: function (data) {
        },
        error: function (xhr, status, error) {
            var err = status + ' \r\n' + error;
        }
    });
});

//예상 당첨금 계산하기
function calcHitMoney(){
    var betMoney = parseInt(numberWithUnCommas($('#betball').val())),
        betRate = parseFloat($.trim($('#betrate_sum').val())),
        betexpect = 0;

    var total = 0;
    if(!betRate){
        total = betMoney;
    } else {
        total = betRate*betMoney;
        betexpect = total.toFixed(2).split('.');
        $('#betexpect').prop('value',numberWithCommas(betexpect[0]));
    }

}




//게임 reset
function game_reset(){
    $('#betball').prop('value','0');
    $('#betrate_sum').prop('value','1.00');
    $('#betexpect').prop('value','0');
    //$('.game-text:first()').text('게임을 선택하세요.');
    $('.game-text:nth-child(2)').find('img').prop('src','');
    $('.btn-box').each(function(){
        $(this).removeClass('active');
    });
    $('#direct_input_money').val('');
    $('#selBet span').removeClass().addClass('tx active');
}
var numberWithCommas = function(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
var numberWithCommas2 = function(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
var numberWithUnCommas = function(x){
    return x.toString().replace(/[^\d]+/g, '');
}

var decimalToInteger1000 = function(x){
    return Math.floor(x * 1000);
}

var digit3Floor = function(x){
    return Math.floor(x * 1000)  / 1000;
}

var digit2Floor = function(x){
    return Math.floor(x * 100)  / 100;
}

var decimalToFloor1000 = function(x){
    return x / 1000;
}


function number_format(data)
{
    var tmp = '';
    var number = '';
    var cutlen = 3;
    var comma = ',';
    var i;
    var sign = data.match(/^[\+\-]/);
    if(sign) {
        data = data.replace(/^[\+\-]/, "");
    }
    len = data.length;
    mod = (len % cutlen);
    k = cutlen - mod;
    for (i=0; i<data.length; i++)
    {
        number = number + data.charAt(i);
        if (i < data.length - 1)
        {
            k++;
            if ((k % cutlen) == 0)
            {
                number = number + comma;
                k = 0;
            }
        }
    }
    if(sign != null)
        number = sign+number;
    return number;
}
$(function() {
    $("input#price").on("keyup", function() {
        var val = String(this.value.replace(/[^0-9]/g, ""));
        if(val.length < 1)
            return false;
        this.value = number_format(val);
    });
});



function refresh_info() {
    //보유머니 항상 최신으로 만들기
    $.ajax({
        type: 'POST',
        dataType: 'json',
        cache : false,
        url: '/user/prc/infochk.php',
        success: function (res) {
            $('.user-money-point').text(res['points']);
            $('.user-money-balance').text(res['money']+'원');
        }
    });
}

