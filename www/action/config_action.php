<? include "../include/common.php"; ?>
<?
	//echo $_POST[HAF_Value_0];
  // 관리자 권한 체크
  if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

  switch( $_POST[HAF_Value_0] ) {
    case "BankUpdate" :
	
		$que  = "UPDATE siteconfig SET ";
		$que .= "I_BankName 		= '{$_POST[HAF_Value_1]}', ";
		$que .= "I_BankNum 			= '{$_POST[HAF_Value_2]}', ";
		$que .= "I_BankOwner 		= '{$_POST[HAF_Value_3]}', ";
		
		/*$que .= "S_BankName 		= '{$_POST[HAF_Value_4]}', ";
		$que .= "S_BankNum 			= '{$_POST[HAF_Value_5]}', ";
		$que .= "S_BankOwner 		= '{$_POST[HAF_Value_6]}', ";*/
		
		$que .= "Show_Join_Btn_YN 	= '{$_POST[HAF_Value_7]}', ";
		$que .= "Chu_Bet_Fail_YN 	= '{$_POST[HAF_Value_8]}', ";
		$que .= "Chu_Bet_YN 		= '{$_POST[HAF_Value_9]}' ";
		$que .= " WHERE idx = 1 ";
		//echo $que;
		$res = setQry($que);
		if($res){
			$lib->ReloadPage( "parent" );
		}
		
        /*// 은행정보 수정
        $record = Null;
        $record[I_BankName]   		= $_POST[HAF_Value_1];
        $record[I_BankNum] 			= $_POST[HAF_Value_2];
        $record[I_BankOwner]  		= $_POST[HAF_Value_3];
		
		$record[S_BankName]   		= $_POST[HAF_Value_4];
        $record[S_BankNum] 			= $_POST[HAF_Value_5];
        $record[S_BankOwner]  		= $_POST[HAF_Value_6];
		
		
        $record[Show_Join_Btn_YN]  	= $_POST[HAF_Value_7];
        $record[Chu_Bet_Fail_YN]  	= $_POST[HAF_Value_8];
        $record[Chu_Bet_YN]  		= $_POST[HAF_Value_9];
		
		
        $where = "1";
        $db->AutoExecute("siteconfig",$record,'UPDATE', $where);

        $lib->ReloadPage( "parent" );*/
      break;

    case "PointUpdate" :
        // 포인트정보 수정
        $record = Null;
        $record[MemberJoin]   	= $_POST[HAF_Value_1];
        $record[RecJoin]      	= $_POST[HAF_Value_2];
        $record[Betting]      	= $_POST[HAF_Value_3];
        $record[RecBetting]   	= $_POST[HAF_Value_4];
		$record[ChuBetting]   	= $_POST[HAF_Value_13];
        $record[BoardWrite]   	= $_POST[HAF_Value_5];
        $record[BoardDelete]  	= $_POST[HAF_Value_6];
        $record[Charge]       	= $_POST[HAF_Value_7];
        $record[BettingFail]  	= $_POST[HAF_Value_8];
		$record[reply]  		= $_POST[HAF_Value_9];
		$record[replyd]  		= $_POST[HAF_Value_10];
		$record[Maxp]  			= $_POST[HAF_Value_12];
        $where = "level='".$_POST[HAF_Value_11]."'";
        $db->AutoExecute("pointconfig",$record,'UPDATE', $where);
        $lib->ReloadPage( "parent" );
      break;

    case "LiveTVWrite" :
        // LiveTV 주소 등록
        $record = Null;
        $record[G_Key]      = $_POST[HAF_Value_1];
        $record[L_Link]     = $_POST[HAF_Value_2];
        $record[L_State]    = 'Normal';
        $record[L_RegDate]  = date("Y-m-d H:i:s");
        $db->AutoExecute("livetv",$record,'INSERT');

        echo "<script> parent.opener.location.reload();</script>";
        $lib->AlertMSGClose("LiveTV 주소를 등록하였습니다.", "parent");
      break;

    case "LiveTVModify" :
        // LiveTV 주소 수정
        $record = Null;
        $record[G_Key]      = $_POST[HAF_Value_1];
        $record[L_Link]     = $_POST[HAF_Value_2];
        $record[L_RegDate]  = date("Y-m-d H:i:s");
        $where = "L_Key=".$_POST[HAF_Value_3];
        $db->AutoExecute("livetv",$record,'UPDATE', $where);

        echo "<script> parent.opener.location.reload();</script>";
        $lib->AlertMSGClose("LiveTV 주소를 수정하였습니다.", "parent");
      break;

    case "LiveTVDelete" :
        // LiveTV 주소 수정
        $record = Null;
        $record[L_State ] = "Delete";
        $where = "L_Key=".$_POST[HAF_Value_1];
        $db->AutoExecute("livetv",$record,'UPDATE', $where);

        $lib->ReloadPage( "parent" );
      break;
      
    case "IPBAN" :
    	$db->Execute("delete from ipbans");
    	$record = Null;
    	$record[seq] = 1;
    	$record[banlist] = $_POST[HAF_Value_1];
    	$db->AutoExecute("ipbans",$record,'INSERT');

    	$lib->ReloadPage( "parent" );
    	break;
			
	case "write":
		$ip = $_POST[ip1].".".$_POST[ip2].".".$_POST[ip3].".".$_POST[ip4];
		$que = "INSERT INTO aib SET ip = '{$ip}', regDate = NOW() ";
		echo $que;
		$res = setQry($que);		
		if($res){
			$lib->ReloadPage( "parent" );		
		}
	break;	
	
	case 'del':
		$que = "DELETE FROM aib WHERE idx = {$_POST[HAF_Value_1]}";
		echo $que;
		$res = setQry($que);		
		if($res){
			$lib->ReloadPage( "parent" );		
		}
	break;

  };
?>
