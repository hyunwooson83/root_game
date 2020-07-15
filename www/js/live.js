var timer;
$(document).ready(function(){
    $('.left-game-list-box').on('click',function(){
        var gid = $(this).data('gid');
        var wdl = $(this).data('wdl');
        var handicap = $(this).data('handicap');
        var ou = $(this).data('ou');
        var gkey = $(this).data('gkey');
        get_data(gid,wdl,handicap,ou,gkey);
        clearInterval(timer);
        timer = setInterval(function(){
            get_data(gid,wdl,handicap,ou, gkey);
        } , 3000);
    });
<?php if($arr_cnt>0){ ?>
        get_live_score();
        setInterval(get_live_score,10000);
    <?php } ?>

    $('.left-game-list-box').on('click',function(){
        $('.left-game-list-box').each(function(){
            $(this).removeClass('on');
        })
        if($(this).hasClass('on')==true){
            $(this).removeClass('on');
        } else {
            $(this).addClass('on');
        }
    })
});


var loading_flag  = false;
//진행중인 경기의 라이브데이터
function get_live_score(){
    $.ajax({
        type : 'post',
        url : '../proc/liveScore.php',
        async: true,
        dataType : 'json',
        data : 'mode=gameLiveScore&item=soccer',
        beforeSend : function(){
            if(loading_flag == false) {
                $('.loading').show();
            }
        },
        success : function(data) {

            for (var i = 0; i < data.length; i++) {

                if (data[i]['timeStatus'] == 3) {
                    //location.reload();
                    $('#game_list_'+data[i]['gid']).hide();
                } else {
                    if (data[i]['sportsId'] != '16') {
                        $('#remind_time_' + data[i]['gid']).text(data[i]['timeM'] + ':' + data[i]['timeS']);
                    } else {
                        $('#remind_time_' + data[i]['gid']).text('');
                    }
                    $('#disp_game_half_text_' + data[i]['gid']).text(data[i]['timeKorMark']);

                    var home_score = (data[i]['homeScore'] == '')?'':data[i]['homeScore'];
                    var away_score = (data[i]['awayScore'] == '')?'':data[i]['awayScore'];

                    $('#home_score_' + data[i]['gid']).text(data[i]['homeScore']);
                    $('#away_score_' + data[i]['gid']).text(data[i]['awayScore']);
                    $('#home_team_name').text(data[i]['homeName']);
                    $('#away_team_name').text(data[i]['awayName']);
                }
            }
            $('.loading').hide();
            loading_flag = true;
        }
    });
}

//get_data();
//setInterval('get_data()',3000);
function get_data(gid, wdl, handicap, ou, gkey){

    //console.log(gkey)
    $.ajax({
        type : 'post',
        url : '../proc/getData.php',
        async: true,
        dataType : 'json',
        data : 'gid='+gid+'&mode=gameSelected&gkey='+gkey+'&item=soccer',
        success : function(data){
            var market_code_wdl = wdl;
            var market_code_handi = handicap;
            var market_code_ou = ou;
            var stop_flag = new Array('HT','FT');
            var minus_rate = 0.03;
            if (data[0]['timeStatus'] == 3) {
                //location.reload();
                $('#markets' + market_code_wdl + '_' + data[0]['gid']).hide();
            } else {




                /* 승패 최종 결과 */
                if(typeof data[0]['markets']['<?php echo $market_code_wdl;?>'] != 'undefined') {
                    var home_team_name = data[0]['markets']['<?php echo $market_code_wdl;?>']['matchOdds'][0]['oddsName'];
                    var away_team_name = data[0]['markets']['<?php echo $market_code_wdl;?>']['matchOdds'][2]['oddsName'];
                    if(home_team_name.length>15){
                        home_team_name = home_team_name.substring(0,10)+'...';
                    }

                    if(away_team_name.length>15){
                        away_team_name = away_team_name.substring(0,10)+'...';
                    }

                    console.log(away_team_name)
                    if (data[0]['markets']['<?php echo $market_code_wdl;?>']['suspended'] == false || inArray(data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][0]['option'],stop_flag) == false) {
                        //$('.live-list-box.wdl').empty();
                        var home_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_wdl;?>']['matchOdds'][0]['odds']))-minus_rate);
                        var draw_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_wdl;?>']['matchOdds'][1]['odds']))-minus_rate);
                        var away_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_wdl;?>']['matchOdds'][2]['odds']))-minus_rate);
                        var cur_home_rate = $('#home_rate_' + data[0]['gid']).text();
                        var cur_draw_rate = $('#draw_rate_' + data[0]['gid']).text();
                        var cur_away_rate = $('#away_rate_' + data[0]['gid']).text();

                        bet_three = '';


                        var wdl_box = $('#markets' + market_code_wdl + '_' + gid);
                        if (wdl_box.length == 0) {
                            bet_three += '<tr id="markets' + market_code_wdl + '_' + gid + '">';
                            bet_three += '    <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">';
                            bet_three += '        <table><tr><td colspan="3" style="width: 100%; text-align: center;  height:30px;">최종 승무패 결과</td></tr>';
                            bet_three += '            <tr>';
                            bet_three += '                <td class="three-bet-btn">';
                            bet_three += '                    <div class="bl_btn bl_text_btn long betting-btn-live win-'+gid+' live-bet home" data-code="<?php echo $market_code_wdl;?>" data-bet="Win" data-gid="'+gid+'" data-gkey="'+gkey+'" data-rate="'+home_rate+'" data-homeName="'+home_team_name+'" data-awayName="'+away_team_name+'">';
                            bet_three += '                        <span class="live-left">' + home_team_name + '</span>';
                            bet_three += '                        <span class="live-right" id="home_rate_' + gid + '">' + home_rate + '</span>';
                            bet_three += '                    </div>';
                            bet_three += '                </td>';
                            bet_three += '                <td class="three-bet-btn">';
                            bet_three += '                    <div class="bl_btn bl_text_btn long betting-btn-live draw-'+gid+' live-bet draw" data-code="<?php echo $market_code_wdl;?>" data-bet="Draw" data-gid="'+gid+'" data-gkey="'+gkey+'" data-rate="'+draw_rate+'" data-homeName="'+home_team_name+'" data-awayName="'+away_team_name+'">';
                            bet_three += '                        <span class="live-left">무승부</span>';
                            bet_three += '                        <span class="live-right" id="draw_rate_' + gid + '">' + draw_rate + '</span>';
                            bet_three += '                    </div>';
                            bet_three += '                </td>';
                            bet_three += '                <td class="three-bet-btn">';
                            bet_three += '                    <div class="bl_btn bl_text_btn long betting-btn-live lose-'+gid+' live-bet away" data-code="<?php echo $market_code_wdl;?>" data-bet="Lose" data-gid="'+gid+'" data-gkey="'+gkey+'" data-rate="'+away_rate+'" data-homeName="'+home_team_name+'" data-awayName="'+away_team_name+'" data-type="패" style="margin-right:3px;">';
                            bet_three += '                        <span class="live-left">' + away_team_name + '</span>';
                            bet_three += '                        <span class="live-right" id="away_rate_' + gid + '">' + away_rate + '</span>';
                            bet_three += '                    </div>';
                            bet_three += '                </td>';
                            bet_three += '            </tr>';
                            bet_three += '        </table>';
                            bet_three += '    </td>';
                            bet_three += '</tr>';

                            $('.live-list-box.wdl').empty().append(bet_three);
                        } else {
                            console.log('승무패 = '+data[0]['gid']+' [ '+home_rate+' : '+draw_rate+' : '+away_rate+']')
                            $('#home_rate_' + data[0]['gid']).text(home_rate);
                            if(cur_home_rate != home_rate) {
                                change_text_color('text-red-blink', $('#home_rate_' + data[0]['gid']));
                            }
                            //$('.cart-rate-Win-'+gid).text(home_rate)
                            $('#draw_rate_' + data[0]['gid']).text(draw_rate);
                            if(cur_draw_rate != draw_rate) {
                                change_text_color('text-yellow-blink', $('#draw_rate_' + data[0]['gid']));
                            }
                            //$('.cart-rate-Draw-'+gid).text(home_rate)
                            $('#away_rate_' + data[0]['gid']).text(away_rate);
                            //$('.cart-rate--'+gid).text(home_rate)
                            if(cur_away_rate != away_rate) {
                                change_text_color('text-blue-blink', $('#away_rate_' + data[0]['gid']));
                            }

                            $('.live-list-box.wdl').show();
                        }

                        $('#markets' + market_code_wdl + '_' + data[0]['gid']).show();
                    } else {
                        $('#markets' + market_code_wdl + '_' + gid).empty();
                        $('.live-list-box.wdl').hide();
                    }
                } else {
                    $('#markets' + market_code_wdl + '_' + gid).empty();
                    $('.live-list-box.wdl').hide();
                }

                /* 핸디캡 최종 결과*/

                /* 핸디캡 최종 결과*/
                var no_handicap = new Array('+0.25','+0.75','+1.25','+1.75','+2.25','+2.75','+3.25','+3.75','+4.25','+4.75','+5.25','+5.75','+6.25','+7.25','+8.25','+9.25','+10.25','+11.25','+12.25','+13.25','0.5','-0.5');
                //$('.live-list-box.handicap').empty();
                //console.log(data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][0]['option']);
                if(typeof data[0]['markets']['<?php echo $market_code_handi;?>'] != 'undefined') {
                    if (data[0]['markets']['<?php echo $market_code_handi;?>']['suspended'] == false) {
                        var home_team_name = data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][0]['oddsName'];
                        var away_team_name = data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][1]['oddsName'];
                        if(home_team_name.length>15){
                            home_team_name = home_team_name.substring(0,10)+'...';
                        }

                        if(away_team_name.length>15){
                            away_team_name = away_team_name.substring(0,10)+'...';
                        }
                        if (inArray(data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][0]['option'],no_handicap) == false && inArray(data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][1]['option'],no_handicap) == false) {

                            var home_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][0]['odds']))-minus_rate);
                            var home_rate_option = data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][0]['option'];
                            var away_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][1]['odds']))-minus_rate);
                            var away_rate_option = data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][1]['option'];

                            var cur_home_rate = $('#home_handicap_rate_' + data[0]['gid']).text();
                            var cur_away_rate = $('#away_handicap_rate_' + data[0]['gid']).text();

                            bet_two = '';

                            console.log('handicap')
                            $('#markets' + market_code_handi + '_' + gid).show();
                            var handi_box = $('#markets' + market_code_handi + '_' + gid);
                            if (handi_box.length == 0) {
                                bet_two += '<tr id="markets' + market_code_handi + '_' + gid + '">';
                                bet_two += '    <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">';
                                bet_two += '        <table>';
                                bet_two += '            <tr><td colspan="2" style="width: 100%; text-align: center;  height:30px;">'+data[0]['markets']['<?php echo $market_code_handi;?>']['korName']+'</td></tr>';
                                bet_two += '            <tr>';
                                bet_two += '                <td class="two-bet-btn">';
                                bet_two += '                    <div class="bl_btn bl_text_btn long betting-btn-live win-'+gid+' live-bet HandiWin" data-bet="HandiWin" data-code="<?php echo $market_code_handi;?>" data-gid="'+gid+'" data-gkey="'+gkey+'" data-rate="'+home_rate+'" data-homeName="'+home_team_name+'" data-awayName="'+away_team_name+'"  data-awayName="'+away_team_name+'">';
                                bet_two += '                        <span class="live-left">' + home_team_name + '</span>';
                                bet_two += '                        <span class="live-right" >' + '[<span id="home_handicap_option_' + gid + '">' + home_rate_option + '</span>] <span id="home_handicap_rate_' + gid + '">'+home_rate + '</span></span>';
                                bet_two += '                    </div>';
                                bet_two += '                </td>';
                                bet_two += '                <td class="two-bet-btn">';
                                bet_two += '                    <div class="bl_btn bl_text_btn long betting-btn-live lose-'+gid+' live-bet HandiLose" data-bet="HandiLose" data-code="<?php echo $market_code_handi;?>" data-gid="'+gid+'" data-gkey="'+gkey+'" data-rate="'+away_rate+'" data-homeName="'+home_team_name+'" data-awayName="'+away_team_name+'" data-type="패" style="margin-right:3px;">';
                                bet_two += '                        <span class="live-left">' + away_team_name + '</span>';
                                bet_two += '                        <span class="live-right" >' + '[<span id="away_handicap_option_' + gid + '">' + away_rate_option + '</span>] <span id="away_handicap_rate_' + gid + '">' + away_rate + '</span></span>';
                                bet_two += '                    </div>';
                                bet_two += '                </td>';
                                bet_two += '            </tr>';
                                bet_two += '        </table>';
                                bet_two += '    </td>';
                                bet_two += '</tr>';
                                $('.live-list-box.handicap').empty().append(bet_two);
                            } else {
                                console.log('핸디 = '+data[0]['gid']+' [ '+home_rate+' : '+away_rate+']')
                                $('#home_handicap_rate_' + data[0]['gid']).text(home_rate);
                                $('#away_handicap_rate_' + data[0]['gid']).text(away_rate);

                                $('#home_handicap_option_' + data[0]['gid']).text(home_rate_option);
                                $('#away_handicap_option_' + data[0]['gid']).text(away_rate_option);

                                if(cur_home_rate != home_rate) {
                                    change_text_color('text-red-blink', $('#home_handicap_rate_' + data[0]['gid']));
                                }
                                if(cur_away_rate != away_rate) {
                                    change_text_color('text-blue-blink', $('#away_handicap_rate_' + data[0]['gid']));
                                }
                            }
                            $('.live-list-box.handicap').show();
                        } else {
                            $('#markets' + market_code_handi + '_' + gid).hide();
                            $('.live-list-box.handicap').hide();
                        }
                    } else {
                        $('#markets' + market_code_handi + '_' + gid).hide();
                        $('.live-list-box.handicap').hide();
                    }
                }


                /* 핸디캠 최종 기타 기준점*/
                var no_handicap_etc = new Array('+0.25','+0.75','+1.25','+1.75','+2.25','+2.75','+3.25','+3.75','+4.25','+4.75','+5.25','+5.75','+6.25','+7.25','+8.25','+9.25','+10.25','+11.25','+12.25','+13.25');
                //$('.live-list-box.handicap').empty();
                //console.log(data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][0]['option']);
                if(typeof data[0]['markets']['<?php echo $handicap_etc;?>'] != 'undefined') {
                    if (data[0]['markets']['<?php echo $market_code_handi;?>']['suspended'] == false) {
                        var home_team_name = data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][0]['oddsName'];
                        var away_team_name = data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][1]['oddsName'];
                        if(home_team_name.length>15){
                            home_team_name = home_team_name.substring(0,10)+'...';
                        }

                        if(away_team_name.length>15){
                            away_team_name = away_team_name.substring(0,10)+'...';
                        }
                        if (inArray(data[0]['markets']['<?php echo $market_code_handi;?>']['matchOdds'][0]['option'],no_handicap) == false && inArray(data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][1]['option'],no_handicap_etc) == false) {

                            var home_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][0]['odds']))-minus_rate);
                            var home_rate_option = data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][0]['option'];
                            var away_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][1]['odds']))-minus_rate);
                            var away_rate_option = data[0]['markets']['<?php echo $handicap_etc;?>']['matchOdds'][1]['option'];

                            var cur_home_rate = $('#home_handicap_rate_' + data[0]['gid']).text();
                            var cur_away_rate = $('#away_handicap_rate_' + data[0]['gid']).text();

                            bet_two = '';

                            console.log('handicap')
                            $('#markets' + market_code_handi + '_' + gid).show();
                            var handi_box_etc = $('#markets' + market_code_handi + '_' + gid);
                            if (handi_box_etc.length == 0) {
                                bet_two += '<tr id="markets' + market_code_handi + '_' + gid + '">';
                                bet_two += '    <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">';
                                bet_two += '        <table>';
                                bet_two += '            <tr><td colspan="2" style="width: 100%; text-align: center;  height:30px;">'+data[0]['markets']['<?php echo $handicap_etc;?>']['korName']+'</td></tr>';
                                bet_two += '            <tr>';
                                bet_two += '                <td class="two-bet-btn">';
                                bet_two += '                    <div class="bl_btn bl_text_btn long betting-btn-live win-'+gid+' live-bet HandiWin" data-bet="HandiWin" data-code="<?php echo $handicap_etc;?>" data-gid="'+gid+'" data-gkey="'+gkey+'" data-rate="'+home_rate+'" data-homeName="'+home_team_name+'" data-awayName="'+away_team_name+'"  data-awayName="'+away_team_name+'">';
                                bet_two += '                        <span class="live-left">' + home_team_name + '</span>';
                                bet_two += '                        <span class="live-right" >' + '[<span id="home_handicap_option_' + gid + '">' + home_rate_option + '</span>] <span id="home_handicap_rate_' + gid + '">'+home_rate + '</span></span>';
                                bet_two += '                    </div>';
                                bet_two += '                </td>';
                                bet_two += '                <td class="two-bet-btn">';
                                bet_two += '                    <div class="bl_btn bl_text_btn long betting-btn-live lose-'+gid+' live-bet HandiLose" data-bet="HandiLose" data-code="<?php echo $handicap_etc;?>" data-gid="'+gid+'" data-gkey="'+gkey+'" data-rate="'+away_rate+'" data-homeName="'+home_team_name+'" data-awayName="'+away_team_name+'" data-type="패" style="margin-right:3px;">';
                                bet_two += '                        <span class="live-left">' + away_team_name + '</span>';
                                bet_two += '                        <span class="live-right" >' + '[<span id="away_handicap_option_' + gid + '">' + away_rate_option + '</span>] <span id="away_handicap_rate_' + gid + '">' + away_rate + '</span></span>';
                                bet_two += '                    </div>';
                                bet_two += '                </td>';
                                bet_two += '            </tr>';
                                bet_two += '        </table>';
                                bet_two += '    </td>';
                                bet_two += '</tr>';
                                $('.live-list-box.handicap_etc').empty().append(bet_two);
                            } else {
                                console.log('핸디 = '+data[0]['gid']+' [ '+home_rate+' : '+away_rate+']')
                                $('#home_handicap_rate_' + data[0]['gid']).text(home_rate);
                                $('#away_handicap_rate_' + data[0]['gid']).text(away_rate);

                                $('#home_handicap_option_' + data[0]['gid']).text(home_rate_option);
                                $('#away_handicap_option_' + data[0]['gid']).text(away_rate_option);

                                if(cur_home_rate != home_rate) {
                                    change_text_color('text-red-blink', $('#home_handicap_rate_' + data[0]['gid']));
                                }
                                if(cur_away_rate != away_rate) {
                                    change_text_color('text-blue-blink', $('#away_handicap_rate_' + data[0]['gid']));
                                }
                            }
                            $('.live-list-box.handicap_etc').show();
                        } else {
                            $('#markets' + market_code_handi + '_' + gid).hide();
                            $('.live-list-box.handicap_etc').hide();
                        }
                    } else {
                        $('#markets' + market_code_handi + '_' + gid).hide();
                        $('.live-list-box.handicap_etc').hide();
                    }
                }

                /* 언오버 최종 결과*/
                //$('.live-list-box.ou').empty();
                var no_point = new Array(0.25,0.75,1.25,1.75,2.25,2.75,3.25,3.75,4.25,4.75,5.25,5.75,6.25,6.75,7.25,7.75,8.25,8.75,9.25,9.75,10.25,10.75,11.25,11.75,12.25,12.75,13.25,13.75,14.25,15.75,15.25,15.75);

                //console.log(data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][0]['option'])
                if(typeof data[0]['markets']['<?php echo $market_code_ou;?>'] != 'undefined') {
                    var home_team_name = data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][0]['oddsName'];
                    var away_team_name = data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][1]['oddsName'];
                    if(home_team_name.length>15){
                        home_team_name = home_team_name.substring(0,10)+'...';
                    }

                    if(away_team_name.length>15){
                        away_team_name = away_team_name.substring(0,10)+'...';
                    }
                    if (data[0]['markets']['<?php echo $market_code_ou;?>']['suspended'] == false) {
                        console.log('언오버 = '+data[0]['gid']+' [ '+home_rate+' : '+away_rate+'] '+data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][0]['option'])
                        if(inArray(data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][0]['option'],no_point) == false) {

                            var home_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][0]['odds']))-minus_rate);
                            var away_rate = rate_point2(parseFloat(rate_point2(data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][1]['odds']))-minus_rate);
                            var base_rate = data[0]['markets']['<?php echo $market_code_ou;?>']['matchOdds'][0]['option'];

                            var cur_home_rate = $('#home_over_rate_' + data[0]['gid']).text();
                            var cur_away_rate = $('#away_under_rate_' + data[0]['gid']).text();

                            bet_three = '';
                            $('#markets' + market_code_ou + '_' + data[0]['gid']).show();
                            var wdl_box = $('#markets' + market_code_ou + '_' + gid);
                            if (wdl_box.length == 0) {
                                bet_three += '<tr id="markets' + market_code_ou + '_' + gid + '">';
                                bet_three += '    <td colspan="5" style="width: 100%; padding-left:15px; padding-right:15px;margin-bottom:10px;" height="40">';
                                bet_three += '        <table><tr><td colspan="3" style="width: 100%; text-align: center;  height:30px;">'+data[0]['markets']['<?php echo $market_code_ou;?>']['korName']+'</td></tr>';
                                bet_three += '            <tr>';
                                bet_three += '                <td class="three-bet-btn">';
                                bet_three += '                    <div class="bl_btn bl_text_btn long betting-btn-live over-' + gid + ' live-bet over" data-bet="Over" data-code="<?php echo $market_code_ou;?>" data-gid="' + gid + '" data-gkey="'+gkey+'" data-rate="' + home_rate + '" data-homeName="' + home_team_name + '" data-awayName="' + away_team_name + '"  data-awayName="' + away_team_name + '" data-option="' + base_rate + '">';
                                bet_three += '                        <span class="live-left">' + home_team_name + '</span>';
                                bet_three += '                        <span class="live-right"><span><img src="/img/pop_live_icon3.png"></span> <span  id="home_over_rate_' + gid + '">' + home_rate + '</span>';
                                bet_three += '                    </div>';
                                bet_three += '                </td>';
                                bet_three += '                <td class="three-bet-btn">';
                                bet_three += '                    <div class="bl_btn bl_text_btn long">';
                                bet_three += '                        <span class="live-left">기준</span>';
                                bet_three += '                        <span class="live-right" id="overunder_rate_' + gid + '">' + base_rate + '</span>';
                                bet_three += '                    </div>';
                                bet_three += '                </td>';
                                bet_three += '                <td class="three-bet-btn">';
                                bet_three += '                    <div class="bl_btn bl_text_btn long betting-btn-live under-' + gid + ' live-bet under" data-bet="Under" data-code="<?php echo $market_code_ou;?>" data-gid="' + gid + '" data-gkey="'+gkey+'" data-rate="' + home_rate + '" data-homeName="' + home_team_name + '" data-awayName="' + away_team_name + '"  data-awayName="' + away_team_name + '" data-option="' + base_rate + '" style="margin-right:3px;">';
                                bet_three += '                        <span class="live-left">' + away_team_name + '</span>';
                                bet_three += '                        <span class="live-right"><span><img src="/img/pop_live_icon4.png"></span><span id="away_under_rate_' + gid + '">' + away_rate + '</span>';
                                bet_three += '                    </div>';
                                bet_three += '                </td>';
                                bet_three += '            </tr>';
                                bet_three += '        </table>';
                                bet_three += '    </td>';
                                bet_three += '</tr>';
                                $('.live-list-box.ou').empty().append(bet_three);
                            } else {
                                console.log('언오버 = '+data[0]['gid']+' [ '+home_rate+' : '+away_rate+']'+base_rate)
                                $('#home_over_rate_' + data[0]['gid']).text(home_rate);

                                $('.cart-rate-Over-'+gid ).text(home_rate);
                                $('#away_under_rate_' + data[0]['gid']).text(away_rate);
                                $('.cart-rate-Under-'+gid).text(away_rate);
                                $('#overunder_rate_' + data[0]['gid']).text(base_rate);

                                if(cur_home_rate != home_rate) {
                                    change_text_color('text-red-blink', $('#home_over_rate_' + data[0]['gid']));
                                }
                                if(cur_away_rate != away_rate) {
                                    change_text_color('text-blue-blink', $('#away_under_rate_' + data[0]['gid']));
                                }
                            }
                            $('.live-list-box.ou').show();
                        } else {
                            $('#markets' + market_code_ou + '_' + data[0]['gid']).hide();
                            $('.live-list-box.ou').hide();
                        }
                    } else {
                        $('#markets' + market_code_ou + '_' + gid).hide();
                        $('.live-list-box.ou').hide();
                    }
                } else {
                    $('#markets' + market_code_ou + '_' + gid).empty();
                    $('.live-list-box.ou').hide();
                }
            }
        }
    });
}
