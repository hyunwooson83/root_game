var myScroll;
var aa;
var menu_f;


function init(){

	myScroll = new IScroll('#menu_right', { scrollX: false, scrollY: true, bounce:true, click:false, preventDefaultException:{ tagName:/.*/}});
	myScroll2 = new IScroll('#menu_left', { scrollX: false, scrollY: true, bounce:true, click:false, preventDefaultException:{ tagName:/.*/}});
	myScroll3 = new IScroll('#menu_cart', { scrollX: false, scrollY: true, bounce:true, click:false, preventDefaultException:{ tagName:/.*/}});

}


document.addEventListener('DOMContentLoaded', function () { setTimeout(init, 200); }, false);
function iScrollRefresh(){
	setTimeout(function(){
		myScroll.refresh(); 
		myScroll2.refresh(); 
		myScroll3.refresh(); 
	}, 200);
}

function get_now_time(){
	var d = new Date();
	var month ="";
	

		month = leadingZeros(d.getMonth()+1,2)+"/"+
		leadingZeros(d.getDate(),2)+" "+
		leadingZeros(d.getHours(),2)+":"+
		leadingZeros(d.getMinutes(),2);

		$(".time > .year").text(d.getFullYear());
		$(".time > .month").text(month);
			

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

$(document).ready(function(){
	get_now_time();
	//공지사항 움직임
	notice_move();
	
	$('#body_wrap').css({"margin-top": $('#header').height()});
	$('.menu_wrap').css({"top": $('.head_top1').height()+1});

	$(".menu_list > li > span,.menu_list > li > dl > dd > div").click(function(){
		
		if( $(this).hasClass('on') ){
			$(this).removeClass('on');
			$(this).parent().children('dl, .m-menu_list_view, .m_type1, .m_type2').stop().slideUp();
		}else{
			$(this).addClass('on');
			$(this).parent().children('dl, .m-menu_list_view, .m_type1, .m_type2').stop().slideDown(function(){
				iScrollRefresh();
			});	
		}
	});
	
	$(".menu_list > li > dl > dd > div, .menu_list > li > dl > dd > em").click(function(){
		
		if( $(this).parent().hasClass('on') ){
			$(this).parent().removeClass('on');
			if($(this).parent().next().is("dt")){
				$(this).parent().next().stop().slideUp();
			}
		}else{
			$(this).parent().addClass('on');
			if($(this).parent().next().is("dt")){
				$(this).parent().next().stop().slideDown(function(){
					$(".menu_list > li > dl").css({"height":"auto"});
					iScrollRefresh();
				});
			}
		}
	});


	$(".site_list > li").click(function(){
		$(".site_list > li").removeClass('on');
		if( $(this).hasClass('on') ){
			$(this).removeClass('on');
		}else{
			$(this).addClass('on');
		}
		$(this).addClass('on');
	});

	slick_on();




});

function slick_on(){
	$('.main_slide_wrap').slick({
		infinite: true,
		speed: 500,
		autoplay : true,
		arrows: false,
		draggable:true,
		dots : true,
	});
	$('.introduce_list_wrap > .list_box').slick({
		infinite: true,
		speed: 500,
		autoplay : true,
		arrows: true,
		draggable:true,
		prevArrow: $('.introduce .prev'),
		nextArrow: $('.introduce .next')
	});
}


function menu_flag(flag){
	
	if( $('.menu_wrap').css("visibility") == "hidden"){
			iScrollRefresh();
			$("html, body").bind('touchmove', function(e){e.preventDefault()});
		
		menu_f =flag;
		$('.menu_wrap').css({"visibility":"visible"});
		$(".menu_wrap .menu."+menu_f).css({"visibility":"visible"});
		if(menu_f=='left' || menu_f=='left2'){
			$(".menu_wrap .menu_mask > .menu_close").css({"float":"right","margin-":"5%"});
			$(".menu_wrap .menu."+menu_f).css({"left":0});
			
		}else{
			$(".menu_wrap .menu_mask > .menu_close").css({"float":"left","margin-left":"5%"});
			$(".menu_wrap .menu."+menu_f).css({"right":0});
		}
	
	}else{
		iScrollRefresh();
		if(menu_f=='left' || menu_f=='left2'){
			$(".menu_wrap .menu."+menu_f).css({"left":"-80%"});	
		}else{
			$(".menu_wrap .menu."+menu_f).css({"right":"-80%"});
		}
		$('.menu_wrap').css({"visibility":"hidden"});
		$(".menu_wrap .menu").css({"visibility":"hidden"});
		$("html, body").unbind('touchmove');

		$(".menu_list > li > span").removeClass('on');
		$(".menu_list > li").children('dl, .m-menu_list_view').slideUp();	
		
	}
		

}

function notice_close(){
	$('.head_top2.notice').slideUp(200);
	$('#body_wrap').css({"margin-top": $('.head_top1').height()});
	
}

//공지사항 움직임
function notice_move(){
	var ment_width = $(".head_top2 > .ment").width();
	var notice_width = $(".head_top2 > .ment > span").width();
	$(".head_top2 > .ment > span").css({"left":ment_width});
	
	$(".head_top2 > .ment > span").animate({"left":-1 *notice_width},10000,'linear',function(){
		notice_move();
	});
}
function numberbox_btn(idx){
	if ($("#number_box"+idx).css("display")=="none"){
		$("#number_box"+idx).slideDown('fast');
	} else {
		$("#number_box"+idx).slideUp('fast');
	}	
}




//계좌확인
function account_confirm(){
	iScrollRefresh();
	if( $(".account_wrap").css("display")=="none"){
		$(".account_wrap").slideDown(function(){
			$(".account_wrap").css({"height":"auto"});
		});
	}else{
		$(".account_wrap").slideUp();
	}
}
//계좌 비밀번호 입력
function account_confirm2(){
	iScrollRefresh();
	$(".account_info1").slideDown();

								
}


//메인퀵메뉴 
function quick(num){
	menu_flag('right');
	setTimeout(function(){
		if( $(".menu_list > li > span.quick"+num).hasClass('on') ){
		
		}else{
			$(".menu_list > li > span.quick"+num).click();
			$(".menu_list > li > span.quick"+num).focus();

		}
	},400);
}

/* TYPE SELECT SLIDE */
$type_select_open = 0;
function slide_type(){
	if($type_select_open == 0){
		$("#type_select_l").slideDown('fast');
		$("#type_select_arr").addClass("open");
		$type_select_open = 1;
	} else {
		$("#type_select_l").slideUp('fast');
		$("#type_select_arr").removeClass("open");
		$type_select_open = 0;
	}
}