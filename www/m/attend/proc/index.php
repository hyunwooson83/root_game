<?php
    $include_path = $_SERVER['DOCUMENT_ROOT'];
    include $include_path."/include/common.php";

    $json['flag'] = true;
    $json['error'] = '';

    $this_month = date("Y-m");
    $today = date("Y-m-d");
    $que = "SELECT COUNT(*) AS attend_cnt FROM attend WHERE DATE_FORMAT(A_Date,'%Y-%m') = '{$this_month}' AND M_Key = '{$_SESSION['S_Key']}'";
    $attend = getRow($que);
    if(!$attend['attend_cnt']) {
        $que = "INSERT INTO attend SET ";
        $que .= "A_Date = '{$today}', ";
        $que .= "M_Key = '{$_SESSION['S_Key']}', ";
        $que .= "A_RegDate = NOW() ";
        $res = setQry($que);

        if (!$res) {
            $json['flag'] = false;
            $json['error'] = '디비입력에러';
        }
    } else {
        $json['flag'] = false;
        $json['error'] = '이미 출석체크 하셨습니다.';
    }
    echo json_encode($json);
?>