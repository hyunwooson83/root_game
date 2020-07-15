<?php
    $include_path = $_SERVER['DOCUMENT_ROOT'];
    include $include_path."/include/common.php";
    
    #사이트 관련 정보 받아 오기
    $que = "SELECT * FROM siteconfig WHERE idx = 1";
    $config = getRow($que);

    switch( $_POST['HAF_Value_0'] ) {   
        case 'requestBank'://계좌요청
            $json['flag'] = true;
            $json['error'] = '';

            
            $res = send_bankinfo($SITECONFIG);
            if(!$res){
                $json['flag'] = false;
                $json['error'] = '디비입력에러';
            }

            echo json_encode($json);
        break;
    }
?>