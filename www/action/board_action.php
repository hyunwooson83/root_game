<?php

    include $_SERVER['DOCUMENT_ROOT']."/include/common.php";

  switch( $_POST['HAF_Value_0'] ) {
    case "BoardGameResultWrite" :
        //if ( !is_numeric($_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );
        
        // 존재하는 게시판인지 체크
        $lib24c->Check_Board( 'betting' );

        // 게시물 넘버링 설정
        $result = $db->Execute('select MAX(B_No) as MaxNumber from board where B_ID=?', array('betting'));
        $NumberRow = $result->FetchRow();

        // 게시물 등록 처리
        $record = Null;
        $record["M_Key"] 		= $lib24c->member_info['M_Key'];
        $record["B_ID"] 		= 'betting';
        $record["B_No"] 		= $NumberRow['MaxNumber'] + 1;
        $record["B_Subject"] 	= $lib24c->member_info['M_NICK']."님의 배팅내역입니다. ";
        $record["B_Content"] 	= '';
        $record["B_BG_Key"] 	= $_POST['HAF_Value_1'];
        $record["B_Count"] 		= 0;
        $record["B_ReplyCount"] = 0;
        if ( $_POST['HAF_Value_8'] != "" ) $record["B_Type"] = $_POST['HAF_Value_8'];
        else $record["B_Type"] 	= "Normal";
        $record["B_RegDate"] 	= date("Y-m-d H:i:s");
        $record["B_AdminRead"] 	= "N";
        $record["B_Delete"] 	= "N";
        $record["B_State"] 		= ( $_POST['HAF_Value_5'] ) ? $_POST['HAF_Value_5'] : "Normal";

        $db->AutoExecute("board",$record,'INSERT');
		
        $insertid = $db->Insert_ID();

        $json['flag'] = true;
        $json['bkey'] = $insertid;
        $json['tn'] = 'betting';
        echo json_encode($json);
        //$lib->MovePage( "/mypage/board/board_modify.php?&tn=betting&b_key=".$insertid , 0, "parent" );

      break;
	
		#배팅 내역 삭제
		case "BettingHistoryDel" :
			$wait = 0;
			$que = "SELECT BGL_State FROM buygamelist WHERE BG_Key IN ({$_POST[HAF_Value_1]})";
			//echo $que;
			$arr = getArr($que);
			if(count($arr)>0){
				foreach($arr as $list){
					//echo $list['BGL_State']; 
					if($list['BGL_State']=='Await'){
						$wait++;
					}
				}
			}
			//echo $wait;
			if($wait>0){
				$lib->AlertMSG("진행중인 경기가 있어서 배팅내역을 삭제할 수 없습니다..","/mypage/betting_history.php", 0, "parent");
			} else {
				$que = "UPDATE buygame SET BG_Visible = '' WHERE BG_Key IN ({$_POST[HAF_Value_1]}) ";
				//echo $que;
				$res = setQry($que);
				
				if($res){
					$lib->AlertMSG("배팅내역이 삭제되었습니다.","/mypage/betting_history.php", 0, "parent");
				}
			}

      break;
	  
	  
	  #배팅 내역 선택 삭제
		case "BettingHistoryAllDel" :
			$wait = 0;
			$que = "SELECT BGL_State FROM buygamelist WHERE BG_Key IN ({$_POST[HAF_Value_1]})";
			//echo $que;
			$arr = getArr($que);
			if(count($arr)>0){
				foreach($arr as $list){
					//echo $list['BGL_State']; 
					if($list['BGL_State']=='Await'){
						$wait++;
					}
				}
			}
			//echo $wait;
			if($wait>0){
				$lib->AlertMSG("진행중인 경기가 있어서 배팅내역을 삭제할 수 없습니다..","/mypage/betting_history.php", 0, "parent");
			} else {
				$que = "UPDATE buygame SET BG_Visible = '' WHERE BG_Key IN ({$_POST[HAF_Value_1]}) ";
				//echo $que;
				$res = setQry($que);
				
				if($res){
					$lib->AlertMSG("배팅내역이 삭제되었습니다.","/mypage/betting_history.php", 0, "parent");
				}
			}		

      break;
	  
	  
    case "BoardReplyWrite" :
        if ( !is_numeric($_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 존재하는 게시물인지 체크
        $result = $db->Execute('select * from board where B_Key=?', array($_POST[HAF_Value_1]));
        if ( $result->RecordCount() != 1 ) $lib->AlertBack( "존재하지 않는 게시물 입니다." );

        // 덧글 등록
        $record = Null;
        $record[B_Key] = $_POST[HAF_Value_1];
		if($_SESSION['S_Admin']=='Y'){
			$ran = getRow("SELECT M_Key FROM members WHERE M_Type='2' ORDER BY RAND() LIMIT 2");
			$record["M_Key"] 			= $ran[M_Key];
		} else {
        	$record["M_Key"] 			= $lib24c->member_info[M_Key];
		}
        //$record[M_Key] = $lib24c->member_info[M_Key];
        $record[BR_Content] = stripslashes($_POST[HAF_Value_2]);
        $record[BR_Delete] = "N";
        $record[BR_RegDate] = date("Y-m-d H:i:s");
        $db->AutoExecute("boardreply",$record,'INSERT');

        // 메인 게시물 리플 카운트 + 1
        $row = $lib24c->Get_Board_Read( $_POST[HAF_Value_1] );
        $record = Null;
        $record["B_ReplyCount"] = $row[B_ReplyCount] + 1;
        $where = "B_Key = " . $_POST[HAF_Value_1];
        $db->AutoExecute("board",$record,'UPDATE',$where);

        $result = $db->Execute("select * from boardreply where BR_RegDate like '".date("Y-m-d")."%' and BR_Delete='N' and M_Key=?", $lib24c->member_info[M_Key]);
		$result1 = $db->Execute("select reply from pointconfig where level={$_SESSION[S_Level]}");
		$NumberRow = $result1->FetchRow();
		$mkeytemp=$lib24c->member_info[M_Key];
		#하루최대 포인트 확인 하기
		
		if($lib24c->member_info[M_Admin] != 'Y' && $result->RecordCount() <= $NumberRow[0]){
			$pi = getRow("SELECT SUM(PI_Point) FROM pointinfo WHERE M_Key = {$_SESSION[S_Key]} AND PI_Type IN ('BoardWrite','Reply') AND DATE_FORMAT(PI_RegDate,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')");
			if($pi[0]<=300){
				$lib24c->Payment_Point($mkeytemp,"Reply");
			}
		}

        $lib->ReloadPage( "parent" );
      break;

    case "BoardReplyDelete" :
        // 덧글 권한 체크
        if ( $lib24c->Check_Auth_BoardReply( $_POST[HAF_Value_2] ) != "Y" ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

       $sql = "SELECT * FROM members a, pointconfig b WHERE a.M_Key = {$_SESSION[S_Key]} AND a.M_Level = b.level";
	   $row = getRow($sql);
	   
	   if($row[M_Point]>abs($row[replyd])){
		
			$que = "DELETE FROM boardreply WHERE BR_Key = {$_POST[HAF_Value_2]}";		
			//echo $que;
			$res = setQry($que);
			if($res){
				$lib24c->Payment_Point($_SESSION[S_Key],"ReplyDelete");
			}
	   }


       $lib->ReloadPage( "parent" );
      break;

    case "BoardModify" :
        //if ( $_POST[HAF_Value_1] == "betting" ) $lib->AlertBack( "수정할 수 없는 게시판입니다." );

        // 게시물 권한 체크
        //if ( $lib24c->Check_Auth_Board( $_POST[HAF_Value_4] ) != "Y" ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        // 팝업공지 최대 카운트는 6
        if ( $_POST['HAF_Value_8'] == 'Alert' ) {
          $result = $db->Execute("select count(B_Key) as CNT from board where B_Type='Alert' and B_ID='board' and B_Delete='N'");
          $NumberRow = $result->FetchRow();
          if ( $NumberRow['CNT'] > 5 ) $lib->AlertMSG( "팝업 공지는 최대 6개까지 할 수 있습니다." );
        };

        // 게시물 수정
        $record = Null;
        $record["B_Subject"] = $_POST['HAF_Value_2'];
        $record["B_Content"] = stripslashes($_POST['HAF_Value_3']);
        $record["B_Category"] = $_POST['HAF_Value_9'];

        if ( $_POST['HAF_Value_8'] != "" ) $record["B_Type"] = $_POST['HAF_Value_8'];
        $where = "B_Key = " . $_POST['HAF_Value_4'];
        $db->AutoExecute("board",$record,'UPDATE',$where);


        $lib->MovePage( "/mypage/{$_POST['HAF_Value_1']}/view/?".$_POST['HAF_Value_5'], 0, "parent" );


      break;

    case "BoardDelete" :
        // 게시물 권한 체크
        if ( $lib24c->Check_Auth_Board( $_POST['HAF_Value_1'] ) != "Y" ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        // 게시물 삭제
        $record = Null;
        $record["B_Delete"] = "Y";
        $where = "B_Key = " . $_POST['HAF_Value_1'];
        $db->AutoExecute("board",$record,'UPDATE',$where);
		if($_POST['HAF_Value_3'] == "Admin"){
			$result2 = $db->Execute("select * from board where B_Key=$_POST[HAF_Value_1]");
			$NumberRow1 = $result2->FetchRow();
			$mkeytemp=$NumberRow1['M_Key'];
		} else {
			$mkeytemp=$lib24c->member_info['M_Key'];
		}
        $result = $db->Execute("select * from board where B_RegDate like '".date("Y-m-d")."%' and B_Delete='N' and M_Key=?", $lib24c->member_info['M_Key']);
		$result1 = $db->Execute("select BoardWrite from pointconfig where level={$_SESSION['S_Level']}");
		$NumberRow = $result1->FetchRow();

		if($lib24c->member_info['M_Admin'] != 'Y') {
			
			$sql = "SELECT * FROM members a, pointconfig b WHERE a.M_Key = {$_SESSION['S_Key']} AND a.M_Level = b.level";
			$row = getRow($sql);
			
			if($row['M_Point']>abs($row['BoardDelete'])){
				if($result->RecordCount() < $NumberRow[0]) $lib24c->Payment_Point($mkeytemp,"BoardDelete");
			}
		} else {
			if($lib24c->member_info['M_Key'] != $mkeytemp) $lib24c->Payment_Point($mkeytemp,"BoardDelete");
		}

		
        $lib->MovePage( "/mypage/".$_POST['HAF_Value_2']."/?tn=".$_POST['HAF_Value_2'], 0, "parent" );
      break;

		#고객센터 게시물 삭제
		case "BoardDelete1" :
        // 게시물 권한 체크
        if ( $lib24c->Check_Auth_Board( $_POST[HAF_Value_1] ) != "Y" ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

        // 게시물 삭제
        $record = Null;
        $record["B_Delete"] = "Y";
        $where = "B_Key = " . $_POST[HAF_Value_1];
        $db->AutoExecute("board",$record,'UPDATE',$where);
		if($_POST[HAF_Value_3] == "Admin"){
			$result2 = $db->Execute("select * from board where B_Key=$_POST[HAF_Value_1]");
			$NumberRow1 = $result2->FetchRow();
			$mkeytemp=$NumberRow1[M_Key];
		} else {
			$mkeytemp=$lib24c->member_info[M_Key];
		}
        $result = $db->Execute("select * from board where B_RegDate like '".date("Y-m-d")."%' and B_Delete='N' and M_Key=?", $lib24c->member_info[M_Key]);
       
		$lib->MovePage( "/board/board_list2.php?tn=customer", 0, "parent" );
      break;
	  
	  #고객센터 게시물 삭제
		case "BoardDelete2" :
		// 게시물 권한 체크
		if ( $lib24c->Check_Auth_Board( $_POST[HAF_Value_1] ) != "Y" ) $lib->AlertBack( "정상적인 접속이 아닙니다." );
	
		// 게시물 삭제
		$record = Null;
		$record["B_Delete"] = "Y";
		$where = "B_Key = " . $_POST[HAF_Value_1];
		$db->AutoExecute("board",$record,'UPDATE',$where);
		if($_POST[HAF_Value_3] == "Admin"){
			$result2 = $db->Execute("select * from board where B_Key=$_POST[HAF_Value_1]");
			$NumberRow1 = $result2->FetchRow();
			$mkeytemp=$NumberRow1[M_Key];
		} else {
			$mkeytemp=$lib24c->member_info[M_Key];
		}
		$result = $db->Execute("select * from board where B_RegDate like '".date("Y-m-d")."%' and B_Delete='N' and M_Key=?", $lib24c->member_info[M_Key]);
		$result1 = $db->Execute("select BoardWrite from pointconfig where level={$_SESSION[S_Level]}");
		$NumberRow = $result1->FetchRow();
	
      $lib->MovePage( "/_adm/?pg=notice_list&menu=board&tn=notice", 0, "parent" );
      break;
	  
	#게시물 작성 
    case "BoardWrite" :
        // 존재하는 게시판인지 체크
        $lib24c->Check_Board( $_POST['HAF_Value_1'] );
        //if ( $_POST[HAF_Value_1] == "betting" ) $lib->AlertBack( "직접 등록할 수 없는 게시판입니다." );

        // 팝업공지 최대 카운트는 6
        if ( $_POST['HAF_Value_8'] == 'Alert' ) {
          $result = $db->Execute("select count(B_Key) as CNT from board where B_Type='Alert' and B_ID='board' and B_Delete='N'");
          $NumberRow = $result->FetchRow();
          if ( $NumberRow['CNT'] > 5 ) $lib->AlertMSG( "팝업 공지는 최대 6개까지 할 수 있습니다." );
        };
		
		$subject = strip_tags($_POST['HAF_Value_2']);
		if($subject==''){
			$lib->AlertMSG( "제목이 등록되지 않았습니다. 다시 입력해주세요." );
		}
		
		#url입력금지
		$rex='/(http|https|ftp|mms):\/\/[0-9a-z-]+(\.[\xa1-\xfe_0-9a-z-]+)+/';
		preg_match_all($rex,$subject,$url);
		
		$cnt = 0;
		print_r($url);
		for($i=0;$i<count($url);$i++){	
			if($url[$i][0]!=''){
				$cnt++;
			}
		}
		if($cnt>0){
			$lib->AlertMSG( "내용에 url을 입력하시면 안됩니다." );
		}
		
		
        // 게시물 넘버링 설정
        $result = $db->Execute('select MAX(B_No) as MaxNumber from board where B_ID=?', array($_POST['HAF_Value_1']));
        $NumberRow = $result->FetchRow();

        // 게시물 등록 처리
        $record = Null;
        if($_SESSION['S_Admin']=='Y'){
			$ran = getRow("SELECT M_Key FROM members WHERE M_Type='2' ORDER BY RAND() LIMIT 2");
			$record["M_Key"] 			= $ran['M_Key'];
		} else {
        	$record["M_Key"] 			= $lib24c->member_info['M_Key'];
		}
		
        $record["B_ID"] 			= $_POST['HAF_Value_1'];
        $record["B_No"] 			= $NumberRow['MaxNumber'] + 1;
        if ( $_POST['HAF_Value_5'] != "" )  $record['B_Category'] = $_POST['HAF_Value_5'];
        $record["B_Subject"] 		= mysql_real_escape_string($subject);
        $record["B_Content"] 		= mysql_real_escape_string($_POST['HAF_Value_3']);
        $record["B_Count"] = 0;
        $record["B_ReplyCount"] = 0;
        if ( $_POST['HAF_Value_8'] != "" ) $record["B_Type"] = $_POST['HAF_Value_8'];
        else $record["B_Type"] = "Normal";
        $record["B_RegDate"] = date("Y-m-d H:i:s");
		if($_POST['HAF_Value_5']=='2'){
			$record["B_AdminRead"] = "Y";
		} else {
        	$record["B_AdminRead"] = "N";
		}
		$record["B_Delete"] = "N";
		$record["B_IP"] = $_SERVER['REMOTE_ADDR'];
        $record["B_State"] = ( $_POST['HAF_Value_5'] ) ? $_POST['HAF_Value_5'] : "Normal";
		
		

        $db->AutoExecute("board",$record,'INSERT');

        $insertid = $db->Insert_ID();

#계좌문의일 경우
		if($_POST['HAF_Value_5']=='2'){
			$account = getRow("SELECT * FROM siteconfig WHERE idx=1");
			$answer = "{$account['I_BankName']} {$account['I_BankNum']} {$account['I_BankOwner']} <br> ";
			$answer .= "타인명의 입금 절대 불가 합니다. <br>";
			$answer .= "수표 입금 절대 불가 합니다. <br>";
			$answer .= "입금후에 충전신청 부탁드립니다. <br>";			
			$answer .= "감사합니다.  <br>";
			
			$sql = "UPDATE board SET B_Answer = '{$answer}', B_AdminRead = 'Y', B_Delete = 'N', B_Popup_YN = 'y' WHERE B_Key = {$insertid}";
			setQry($sql);
		}

        $result = $db->Execute("select * from board where B_RegDate like '".date("Y-m-d")."%' and B_Delete='N' and M_Key=?", $lib24c->member_info['M_Key']);
		$result1 = $db->Execute("select BoardWrite from pointconfig where level={$_SESSION['S_Level']}");
		$NumberRow = $result1->FetchRow();
		if($lib24c->member_info['M_Admin'] != 'Y'  && $result->RecordCount() <= $NumberRow[0]){
						$mkeytemp=$lib24c->member_info['M_Key'];
			if($_POST['HAF_Value_1']!='customer'){
				$pi = getRow("SELECT SUM(PI_Point) FROM pointinfo WHERE M_Key = {$_SESSION['S_Key']} AND PI_Type IN ('BoardWrite','Reply') AND DATE_FORMAT(PI_RegDate,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')");
				if($pi[0]<=300){
					$lib24c->Payment_Point($mkeytemp,"BoardWrite");
				}
				//$lib24c->Payment_Point($mkeytemp,"BoardWrite");
			}
		}


        $lib->MovePage( "/mypage/".$_POST['HAF_Value_1']."/?tn=".$_POST['HAF_Value_1'] , 0, "parent" );


      break;
	  
	  
	  #작업용 게시물 작성
	  case "BoardWriteTest" :
        // 존재하는 게시판인지 체크
        $lib24c->Check_Board( $_POST[HAF_Value_1] );
        
		$nick = array('씨크릿벌바','아찌','준사마','이런된장','underman','아다폭격기','아이루카','오랄비','최감삼성','똥맨','룸방매니아','유쮸쮸','아찌','밝히는소년','이돼호','sss','광동화탕','preemx','이대호짱','토토따서주식','메시','장재인');
		
		
		shuffle($nick);
		$n = rand(0,22);
		$row = getRow("SELECT M_Key FROM members WHERE M_NICK = '".$nick[$n]."'");
		
		
        // 게시물 넘버링 설정
        $result = $db->Execute('select MAX(B_No) as MaxNumber from board where B_ID=?', array($_POST[HAF_Value_1]));
        $NumberRow = $result->FetchRow();

        // 게시물 등록 처리
        $record = Null;
        $record["M_Key"] 		= $row[M_Key];
        $record["B_ID"] 		= $_POST[HAF_Value_1];
        $record["B_No"] 		= $NumberRow[MaxNumber] + 1;
        $record["B_Subject"] 	= mysql_real_escape_string($_POST[HAF_Value_2]);
        $record["B_Content"] 	= mysql_real_escape_string($_POST[HAF_Value_3]);
        $record["B_Count"] 		= 0;
        $record["B_ReplyCount"] = 0;
        if ( $_POST[HAF_Value_8] != "" ) $record["B_Type"] = $_POST[HAF_Value_8];
        else $record["B_Type"] 	= "Normal";
        $record["B_RegDate"] 	= date("Y-m-d H:i:s");
        $record["B_AdminRead"] 	= "N";
        $record["B_Delete"] 	= "N";
        $record["B_State"] 		= ( $_POST[HAF_Value_5] ) ? $_POST[HAF_Value_5] : "Normal";

        $db->AutoExecute("board",$record,'INSERT');
        $insertid = $db->Insert_ID();

        $result = $db->Execute("select * from board where B_RegDate like '".date("Y-m-d")."%' and B_Delete='N' and M_Key=?", $lib24c->member_info[M_Key]);
		$result1 = $db->Execute("select BoardWrite from pointconfig where level=99");
		$NumberRow = $result1->FetchRow();
		if($lib24c->member_info[M_Admin] != 'Y'  && $result->RecordCount() <= $NumberRow[0]){
			$mkeytemp=$lib24c->member_info[M_Key];
			$lib24c->Payment_Point($mkeytemp,"BoardWrite");
		}

        if ( $_POST[HAF_Value_6] != "Admin" ) $lib->MovePage( "/board/board_view.php?tn=".$_POST[HAF_Value_1]."&b_key=".$insertid , 0, "parent" );
        else $lib->MovePage( "/_adm/?pg=view&menu=board&tn=".$_POST[HAF_Value_1]."&b_key=".$insertid , 0, "parent" );

      break;
	  
	  
	  
	  case "movieDel";	  
	  	for($i=0;$i<count($_POST[idx]);$i++){
			setQry("DELETE FROM movie WHERE B_Key = {$_POST[idx][$i]}");
			move("/board/movie.php");
		}
	  break;
  };
?>