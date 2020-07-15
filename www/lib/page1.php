<? 
	
	// 전페 페이지 구함. ceil() 올림함수  
	$total_page    = ceil($total_article / $view_article);  
	
	// 이전 페이지값 구함 (1보다 작을 경우 1로 지정)  
	$prev_page = $_GET['page'] - 1;  
	if ($prev_page < 1) $prev_page = 1;  
	
	// 다음 페이지값 구함 (전체페이지값 넘으면 전체페이지값으로 지정)  
	$next_page = $_GET['page'] + 1;  
	if ($next_page > $total_page) $next_page = $total_page;  
	
	// 페이지 인덱스의 시작과 종료 범위 구함  
	if ($_GET['page']%10) $start_page = $_GET['page'] - $_GET['page']%10 + 1;  
	else          $start_page = $_GET['page'] - 9;  
	$end_page = $start_page + 10;  
	
	// 이전 페이지 그룹을 지정  
	$prev_group = $start_page - 1;  
	if ($prev_group < 1) $prev_group = 1;  
	
	// 다음 페이지 그룹을 지정  
	$next_group = $end_page;  
	if ($next_group > $total_page) $next_group = $total_page;  
?>
<br>
<div class="paging">

<? if ($page != 1) {?>
<a href="<?=$PHP_SELF?>?page=1<?=$href?>" class="pre_end"><img src="/images/btn/paging_s.png" class="png_bg png"  /></a>&nbsp;
<a href="<?=$PHP_SELF?>?page=<?=$prev_page,$href?>" class="pre"><img src="/images/btn/paging_p.png" class="png_bg png"  /></a>&nbsp;
<? } ?> 
<?
	for ($i=$start_page; $i<$end_page; $i++) {  
	if ($i>$total_page) break;  
	if ($i==$_GET['page']){
?> 
  <?=$i?>
<? } else { ?>                 
  <a href="<?=$PHP_SELF?>?page=<?=$i,$href?>"><?=$i?></a>
<? }} ?>
<? if ($page != $total_page ) {?>
&nbsp;<a href="<?=$PHP_SELF?>?page=<?=$next_page,$href?>" class="next"><img src="../images/btn/paging_n.png" class="png_bg png"  /></a>
&nbsp;<a href="<?=$PHP_SELF?>?page=<?=$total_page,$href?>" class="next_end"><img src="../images/btn/paging_f.png" class="png_bg png"  /></a>

<? } ?>


</div>