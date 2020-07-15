<?php
$include_path = $_SERVER['DOCUMENT_ROOT']."/m";
include $include_path."/include/common.php";



switch( $_REQUEST['mode'] ) {
    case 'delMessage'://계좌요청
        $json['flag'] = true;
        $json['error'] = '';

        $que = "DELETE FROM message WHERE idx = '{$_REQUEST['idx']}'";
        $res = setQry($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '쪽지 삭제 에러';
        }

        echo json_encode($json);
        break;
}
switch( $_REQUEST['HAF_Value_0'] ) {
    case 'requestBank'://계좌요청
        $json['flag'] = true;
        $json['error'] = '';

        $bank = "";
        $bank .= $SITECONFIG['I_BankName'];
        $bank .= " / ";
        $bank .= $SITECONFIG['I_BankNum'];
        $bank .= " / ";
        $bank .= $SITECONFIG['I_BankOwner']."입니다.";
        $bank .= "<br>";

        $que  = "INSERT INTO message SET ";
        $que .= "M_Key = '{$_SESSION['S_Key']}', ";
        $que .= "message = '{$bank}', ";
        $que .= "regDate = NOW() ";
        //echo $que;
        $res = setQry($que);

        if(!$res){
            $json['flag'] = false;
            $json['error'] = '디비입력에러';
        }

        echo json_encode($json);
        break;
    case 'delMessage'://계좌요청
        $json['flag'] = true;
        $json['error'] = '';

        $que = "DELETE FROM message WHERE idx = '{$_REQUEST['idx']}'";
        $res = setQry($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '쪽지 삭제 에러';
        }

        echo json_encode($json);
        break;
}
?>