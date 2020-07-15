<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

    $json['flag'] = false;
    $json['error'] = '';
    $que = "SELECT token FROM members WHERE M_Key = '{$_SESSION['S_Key']}' ";
    //echo $que;
    $row = getRow($que);
    //echo $row['token']."-".$_SESSION['S_Token'];
    if (!empty($row['token'])) {
        if ($row['token'] != $_SESSION['S_Token']) {
            //중복로그인됨
            //msgMove('중복 로그인으로 로그아웃 되었습니다.','/login');
            unlink($_SERVER['DOCUMENT_ROOT'] . "/session/sess_" . $_SESSION['S_Token']);
            unset($_SESSION['S_Key']);
            $json['flag'] = true;
            $json['error'] = '중복 로그인으로 로그아웃 됩니다.';
        }
    }

    echo json_encode($json);

?>