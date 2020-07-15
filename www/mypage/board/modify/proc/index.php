<?php
$include_path = $_SERVER['DOCUMENT_ROOT'];
include $include_path."/include/common.php";

switch($_POST['mode'])
{
    case 'attachDel':

        $json['flag'] = true;
        $json['error'] = '';
        $cnt = 0;
        $sql = "SELECT * FROM board WHERE B_Key = ({$bkey})";
        $row = getRow($sql);
        $akey = explode(",",$row['B_BG_Key']);
        for($i=0;$i<count($akey);$i++){
            if($akey[$i]==$delkey){
                $json['bgkey'] = $akey[$i];
                unset($akey[$i]);
            }
        }

        $bbgkey = implode(",",$akey);

        $que = "UPDATE board SET B_BG_Key = '{$bbgkey}' WHERE B_Key = '{$bkey}'";
        $res = setQry($que);
        if (!$res) {
            $json['flag'] = false;
            $json['error'] = '삭제시 업데이트 오류';
        }


        //echo $que;
        echo json_encode($json);
        break;
}
?>