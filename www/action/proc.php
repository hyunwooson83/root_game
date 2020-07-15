<?
	include_once ($_SERVER['DOCUMENT_ROOT']."/include/common.php");
	
	switch($_POST['mode'])
	{
		case 'answer':
			/*if($_POST['gubun']=='MG'){
				$tb = "Z_mugbang_board";
			} else {*/
				$tb = "board";
			//}
			$que = "UPDATE {$tb} SET B_Answer = '{$_POST['answer']}' WHERE B_Key = '{$_POST['bkey']}'";
			//echo $que;
			$res = setQry($que);
			if($res){
				msgMove('답변되었습니다.','/_adm/?pg=list2&menu=board&tn=customer&page='.$_POST['page']);
			}
		break;
		
		
		case 'delete':

		break;
	}
?>