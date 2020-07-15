$(document).ready(function(){

	/* MENU */
	$("#menu-navigator > li").hover(function(){
		$("dl",this).show();
		$("dl",this).animate({opacity:1},50);
	},function(){
		$("dl",this).hide();
		$("dl",this).css("opacity","0");
	});

	/* CASINO */
	$(".casino_id_state_btn").hover(function(){
		$(".casino_id_state").animate({"top":"0"},300);
	},function(){
		$(".casino_id_state").css("top","-275px");
	});

	/*서브 탑 셀렉트*/
	$(".top_select > div").hover(function(){
		$(this).children('ul').stop().slideDown();
	},function(){
		$(".top_select > div > ul").stop().slideUp()
	});

});

/* 언어 전환 */
$lang = 'kr';
function lang_change(){
	if ($lang == 'kr') {
		$(".lang_change").html("<img src='./img/lang_en.png' />");
		$lang = 'en';
	} else {
		$(".lang_change").html("<img src='./img/lang_kr.png' />");
		$lang = 'kr';
	}
}