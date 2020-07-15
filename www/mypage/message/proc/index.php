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

            $bank = "";
            $bank .= $SITECONFIG['I_BankName'];
            $bank .= "<br>";
            $bank .= $SITECONFIG['I_BankNum'];
            $bank .= "<br>";
            $bank .= $SITECONFIG['I_BankOwner'];
            $bank .= "<br>";

            $que  = "INSERT INTO board SET B_ID = 'message', ";
            $que .= "M_Key = '{$_SESSION['S_Key']}', ";
            $que .= "B_Subject = '충전계좌요청[자동]', ";
            $que .= "B_Content = '충전계좌요청[자동]', ";
            $que .= "B_Answer = '{$bank}', ";
            $que .= "B_RegDate = NOW() ";
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