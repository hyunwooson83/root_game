
String.prototype.trim = function()
{
 return this.replace(/(^\s*)|(\s*$)/gi, "");
}

function CheckCart(v) {
	  var urls = '/action/ajax_cart_action.php';
	  var parm = '&action=Loading&v='+v;
	  new ajax.xhr.Request( urls , parm, GameCartCallback  , "GET" );
	}

function is_numeric( v, o ) {
  var chk = v.replace(/[^.0-9]/g,'');
  if( v != chk ) { window.alert('숫자 또는 . 만 입력해주세요.'); o.value = chk; return false; }
}

function is_numericbar( v, o ) {
  var chk = v.replace(/[^+-.0-9]/g,'');
  if( v != chk ) { window.alert('숫자 또는 . - 만 입력해주세요.'); o.value = chk; return false; }
}

function is_onlynumeric( v, o ) {
  var chk = v.replace(/[^0-9]/g,'');
  if( v != chk ) { window.alert('숫자만 입력해주세요.'); o.value = chk; return false; }
}

function is_banknumeric( v, o ) {
  var chk = v.replace(/[^-0-9]/g,'');
  if( v != chk ) { window.alert('숫자 또는 - 만 입력해주세요.'); o.value = chk; return false; }
}

function checkPointorMoney(v) {
  var pt = /[0-9]+0{4}/;
  return pt.test(v);

}

function checkEmail(v) {
  var pt = /[a-zA-Z\d\-\.]+@([a-zA-Z\d\-]+(\.[a-zA-Z\d\-]{2,4})+)/;
  return pt.test(v);
}

function checkIDPass(v) {
  var chk = v.replace(/[^a-zA-Z0-9]/g,'');
  var pt = /[a-zA-Z0-9]{1,10}/;
  if( v == chk && pt.test(v) ) return true;
  else return false;
}

function LogIn() {
	
  var f = document.HiddenActionForm;

  var login_id        = document.getElementById( "login_id" );
  var login_pass      = document.getElementById( "login_pass" );

  if ( login_id.value.trim() == "" || !checkIDPass(login_id.value.trim()) ) {
  	swal('','회원아이디를 1~10자리로 입력해주세요. (영문대,소문자 및 숫자만 허용)','warning');
    //alert("'회원아이디'를 1~10자리로 입력해주세요.(영문대,소문자 및 숫자만 허용)");
    login_id.focus();
  
  } else {
    f.HAF_Value_0.value = "MemberLogin";
    f.HAF_Value_1.value = login_id.value.trim();
    f.HAF_Value_2.value = login_pass.value.trim();

    f.method = "POST";
    f.action = "/m/action/member_action.php";
    f.submit();
  };
}

function LogOut() {
  var f = document.HiddenActionForm;
  f.HAF_Value_0.value = "MemberLogout";
  f.method = "POST";
  f.action = "/m/action/member_action.php";
  f.submit();
}

function ChargeRequestCancel( k ) {
  if ( confirm("충전 요청을 취소하시겠습니까?") ) {
    var f = document.HiddenActionForm;
    f.HAF_Value_0.value = "RequestMoneyChargeCancel";
    f.HAF_Value_1.value = k;
    f.method = "POST";
    f.action = "/m/action/money_action.php";
    f.submit();
  };
}

function ChargeRequestDelete( k ) {
	  if ( confirm("충전 요청을 삭제하시겠습니까?") ) {
		    var f = document.HiddenActionForm;
		    f.HAF_Value_0.value = "RequestMoneyChargeDelete";
		    f.HAF_Value_1.value = k;
		    f.method = "POST";
		    f.action = "/m/action/money_action.php";
		    f.submit();
		  };
}

function ExchangeRequestDelete( k ) {
	  if ( confirm("환전 요청을 삭제하시겠습니까?") ) {
		    var f = document.HiddenActionForm;
		    f.HAF_Value_0.value = "RequestMoneyExchangeDelete";
		    f.HAF_Value_1.value = k;
		    f.method = "POST";
		    f.action = "/m/action/money_action.php";
		    f.submit();
		  };
}

function BetHistoryDelete( k ) {
	  if ( confirm("베팅 내역을 삭제하시겠습니까?") ) {
		    var f = document.HiddenActionForm;
		    f.HAF_Value_0.value = "BetHistoryDelete";
		    f.HAF_Value_1.value = k;
		    f.method = "POST";
		    f.action = "/m/action/money_action.php";
		    f.submit();
		  };
}

function BoardReplyWrite( b_key ) {
  var f = document.HiddenActionForm;

  var comment = document.getElementById( "comment" );

  if ( comment.value.trim() == "" ) {
    alert("내용을 입력해 주세요.");
    comment.focus();
  } else if ( confirm("등록하시겠습니까?") ) {
    f.HAF_Value_0.value = "BoardReplyWrite";
    f.HAF_Value_1.value = b_key;
    f.HAF_Value_2.value = comment.value.trim();

    f.method = "POST";
    f.action = "/m/action/board_action.php";
    f.submit();
  };
}

function BoardReplyDelete( b_key, br_key ,auth ) {
  var f = document.HiddenActionForm;
  if ( confirm("삭제하시겠습니까?") ) {
    f.HAF_Value_0.value = "BoardReplyDelete";
    f.HAF_Value_1.value = b_key;
    f.HAF_Value_2.value = br_key;
    f.HAF_Value_3.value = auth;

    f.method = "POST";
    f.action = "/m/action/board_action.php";
    f.submit();
  };
}

function BoardDelete( b_key, vars, auth ) {
		
  var f = document.HiddenActionForm;
  if ( confirm("삭제하시겠습니까?") ) {
    f.HAF_Value_0.value = "BoardDelete";
    f.HAF_Value_1.value = b_key;
    f.HAF_Value_2.value = vars;
    f.HAF_Value_3.value = auth;

    f.method = "POST";
    f.action = "/m/action/board_action.php";
    f.submit();
  };
}
function BoardDelete2( b_key, vars, auth ) {
		
  var f = document.HiddenActionForm;
  if ( confirm("삭제하시겠습니까?") ) {
    f.HAF_Value_0.value = "BoardDelete2";
    f.HAF_Value_1.value = b_key;
    f.HAF_Value_2.value = vars;
    f.HAF_Value_3.value = auth;

    f.method = "POST";
    f.action = "/m/action/board_action.php";
    f.submit();
  };
}
function BoardDelete1( b_key, vars, auth ) {	
  var f = document.HiddenActionForm;
  if ( confirm("삭제하시겠습니까?") ) {
    f.HAF_Value_0.value = "BoardDelete1";
    f.HAF_Value_1.value = b_key;
    f.HAF_Value_2.value = vars;
    f.HAF_Value_3.value = auth;

    f.method = "POST";
    f.action = "/m/action/board_action.php";
    f.submit();
  };
}

function BoardGameResultWrite( bg_key ) {
  var f = document.HiddenActionForm;
  if ( confirm("베팅정보를 등록하시겠습니까?") ) {
    var arr = new Array();
	  var cnt = 0;
	  $('.bet_box').each(function() {
			if($(this).is(':checked')==true){
				arr[cnt] = $(this).val();
				cnt++;
			}
	  });
	 
	if(cnt>0){ 
		f.HAF_Value_0.value = "BoardGameResultWrite";
		f.HAF_Value_1.value = arr;
		//f.HAF_Value_2.value = arr;
	
		f.method = "POST";
		f.action = "/m/action/board_action.php";
		f.submit();
	} else {
		alert('배팅내역을 선택해주세요.');
	}
  };
};


<!-- 배팅 내역 삭제 -->
function BettingDel( bg_key ) {
  var f = document.HiddenActionForm;
  if ( confirm("배팅 내역을 삭제하시겠습니까?\n한번 삭제한 내역은 복구가 불가능합니다.") ) {      
    f.HAF_Value_0.value = "BettingHistoryDel";
    f.HAF_Value_1.value = bg_key;
    //f.HAF_Value_2.value = arr;

    f.method = "POST";
    f.action = "/m/action/board_action.php";
    f.submit();
  };
};


<!-- 배팅 내역 선택 삭제 -->
function BettingAllDel( bg_key ) {
	var f = document.HiddenActionForm;
	var arr = new Array();
	var cnt = 0;
	var bet = 0;
	if(confirm('선택 배팅내역을 삭제하시겠습니까?')){
	
		
		
		$('.bet_box').each(function() {
			if($(this).is(":checked")==true){
				arr[cnt] = $(this).val(); 
				cnt++;
			}
		});
		
		if(!cnt){
			alert('삭제할 배팅 내역을 선택해주세요.');
		} else {
			f.HAF_Value_0.value = "BettingHistoryAllDel";
			f.HAF_Value_1.value = arr;
			//f.HAF_Value_2.value = arr;
			
			f.method = "POST";
			f.action = "/m/action/board_action.php";
			f.submit();
		}
	
	} 
};


<!-- 관리자 내역 선택 삭제 -->
function AdminBettingAllDel( bg_key ) {
	var f = document.HiddenActionForm;
	var arr = new Array();
	var cnt = 0;
	var bet = 0;
	if(confirm('선택 배팅내역을 삭제하시겠습니까?')){
	
		
		
		$('.bet_box').each(function() {
			if($(this).is(":checked")==true){
				arr[cnt] = $(this).val(); 
				cnt++;
			}
		});
		
		if(!cnt){
			alert('삭제할 배팅 내역을 선택해주세요.');
		} else {
			f.HAF_Value_0.value = "AdminBettingHistoryAllDel";
			f.HAF_Value_1.value = arr;
			//f.HAF_Value_2.value = arr;
			
			f.method = "POST";
			f.action = "/m/action/board_action.php";
			f.submit();
		}
	
	} 
};

function comma(n) { 
    var reg = /(^[+-]?\d+)(\d{3})/; 
    n += ''; 
    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2'); 
    return n; 
}
function removeComma(str)
{
	n = parseInt(str.replace(/,/g,""));
	return n;
}
function CalcCart( price ) {
	var cur_price = parseInt(remove_comma($('#BettingMoney').val()));
	if ( price != "" ) {
		var BettingQuota = $("#BettingQuota").text();
		var BettingQuotaMoney = $("#BettingQuotaMoney");
		var BettingPrice = parseInt(parseFloat(BettingQuota) * price);

		if(cur_price > config_bet_bound_max){
		  swal('','최대배팅 가능 금액은 '+comma(config_bet_bound_max)+' 입니다.','warning');
		  $('#BettingMoney').val('0');
		  return false;
		}
		if(BettingPrice > config_bet_reward_max){
		  swal('','최대적중예상 가능 금액은 '+comma(config_bet_reward_max)+' 입니다.','warning');
		  $('#BettingMoney').val('0');
		  return false;
		}
		BettingQuotaMoney.text(comma(BettingPrice));
	}
}
$('.cart-price1').on('click',function(){
	var price = parseInt($(this).data('money'));
	var BettingMoney = parseInt(remove_comma($('#BettingMoney').val()));
	var type = $(this).data('text');
	var max_bet_money = config_bet_bound_max;
	var max_hit_money = config_bet_reward_max;
	var my_money = 0;
	if(price == 0){
		$('#BettingMoney').val('0');
		CalcCartLive(10000);
	} else {
		//my_money = parseInt(price+BettingMoney);
		if(type == 'max'){//max버튼을 눌렀을때
			if($('.betting_cart > li').length == 0){
				swal('','배팅하실 경기를 먼저 선택해주세요.','warning');
				return false;
			} else {
				var bet_rate = parseFloat($('#selected_rate').text());
				var bet_hit_max = max_hit_money*100;
				var bet_max = 1;

				bet_rate = bet_rate*100;
				bet_max = parseInt((bet_hit_max / bet_rate)).toFixed(0);
				if(bet_max > max_bet_money){
					bet_max = max_bet_money;
				}
				price = bet_max;
			}
			$('#BettingMoney').val(comma(price));
			CalcCartLive(price);
		} else if(type == 'half'){
			if($('.betting_cart > li').length == 0){
				swal('','배팅하실 경기를 먼저 선택해주세요.','warning');
				return false;
			} else {
				var bet_rate = parseFloat($('#selected_rate').text());
				var bet_hit_max = (max_hit_money*100)/2;
				var bet_max = 1;
				bet_rate = bet_rate*100;
				bet_max = bet_hit_max / bet_rate;
				if(bet_max > max_bet_money){
					bet_max = max_bet_money;
				}
				price = bet_max.toFixed(0);
			}
			$('#BettingMoney').val(comma(price));
			CalcCartLive(price);
		} else {
			my_money = BettingMoney + price;
			$('#BettingMoney').val(comma(my_money));
			CalcCartLive(my_money);
		}


	}

});
function CalcCartLive( price ) {

	var cur_price = parseInt(remove_comma($('#BettingMoney').val()));
	if ( price != "" ) {
		var BettingQuota = $("#selected_rate").text();
		var BettingQuotaMoney = $("#BettingQuotaMoney");
		var BettingPrice = parseInt(parseFloat(BettingQuota) * price);
		console.log(BettingPrice);

		if(cur_price > config_bet_bound_max){
			swal('','최대배팅 가능 금액은 '+comma(config_bet_bound_max)+' 입니다.','warning');
			$('#BettingMoney').val('10000');
			return false;
		}
		if(BettingPrice > config_bet_reward_max){
			swal('','최대적중예상 가능 금액은 '+comma(config_bet_reward_max)+' 입니다.[calc]','warning');
			$('#BettingMoney').val('10000');
			return false;
		}
		BettingQuotaMoney.text(comma(BettingPrice));
	};
}

function CalcCart1() {
    var BettingQuota = document.getElementById( "BettingQuota" );
	var BettingMoney = document.getElementById( "BettingMoney" );
    var BettingQuotaMoney = document.getElementById( "BettingQuotaMoney" );
    var BettingPrice = parseInt( 3000000 / BettingQuota.innerHTML);
    //if ( BettingPrice > 3000000 ) BettingPrice = 3000000;
    BettingQuotaMoney.innerHTML = comma(parseInt( BettingPrice * BettingQuota.innerHTML));
    BettingMoney.value = BettingPrice;
}

function GameCartDelete ( cgl_key ) {
	$.ajax({
		type :'POST',
		url : '/action/ajax.php',
		data : 'mode=cartCnt1',
		success : function(res)
		{			
			$('#cartCnt').val(res);
		}
	});	
  if ( cgl_key != "" ) {
    var BettingMoney = document.getElementById( "BettingMoney" ).value;
    var urls = '/action/ajax_cart_action.php';
    var parm = '&action=DeleteCart&cgl_key=' + cgl_key + '&g_price=' + BettingMoney;
    new ajax.xhr.Request( urls , parm, GameCartCallback  , "GET" );
  };
}

function GameCartAllDelete () {
	$('#cartCnt').val(1);
	$('#crossbet').val('');
  var BettingMoney = document.getElementById( "BettingMoney" ).value;
  var urls = '/action/ajax_cart_action.php';
  var parm = '&action=AllDeleteCart&g_price=' + BettingMoney;
  new ajax.xhr.Request( urls , parm, GameCartCallback  , "GET" );
}


<!-- 게임 선택시 실행됨 -->
function GameCartInsertLadder ( g_list ) {
	//alert(g_list);
	var cnt = $('#cartCnt').val();

	var bet_game = g_list.split("_");
	var g_key = bet_game[0];
	var g_result = bet_game[3];
	var gl_key = bet_game[1];
	var g_time = bet_game[7];


	$.ajax({
		type :'POST',
		url : '/action/ajax.php',
		data : 'mode=ladderChk&glkey='+gl_key+'&gtime='+g_time,
		success : function(res)
		{
			//$('#server').html(res);
			if(res=='N'){
				alert('같은 회차의 홀/짝 3줄/4줄과 좌/우는 배팅하실 수 없습니다.');
				return;
			} else {
				$('#noJoinBet').val(bet_game[1]);
				if(cnt<10){
					if ( g_key != "" ) {
						var BettingMoney = document.getElementById( "BettingMoney" ).value;
						var urls = '/action/ajax_cart_action.php';
						var parm = '&action=InsertCart&g_key=' + g_key + '&g_result=' + g_result +'&g_price=' + BettingMoney;
						new ajax.xhr.Request( urls , parm, GameCartCallback  , "GET" );
					};
				} else {
					alert("최대 배팅가능한 갯수는 10개입니다.\n오른쪽 배팅카트에서 '삭제'를 눌러주세요!.");
				}
			}
		}
	});


}


<!-- 게임 선택시 실행됨 -->
function GameCartInsert ( g_key, g_result ) {

	var cnt = $('#cartCnt').val();
	
	if(cnt<10){	
	
		if ( g_key != "" ) {	
			
			var BettingMoney = document.getElementById( "BettingMoney" ).value;
			var urls = '/m/action/ajax_cart_action.php';
			var parm = '&action=InsertCart&g_key=' + g_key + '&g_result=' + g_result +'&g_price=' + BettingMoney;
			new ajax.xhr.Request( urls , parm, GameCartCallback  , "GET" );
		};
	} else {
		alert("최대 배팅가능한 갯수는 10개입니다.\n오른쪽 배팅카트에서 '삭제'를 눌러주세요!.");
	}
}



<!-- 게임 선택시 실행됨 -->
function GameCartInsertDal ( g_key, g_result, glkey ) {
	
	var cnt = $('#cartCnt').val();
	var crossbet = $('#cbet').val();
	if(glkey != crossbet && crossbet != ''){
		alert('달팽이는 달팽이 스페셜과 조합배팅이 불가능합니다.');
		return;
	}
	
	$('#cbet').val(glkey);
	if(cnt<10){	
	
		if ( g_key != "" ) {	
			
			var BettingMoney = document.getElementById( "BettingMoney" ).value;
			var urls = '/action/ajax_cart_action.php';
			var parm = '&action=InsertCart&g_key=' + g_key + '&g_result=' + g_result +'&g_price=' + BettingMoney;
			new ajax.xhr.Request( urls , parm, GameCartCallback  , "GET" );
		};
	} else {
		alert("최대 배팅가능한 갯수는 10개입니다.\n오른쪽 배팅카트에서 '삭제'를 눌러주세요!.");
	}
}



<!-- 게임 선택시 실행됨 -->
function GameCartInsertPball ( g_key, g_result, glkey ) {

	var cnt = $('#cartCnt').val();
	var crossbet = $('#cbet').val();
	if(glkey != crossbet && crossbet != ''){
		alert('파워볼[홀/짝]은 파워볼[소/중/대]과 조합배팅이 불가능합니다.');
		return;
	}
	
	$('#cbet').val(glkey);
	if(cnt<10){	
	
		if ( g_key != "" ) {	
			
			var BettingMoney = document.getElementById( "BettingMoney" ).value;
			
			var urls = '/action/ajax_cart_action.php';
			var parm = '&action=InsertCart&g_key=' + g_key + '&g_result=' + g_result +'&g_price=' + BettingMoney;
			new ajax.xhr.Request( urls , parm, GameCartCallback  , "GET" );
		};
	} else {
		alert("최대 배팅가능한 갯수는 10개입니다.\n오른쪽 배팅카트에서 '삭제'를 눌러주세요!.");
	}
}



function GameCartLoading() {
	var urls = '/action/ajax_cart_action.php';
	var parm = '&action=Loading&v=1';
	new ajax.xhr.Request( urls , parm, GameCartCallback  , "GET" );	
};

function GameCartCallback (req) {
	
    if (req.readyState == 4) {
      if (req.status == 200) {
		  //console.log(req.responseText);
		 $.ajax({
		  type : 'POST',
		  url : '../game/ajax.php',
		  data : 'mode=ladder',
		  success : function(res)
		  {
			
			//if(res<2){
				 
				GameCartRefresh( req.responseText );	
				GameCartAllListBlank();			
				GameCartCheckedListLoading();			
			/*	CartCountChk();
			} else {
				alert('사다리는 사다리스페셜과 배팅이 불가합니다.');
				$.ajax({
				 type : 'POST',
				 url : '../game/ajax.php',
				 data : 'mode=ladderDel',
				 success : function(res)
				 {
					if(res!='N'){
						selectedColorChange(res,1);
					}
				 }
				});
				CartCountChk();				
				
			}*/
		  }
	  });
        
      } else {
        alert("에러 발생: "+req.status);
      }
    }
}

function CartCountChk()
{
	$.ajax({
		type :'POST',
		url : '/action/ajax.php',
		data : 'mode=cartCnt',
		success : function(res)
		{			
			$('#cartCnt').val(res);
		}
	});	
}

function GameCartRefresh( _html ) {
		

  $('#floatdiv').html(_html);
}

<!-- 클릭시 실행된다.. 경기 리스트 항목-->
function GameCartCheckedList( g_key , g_result) {	
	
  if ( g_key && g_result) {
    var g_win 			= $("#"+g_key + "_Win" );
    var g_draw 			= $("#"+g_key + "_Draw" );
    var g_lose 			= $("#"+g_key + "_Lose" );
    var g_handiwin 		= $("#"+g_key + "_HandiWin" );
    var g_handilose 	= $("#"+g_key + "_HandiLose" );
    var g_under 		= $("#"+g_key + "_Under" );
    var g_over 			= $("#"+g_key + "_Over" );

    
	
	//alert();
    //var g_result =  document.getElementById( g_key + "_" + g_result );
	var g = g_key+'_'+g_result;	
    if ( g_result){ $('#'+g_key+'_'+g_result).addClass('bet_game'); }
  };
};


function GameCartCheckedListLoading() {

  var urls = '/action/ajax_cart_action.php';
  var parm = '&action=CheckedList';
  new ajax.xhr.Request( urls , parm, GameCartCheckedListLoadingback  , "GET" );
}

function GameCartCheckedListLoadingback (req) {	
    if (req.readyState == 4) {
      if (req.status == 200) {	
	  
        eval ( req.responseText );		
      } else {
        alert("에러 발생: "+req.status);
      }
    }
}

function GameCartAllListBlank() {
	$('*').removeClass('bet_game');
}


function MinigameCartBuy(bet_money, betexpect, num, type, choice_rate, gkey, glkey, datetime, all_rate, bet_selected) {


	/* type : ladder, power, dal, ...*/
	var f = document.HiddenActionForm;

	if(bet_money == 0 || bet_money == 'undifined'){
		swal('','배팅금액을 선택해주세요.','warning');
		return;
	}

	if ( bet_money.value < 5000 ) {
		alert("배팅금액은 최소 10,000원부터 입력해주세요. ex) 10,000 ~ 1,000,000");
		return;
	}

	f.HAF_Value_0.value = "BuyCart";
	f.HAF_Value_1.value = bet_money;
	f.HAF_Value_2.value = betexpect;
	f.HAF_Value_3.value = num;
	f.HAF_Value_4.value = type;
	f.HAF_Value_5.value = choice_rate;
	f.HAF_Value_6.value = gkey;
	f.HAF_Value_7.value = glkey;
	f.HAF_Value_8.value = datetime;
	f.HAF_Value_9.value = all_rate;
	f.HAF_Value_10.value = bet_selected;
	//console.log('bs->'+bet_selected)
	f.method = "POST";
	f.action = "../proc/";
	f.submit();



}


function GameCartBuy( cnt ) {
	var BettingMoney = parseInt(removeComma($("#BettingMoney").val()));
	var hitmoney = $('#BettingQuotaMoney').text();

	var f = document.HiddenActionForm;
	if ( cnt < 1 ) {
		swal("","구매할 게임이 없습니다.","warning");
		return;
	}
	if(!BettingMoney){
		swal("","배팅금액을 적어주세요.","warning");
		$('#BettingMoney').focus();
		return;
	}
	if ( BettingMoney < 10000 ) {
		swal("","배팅금액은 최소 10,000원부터 입력해주세요. ex) 5,000 ~ 1,000,000","warning");
		$('#BettingMoney').focus();
		return;
	}
	/*if(cnt == 1){
		swal("","회원님은 단폴더 배팅을 하실 수 없습니다.","warning");
		return;
	}
	if(cnt == 2){
		swal("","회원님은 두폴더 배팅을 하실 수 없습니다.","warning");
		return;
	}*/
	swal({
		title : "게임구매",
		text: "선택한 게임을 구매하시겠습니까?",
		type: "success",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "확인",
		cancelButtonText: "취소"
	}).then(function(isConfirm) {
		if (isConfirm) {
			make_click_log('게임구매 확인버튼 클릭');
			$.ajax({
				type : 'post',
				url : '/m/action/ajax_buygame.php',
				dataType : 'json',
				data : {'BettingMoney':BettingMoney,'gtg':$('#gtg').val()},
				success : function(data){
					if(data.flag == true){
						$('.sub-game-list').each(function(){
							$(this).removeClass('d-show').addClass('d-hide');
						})

						/*loadingCart();
						$('#member_cur_money').val(comma(data.money));*/
						swal('','배팅이 정상적으로 완료되었습니다.','success');
						setTimeout(function(){location.href='/m/game/sports/cross/';},2000);
					} else {
						swal('',data.error,'warning');
					}
				}, error: function(jqXHR, exception) {
					if (jqXHR.status === 0) {
						alert('Not connect.\n Verify Network.');
					}
					else if (jqXHR.status == 400) {
						alert('Server understood the request, but request content was invalid. [400]');
					}
					else if (jqXHR.status == 401) {
						alert('Unauthorized access. [401]');
					}
					else if (jqXHR.status == 403) {
						alert('Forbidden resource can not be accessed. [403]');
					}
					else if (jqXHR.status == 404) {
						alert('Requested page not found. [404]');
					}
					else if (jqXHR.status == 500) {
						alert('Internal server error. [500]');
					}
					else if (jqXHR.status == 503) {
						alert('Service unavailable. [503]');
					}
					else if (exception === 'parsererror') {
						alert('Requested JSON parse failed. [Failed]');
					}
					else if (exception === 'timeout') {
						alert('Time out error. [Timeout]');
					}
					else if (exception === 'abort') {
						alert('Ajax request aborted. [Aborted]');
					}
					else {
						alert('Uncaught Error.n' + jqXHR.responseText);
					}
				}



			});
		}
	});
}
//라이브 게임 구매하기
function GameCartBuyLive( cnt ) {
	var gid = $('#selected_gid').text();
	var choice = $('#selected_type').text();
	var gkey = $('#selected_gkey').text();
	var code = $('#selected_code').text();
	var item = item_name;
	var bettingMoney = parseInt(remove_comma($('#BettingMoney').val()));

	if(gid!='' && choice != '' && code != '' && bettingMoney > 0 && mkey != '' && gkey != '' ) {
		swal({
			title: "라이브배팅",
			text: "라이브게임에 배팅하시겠습니까? 배당률은 실시간으로 변경되면 배팅하신 동안에 [변경된 배당률]로 배팅이 됩니다. 구매후 배팅내역을 확인해주세요.",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "확인",
			cancelButtonText: "취소"
		}).then(function (isConfirm) {
			if (isConfirm) {
				$.ajax({
					type: 'get',
					url: '../proc/buygamelive.php',
					dataType: 'json',
					data: {'mode': 'buyGameLive',choice:choice, gid:gid, mcode:code, type:item, BettingPrice:bettingMoney,mkey:mkey, gkey:gkey},
					beforeSend:function(){
						$('.loading').show();
					},
					success: function (data) {
						if(data.flag == true){
							$('.loading').hide();
							swal('','정상적으로 배팅이 완료되었습니다.','success');
							loadingCart();
							$('#BettingQuotaMoney').val('0');
							$('#BettingMoney').val('10,000');
							$('.betting_cart').empty().append('<div class="empty">베팅카트가 비었습니다.</div>');

							//setTimeout(function(){ location.reload();},3000);
						} else {
							swal('',data.error,'warning');
						}
					}, error: function (request, status, error) {
						console.log(request);
						console.log(status);
						console.log(error);
					}
				});
			}
		});
	} else {
		swal('','배팅에 필요한 정보가 넘어오지 않았습니다. 잠시 후 다시 구매해주세요.','warning');
	}


}

$(document).on('click','.betting-btn-live',function() {
	var gid = $(this).data('gid');
	var gkey = $(this).data('gkey');
	var home_name = $(this).data('homename');
	var away_name = $(this).data('awayname');
	var rate = 1;
	var type = $(this).data('bet');
	var code = $(this).data('code');

	$('.betting-btn-live').each(function () {
		$(this).removeClass('active');
	});


	if (code == $('#selected_code').text() && type == $('#selected_type').text() && gkey == $('#selected_gkey').text()) {

		$('#BettingQuotaMoney').val('0');
		$('#BettingMoney').val('10,000');
		$('.betting_cart').empty().append('<div class="empty">베팅카트가 비었습니다.</div>');
		$(this).removeClass('active');
		return;
	} else {
		if ($(this).hasClass('active') == false) {
			$(this).addClass('active')
			console.log('처음')
		} else {
			$(this).removeClass('active')
			console.log('다시')
		}
	}
	if(type == 'Win'){
		rate = parseFloat($('#home_rate_'+gid).text());
	} else if(type == 'Draw'){
		rate = parseFloat($('#draw_rate_'+gid).text());
	} else if(type == 'Lose'){
		rate = parseFloat($('#away_rate_'+gid).text());
	} else if(type == 'HandiWin'){
		rate = parseFloat($('#home_handicap_rate_'+gid).text());
	} else if(type == 'HandiLose'){
		rate = parseFloat($('#away_handicap_rate_'+gid).text());
	} else if(type == 'Over'){
		rate = parseFloat($('#home_over_rate_'+gid).text());
	} else if(type == 'Under'){
		rate = parseFloat($('#away_under_rate_'+gid).text());
	}
	var select_type = $(this).data('bet');
	var total_price = (rate*1000)*parseInt($('#BettingMoney').val());

	if(select_type == 'Win'){
		select_type_kor = '승';
	} else if(select_type == 'Draw'){
		select_type_kor = '무';
	} else if(select_type == 'Lose'){
		select_type_kor = '패';
	} else if(select_type == 'Over'){
		select_type_kor = '오버';
	} else if(select_type == 'Under'){
		select_type_kor = '언더';
	} else if(select_type == 'HandiWin'){
		select_type_kor = '핸승';
	} else if(select_type == 'HandiLose'){
		select_type_kor = '핸패';
	}
	cart = '';
	cart += '<li style="border:#484848 solid 1px !important;">';
	cart += '    <span title="삭제" onclick="cartDel('+gid+');" style="cursor: pointer;"><img src="/img/icon_betting_cart_close.png"  /></span>';
	cart += '    <h1 class="first select">[홈팀] ' + home_name + '</h1>';
	cart += '    <h1>[원정] ' + away_name + '</h1>';
	cart += '    <h2>';
	cart += '        <em>' + select_type_kor + ' <font>@&nbsp;</font><span class="cart-rate-'+select_type+'-'+gid+'" id="selected_rate">' + rate + '</span>';
	cart += '           <span id="selected_gid" style="display: none;">'+gid+'</span>';
	cart += '           <span id="selected_type" style="display: none;">'+select_type+'</span>';
	cart += '           <span id="selected_gkey" style="display: none;">'+gkey+'</span>';
	cart += '           <span id="selected_code" style="display: none;">'+code+'</span>';
	cart += '        </em>';
	cart += '    </h2>';
	cart += '</li>';
	//$('.'+data.cart[i]['select']).addClass('active');
	$('.betting_cart').empty().append(cart);
	$('#BettingQuota').text(rate);
	$('#BettingQuotaMoney').text(comma(parseInt(total_price)));
});
function loadingCart(){
	/*페이지 로딩시 카트 만들기*/
	$.ajax({
		type : 'get',
		url : '/m/include/ajax.php',
		dataType : 'json',
		data : {'mode':'makeCart','price':remove_comma($('#BettingMoney').val())},
		success : function(data){
			var cart = '';
			var cnt = 0;

			if(data.flag == true){
				$('dd.betting-btn, td.betting-btn').removeClass('on');
				if(data.total_cnt>0) {
					$('#cartCnt').val(data.total_cnt);
					for (var i = 0; i < data.cart.length; i++) {
						if(i==0){
							cart += '<li>';							
							cart += '<span title="delete" onclick="cartDelAll();" style="color:#fff;">카트비우기</span>';
							cart += '</li>';
						}
						cart += '<li>';
						cart += '<div class="left">' + data.cart[i]['home_team'] + '<BR /><span>' + data.cart[i]['away_team'] + '</span></div>';
						cart += '<div class="right">'+data.cart[i]['select_type']+'<font class="lose">'+ data.cart[i]['rate'] +'</font></div>';
						cart += '<label title="delete" onclick="cartDel('+data.cart[i]['cglkey']+');"><img src="/mobile/img/icon_menu_close.png"></label>';
						cart += '</li>';						
						$('.'+data.cart[i]['select']).addClass('on');
						cnt++;
					}
					$('.cart_pick').empty().append(cart);
					$('#BettingQuota, #select_game_rate').text(data.total);
					$('#BettingQuotaMoney').text(comma(parseInt(data.total_price)));
				} else {
					$('.cart_pick').empty().append('<div class="empty">베팅카트가 비었습니다.</div>');
					$('#BettingQuota').text('1.00');
					$('#BettingQuotaMoney').text($('#BettingMoney').val());
				}
			} else {
				swal('','카트가 정상적으로 로딩되지 않았습니다.','warning');
			}
			$('#select_game_cnt').text(cnt);
		}
	});
}
/*function GameCartBuyCancel( cnt1, cnt2 ) {
  var BettingMoney = document.getElementById( "BettingMoney" );
  var f = document.HiddenActionForm;
  if ( confirm("배팅취소는 하루에 한번만 5분이내에 할 수 있습니다.\n배팅을 취소하시겠습니까?") ) {
    f.HAF_Value_0.value = "BuyCartCancel";
    f.HAF_Value_1.value = cnt1;
	f.HAF_Value_2.value = cnt2;

    f.method = "POST";
    f.action = "/m/action/ajax_cart_action.php";
    f.submit();
  }
}*/

function GameCartBuyCancel( cnt1, cnt2, t ) {
	
  var BettingMoney = document.getElementById( "BettingMoney" );
  var f = document.HiddenActionForm;
  var d  = new Date();
  var s = 
  leadingZeros(d.getFullYear(), 4) + '-' +
  leadingZeros(d.getMonth() + 1, 2) + '-' +
  leadingZeros(d.getDate(), 2) + ' ' +
  
  leadingZeros(d.getHours(), 2) + ':' +
  leadingZeros(d.getMinutes(), 2);
 
 
  if(t<=s){
	  alert('배팅취소 가능시간이 아닙니다. 배팅취소는 배팅후 5분이내에만 가능합니다.');
	  location.reload();
  } else {
	  if ( confirm("배팅을 취소하시겠습니까?") ) {
		f.HAF_Value_0.value = "BuyCartCancel";
		f.HAF_Value_1.value = cnt1;
		f.HAF_Value_2.value = cnt2;
	
		f.method = "POST";
		f.action = "/m/action/ajax_cart_action.php";
		f.submit();
	  }
  }
}


function leadingZeros(n, digits) {
 // 1 -> 01 과 같이 변경하기
 var zero = '';
 n = n.toString();
 
 if (n.length < digits) {
  for (i = 0; i < digits - n.length; i++)
  zero += '0';
 }
 return zero + n;
}

function SearchGame( f, sch_gtype1, sch_gtype2 ) {
  var sch_league  = document.getElementById( "sch_league" ).value.trim();
  var sch_sort    = document.getElementById( "sort" ).value.trim();
  var search_type   = document.getElementById( "search_type" ).value.trim();
  var search_text   = document.getElementById( "search_text" ).value.trim();

  location.href = "./" + f + ".php?sch_league=" + sch_league + "&sort=" + sch_sort + "&sch_gtype1=" + sch_gtype1 + "&sch_gtype2=" + sch_gtype2+"&search_text="+search_text+"&search_type="+search_type;
}

function flash(src,width,height,tr){	
	object = '';
	object += '<object type="application/x-shockwave-flash" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="param" width="'+width+'" height="'+height+'">';
	object += '<param name="movie" value="'+src+'">';
	if(tr=='1'){
		 object += '<param name="wmode" Value="Transparent">';
	}
	object += '<embed src="'+src+'" quality="high" ';    
	if(tr=='1'){
		object += ' wmode="transparent" ';
	}
	object += ' bgcolor="#ffffff" menu="false" width="'+width+'" height="'+height+'" swliveconnect="true" id="param" name="param" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';
	object += '</object>';
	document.write(object);
}


function IsMobile() {
	var uAgent = navigator.userAgent.toLowerCase();
	var mobile = new Array('iphone', 'ipod', 'android', 'samsung', 'lgtel', 'blackberry', 'windows ce', 'nokia', 'webos', 'opera mini', 'sonyericsson', 'opera mobi', 'iemobile', 'mobile');

	for (var i = 0; i < mobile.length; i++) {
		if (uAgent.indexOf(mobile[i]) != -1) {
			return true;
		}
	}

	return false;
}

function IsLogo() {
	if (!IsMobile()) {
		flash('/swf/main_logo.swf','260','80','1');
	} else {
		document.write("<img src='/images/main/header/main_logo.png' width='260' height='80' border='0' align='absmiddle' style='cursor:pointer;' onClick='top.location.reload();' alt='Welcome to TOBI'>");
	}
}

function IsMAin() {
	if (!IsMobile()) {
		flash('/swf/main.swf','980','343','1');
	} else {
		document.write("<img src='/images/main_img.png' width='980' height='343' border='0' align='absmiddle' style='cursor:pointer;' onClick='top.location.reload();' alt='Welcome to TOBI'>");
	}
}

function get_bank(){
	swal({
		title: "계좌번호",
		text: "계좌번호를 요청 하시겠습니까?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "확인",
		cancelButtonText: "취소"
	}).then(function(isConfirm) {
		if (isConfirm) {
			$.ajax({
				url : './proc/',
				type : 'post',
				dataType : 'json',
				data : {'HAF_Value_0' : 'requestBank'},
				success : function(res){

					if(res.flag == true){
						swal('','완료되었습니다. 쪽지를 확인해주세요.','success');
					} else {
						swal('','계좌요청시 오류가 발생했습니다. 잠시후에 다시 시도해주세요.'+res.error,'warning');
					}
					setTimeout(function(){ location.reload();},3000);
				},complete:function(data){
				},error:function(request, status, error){
					console.log(request);
					console.log(status);
					console.log(error);
				}
			});
		}
	});
}


function remove_comma(str){
	n = parseInt(str.replace(/,/g,""));
	return n;
}

function cartDelAll(){
	$.ajax({
		type : 'get',
		url : '/m/include/ajax.php',
		dataType : 'json',
		data : {'mode':'delCartAll'},
		success : function(data){
			var cart = '';
			if(data.flag == true){
				loadingCart();
			} else {
				swal('','카트가 정상적으로 삭제되지 않았습니다.','warning');
			}
		}
	});
}
$(document).ready(function(){
	
	$('.betting-btn').on('click',function(){
		$(this).blur();
		var cnt = $('#cartCnt').val();//카트에 담을 최대 갯수
		var gkey = $(this).data('gkey');
		var gresult = $(this).data('bet');
		var glist = $(this).data('glist');
		var BettingMoney = remove_comma($( "#BettingMoney" ).val());
		var gtype = $(this).data('gtype');//스페셜 일반 경기 구분
		var cur_bet_rate = $(this).data('rate');
		var obj = $(this);

		var cur_max_hit_money = parseInt(remove_comma($('#BettingQuotaMoney').text()));
		var cur_max_bet_money = parseInt(remove_comma($('#BettingMoney').val()));
		var game_cnt = $(this).data('bet_cnt');

		var total_rate = parseFloat($('#BettingQuota').text()) * parseFloat(cur_bet_rate);
		var mhm = cur_max_bet_money * total_rate;


		if(cur_bet_rate == 999){
			swal('','해당 배팅을 하실 수 없습니다.','warning');
			return false;
		}
		if (cur_max_bet_money < config_bet_bound_min) {
			swal('', '최소배팅 가능 금액은 ' + comma(config_bet_bound_min) + ' 입니다.', 'warning');
			$('#BettingMoney').val(config_bet_bound_min);
			return false;
		}

		if (cur_max_bet_money > config_bet_bound_max) {
			swal('', '최고배팅 가능 금액은 ' + comma(config_bet_bound_max) + ' 입니다.', 'warning');
			$('#BettingMoney').val('10000');
			return false;
		}

		var total_rate = parseFloat($('#BettingQuota').text()) * parseFloat(cur_bet_rate);
		var mhm = cur_max_bet_money * total_rate;


		if (mhm > config_bet_reward_max) {
			swal('', '최대적중예상 가능 금액은 ' + comma(config_bet_reward_max) + ' 입니다.[btn]', 'warning');
			$('#BettingMoney').val('10000');
			return false;
		}

		if(config_max_bet_rate < total_rate){
			swal('', '최대 배팅가능한 배당률은 ' + config_max_bet_rate + '배 입니다. 배팅하실 수 없습니다.', 'warning');
			return false;
		}

		if (same_bet_cnt < game_cnt) {
			swal('', '동일한(축) 경기에 ' + comma(game_cnt) + '이상 배팅하실 수 없습니다.', 'warning');
			$('#BettingMoney').val('10000');
			return false;
		}

		if (same_bet_max < cur_max_bet_money) {
			swal('', '동일한(축) 경기에 최고배팅 가능 금액은' + comma(same_bet_max) + '이상 배팅하실 수 없습니다.', 'warning');
			$('#BettingMoney').val('10000');
			return false;
		}

		if (same_hit_max < mhm) {
			swal('', '동일한(축) 당첨상한 금액 ' + comma(same_hit_max) + '이상 배팅하실 수 없습니다.', 'warning');
			$('#BettingMoney').val('10000');
			return false;
		}

		//보너스 배당 체크하기

		var bonus_cnt = 0;
		$('.bonus-btn').each(function(){
			if($(this).hasClass('active')==true){
				bonus_cnt++;
			}
		});

		if(gkey == 1 || gkey == 2 || gkey == 3){
			if(bonus_cnt>0){
				swal('','보너스는 한개만 선택이 가능합니다. 다른 보너스를 선택하시려면 카트에서 먼저 삭제해주세요.','warning');
				return false;
			}
		}
		if (gkey == 1 && cnt < 3) {
			swal('','해당 보너스는 3폴더 이상일때만 배팅이 가능합니다.','warning');
			return false;
		} else if(gkey == 2 && cnt < 5) {
			swal('','해당 보너스는 5폴더 이상일때만 배팅이 가능합니다.','warning');
			return false;
		} else if(gkey == 3 && cnt < 7) {
			swal('','해당 보너스는 7폴더 이상일때만 배팅이 가능합니다.','warning');
			return false;
		} else {
			if (cnt < config_max_bet_cnt) {
				if (gkey != "") {
					$.ajax({
						type: 'get',
						url: '/m/action/ajax_cart_action.php',
						dataType: 'json',
						data: {
							'action': 'InsertCart'
							, 'g_list': glist
							, 'g_key': gkey
							, 'g_result': gresult
							, 'g_type': gtype
							, 'g_price': BettingMoney
							, 'price': remove_comma($('#BettingMoney').val())
						},
						success: function (data) {
							if (data.flag == true) {
								loadingCart();
							} else {
								swal('', data.error, 'warning');
							}
						}
					});
				}
			} else {
				swal("", "최대 배팅가능한 갯수는  " + config_max_bet_cnt + "개입니다.\n오른쪽 배팅카트에서 '삭제'를 눌러주세요!.", "warning");
				return false;
			}
		}
	});


	$('.cart-price').on('click',function(){
		var price = parseInt($(this).data('money'));
		var BettingMoney = parseInt(remove_comma($('#BettingMoney').val()));
		var type = $(this).data('text');
		var max_bet_money = config_bet_bound_max;
		var max_hit_money = config_bet_reward_max;
		var my_money = 0;


		if(price == 0){
			$('#BettingMoney').val('0');
			CalcCart(10000);
		} else {
			if(type == 'max'){//max버튼을 눌렀을때
				if($('.cart_pick > li').length == 0){
					swal('','배팅하실 경기를 먼저 선택해주세요.','warning');
					return false;
				} else {
					var bet_rate = parseFloat($('#BettingQuota').text());
					var bet_hit_max = max_hit_money*100;
					var bet_max = 1;
					bet_rate = bet_rate*100;
					bet_max = parseInt((bet_hit_max / bet_rate)).toFixed(0);
					if(bet_max > max_bet_money){
						bet_max = max_bet_money;
					}
					price = bet_max;
				}
				$('#BettingMoney').val(comma(price));
				CalcCart(price);
			} else if(type == 'half'){
				if($('.cart_pick > li').length == 0){
					swal('','배팅하실 경기를 먼저 선택해주세요.','warning');
					return false;
				} else {
					var bet_rate = parseFloat($('#BettingQuota').text());
					var bet_hit_max = (max_hit_money*100)/2;
					var bet_max = 1;
					bet_rate = bet_rate*100;
					bet_max = bet_hit_max / bet_rate;
					if(bet_max > max_bet_money){
						bet_max = max_bet_money;
					}
					price = bet_max.toFixed(0);
					$('#BettingMoney').val(comma(price));
					CalcCart(price);
				}
			} else {
				my_money = BettingMoney + price;
				$('#BettingMoney').val(comma(my_money));
				CalcCart(my_money);
			}

		}

	});
	$('#BettingMoney').keyup(function(){
		var price = parseInt(remove_comma($(this).val()));
		CalcCart(price);
	});
	$('#buyGameBtn').on('click',function(){
		//클릭로그 만들기
		make_click_log('모바일 게임구매 버튼 클릭함');
		GameCartBuy($('#cartCnt').val());
	});
});


function inArray(needle, haystack) {
	var length = haystack.length;
	for(var i = 0; i < length; i++) {
		if(haystack[i] == needle) return true;
	}
	return false;
}

function rate_point2(num){
	var rate = String(num);
	var changeRate = rate.substring(0,4);

	return parseFloat(changeRate);
}

function change_text_color(color, who){
	console.log(color + ' : ' + who)
	who.addClass(color).fadeIn(100).fadeOut(800).fadeIn(800).fadeOut(800).fadeIn(800).fadeOut(800).fadeIn(800).fadeOut(800).fadeIn(800);
	setTimeout(function(){ who.removeClass(color)},8000);
}

function text_blink(who){
	setInterval(function(){ $('.'+who).fadeOut('slow').fadeIn('slow'); }, 2000)
}


function make_click_log(gubun){
	var type = gubun;
	$.ajax({
		type: 'get',
		url: '/m/action/click_log.php',
		dataType: 'json',
		data: {
			'type': type
		},
		success: function (data) {
		}
	});
}