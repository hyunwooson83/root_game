<?php 
	include "../include/common.php"; 
	
	

	
	
  switch( $_POST['HAF_Value_0'] ) {
    case "AdminRequestRefundOK" :
        // 관리자 권한 체크
        if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        if ( !is_numeric( $_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 환전 요청 정보 확인
        $result = $db->Execute("select * from requests where R_Key=?", array($_POST[HAF_Value_1]));
        if ( $result->RecordCount() != 1 ) $lib->AlertBack( "정상적인 접속이 아닙니다." );
        $row = $result->FetchRow();

        // 환전 요청 상태 확인
        if ( $row[R_State] != 'Await' ) $lib->AlertBack( "이미 처리된 요청 입니다." );

        // 회원 정보 획득
        $mrow = $lib24c->Member_Info( $row[M_Key], 'Y' );

        // 환전 요청 처리
        $record = Null;
        $record[R_State] = 'Done';
		$record['R_ResultDate'] = date("Y-m-d H:i:s");
        $where = "R_Key = " . $_POST[HAF_Value_1];
		
        $db->AutoExecute("requests",$record,'UPDATE', $where);
		
		#회원정보 업데이트
		
		$row = getRow("SELECT R_Money FROM requests WHERE R_Key = {$_POST[HAF_Value_1]}");
		setQry("UPDATE members SET M_Refund_Money = M_Refund_Money+{$row[R_Money]} WHERE M_Key = {$row[M_Key]}");
		
        $lib->ReloadPage("parent");
      break;

    case "AdminRequestChargeOK" :
        // 관리자 권한 체크
        //if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        if ( !is_numeric( $_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 충전 요청 정보 확인
        $result = $db->Execute("select * from requests where R_Key=?", array($_POST[HAF_Value_1]));
        if ( $result->RecordCount() != 1 ) $lib->AlertBack( "정상적인 접속이 아닙니다." );
        $row = $result->FetchRow();

        // 충전 요청 상태 확인
        if ( $row[R_State] != 'Await' ) $lib->AlertBack( "이미 처리된 요청 입니다." );

        // 회원 정보 획득
        $mrow = $lib24c->Member_Info( $row[M_Key], 'Y' );

        // 실제 충전
        if ( $row[R_Type2] == 'Money' ) {
          $lib24c->Payment_Money( $mrow[M_Key], "Charge", $_POST[HAF_Value_2], "", "", $row[R_Key], "" );
          // VIP 회원일 경우 추가 포인트 충전
          if ( $mrow[M_Level] == '1' )
          {
          	if( ((int)0+$lib24c->point_info[Charge]) > 0)
          		$lib24c->Payment_Point( $mrow[M_Key], "Charge", (int)($_POST[HAF_Value_2] * ($lib24c->point_info[Charge]) / 100) ,  "" );
          }
        } else if ( $row[R_Type2] == 'Point' ) {
        	//$lib->AlertBack( "--".$row[R_Point]*-1 );
        	$r_point = $row[R_Point];
        	$r_point = $r_point * -1;
          $p_key = $lib24c->Payment_Point( $mrow[M_Key], "PointConvert", $r_point );
          //$lib->AlertBack( "p_key:".$p_key );
          $lib24c->Payment_Money( $mrow[M_Key], "PointConvert", $row[R_Point], $p_key, "", $row[R_Key], "" );
        };

        // 충전 요청 처리
        $record = Null;
        $record[R_State] 		= 'Done';
		$record[R_Money] 		= $_POST[HAF_Value_2];
		$record[R_ResultDate] 	= date('y-m-d H:i',time());
        $where 					= "R_Key = " . $_POST[HAF_Value_1];
        $db->AutoExecute("requests",$record,'UPDATE', $where);
		
		#회원정보 업데이트
		setQry("UPDATE members SET M_Charge_Money = M_Charge_Money+{$_POST[HAF_Value_2]} WHERE M_Key = {$mrow[M_Key]}");
        
        $lib->ReloadPage("parent");
      break;
	  
    case 'AdminRequestChargeAwait':
		// 관리자 권한 체크
        if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        if ( !is_numeric( $_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 충전 요청 정보 확인
        $result = $db->Execute("select * from requests where R_Key=?", array($_POST[HAF_Value_1]));
        if ( $result->RecordCount() != 1 ) $lib->AlertBack( "정상적인 접속이 아닙니다." );
        $row = $result->FetchRow();
       

        // 회원 정보 획득
        $mrow = $lib24c->Member_Info( $row[M_Key], 'Y' );

        // 실제 충전
        if ( $row[R_Type2] == 'Money' ) {
          $lib24c->Payment_Money( $mrow[M_Key], "Charge", -$row[R_Money], "", "", $row[R_Key], "" );
          // VIP 회원일 경우 추가 포인트 충전
          if ( $mrow[M_Level] == '1' )
          {
          	if( ((int)0+$lib24c->point_info[Charge]) > 0)
          		$lib24c->Payment_Point( $mrow[M_Key], "Await", -(int)($row[R_Money] * ($lib24c->point_info[Charge]) / 100) ,  "Charge" );
          }
        } else if ( $row[R_Type2] == 'Point' ) {
        	//$lib->AlertBack( "--".$row[R_Point]*-1 );
        	$r_point = $row[R_Point];
        	$r_point = $r_point * -1;
          $p_key = $lib24c->Payment_Point( $mrow[M_Key], "PointConvert", $r_point );
          //$lib->AlertBack( "p_key:".$p_key );
          $lib24c->Payment_Money( $mrow[M_Key], "PointConvert", $row[R_Point], $p_key, "", $row[R_Key], "" );
        };

        // 충전 요청 처리
        $record = Null;
        $record[R_State] = 'Await';
		$record[R_ResultDate] = "0000-00-00 00:00:00";
        $where = "R_Key = " . $_POST[HAF_Value_1];
        $db->AutoExecute("requests",$record,'UPDATE', $where);
        
        $lib->ReloadPage("parent");
		
		
	break;
	
	#환전대기처리
	case 'AdminRequestRefundAwait':
		// 관리자 권한 체크
        if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        if ( !is_numeric( $_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 환전 요청 정보 확인
        $result = $db->Execute("select * from requests where R_Key=?", array($_POST[HAF_Value_1]));
        if ( $result->RecordCount() != 1 ) $lib->AlertBack( "정상적인 접속이 아닙니다." );
        $row = $result->FetchRow();

        // 환전 요청 상태 확인
        if ( $row[R_State] != 'Done' ) $lib->AlertBack( "이미 처리된 요청 입니다." );

        // 회원 정보 획득
        $mrow = $lib24c->Member_Info( $row[M_Key], 'Y' );

        // 환전 요청 처리
        $record = Null;
        $record[R_State] = 'Await';
        $where = "R_Key = " . $_POST[HAF_Value_1];
        $db->AutoExecute("requests",$record,'UPDATE', $where);

        $lib->ReloadPage("parent");
		
		
	break;
	
	
    case "AdminRequestChargeDelete" :
        // 관리자 권한 체크
        if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        if ( !is_numeric( $_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );		
		
        //삭제
        $db->Execute("delete from requests where R_Key=?", array($_POST[HAF_Value_1]));

        $lib->ReloadPage("parent");
    break;	 
	  
	  
    #환전 요청 삭제
	case "AdminRequestRefundDelete" :
       // 관리자 권한 체크
        if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        if ( !is_numeric( $_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 충전 요청 정보 확인
        $result = $db->Execute("select * from requests where R_Key=?", array($_POST[HAF_Value_1]));
        if ( $result->RecordCount() != 1 ) $lib->AlertBack( "정상적인 접속이 아닙니다." );
        $row = $result->FetchRow();
       

        // 회원 정보 획득
        $mrow = $lib24c->Member_Info( $row[M_Key], 'Y' );

        // 실제 충전
        if ( $row[R_Type2] == 'Money' ) {
          $lib24c->Payment_Money( $mrow[M_Key], "Charge", $row[R_Money], "", "", $row[R_Key], "" );
          // VIP 회원일 경우 추가 포인트 충전
          if ( $mrow[M_Level] == '1' )
          {
          	if( ((int)0+$lib24c->point_info[Charge]) > 0)
          		$lib24c->Payment_Point( $mrow[M_Key], "Await", (int)($row[R_Money] * ($lib24c->point_info[Charge]) / 100) ,  "Charge" );
          }
        } else if ( $row[R_Type2] == 'Point' ) {
        	$r_point = $row[R_Point];
        	$r_point = $r_point * -1;
            $p_key = $lib24c->Payment_Point( $mrow[M_Key], "PointConvert", $r_point );            
            $lib24c->Payment_Money( $mrow[M_Key], "PointConvert", $row[R_Point], $p_key, "", $row[R_Key], "" );
        };

         //삭제
        $db->Execute("delete from requests where R_Key=?", array($_POST[HAF_Value_1]));
        
        $lib->ReloadPage("parent");
    break;	
	
	
    case "MoneyPlus" :
        // 관리자 권한 체크
        if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        if ( !is_numeric($_POST[HAF_Value_1] ) || !is_numeric($_POST[HAF_Value_2] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 회원 정보 획득
        $row = $lib24c->Member_Info( $_POST[HAF_Value_1], 'Y' );

        // 머니 지급
        $lib24c->Payment_Money( $_POST[HAF_Value_1], "Other", $_POST[HAF_Value_2] , "", "", "" , $_POST[HAF_Value_3] );
        
    	//기록
    	$record = Null;
        $record["M_Key"]   			= $_POST[HAF_Value_1];
        $record["ML_RegDate"]		= date("Y-m-d H:i:s");
        $record["ML_Memo"]		= number_format($_POST[HAF_Value_2]). " 원 지급 - 사유 : ".$_POST[HAF_Value_3];
        $db->AutoExecute("members_log",$record,'INSERT');
        
		
		/*//기록
    	$record = Null;
        $record["M_Key"]   		= $_POST[HAF_Value_1];
		$record["MI_Money"]		= $_POST[HAF_Value_2];
		$record["MI_Memo"]		= "관리자 : ".$_POST[HAF_Value_3];
        $record["MI_RegDate"]	= date("Y-m-d H:i:s");
        $record["MI_Type"]		= "Charge";
        $db->AutoExecute("moneyinfo",$record,'INSERT');*/
		
		
       
		$lib->ReloadPage("parent");
      break;

    case "MoneyMinus" :
        // 관리자 권한 체크
        if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        if ( !is_numeric($_POST[HAF_Value_1] ) || !is_numeric($_POST[HAF_Value_2] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 회원 정보 획득
        $row = $lib24c->Member_Info( $_POST[HAF_Value_1], 'Y' );

        // 머니 차감
        $lib24c->Payment_Money( $_POST[HAF_Value_1], "Other", $_POST[HAF_Value_2]*-1 , "", "", "" , $_POST[HAF_Value_3] );

    	//기록
    	$record = Null;
        $record["M_Key"]   			= $_POST[HAF_Value_1];
        $record["ML_RegDate"]		= date("Y-m-d H:i:s");
        $record["ML_Memo"]		= number_format($_POST[HAF_Value_2]). " 원 차감 - 사유 : ".$_POST[HAF_Value_3];
        $db->AutoExecute("members_log",$record,'INSERT');
		
		
       	$lib->ReloadPage("parent");
      break;

    case "PointPlus" :
        // 관리자 권한 체크
        if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        if ( !is_numeric($_POST[HAF_Value_1] ) || !is_numeric($_POST[HAF_Value_2] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 회원 정보 획득
        $row = $lib24c->Member_Info( $_POST[HAF_Value_1], 'Y' );

        // 포인트 지급
        $lib24c->Payment_Point( $_POST[HAF_Value_1], "Other", $_POST[HAF_Value_2] , $_POST[HAF_Value_3] );
        
    	//기록
    	$record = Null;
        $record["M_Key"]   			= $_POST[HAF_Value_1];
        $record["ML_RegDate"]		= date("Y-m-d H:i:s");
        $record["ML_Memo"]			= number_format($_POST[HAF_Value_2]). " 포인트 지급 - 사유 : ".$_POST[HAF_Value_3];
        $db->AutoExecute("members_log",$record,'INSERT');
        

        $lib->ReloadPage("parent");
      break;

    case "PointMinus" :
        // 관리자 권한 체크
        if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        if ( !is_numeric($_POST[HAF_Value_1] ) || !is_numeric($_POST[HAF_Value_2] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 회원 정보 획득
        $row = $lib24c->Member_Info( $_POST[HAF_Value_1], 'Y' );

        // 포인트 차감
        $lib24c->Payment_Point( $_POST[HAF_Value_1], "Other", $_POST[HAF_Value_2]*-1 , $_POST[HAF_Value_3] );
        
    	//기록
    	$record = Null;
        $record["M_Key"]   			= $_POST[HAF_Value_1];
        $record["ML_RegDate"]		= date("Y-m-d H:i:s");
        $record["ML_Memo"]		= number_format($_POST[HAF_Value_2]). " 포인트 차감 - 사유 : ".$_POST[HAF_Value_3];
        $db->AutoExecute("members_log",$record,'INSERT');

        $lib->ReloadPage("parent");
      break;

    case "RequestMoneyRefund" :
        // 보유금액이 환전금액보다 많은지 확인
        if ( $lib24c->member_info['M_Money'] < $_POST['HAF_Value_1'] ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        //환전 암호가 맞는지 확인
        if ( $lib24c->member_info['M_BankPass'] != $_POST['HAF_Value_2'] ) $lib->AlertMSG( "환전 암호가 다릅니다." );

        // 개인별 넘버링 설정
        $result = $db->Execute('select MAX(R_No) as MaxNumber from requests where R_Type1=? and M_Key=?', array('Refund', $lib24c->member_info['M_Key']));
        $NumberRow = $result->FetchRow();

        // 환전 요청
        $record = Null;
        $record["M_Key"]        = $lib24c->member_info['M_Key'];
        $record["R_No"]         = $NumberRow['MaxNumber'] + 1;
        $record["R_Type1"]      = "Refund";
        $record["R_Type2"]      = "Money";
        $record["R_BankName"]   = $lib24c->member_info['M_BankName'];
        $record["R_BankNumber"] = $lib24c->member_info['M_BankNumber'];
        $record["R_BankOwner"]  = $lib24c->member_info['M_BankOwner'];

        $record["R_Money"]      = $_POST['HAF_Value_1'];

        $record["R_State"]      = "Await";
		$record["R_IP"]      	= $_SERVER['REMOTE_ADDR'];
        $record["R_RegDate"]    = date("Y-m-d H:i:s");

        $db->AutoExecute("requests",$record,'INSERT');

        $r_key = $db->Insert_ID();

        // 환전 처리 ( 선차감 )
        $lib24c->Payment_Money( $lib24c->member_info['M_Key'], "RefundAwait", "-".$_POST['HAF_Value_1'], "", "", $r_key, "" );

        $lib->AlertMSGSwal( "환전 요청을 하였습니다.", "/mypage/refund/" , 0, "parent" );
        //$lib->AlertMSG( "환전 요청을 하였습니다.", "/mypage/exchange.php" , 0, "parent" );
      break;

    case "RequestMoneyChargeCancel" :
        if ( !is_numeric($_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 신청자의 충전 요청 기록 확인
        $result = $db->Execute("select * from requests where R_Key=? and M_Key=?", array($_POST['HAF_Value_1'], $lib24c->member_info['M_Key']));
        if ( $result->RecordCount() < 1 ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );
        $row = $result->FetchRow();

        if ( $row[R_State] != 'Await' ) $lib->AlertMSG( "이미 처리된 요청입니다." );

        // 충전 요청 취소
        $record = Null;
        $record["R_State"]  = "Cancel";
        $where              = "R_Key = ".$_POST['HAF_Value_1']." and M_Key = ".$lib24c->member_info['M_Key'];
        $db->AutoExecute("requests",$record,'UPDATE',$where );

        $lib->ReloadPage("parent");
      break;
      
    case "RequestMoneyChargeDelete":
    	if ( !is_numeric($_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );
        // 충전 요청 삭제
       
		
		$db->Execute("UPDATE requests SET R_Visible = '2' WHERE R_Key=?", array($_POST[HAF_Value_1]));
		
        $lib->ReloadPage("parent");
    	break;

    case "RequestMoneyExchangeDelete":
    	if ( !is_numeric($_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );
        // 환전 요청 삭제
        $record = Null;
        $record["R_Visible"]  = "0";
        $where              = "R_Key = ".$_POST[HAF_Value_1]." and M_Key = ".$lib24c->member_info[M_Key];
        $db->AutoExecute("requests",$record,'UPDATE',$where );

        $lib->ReloadPage("parent");
    	break;

    case "BetHistoryDelete":
    	if ( !is_numeric($_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );
        // 환전 요청 삭제
        $record = Null;
        $record["BG_Visible"]  = "0";
        $where              = "BG_Key = ".$_POST[HAF_Value_1]." and M_Key = ".$lib24c->member_info[M_Key];
        $db->AutoExecute("buygame",$record,'UPDATE',$where );

        $lib->ReloadPage("parent");
    	break;
    	
    case "RequestMoneyCharge" :


		if($_SESSION['S_Key']==''){
			$lib->AlertMSG( "정상적인 접속이 아닙니다." );
		} else {
			if ( !is_numeric($_POST['HAF_Value_2'] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

            $que = "SELECT COUNT(*) FROM requests WHERE M_Key = '{$_SESSION['S_Key']}' AND R_Type1 = 'Charge' AND R_Type2 = 'Money' AND R_State IN ('Await','Ing')";
            $row = getRow($que);
            $already_charge = $row[0];
            if($already_charge>0){
                $lib->AlertMSG( '머니 충전을 요청하신 상태입니다.\n잘못 신청하셨을 경우 충전내역에서 "취소"하신후 다시 충전요청하시기 바랍니다.', "/mypage/charge.php", 0 ,"parent");
            }
	
			// 개인별 넘버링 설정
			$result = $db->Execute('select MAX(R_No) as MaxNumber from requests where R_Type1=? and M_Key=?', array('Charge', $lib24c->member_info['M_Key']));
			$NumberRow = $result->FetchRow();
	
			// 충전 요청 등록
			$record = Null;
			$record["M_Key"]        = $lib24c->member_info['M_Key'];
			$record["R_No"]         = $NumberRow['MaxNumber'] + 1;
			$record["R_Type1"]      = "Charge";
			$record["R_Type2"]      = $_POST['HAF_Value_1'];
	
			if ( $_POST['HAF_Value_1'] == "Money" ) {
				$record["R_Money"]      = $_POST['HAF_Value_2'];
                $record["R_Money_Org"]  = $_POST['HAF_Value_2'];
                $record["R_Bonus"]      = $_POST['HAF_Value_3'];
                $record["R_Rolling"]    = $_POST['HAF_Value_4'];
                $record["R_BankOwner"]    = $_POST['HAF_Value_5'];
				$record["R_State"]      = "Await";
			} else {
				$record["R_Point"] = $_POST['HAF_Value_2'];
				$record["R_State"]      = "Done";
			}
			$record["R_IP"]    = $_SERVER['REMOTE_ADDR'];
			$record["R_RegDate"]    = date("Y-m-d H:i:s");
	
			$db->AutoExecute("requests",$record,'INSERT');
	
	
	
			if( $_POST['HAF_Value_1'] == "Point" ){
				$res = $db->Execute('SELECT M_Point FROM members WHERE M_Key = '.$lib24c->member_info[M_Key]);
				$row = $res->FetchRow();
				if($row[M_Point]<$_POST[HAF_Value_2]){
					$lib->AlertMSG( "잔여 포인트가 부족합니다.", "/mypage/point.php" , 0, "parent" );
				} else {
					$result1 = $db->Execute('select *  from requests where R_No='.($NumberRow[MaxNumber] + 1));
					$keyRow = $result1->FetchRow();
		
					$r_point1 = $_POST['HAF_Value_2'];
					$r_point1 = $r_point1 * -1;
					$p_key1 = $lib24c->Payment_Point( $lib24c->member_info[M_Key], "PointConvert", $r_point1 );
					  //$lib->AlertBack( "p_key:".$p_key );
					$lib24c->Payment_Money( $lib24c->member_info[M_Key], "PointConvert", (int)($_POST[HAF_Value_2]), $p_key1, "", $keyRow[R_Key], "" );
					$lib->AlertMSG( "포인트전환이 완료되었습니다.", "/mypage/point/exchangelist/" , 0, "parent" );
				}
			} else {
				$lib->AlertMSG( "충전 요청을 하였습니다.", "/mypage/charge/" , 0, "parent" );
			}
		}
      break;

      case "RequestMoneyChargeCasino" :
          //print_r($_REQUEST);
          $fail = 0;
          setQry("BEGIN");

          if($_SESSION['S_Key']==''){
              $lib->AlertMSG( "정상적인 접속이 아닙니다." );
          } else {
              if ( !is_numeric($_POST['HAF_Value_2'] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다[money]." );
              $que = "SELECT M_Money FROM members WHERE M_Key = '{$_SESSION['S_Key']}'";
              $mem = getRow($que);

              $sql = "SELECT MAX(R_No) as MaxNumber from requests where R_Type1='{$_POST['HAF_Value_3']}' and M_Key='{$_SESSION['S_Key']}'";
              $NumberRow = getRow($sql);

              $money = $_POST['HAF_Value_2'];
              if($mem['M_Money']<$money){
                  $fail++;
                  $lib->AlertMSG( "보유머니가 부족합니다." );
              }
              $que  = "INSERT INTO requests SET ";
              $que .= "M_Key        = '{$_SESSION['S_Key']}', ";
              $que .= "R_No         = '".($NumberRow['MaxNumber'] + 1)."', ";
              $que .= "R_Type1      = '{$_POST['HAF_Value_3']}', ";
              $que .= "R_Type2      = 'Money', ";
              $que .= "R_Casino_YN  = 'Y', ";
              $que .= "R_Money      = '{$_POST['HAF_Value_2']}', ";
              $que .= "R_State      = 'Done', ";
              $que .= "R_IP         = '{$_SERVER['REMOTE_ADDR']}', ";
              $que .= "R_RegDate    = NOW(), ";
              $que .= "R_ResultDate = NOW() ";
              //echo $que."<br>";
              $res1 = setQry($que);
              if(!$res1){
                  $fail++;
              }

              $rkey = mysql_insert_id();

              if($_POST['HAF_Value_3']=='Charge'){
                  $mi_type = 'CasinoCharge';
                  $mi_memo = '카지노 머니 충전';
              } else {
                  $mi_type = 'CasinoRefund';
                  $mi_memo = '카지노 머니 환전';
              }

              $que = "SELECT M_Money FROM members WHERE M_Key = '{$_SESSION['S_Key']}' ";
              $que_row = getRow($que);

              $que  = "INSERT INTO moneyinfo SET ";
              $que .= "M_Key            = '{$_SESSION['S_Key']}', ";
              $que .= "MI_Type          = '{$mi_type}', ";
              $que .= "R_Key            = '{$rkey}', ";
              $que .= "MI_Money         = '-{$_POST['HAF_Value_2']}', ";
              $que .= "MI_Prev_Money    = '{$que_row['M_Money']}', ";
              $que .= "MI_Memo          = '{$mi_memo}', ";
              $que .= "MI_RegDate       = NOW() ";
              //echo $que."<br>";
              $res = setQry($que);
              if(!$res){
                  $fail++;
              }


              $que = "UPDATE members SET M_Money = M_Money - {$_POST['HAF_Value_2']} WHERE M_Key = '{$_SESSION['S_Key']}'";
              $res_m = setQry($que);
              if(!$res_m){
                  $fail++;
              }
              //echo $fail;

              if($fail==0) {
                  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                  /// 카지노 머니 충전//////////////////////////////////////////////////////////////////////////////////////////////
                  /// /////////////////////////////////////////////////////////////////////////////////////////////////////////////
                  $trid = make_transaction_id($mid);

                  if (empty($row['M_CasinoID'])) {
                      $user_id = make_casino_account();
                      if (!$user_id) {
                          $json['flag'] = false;
                          echo $json['error'] = '카지노 아이디 생성 오류';
                          /*echo json_encode($json);
                          break;*/
                      }
                  } else {
                      $user_id = $row['M_CasinoID'];
                  }


                  if (!empty($trid) && !empty($user_id) && $money > 0) {
                      list($microtime, $timestamp) = explode(' ', microtime());
                      $time = $timestamp . substr($microtime, 2, 3);

                      $private = "C7F4CAD22CFEA245E98A6E790D4F72F0amount={$money}&operatorID=beanpole&time={$time}&transactionID={$trid}&userID={$user_id}&vendorID=0";
                      $hash_code = md5($private);

                      $ch = curl_init(); // 리소스 초기화

                      $url = "http://api.krw.ximaxgames.com/wallet/api/addMemberPoint";

                      // 옵션 설정
                      curl_setopt($ch, CURLOPT_URL, $url);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                      // post 형태로 데이터를 전송할 경우
                      $postdata = array(
                          'amount' => $money
                      , 'operatorID' => 'beanpole'
                      , 'time' => $time
                      , 'transactionID' => $trid
                      , 'userID' => $user_id
                      , 'vendorID' => '0'
                      , 'hash' => $hash_code
                      );
                      curl_setopt($ch, CURLOPT_POST, 1);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
                      $output = curl_exec($ch); // 데이터 요청 후 수신
                      $out = json_decode($output);
                      $res = objectToArray($out);
                      curl_close($ch);  // 리소스 해제

                      //print_r($res);
                      if ($res['returnCode'] == 0) {
                          setQry('COMMIT');
                          echo '<script>parent.rollbackCharge(true);</script>';
                      } else {
                          setQry('ROLLBACK');
                          echo '<script>parent.rollbackCharge(false);</script>';
                      }
                  }
              }
          }

          break;

      case "RequestMoneyRefundCasino" :
          //print_r($_REQUEST);
          $fail = 0;
          setQry("BEGIN");

          if($_SESSION['S_Key']==''){
              $lib->AlertMSG( "정상적인 접속이 아닙니다." );
          } else {
              if ( !is_numeric($_POST['HAF_Value_2'] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다[money]." );

              $sql = "SELECT MAX(R_No) as MaxNumber from requests where R_Type1='{$_POST['HAF_Value_3']}' and M_Key='{$_SESSION['S_Key']}'";
              $NumberRow = getRow($sql);

              $money = $_POST['HAF_Value_2'];

              $que  = "INSERT INTO requests SET ";
              $que .= "M_Key        = '{$_SESSION['S_Key']}', ";
              $que .= "R_No         = '".($NumberRow['MaxNumber'] + 1)."', ";
              $que .= "R_Type1      = '{$_POST['HAF_Value_3']}', ";
              $que .= "R_Type2      = 'Money', ";
              $que .= "R_Casino_YN  = 'Y', ";
              $que .= "R_Money      = '{$_POST['HAF_Value_2']}', ";
              $que .= "R_State      = 'Done', ";
              $que .= "R_IP         = '{$_SERVER['REMOTE_ADDR']}', ";
              $que .= "R_RegDate    = NOW(), ";
              $que .= "R_ResultDate = NOW() ";
              //echo $que."<br>";
              $res1 = setQry($que);
              if(!$res1){
                  $fail++;
              }

              $rkey = mysql_insert_id();

              if($_POST['HAF_Value_3']=='Charge'){
                  $mi_type = 'CasinoCharge';
                  $mi_memo = '카지노 머니 충전';
              } else {
                  $mi_type = 'CasinoRefund';
                  $mi_memo = '카지노 머니 환전';
              }

              $que = "SELECT M_Money FROM members WHERE M_Key = '{$_SESSION['S_Key']}' ";
              $que_row = getRow($que);

              $que  = "INSERT INTO moneyinfo SET ";
              $que .= "M_Key            = '{$_SESSION['S_Key']}', ";
              $que .= "MI_Type          = '{$mi_type}', ";
              $que .= "R_Key            = '{$rkey}', ";
              $que .= "MI_Money         = '{$_POST['HAF_Value_2']}', ";
              $que .= "MI_Prev_Money    = '{$que_row['M_Money']}', ";
              $que .= "MI_Memo          = '{$mi_memo}', ";
              $que .= "MI_RegDate       = NOW() ";
              //echo $que."<br>";
              $res = setQry($que);
              if(!$res){
                  $fail++;
              }


              $que = "UPDATE members SET M_Money = M_Money + {$_POST['HAF_Value_2']} WHERE M_Key = '{$_SESSION['S_Key']}'";
              $res_m = setQry($que);
              if(!$res_m){
                  $fail++;
              }


              //echo $fail;

              if($fail==0) {
                  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                  /// 카지노 머니 환전//////////////////////////////////////////////////////////////////////////////////////////////
                  /// /////////////////////////////////////////////////////////////////////////////////////////////////////////////
                  $trid = make_transaction_id($mid);

                  if (empty($row['M_CasinoID'])) {
                      $user_id = make_casino_account();
                      if (!$user_id) {
                          $json['flag'] = false;
                          $json['error'] = '카지노 아이디 생성 오류';
                          /*echo json_encode($json);
                          break;*/
                      }
                  } else {
                      $user_id = $row['M_CasinoID'];
                  }


                  if (!empty($trid) && !empty($user_id) && $money > 0) {
                      list($microtime, $timestamp) = explode(' ', microtime());
                      $time = $timestamp . substr($microtime, 2, 3);

                      $private = "C7F4CAD22CFEA245E98A6E790D4F72F0amount={$money}&operatorID=beanpole&time={$time}&transactionID={$trid}&userID={$user_id}&vendorID=0";
                      $hash_code = md5($private);

                      $ch = curl_init(); // 리소스 초기화

                      $url = "http://api.krw.ximaxgames.com/wallet/api/subtractMemberPoint";

                      // 옵션 설정
                      curl_setopt($ch, CURLOPT_URL, $url);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                      // post 형태로 데이터를 전송할 경우
                      $postdata = array(
                          'amount' => $money
                      , 'operatorID' => 'beanpole'
                      , 'time' => $time
                      , 'transactionID' => $trid
                      , 'userID' => $user_id
                      , 'vendorID' => '0'
                      , 'hash' => $hash_code
                      );
                      curl_setopt($ch, CURLOPT_POST, 1);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
                      $output = curl_exec($ch); // 데이터 요청 후 수신
                      $out = json_decode($output);
                      $res = objectToArray($out);
                      curl_close($ch);  // 리소스 해제

                      //print_r($res);
                      if ($res['returnCode'] == 0) {
                          setQry('COMMIT');
                          echo '<script>parent.rollbackRefund(true);</script>';
                      } else {
                          setQry('ROLLBACK');
                          echo '<script>parent.rollbackRefund(false);</script>';
                      }
                  }
              }
          }

          break;
  };
?>