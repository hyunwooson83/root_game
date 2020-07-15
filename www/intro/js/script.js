$(function(){

	$('#counter').countdown({
		timestamp	: (new Date()).getTime() + (1567263600*1000)-(60*60*24*5*1000)
	});
	
});
