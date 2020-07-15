<?php
    $include_path = $_SERVER['DOCUMENT_ROOT'];
    include $include_path."/include/common.php";

    #사이트 관련 정보 받아 오기
    $que = "SELECT * FROM siteconfig WHERE idx = 1";
    $config = getRow($que);

    switch( $_REQUEST['mode'] ) {
        case 'bettingDel'://배팅내역삭제
            $json['flag'] = true;
            $json['error'] = '';
            $idx = implode(",",$_REQUEST['idx']);
            $que = "UPDATE buygame SET BG_Visible = 2 WHERE BG_Key IN ({$idx})";
            $res = setQry($que);
            if(!$res){
                $json['flag'] = false;
                $json['error'] = '디비입력에러';
            }

            echo json_encode($json);
            break;
    }
?>