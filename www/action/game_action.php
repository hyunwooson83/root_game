<? include "../include/common.php"; ?>
<?

  switch( $_POST[HAF_Value_0] ) {
  	case "DeleteLeague" :
  		if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertMSG( "정상적인 접속이 아닙니다.", "", 0, "parent");
  		
  		$db->Execute("UPDATE gameleague SET GL_State='Delete' WHERE GL_Key=".$_POST[HAF_Value_1]);
        echo "<script> parent.location.reload();</script>";
        $lib->AlertMSG( "삭제 되었습니다." );
  		break;
    case "GameSettlementofAccount" :
        // 관리자 권한 체크
        if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertMSG( "정상적인 접속이 아닙니다.", "", 0, "parent");

        if ( !is_numeric($_POST[HAF_Value_1]) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 정상적인 게임인지 체크
        $result = $db->Execute('select * from gamelist where G_Key=?', array( $_POST[HAF_Value_1]));
        if ( $result->RecordCount() != 1 ) $lib->AlertMSG( "게임이 존재하지 않습니다." );

        // 해당 게임이 정상적으로 종료가 되었는지 체크 ( 게임 중지 상태 , 결과 입력 여부 )
        $ori_row = $result->FetchRow();
		
		//print_r($ori_row);
        if( $ori_row[G_State] != 'Stop' || $ori_row[G_ResultScoreWin ] == null || $ori_row[G_ResultScoreLose ] == null ) $lib->AlertMSG( "게임이 진행중이거나, 경기결과가 입력되지 않았습니다." );

        // 구매자 경기 결과 업데이트
        $result = $db->Execute("select * from buygamelist where G_Key=?", array( $ori_row[G_Key] ) );
        $update_buygame_success = "";
        $update_buygame_fail    = "";
        $update_bg_key          = "";
        $update_bg_key_by_fail  = "";
        if ( $result ) {
          while ($rows = $result->FetchRow()) {
            if ( $rows[BGL_State] == 'Cancel' || $rows[BGL_ResultChoice] == $ori_row[G_ResultWDL] || $rows[BGL_ResultChoice] == $ori_row[G_ResultUnderOver] || $rows[BGL_ResultChoice] == $ori_row[G_ResultOddEven] || $rows[BGL_ResultChoice] == $ori_row[G_ResultHandicap] )
              $update_buygame_success .= $rows[BGL_Key].", ";
          	else {
              $update_buygame_fail .= $rows[BGL_Key].", ";
              $update_bg_key_by_fail .= $rows[BG_Key].", ";
            };

            $update_bg_key .= $rows[BG_Key].", ";
          };
        };

        $update_buygame_success = trim(substr( $update_buygame_success, 0, -2 ));
        $update_buygame_fail    = trim(substr( $update_buygame_fail, 0, -2 ));
        $update_bg_key          = trim(substr( $update_bg_key, 0, -2 ));
        $update_bg_key_by_fail  = trim(substr( $update_bg_key_by_fail, 0, -2 ));

        // 적중 게임 업데이트
        if ( $update_buygame_success != "" ) {
          $record = null;
          $record[BGL_State] = 'Success';
          $where = "BGL_Key in (".$update_buygame_success.")";
          $db->AutoExecute("buygamelist",$record,'UPDATE', $where);
        };

        // 실패 게임 업데이트
        if ( $update_buygame_fail != "" ) {
          $record = null;
          $record[BGL_State] = 'Fail';
          $where = "BGL_Key in (".$update_buygame_fail.")";
          $db->AutoExecute("buygamelist",$record,'UPDATE', $where);

          $record = null;
          $record[BG_Result] = 'Fail';
          $where = "BG_Key in (".$update_bg_key_by_fail.")";
          $db->AutoExecute("buygame",$record,'UPDATE', $where);

          // 실패 게임 금액의 일정 % 환급
          $result = $db->Execute("select * from buygame where BG_Key in (".$update_bg_key_by_fail.")");
          if( $result) {
            while ( $rows = $result->FetchRow() ) {
              if((int)$lib24c->point_return($rows[M_Key],'BettingFail') > 0)
              	$lib24c->Payment_Point( $rows[M_Key], 'BettingFail', (int)($rows[BG_BettingPrice] * ($lib24c->point_return($rows[M_Key],'BettingFail')) / 100), $rows[BG_Key] );
              	
              if((int)$lib24c->point_info[RecBetting] > 0) {
              	//추천인 베팅
              	$rec = $db->Execute('SELECT M_RecJoin FROM members WHERE M_Key=?', array($rows[M_Key]));
              	if($rec && $rec->RecordCount() > 0)
              	{
              		$rrow = $rec->FetchRow();
              		if($rrow[M_RecJoin] > 0)
              			$lib24c->Payment_Point( $rrow[M_RecJoin], 'RecBetting', (int)($rows[BG_BettingPrice] * ($lib24c->point_return($rrow[M_RecJoin],'RecBetting')) / 100), $rows[BG_Key] );
              	}
              }
            };
          }
        };

        // 구매 게임 결과 카운트 업데이트
        //전체 리스트 싹 뒤져서 갱신
        $result = $db->Execute("SELECT * FROM buygame WHERE BG_Result='Await'");
        if($result) {
        	while($rows = $result->FetchRow()) {
        		//각 buygame 에 소속된 buygamelist 의 상태를 체크한다.
        		$t_BG_Key = $rows[BG_Key];
        		$SQL = "SELECT COUNT(*) AS CNT FROM buygamelist WHERE BG_Key='$t_BG_Key' AND BGL_State!='Await'";
        		$cnt = $db->Execute($SQL);
        		$NumberRow = $cnt->FetchRow();
        		$t_cnt = $NumberRow['CNT'];
        		$db->Execute("UPDATE buygame SET BG_GameCompleteCount='$t_cnt' WHERE BG_Key='$t_BG_Key'");
        	}
        }
        
        // 구매 게임중 모든 정보가 업데이트된 게임 최종 정산
        $result = $db->Execute("select * from buygame where BG_GameCount = BG_GameCompleteCount and BG_Result = 'Await'");
        $update_buygame_complate = "";
        if ( $result ) {
          while ($rows = $result->FetchRow()) {
            $lib24c->Payment_Money( $rows[M_Key], 'Quota', $rows[BG_ForecastPrice] , "", $rows[BG_Key], "" , "" );
          };
        };
        $db->Execute("UPDATE buygame SET BG_Result = 'Success' WHERE BG_GameCount = BG_GameCompleteCount and BG_Result = 'Await'");

        // 게임 정산 완료 처리
        $db->Execute("UPDATE gamelist SET G_State = 'End' WHERE G_Key=?", array($_POST[HAF_Value_1]) );

        echo "<script> location.href='/_adm/?pg=result&menu=game'; alert('정산이 완료 되었습니다.');</script>";

		
      break;

    case "GameResult" :
        // 관리자 권한 체크
        if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertMSG( "정상적인 접속이 아닙니다.", "", 0, "parent");

        if ( !is_numeric($_POST[HAF_Value_1]) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );
        if ( !is_numeric($_POST[HAF_Value_2]) ) $lib->AlertMSG( "점수를 숫자로 입력해주세요." );
        if ( !is_numeric($_POST[HAF_Value_3]) ) $lib->AlertMSG( "점수를 숫자로 입력해주세요." );
        
        // 정상적인 게임인지 체크
        $result = $db->Execute('select * from gamelist where G_Key=?', array($_POST[HAF_Value_1]));
        if ( $result->RecordCount() != 1 ) $lib->AlertMSG( "게임이 존재하지 않습니다." );
        $rows = $result->FetchRow();

        $lib24c->GameResult($_POST[HAF_Value_1],$_POST[HAF_Value_2],$_POST[HAF_Value_3]);

        echo "<script>parent.closePopup();</script>";
       // $lib->AlertMSG( "입력이 완료 되었습니다." );
		
      break;
	  
	  
	######################################################################################################
	###########   적중 특례 ##############################################################################
	case "RefundResult" :
        // 관리자 권한 체크
        if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertMSG( "정상적인 접속이 아닙니다.", "", 0, "parent");

        if ( !is_numeric($_POST[HAF_Value_1]) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );        
        //echo $_POST[HAF_Value_1];
		//echo $_POST[HAF_Value_2];
        // 정상적인 게임인지 체크
        $result = $db->Execute('select * from gamelist where G_Key=?', array($_POST[HAF_Value_1]));
        if ( $result->RecordCount() != 1 ) $lib->AlertMSG( "게임이 존재하지 않습니다." );
        $rows = $result->FetchRow();
		
        $lib24c->refund($_POST[HAF_Value_1],$_POST[HAF_Value_2]);

        echo "<script> parent.location.reload();</script>";
        $lib->AlertMSG( "적중특례로 적용되었습니다." );
      break;
    ######################################################################################################
	
	
	
    case "GameCancel" :
        // 관리자 권한 체크
        if ( $_SESSION['S_Admin'] != 'Y' ) $lib->AlertMSG( "정상적인 접속이 아닙니다.", "", 0, "parent");

        if ( !is_numeric($_POST['HAF_Value_1']) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 정상적인 게임인지 체크
        $result = $db->Execute("select * from gamelist where G_Key=?", array( $_POST['HAF_Value_1']));
        if ( $result->RecordCount() != 1 ) $lib->AlertMSG( "게임이 존재하지 않습니다." );
        $ori_row = $result->FetchRow();

        // 수정 가능 여부 체크
        if( !( $ori_row['G_State'] = 'Await' &&  $ori_row['G_State'] = 'Stop' ) ) $lib->AlertMSG( "이미 정산이 끝난 게임은 취소할 수 없습니다." );

        // 구매자 경기 결과 업데이트
        $result = $db->Execute("select * from buygamelist where G_Key=?", array( $ori_row['G_Key'] ) );
        $update_buygame_success = "";
        $update_buygame_fail    = "";
        $update_bg_key          = "";
        $update_bg_key_by_fail  = "";
        if ( $result ) {
          while ($rows = $result->FetchRow()) {
            $update_buygame_success .= $rows['BGL_Key'].", ";
            $update_bg_key .= $rows['BG_Key'].", ";
          };
        };

        $update_buygame_success = trim(substr( $update_buygame_success, 0, -2 ));
        $update_bg_key          = trim(substr( $update_bg_key, 0, -2 ));
        $update_bg_key_by_fail  = trim(substr( $update_bg_key_by_fail, 0, -2 ));

        // 취소 게임 업데이트 및 배당률 변경
        if ( $update_buygame_success != "" ) {
          $record = null;
          $record['BGL_QuotaWin']       = "1.00";
          $record['BGL_QuotaDraw']      = "1.00";
          $record['BGL_QuotaLose']      = "1.00";
          $record['BGL_QuotaHandiWin']  = "1.00";
          $record['BGL_QuotaHandiLose'] = "1.00";
          $record['BGL_QuotaUnderOver'] = "1.00";
          $record['BGL_QuotaUnder']     = "1.00";
          $record['BGL_QuotaOver']      = "1.00";
          $record['BGL_QuotaOdd']       = "1.00";
          $record['BGL_QuotaLose']      = "1.00";
          $record['BGL_State']          = 'Cancel';
          $where = "BGL_Key in (".$update_buygame_success.")";
          $db->AutoExecute("buygamelist",$record,'UPDATE', $where);
        };

        if ( $update_bg_key != "" ) {
          // 취소 게임 전체 배당률 변경 및 게임 결과 카운트 업데이트
          $tmp_key = explode(",", $update_bg_key);
          for( $i=0 ; $i < count($tmp_key) ; $i++ ) {
            $result = $db->Execute("select * from buygamelist where BG_Key=?", array( trim($tmp_key[$i]) ) );
            $quota = 1;
            while( $bgl_row = $result->FetchRow() ) {
              $quota *= $bgl_row["BGL_Quota".$bgl_row['BGL_ResultChoice']];
            };

            $result = $db->Execute("select * from buygame where BG_Key=?", array( trim($tmp_key[$i]) ) );
            $bg_row = $result->FetchRow();

            $record = null;
            $record['BG_GameCompleteCount'] = $bg_row['BG_GameCompleteCount'] + 1;
            $record['BG_TotalQuota']        = $quota;
            if ( $quota * $bg_row['BG_BettingPrice'] > 3000000 ) $BettingPrice = 3000000;
            else $BettingPrice = $quota * $bg_row['BG_BettingPrice'];
            $record['BG_ForecastPrice']     = $BettingPrice;
            $where = "BG_Key=".trim($tmp_key[$i]);
            $db->AutoExecute("buygame", $record, "UPDATE", $where);
          };
        };

        // 취소 게임중 모든 정보가 업데이트된 게임 최종 정산
        $lib24c->CalcEndGameCount();
        $result = $db->Execute("select * from buygame where BG_GameCount = BG_GameCompleteCount and BG_Result = 'Await'");
        $update_buygame_complate = "";
        if ( $result ) {
          while ($rows = $result->FetchRow()) {
            $lib24c->Payment_Money( $rows['M_Key'], 'Quota', $rows['BG_ForecastPrice'] , "", $rows['BG_Key'], "" , "" );
          };
        };
        $db->Execute("UPDATE buygame SET BG_Result = 'Success' WHERE BG_GameCount = BG_GameCompleteCount and BG_Result = 'Await'");

        // 게임 정산 완료 처리
        $db->Execute("UPDATE gamelist SET G_State = 'Cancel' WHERE G_Key=?", array($_POST['HAF_Value_1']) );

        echo "<script> alert('게임을 취소하였습니다.'); history.back(); </script>";
        //$lib->AlertMSG( "게임을 취소하였습니다." );
      break;

    case "GameModify" :
        // 관리자 권한 체크
        //if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        // 정상적인 게임인지 체크
        $result = $db->Execute('select * from gamelist where G_Key=?', array( $_POST[HAF_Value_16]));
        if ( $result->RecordCount() != 1 ) $lib->AlertMSG( "게임이 존재하지 않습니다." );
        $ori_row = $result->FetchRow();

        // 정상적인 리그인지 체크
        $result = $db->Execute('select * from gameleague where GL_Key=?', array($_POST[HAF_Value_1]));
        if ( $result->RecordCount() != 1 ) $lib->AlertMSG( "리그가 존재하지 않습니다." );

        // 수정 가능 여부 체크
        if( !( $ori_row[G_State] = 'Await' ||  $ori_row[G_State] = 'Stop' ) ) $lib->AlertMSG( "이미 정산이 끝난 게임은 수정할 수 없습니다." );
		
		
		
        // 게임 수정
        $record = Null;
        $record[GL_Key]             = $_POST[HAF_Value_1];
        $record[G_Type1]            = $_POST[HAF_Value_2];
        //$record[G_Type2]            = $_POST[HAF_Value_3];
        $record[G_Datetime]         = $_POST[HAF_Value_4];
        $record[G_Team1]            = $_POST[HAF_Value_5];
        $record[G_Team2]            = $_POST[HAF_Value_6];
        $record[G_QuotaWin]         = $_POST[HAF_Value_7];
        $record[G_QuotaDraw]        = $_POST[HAF_Value_8];
        $record[G_QuotaLose]        = $_POST[HAF_Value_9];
        $record[G_QuotaHandicap]    = $_POST[HAF_Value_10];
        $record[G_QuotaHandiWin]    = $_POST[HAF_Value_17];
        $record[G_QuotaHandiLose]   = $_POST[HAF_Value_18];
        $record[G_QuotaUnderOver]   = $_POST[HAF_Value_11];
        $record[G_QuotaUnder]       = $_POST[HAF_Value_12];
        $record[G_QuotaOver]        = $_POST[HAF_Value_13];
        $record[G_QuotaOdd]         = $_POST[HAF_Value_19];
        $record[G_QuotaEven]        = $_POST[HAF_Value_20];
        $record[G_Notice]           = $_POST[HAF_Value_15];
        $record[G_ResultWDL]        = Null;
        $record[G_ResultUnderOver]  = Null;
        $record[G_ResultOddEven]    = Null;
        $record[G_ResultScoreWin]   = Null;
        $record[G_ResultScoreLose]  = Null;
        $record[G_State]            = $_POST[HAF_Value_14];
        $where = "G_Key=".$_POST[HAF_Value_16];
		
		
        $db->AutoExecute("gamelist",$record,'UPDATE', $where);
		
		
		#경기 수정시 배팅 내역변경을 체크하면#################################################################################
		changeAllBetting($_POST[HAF_Value_1]);
		######################################################################################################################
        echo "<script> parent.parent.location.reload();</script>";
        //$lib->AlertMSGClose("게임을 수정하였습니다.", "parent");
      break;

    case "GameWrite" :
        // 관리자 권한 체크
        if ( $_SESSION[S_Admin] != 'Y' ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        // 정상적인 리그인지 체크
        $result = $db->Execute('select * from gameleague where GL_Key=?', array($_POST[HAF_Value_1]));
        if ( $result->RecordCount() != 1 ) $lib->AlertMSG( "리그가 존재하지 않습니다." );

        //승무패 값이 있으면 승무패 등록
        $record = Null;
        $record[GL_Key]             = $_POST[HAF_Value_1];
        $record[G_Type1]            = $_POST[HAF_Value_2];
        $record[G_Type2]            = $_POST[HAF_Value_3];
        $record[G_Datetime]         = $_POST[HAF_Value_4];
        $record[G_Team1]            = $_POST[HAF_Value_5];
        $record[G_Team2]            = $_POST[HAF_Value_6];
        $record[G_QuotaWin]         = $_POST[HAF_Value_7];
        $record[G_QuotaDraw]        = $_POST[HAF_Value_8];
        $record[G_QuotaLose]        = $_POST[HAF_Value_9];
        $record[G_QuotaHandicap]    = "";
        $record[G_QuotaHandiWin]    = "";
        $record[G_QuotaHandiLose]   = "";
        $record[G_QuotaUnderOver]   = "";
        $record[G_QuotaUnder]       = "";
        $record[G_QuotaOver]        = "";
        $record[G_QuotaOdd]         = "";
        $record[G_QuotaEven]        = "";
        $record[G_Notice]           = $_POST[HAF_Value_15];
        $record[G_ResultWDL]        = Null;
        $record[G_ResultUnderOver]  = Null;
        $record[G_ResultOddEven]    = Null;
        $record[G_ResultScoreWin]   = Null;
        $record[G_ResultScoreLose]  = Null;
        $record[G_State]            = $_POST[HAF_Value_14];
        if($record[G_QuotaWin] != "" && $record[G_QuotaDraw] != "" && $record[G_QuotaLose] != "")
        {
        	$record[G_Type2] = "WDL";
        	//만약 스페셜이면 승무패 등록 안 함
        	if($record[G_Type1] == "Full")
        		$db->AutoExecute("gamelist",$record,'INSERT');
        }
        
        //핸디 값이 있으면 핸디 등록
        $record = Null;
        $record[GL_Key]             = $_POST[HAF_Value_1];
        $record[G_Type1]            = $_POST[HAF_Value_2];
        $record[G_Type2]            = $_POST[HAF_Value_3];
        $record[G_Datetime]         = $_POST[HAF_Value_4];
        $record[G_Team1]            = $_POST[HAF_Value_5];
        $record[G_Team2]            = $_POST[HAF_Value_6];
        $record[G_QuotaWin]         = "";
        $record[G_QuotaDraw]        = "";
        $record[G_QuotaLose]        = "";
        $record[G_QuotaHandicap]    = $_POST[HAF_Value_10];
        $record[G_QuotaHandiWin]    = $_POST[HAF_Value_17];
        $record[G_QuotaHandiLose]   = $_POST[HAF_Value_18];
        $record[G_QuotaUnderOver]   = "";
        $record[G_QuotaUnder]       = "";
        $record[G_QuotaOver]        = "";
        $record[G_QuotaOdd]         = "";
        $record[G_QuotaEven]        = "";
        $record[G_Notice]           = $_POST[HAF_Value_15];
        $record[G_ResultWDL]        = Null;
        $record[G_ResultUnderOver]  = Null;
        $record[G_ResultOddEven]    = Null;
        $record[G_ResultScoreWin]   = Null;
        $record[G_ResultScoreLose]  = Null;
        $record[G_State]            = $_POST[HAF_Value_14];
        if($record[G_QuotaHandicap] != "" && $record[G_QuotaHandiWin] != "" && $record[G_QuotaHandiLose] != "")
        {
        	$record[G_Type2] = "Handicap";
        	$db->AutoExecute("gamelist",$record,'INSERT');
        }
        
        //언더오버 값이 있으면 언더오버 등록
        $record = Null;
        $record[GL_Key]             = $_POST[HAF_Value_1];
        $record[G_Type1]            = $_POST[HAF_Value_2];
        $record[G_Type2]            = $_POST[HAF_Value_3];
        $record[G_Datetime]         = $_POST[HAF_Value_4];
        $record[G_Team1]            = $_POST[HAF_Value_5];
        $record[G_Team2]            = $_POST[HAF_Value_6];
        $record[G_QuotaWin]         = "";
        $record[G_QuotaDraw]        = "";
        $record[G_QuotaLose]        = "";
        $record[G_QuotaHandicap]    = "";
        $record[G_QuotaHandiWin]    = "";
        $record[G_QuotaHandiLose]   = "";
        $record[G_QuotaUnderOver]   = $_POST[HAF_Value_11];
        $record[G_QuotaUnder]       = $_POST[HAF_Value_12];
        $record[G_QuotaOver]        = $_POST[HAF_Value_13];
        $record[G_QuotaOdd]         = "";
        $record[G_QuotaEven]        = "";
        $record[G_Notice]           = $_POST[HAF_Value_15];
        $record[G_ResultWDL]        = Null;
        $record[G_ResultUnderOver]  = Null;
        $record[G_ResultOddEven]    = Null;
        $record[G_ResultScoreWin]   = Null;
        $record[G_ResultScoreLose]  = Null;
        $record[G_State]            = $_POST[HAF_Value_14];
        if($record[G_QuotaUnderOver] != "" && $record[G_QuotaUnder] != "" && $record[G_QuotaOver] != "")
        {
        	$record[G_Type2] = "UnderOver";
        	$db->AutoExecute("gamelist",$record,'INSERT');
        }
        
        //홀짝이 있다면 홀짝 등록
        $record = Null;
        $record[GL_Key]             = $_POST[HAF_Value_1];
        $record[G_Type1]            = $_POST[HAF_Value_2];
        $record[G_Type2]            = $_POST[HAF_Value_3];
        $record[G_Datetime]         = $_POST[HAF_Value_4];
        $record[G_Team1]            = $_POST[HAF_Value_5];
        $record[G_Team2]            = $_POST[HAF_Value_6];
        $record[G_QuotaWin]         = "";
        $record[G_QuotaDraw]        = "";
        $record[G_QuotaLose]        = "";
        $record[G_QuotaHandicap]    = "";
        $record[G_QuotaHandiWin]    = "";
        $record[G_QuotaHandiLose]   = "";
        $record[G_QuotaUnderOver]   = "";
        $record[G_QuotaUnder]       = "";
        $record[G_QuotaOver]        = "";
        $record[G_QuotaOdd]         = $_POST[HAF_Value_19];
        $record[G_QuotaEven]        = $_POST[HAF_Value_20];
        $record[G_Notice]           = $_POST[HAF_Value_15];
        $record[G_ResultWDL]        = Null;
        $record[G_ResultUnderOver]  = Null;
        $record[G_ResultOddEven]    = Null;
        $record[G_ResultScoreWin]   = Null;
        $record[G_ResultScoreLose]  = Null;
        $record[G_State]            = $_POST[HAF_Value_14];
        if($record[G_QuotaOdd] != "" && $record[G_QuotaEven] != "")
        {
        	$record[G_Type1] = "OddEven";
        	$record[G_Type2] = "None";
        	$db->AutoExecute("gamelist",$record,'INSERT');
        }
        
        
        
		
        echo "<script> parent.opener.location.reload();</script>";
        $lib->AlertMSGClose("게임을 등록하였습니다.", "parent");
      break;

    case "GameLeagueModify" :
        if ( !is_numeric($_POST[m_league_key]) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 정상적인 종목인지 체크
        $result = $db->Execute('select * from gameitem where GI_Key=?', array($_POST[m_item]));
        if ( $result->RecordCount() != 1 ) $lib->AlertMSG( "종목이 존재하지 않습니다." );

        // 정상적인 리그인지 체크
        $result = $db->Execute('select * from gameleague where GL_State=\'Normal\' and GL_Key=?', array($_POST[m_league_key]));
        if ( $result->RecordCount() != 1 ) $lib->AlertMSG( "수정할 리그가 존재하지 않습니다." );

        // 등록된 리그인지 체크
        //$result = $db->Execute('select * from gameleague where GL_Type=? and GL_Key!=?', array($_POST[m_league], $_POST[m_league_key]));
        //if ( $result->RecordCount() == 1 ) $lib->AlertMSG( "이미 등록된 리그 입니다." );

        // 리그 수정
		if($_FILES['m_league_img']['name']){
			$ext = explode(".",$_FILES['m_league_img']['name']);
			$file_name = md5(uniqid()).".".$ext[1];
			$res = move_uploaded_file($_FILES['m_league_img']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/images/league/".$file_name);
			
			$record = Null;
			
			if($_FILES['m_league_img']['name']){
				$record[GL_Img] 	= $file_name;
			}
			
		}
		if($_POST['m_item']){
			$record[GI_Key] 	= $_POST['m_item'];
		}
		if($_POST['m_league']){
			$record[GL_Type] 	= $_POST['m_league'];
		}
		
		$where = "GL_Key=".$_POST[m_league_key];
		$db->AutoExecute("gameleague",$record,'UPDATE',$where);
			
		//$lib->AlertMSG( "리그가 수정되었습니다." );
       // $lib->ReloadPage( "parent" );
	   echo "<script>parent.parent.location.reload();</script>";
      break;

    case "GameLeagueWrite" :
        // 정상적인 종목인지 체크
        $result = $db->Execute('select * from gameitem where GI_Key=?', array($_POST['item']));
        if ( $result->RecordCount() != 1 ) $lib->AlertMSG( "등록할 종목이 존재하지 않습니다." );

        // 등록된 리그인지 체크
        $result = $db->Execute('select * from gameleague where GL_State=\'Normal\' and GL_Type=?', array($_POST['league']));
        if ( $result->RecordCount() == 1 ) $lib->AlertMSG( "이미 등록된 리그 입니다." );
		//이미지 업로드
		
		if($_FILES['league_img']['name']){
			$ext = explode(".",$_FILES['league_img']['name']);
			$file_name = md5(uniqid()).".".$ext[1];
			$res = move_uploaded_file($_FILES['league_img']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/images/league/".$file_name);
			
			$record = Null;
			$record[GI_Key] 	= $_POST['item'];
			$record[GL_Type] 	= $_POST['league'];
			$record[GL_State] 	= "Normal";
			$record[GL_Img] 	= $file_name;
			$db->AutoExecute("gameleague",$record,'INSERT');
	
			$lib->ReloadPage( "parent" );
			
		}
        // 리그 등록
		
      break;

    case "GameItemModify" :
        if ( !is_numeric($_POST[m_item_key] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 정상적인 자료인지 체크
        $result = $db->Execute('select * from gameitem where GI_Key=?', array($_POST[m_item_key]));
        if ( $result->RecordCount() != 1 ) $lib->AlertMSG( "수정할 종목이 존재하지 않습니다." );

        // 등록된 종목인지 체크
        $result = $db->Execute('select * from gameitem where GI_Key!=? and GI_Type=?', array($_POST[m_item_key], $_POST[m_item]));
        if ( $result->RecordCount() == 1 ) $lib->AlertMSG( "이미 등록된 종목 입니다." );

        // 종목 수정
        $record = Null;
        $record[GI_Type] = $_POST[m_item];
        if ( $_FILES[m_item_img][tmp_name] ) $record[GI_Image] = $lib->file_manager_upload($_FILES[m_item_img] , FILE_FOLDER );
        $where = "GI_Key=".$_POST[m_item_key];
        $db->AutoExecute("gameitem",$record,'UPDATE',$where);

        $lib->ReloadPage( "parent" );
      break;

    case "GameItemWrite" :
        // 등록된 종목인지 체크
        $result = $db->Execute('select * from gameitem where GI_Type=?', array($_POST[item]));
        if ( $result->RecordCount() == 1 ) $lib->AlertMSG( "이미 등록된 종목 입니다." );

        // 종목 등록
        $record = Null;
        $record[GI_Type] = $_POST[item];
        $record[GI_Image] = ( $_FILES[item_img][tmp_name] ) ? $lib->file_manager_upload($_FILES[item_img] , FILE_FOLDER ) : "";
        $record[GI_State] = "Normal";
        $db->AutoExecute("gameitem",$record,'INSERT');

        $lib->ReloadPage( "parent" );
      break;
      
	case "GameDelete" :
		if ( !is_numeric($_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );
		$G_Key=$_POST[HAF_Value_1];
		$db->Execute('DELETE FROM gamelist WHERE G_Key=? AND G_Key NOT IN (SELECT G_Key FROM buygamelist)', array($G_Key) );
		move('/_adm/?pg=list&menu=game');
      
  };
?>