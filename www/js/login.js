$(window).resize(function(){
    $("body").height($(window).height());
    $('.umMovie').height($(window).height());
	video_resize();
}).resize();

$(document).ready(function(){
    $("body").height($(window).height());
    $('.umMovie').height($(window).height());
	video_resize();
});

function video_resize(){
	var movie_calc = $('.umMovie').width() /$('.umMovie').height();
	//alert();
    if(movie_calc <= 1.78){
        $('.umMovie').width('auto');
        $('.umMovie').height($(window).height());
    }else {
        $('.umMovie').width($(window).width());
        $('.umMovie').height('auto');
    }

}