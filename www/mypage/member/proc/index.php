<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';
switch( $_POST['HAF_Value_0'] ) {

    case "MemberLogin" :
        $json['flag'] = true;
        $json['error'] = "";

        if( !$_POST['HAF_Value_1'] || !$_POST['HAF_Value_2'] ) $lib->AlertMSG( "아이디와 비밀번호를 정확히 입력해주세요1.");

        $result = $db->Execute('select * from members where M_ID = ? and M_Passwd = password(?)', array( $_POST['HAF_Value_1'], $_POST['HAF_Value_2'] ) );
        if ( $result->RecordCount() < 1 ){
            $json['flag'] = false;
            $json['error'] = "아이디 또는 비밀번호 오류";
        }

        echo json_encode($json);
        break;



    case "MemberModify" :

        // 정보 수정 처리
        $record = Null;
        if ( $_POST['HAF_Value_3'] != "" ) {
            $sql  = "UPDATE members SET ";
            $sql .= "M_Passwd =  PASSWORD('{$_POST['HAF_Value_3']}'), ";
            $sql .= "M_LastModify = NOW() ";
            $sql .= " WHERE M_Key = '{$_SESSION['S_Key']}' ";
            echo $sql;
            $res = setQry($sql);
            if($res){
                echo "<script>parent.callback(true);</script>";
            } else {
                echo "<script>parent.callback(false);</script>";
            }
        } else {
            echo "<script>parent.callback(false);</script>";
        }

        break;


};
?>