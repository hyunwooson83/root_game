<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

$que = "SELECT *
FROM `moneyinfo`
WHERE `MI_Type` = ''
AND `MI_Memo` = '게임을 구매하였습니다.' ORDER BY MI_Key DESC";

$arr = getArr($que);
foreach($arr as $rs){
    if($rs['MI_Type']==''){

        $sql = "UPDATE moneyinfo SET MI_Type = 'GameBetting' WHERE MI_Key = '{$rs['MI_Key']}'";
        echo $sql."<br>";
        setQry($sql);
    }
}

?>
