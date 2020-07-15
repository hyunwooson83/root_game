<?php
$include_path = $_SERVER['DOCUMENT_ROOT'];
include $include_path."/include/common.php";




switch($mode){
    case 'chargeListDel':
        $json['flag'] = true;
        $json['error'] = '';
        $que = "UPDATE requests SET R_Visible = 'N' WHERE R_Key = '{$rkey}'";
        $res = setQry($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '데이터 삭제시 오류발생[DB]';
        }
        echo json_encode($json);
        break;
}

?>