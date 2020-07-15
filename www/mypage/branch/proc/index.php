<?php

$include_path = $_SERVER['DOCUMENT_ROOT'];
include $include_path."/include/common.php";

$clear = array();

if(ctype_alpha($_POST['mode'])){
    $clear['mode'] = $_POST['mode'];
}

if(ctype_alnum($_POST['userId'])){
    $clear['loginId'] = $_POST['userId'];
}

if(ctype_alnum($_POST['userNick'])){
    $clear['userNick'] = $_POST['userNick'];
}

switch($clear['mode'])
{


    case 'shopJoin':
        $json['flag'] = true;
        $json['error'] = '';

        $sql = "SELECT COUNT(*) FROM members WHERE M_ID = '{$user_id}' OR M_CP = '{$hp}' OR M_NICK = '{$user_nick}' ";
        $row = getRow($sql);
        if($row[0]>0){
            $json['flag'] = false;
            $json['error'] = '아이디,닉네임,휴대폰 중복됨';
        } else {
            $que = "INSERT INTO members SET ";
            $que .= "M_ID           = '{$user_id}', ";
            $que .= "M_NICK         = '{$user_nick}', ";
            $que .= "M_Passwd       =  password('" . $user_pass . "'), ";
            $que .= "M_CP           = '{$hp}', ";
            $que .= "M_BankName     = '{$bank}', ";
            $que .= "M_BankNumber   = '{$bank_num}', ";
            $que .= "M_BankOwner    = '{$bank_owner}', ";
            $que .= "M_BankPass     = '{$bank_pass}', ";
            $que .= "M_Type         = '2', ";
            $que .= "M_Level        = '{$level}', ";
            $que .= "M_Shop_Level   = '{$shopLevel}', ";
            $que .= "M_ShopTop      = '{$grade}', ";
            $que .= "M_ShopYn       = 'Y', ";
            $que .= "M_Recom_Code   = '{$user_id}', ";
            $que .= "M_Recommend    = 'Y', ";
            $que .= "M_ShopParentID = '{$_SESSION['S_ID']}', ";
            $que .= "M_ShopPayType  = '{$pay_type}', ";
            $que .= "M_ShopPrecent  = '{$shop_percent}', ";
            $que .= "M_State        = 'Normal', ";
            $que .= "M_RegistDate   = NOW() ";
            //echo $que;
            $res = setQry($que);
            if (!$res) {
                $json['flag'] = false;
                $json['error'] = '회원 디입 입력 오류';
            }
        }
        echo json_encode($json);


        break;

    case 'changePer':
        $json['flag'] = true;
        $json['error'] = '';
        $que = "UPDATE members SET M_ShopPrecent = '{$per}' WHERE M_Key = '{$mkey}'";
        //echo $que;
        $res = setQry($que);
        if (!$res) {
            $json['flag'] = false;
            $json['error'] = '회원 디입 입력 오류';
        }
        echo json_encode($json);
        break;
}
?>
