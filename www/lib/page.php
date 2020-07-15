<?php
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
                        
<div class="paging_box">
<?php if ($page != 1) {?>
	<a href="<?=$PHP_SELF?>?page=<?=$prev_page,$href?>" >◀</a>
<?php } ?>

<?php
	for ($i=$start_page; $i<$end_page; $i++) {  
	if ($i>$total_page) break;  
	if ($i==$_GET['page']){
?> 
	<a href="#" class="hit"><?=$i?></a>

<?php } else { ?>
  <a href="<?=$PHP_SELF?>?page=<?=$i,$href?>"><?=$i?></a>
<?php }} ?>

<?php if ($page != $total_page ) {?>
	<a href="<?=$PHP_SELF?>?page=<?=$next_page,$href?>" >▶</a>
<?php } ?>

</div>