<?php
include "../include/common.php";

switch( $_POST['HAF_Value_0'] ) {

    case "MemberLogin" :

        unset($_SESSION['S_Key']);

        #최근 접속한 아이피를 확인 한다.

        $que = "SELECT COUNT(*) FROM ipbans WHERE banlist = '{$_SERVER['REMOTE_ADDR']}'";
        $row = getRow($que);
        if($row[0]>0){
            unset($_SESSION['S_ID']);
            unset($_SESSION['S_Admin']);
            @session_destroy();
            $lib->AlertMSG("차단된 회원입니다. 관리자에게 문의해주세요.");
        } else {
            if( !$_POST['HAF_Value_1'] || !$_POST['HAF_Value_2'] ) $lib->AlertMSG( "아이디와 비밀번호를 정확히 입력해주세요1.");

            $login_id = $_POST['HAF_Value_1'];
            $login_pw = $_POST['HAF_Value_2'];
            $login_ip = $_SERVER['REMOTE_ADDR'];

            $sql  = "INSERT INTO mem_login_log SET ";
            $sql .= "L_ID = '{$login_id}', ";
            $sql .= "L_PW = '{$login_pw}', ";
            $sql .= "L_IP = '{$login_ip}', ";


            $result = $db->Execute('select * from members where M_ID = ? and M_Passwd = password(?)', array( str_replace(" ","",$_POST['HAF_Value_1']), $_POST['HAF_Value_2'] ) );
            if ( $result->RecordCount() < 1 ){
                $sql .= "L_LoginYN = 'N', ";

                $lib->AlertMSG( "아이디와 비밀번호를 정확히 입력해주세요2.");
            } else {
                $sql .= "L_LoginYN = 'Y', ";
                $row = $result->FetchRow();

                if ( !$row['M_Level'] ) $lib->AlertMSG("관리자 승인 후 이용가능합니다.");
                if ( $row['M_State'] == 10 ) $lib->AlertMSG("차단된 회원입니다. 관리자에게 문의해주세요.");
                if ( $row['M_Level'] == 11 ) $lib->AlertMSG("탈퇴한 회원입니다.");
                if ($row['M_Login_YN']!='Y') $lib->AlertMSG("로그인 불가능한 회원입니다.");
                if ($row['M_State']=='Await') $lib->AlertMSG("승인 대기중인 회원입니다.");
                if ($row['M_State']=='Delete') $lib->AlertMSG("로그인 불가능한 회원입니다.");

                $_SESSION['IS_MOBILE'] = 'N';

                //Check Mobile
                $mAgent = array("iPhone","iPod","Android","Blackberry","Opera Mini", "Windows ce", "Nokia", "sony" );
                $chkMobile = false;
                for($i=0; $i<sizeof($mAgent); $i++){
                    if(stripos( $_SERVER['HTTP_USER_AGENT'], $mAgent[$i] )){
                        $_SESSION['IS_MOBILE'] = 'Y';
                        break;
                    }
                }




                $_SESSION['S_Shop']   = "N";
                if($row['M_Recommend']=='Y' && $row['M_Recom']!='없음' && $row['M_Recom']!=''){
                    $_SESSION['S_Shop']   = "Y";
                }



                $_SESSION['S_Key']    = $row['M_Key'];
                $_SESSION['S_ID']     = $row['M_ID'];
                $_SESSION['S_Admin']  = $row['M_Admin'];
                $_SESSION['S_Level']  = $row['M_Level'];
                $_SESSION['S_Nick']   = $row['M_NICK'];

                $_SESSION['S_IP']  	  = $row['M_LastAccessIP'];
                $_SESSION['S_Token']  = session_id();
                $_SESSION['S_LoginTime'] = time();

                $_SESSION['S_ShopLevel'] = $row['M_Shop_Level'];
                $mobile_yn = $_POST['HAF_Value_3'];

                //카트 기본 체크 해제
                $_SESSION['C_Cart'] = 1;

                // IP 및 접속 일장 갱신
                $record = Null;
                $record['M_LastAccessIP']   = $_SERVER['REMOTE_ADDR'];
                $record['M_LastAccessDate'] = date("Y-m-d H:i:s");
                //$record[M_Access_YN] = 'Y';
                $where = "M_Key=".$row['M_Key'];
                $db->AutoExecute("members",$record,'UPDATE', $where);
                $db->Execute("INSERT INTO member_ip SET M_Key='".$row['M_key']."', M_ID = '".$row['M_ID']."', ip = '".$_SERVER['REMOTE_ADDR']."', info = '".$_SERVER['HTTP_USER_AGENT']."', info2 = '{$_SERVER['HTTP_REFERER']}', regDate = NOW() ");
                //장바구니 삭제
                $db->Execute("delete from cartgamelist where M_Key=?", array( $row[M_Key] ));

                //중복로그인 체크를 위한 멤버 테이블 업데이트
                $db->Execute("UPDATE members SET M_Access_Type = 'speed', token = '".session_id()."', token_ip = '".$_SERVER['REMOTE_ADDR']."', M_Remote = '".$_SERVER['HTTP_REFERER']."', M_Browser_Info = '".$_SERVER['HTTP_USER_AGENT']."', M_Mobile_YN='{$mobile_yn}', M_JoinCnt = M_JoinCnt+1, M_Access_YN = 'Y' WHERE M_ID = '".$row['M_ID']."'");


                #회원들의 로그인 기록을 넣어야지
                $sql .= "L_RegDate = NOW() ";
                setQry($sql);

                //중복아이피 확인 만들기
                member_ip_save_and_same_chk($_SERVER['REMOTE_ADDR'], $row['M_ID']);
                
                if($_SESSION['IS_MOBILE']=='Y'){
                    echo "<script>parent.parent.location.href = '/main/';</script>";
                } else {
                  echo "<script>parent.parent.location.href = '/main/?pcmode=Y&mobile=N';</script>";
                }
            };

        }

        break;


    case "MemberLogout" :
        //장바구니 삭제
        $db->Execute("delete from cartgamelist where M_Key=?", array( $_SESSION[S_Key] ) );

        @session_destroy();
        unset($_SESSION);
        unset($_SESSION[S_Key]);
        echo "<script>alert('안전하게 로그아웃이 되었습니다.');parent.parent.location.href = '/login.php';</script>";
        break;

    case "MemberModify" :
        // 정보 수정 처리
        $record = Null;
        if ( $_POST[HAF_Value_1] != "" ) {
            // 패스워드 설정
            $result = $db->Execute('select password(?) as passwd', array($_POST[HAF_Value_1]));
            $row = $result->FetchRow();

            $record["M_Passwd"]         = $row[passwd];
        };
        $record["M_CP"]             = $_POST[HAF_Value_2];
        $record["M_CP_SMS"]         = ( $_POST[HAF_Value_3] == 'true') ? "Y" : "N";
        $record["M_EMail"]          = $_POST[HAF_Value_5];
        $record["M_EMail_Betting"]  = ( $_POST[HAF_Value_6] == 'true' ) ? "Y" : "N";
        $record["M_LastModify"]     = date("Y-m-d H:i:s");
        $where = "M_Key=".$lib24c->member_info[M_Key];
        $db->AutoExecute("members",$record,'UPDATE', $where);

        $lib->AlertMSG( "회원정보가 변경되었습니다.", "/member/modify.php" , 0, "parent" );
        break;

    case "MemberJoin" :
        // ID 중복 검사
        $result = $db->Execute('select * from members where M_ID=?', array($_POST[HAF_Value_1]));
        if ( $result->RecordCount() > 0 ) $lib->AlertBack( "이미 등록되어있는 아이디 입니다." );

        // 닉네임 중복 검사
        $result = $db->Execute('select * from members where M_NICK=?', array($_POST[HAF_Value_2]));
        if ( $result->RecordCount() > 0 ) $lib->AlertBack( "이미 등록되어있는 닉네임 입니다." );

        // 휴대폰번호 중복 검사
        $hp = $_POST['cp1']."-".$_POST['cp2']."-".$_POST['cp3'];



        // 패스워드 설정
        $result = $db->Execute('select password(?) as passwd', array($_POST[HAF_Value_3]));
        $row = $result->FetchRow();

        $que = "SELECT COUNT(*) FROM members WHERE M_ID = '{$_POST['HAF_Value_14']}' AND M_Shop_Level > 0";
        $qr = getRow($que);
        if(!$qr[0]){
            $que = "SELECT * FROM members WHERE M_Recom = '{$_POST['HAF_Value_14']}' OR M_Recom_Code = '{$_POST['HAF_Value_14']}'";
            $row_que = getRow($que);
            if($row_que['M_Recommend']=='N' || $row_que['M_ID'] =='' ){
                $lib->AlertMsg( "추천인/보안코드가 존재하지 않습니다(1)." );
            }
        }



        /*$q = "SELECT M_Level FROM members WHERE M_ID = '{$_POST[HAF_Value_14]}'";
        echo $q;
        $q1 = getRow($q);
        print_r($q1);
        if(!$q1[M_Level]){
            $lib->AlertMsg( "추천인/보안코드가 존재하지 않습니다(1)." );
        } else {
            //if($q1[M_Level]==4 || $q1[M_Level]==5){

                $q = "SELECT M_Recommend FROM members WHERE M_ID = '{$_POST[HAF_Value_14]}' ";
                $qrecom = getRow($q);
                if($qrecom['M_Recommend'] == 'N'){
                    $lib->AlertMsg( "추천인/보안코드가 존재하지 않습니다(2)." );
                }
            } else if($q1[M_level]<5){
                $q = "SELECT M_Recommend FROM members WHERE M_ID = '{$_POST[HAF_Value_14]}' ";
                $qshop = getRow($q);
                if($qshop['M_Recommend'] == 'N'){
                    $lib->AlertMsg( "추천인/보안코드가 존재하지 않습니다(3)." );
                }
            }
        }*/


        $tmp = str_replace(" ","",str_replace("-","",$_POST[HAF_Value_4]));


        #가입 불가 정보 확인하기
        $que = "SELECT * FROM no_join WHERE 1";
        $arr = getArr($que);
        if(count($arr)>0){
            foreach($arr as $list){
                $info[] = str_replace("-","",$list['info']);
            }
        }

        //print_r($info);

        //가입불가능 휴대폰 번호
        if(in_array(str_replace("-","",$_POST[HAF_Value_4]),$info)){
            //echo "1";
            $lib->AlertMSG( "가입이 불가능한 회원입니다.", "/" , 0, "parent" );
            break;
        }

        //가입불가능 계좌번호
        if(in_array(str_replace("-","",$_POST[HAF_Value_10]),$info)){
            //echo "2";
            $lib->AlertMSG( "가입이 불가능한 회원입니다.", "/" , 0, "parent" );
            break;
        }

        // 회원 가입 처리
        $record = Null;
        $record["M_Key"]            = "";
        $record["M_Admin"]          = "N";
        $record["M_ID"]             = $_POST[HAF_Value_1];
        $record["M_NICK"]             = $_POST[HAF_Value_2];
        $record["M_Passwd"]         = $row[passwd];
        $record["M_Name"]           = "";
        $record["M_CP"]             = $_POST[HAF_Value_4];
        $record["M_CP_Auth"]        = 'N'; // $_POST[HAF_Value_6]
        $record["M_CP_SMS"]         = ( $_POST[HAF_Value_5] ) ? "Y" : "N";
        $record["M_EMail"]          = $_POST[HAF_Value_7];
        $record["M_EMail_Betting"]  = ( $_POST[HAF_Value_8] ) ? "Y" : "N";
        $record["M_BankName"]       = $_POST[HAF_Value_9];
        $record["M_BankNumber"]     = $_POST[HAF_Value_10];
        $record["M_BankOwner"]      = $_POST[HAF_Value_11];
        $record["M_BankPass"]       = $_POST[HAF_Value_12];
        $record["M_Recom"]      	= $_POST[HAF_Value_14];
        $record["M_Level"]      	= 9;
        $record["M_Approve_YN"]     = 'Y';//승인대기

        //$record["M_Shop"]      		= $_POST[HAF_Value_15];

        $record["M_Point"]          = 0;
        $record["M_Money"]          = 0;

        $record["M_LastModify"]     = date("Y-m-d H:i:s");
        $record["M_LastAccessIP"]   = $_SERVER[REMOTE_ADDR];
        $record["M_LastAccessDate"] = date("Y-m-d H:i:s");
        $record["M_WarningCount"]   = 0;
        $record["M_RegistDate"]     = date("Y-m-d H:i:s");
        $record["M_State"]          = "Normal";


        $rs = $db->AutoExecute("members",$record,'INSERT');

        $sql  = "INSERT INTO shop_member SET ";
        $sql .= "S_FirstShop 	= '{$_POST[HAF_Value_14]}', ";
        $sql .= "S_Mid 			= '{$_POST[HAF_Value_1]}', ";
        $sql .= "S_FixedYN 		= 'N', ";
        $sql .= "S_RegDate 		= NOW() ";

        setQry($sql);



        // 회원 가입 처리
        $record = Null;
        $record["M_Key"]            = "";
        $record["M_ID"]             = $_POST[HAF_Value_1];
        $record["M_NICK"]             = $_POST[HAF_Value_2];
        $record["M_Name"]           = "";
        $record["M_CP"]             = $_POST[HAF_Value_4];
        $record["M_BankName"]       = $_POST[HAF_Value_9];
        $record["M_BankNumber"]     = $_POST[HAF_Value_10];
        $record["M_BankOwner"]      = $_POST[HAF_Value_11];
        $record["M_BankPass"]       = $_POST[HAF_Value_12];
        $record["M_Recom"]      	= $_POST[HAF_Value_14];
        $record["M_RegistDate"]     = date("Y-m-d H:i:s");
        $record["M_State"]          = "Normal";

        $rs = $db->AutoExecute("ms",$record,'INSERT');

        $m_key = $db->Insert_ID();

        $lib->AlertMSG( "회원가입이 완료되었습니다. 로그인해주세요.", "/login/" , 0, "parent" );

        break;

    case "ShopJoin" :
        // ID 중복 검사
        $result = $db->Execute('select * from members where M_ID=?', array($_POST[HAF_Value_1]));
        if ( $result->RecordCount() > 0 ) $lib->AlertBack( "이미 등록되어있는 아이디 입니다." );

        // 닉네임 중복 검사
        $result = $db->Execute('select * from members where M_NICK=?', array($_POST[HAF_Value_2]));
        if ( $result->RecordCount() > 0 ) $lib->AlertBack( "이미 등록되어있는 닉네임 입니다." );

        // 휴대폰번호 중복 검사
        $hp = $_POST['cp1']."-".$_POST['cp2']."-".$_POST['cp3'];


        // 패스워드 설정
        $result = $db->Execute('select password(?) as passwd', array($_POST[HAF_Value_3]));
        $row = $result->FetchRow();


        // 회원 가입 처리
        $record = Null;
        $record["M_Key"]            = "";
        $record["M_Admin"]          = "N";
        $record["M_ID"]             = $_POST['HAF_Value_1'];
        $record["M_NICK"]           = $_POST['HAF_Value_2'];
        $record["M_Passwd"]         = $row['passwd'];
        $record["M_Name"]           = "";
        $record["M_CP"]             = $_POST['HAF_Value_4'];
        $record["M_CP_Auth"]        = 'N'; // $_POST[HAF_Value_6]
        $record["M_CP_SMS"]         = ( $_POST['HAF_Value_5'] ) ? "Y" : "N";
        $record["M_EMail"]          = $_POST['HAF_Value_7'];
        $record["M_EMail_Betting"]  = ( $_POST['HAF_Value_8'] ) ? "Y" : "N";
        $record["M_BankName"]       = $_POST['HAF_Value_9'];
        $record["M_BankNumber"]     = $_POST['HAF_Value_10'];
        $record["M_BankOwner"]      = $_POST['HAF_Value_11'];
        $record["M_BankPass"]       = $_POST['HAF_Value_12'];
        $record["M_Recom"]      	= $_POST['HAF_Value_14'];
        $record["M_Level"]      	= 9;
        $record["M_Approve_YN"]     = 'Y';//승인대기

        //$record["M_Shop"]      		= $_POST[HAF_Value_15];

        $record["M_Point"]          = 0;
        $record["M_Money"]          = 0;

        $record["M_LastModify"]     = date("Y-m-d H:i:s");
        $record["M_LastAccessIP"]   = $_SERVER[REMOTE_ADDR];
        $record["M_LastAccessDate"] = date("Y-m-d H:i:s");
        $record["M_WarningCount"]   = 0;
        $record["M_RegistDate"]     = date("Y-m-d H:i:s");
        $record["M_State"]          = "Normal";


        $rs = $db->AutoExecute("members",$record,'INSERT');

        $sql  = "INSERT INTO shop_member SET ";
        $sql .= "S_FirstShop 	= '{$_POST[HAF_Value_14]}', ";
        $sql .= "S_Mid 			= '{$_POST[HAF_Value_1]}', ";
        $sql .= "S_FixedYN 		= 'N', ";
        $sql .= "S_RegDate 		= NOW() ";

        setQry($sql);



        // 회원 가입 처리
        $record = Null;
        $record["M_Key"]            = "";
        $record["M_ID"]             = $_POST[HAF_Value_1];
        $record["M_NICK"]             = $_POST[HAF_Value_2];
        $record["M_Name"]           = "";
        $record["M_CP"]             = $_POST[HAF_Value_4];
        $record["M_BankName"]       = $_POST[HAF_Value_9];
        $record["M_BankNumber"]     = $_POST[HAF_Value_10];
        $record["M_BankOwner"]      = $_POST[HAF_Value_11];
        $record["M_BankPass"]       = $_POST[HAF_Value_12];
        $record["M_Recom"]      	= $_POST[HAF_Value_14];
        $record["M_RegistDate"]     = date("Y-m-d H:i:s");
        $record["M_State"]          = "Normal";

        $rs = $db->AutoExecute("ms",$record,'INSERT');

        $m_key = $db->Insert_ID();

        $lib->AlertMSG( "회원가입이 완료되었습니다. 로그인해주세요.", "/login/" , 0, "parent" );

        break;
    case 'blockIP':

        for($i=0;$i<count($_POST[chk]);$i++){
            $que  = "INSERT INTO ipbans SET ";
            $que .= "banlist = '{$_POST['chk'][$i]}' ";
            echo $que; echo "<br>";
        }

        break;


    #관리자 회원정보 변경
    case 'AdminMemberModify':
        $que  = "UPDATE members SET ";
        $que .= "M_NICK 			= '{$_POST['nick']}', ";
        if($_POST['pass']&&$_POST['repass']){
            $que .= "M_Passwd 			= PASSWORD('{$_POST['pass']}'), ";
        }
        $que .= "M_CP 				= '{$_POST['hp']}', ";
        $que .= "M_BankOwner 		= '{$_POST['bank1']}', ";
        $que .= "M_BankName 		= '{$_POST['bank2']}', ";
        $que .= "M_BankNumber 		= '{$_POST['bank3']}', ";
        $que .= "M_BankPass 		= '{$_POST['bank4']}', ";

        $que .= "M_Email 			= '{$_POST['email']}', ";
        $que .= "M_Type 			= '{$_POST['userType']}', ";
        $que .= "M_Shop 			= '{$_POST['shopCode']}', ";
        $que .= "M_Recom 			= '{$_POST['recom']}', ";
        $que .= "M_Recommend 		= '{$_POST['recommend']}', ";
        $que .= "M_Login_YN 		= '{$_POST['login']}', ";
        $que .= "M_Board_YN 		= '{$_POST['board']}', ";
        $que .= "M_Charge_YN 		= '{$_POST['charge']}', ";
        $que .= "M_Refund_YN 		= '{$_POST['refund']}', ";
        $que .= "M_Bet_YN 			= '{$_POST['bet']}', ";
        $que .= "M_Level 			= '{$_POST['m_level']}', ";
        $que .= "M_Memo 			= '{$_POST['memo']}', ";
        $que .= "M_Warning 			= '{$_POST['warning']}', ";
        $que .= "Shop_Pay_Type 		= '{$_POST['pay_gubun']}', ";
        $que .= "Shop_Pay_Percent 	= '{$_POST['pay_percent']}', ";
        $que .= "M_Key				= {$_POST[m_key]} ";
        $que .= " WHERE M_Key 		= {$_POST[m_key]}";
        //echo $que;
        $res = setQry($que);
        if($res){
            $lib->AlertMSG( "정보수정이 완료되었습니다.", "/_adm/?pg=modify&menu=member&M_Key=".$_POST[m_key] , 0, "parent" );
        }
        break;
};

switch($mode){
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
            $que .= "M_ShopParentID = '{$shopParentId}', ";
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
}
?>