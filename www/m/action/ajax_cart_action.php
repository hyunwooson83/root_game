<?php
    include "../include/common.php";

  switch( $_POST['HAF_Value_0'] ) {
    case "BuyCart" :
        setQry("start transaction");
        $json['flag'] = true;
        $json['error'] = "";
        $fail = 0;

        // 로그인 체크
        if ( !$_SESSION['S_Key'] ) $lib->AlertMSG( "정상적인 접속이 아닙니다1.", "", 0, "parent");

        if ( !is_numeric($_POST['HAF_Value_1'] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다2." );

        if ( $lib24c->member_info['M_Money'] < $_POST['HAF_Value_1'] ) $lib->AlertMSG( "보유머니가 배팅할려는 금액보다 적습니다." );
		
		$r = getRow("SELECT M_SportYN, M_Type FROM members WHERE M_Key = {$_SESSION['S_Key']}");
		if($r[0]=='N'){
		    $json['flag'] = false;
		    $json['error'] = '배팅 불가능한 회원입니다.';
            echo json_encode($json);
            break;
        }
		
        // 구매 불가 게임 체크
        $lib24c->Cart_Info( $_POST['HAF_Value_1'],"", "Y");
        
		$count = 0;
        $bs = get_base_bet($_SESSION['S_Key']);


		//동일경기 확인
		/*$que = "SELECT * FROM cartgamelist a, gamelist b, gameleague d WHERE a.M_Key = {$_SESSION['S_Key']} AND a.G_Key = b.G_Key AND b.G_Type2 IN ('Handicap','UnderOver') AND b.GL_key = d.GL_Key_IDX  ";

		$arr = getArr($que);
		if(count($arr)>0){
			foreach($arr as $list){
				$team1[] = $list['G_Team1'];
				$team2[] = $list['G_Team2'];
			}
		}
		
		$today = date("Y-m-d");
		$tomorrow = date("Y-m-d",strtotime("+1 day"));
		$chk = false;
		for($i=0;$i<count($arr);$i++){
			$sql = "SELECT COUNT(*) FROM cartgamelist WHERE M_Key = {$_SESSION['S_Key']} AND G_Team1='{$team1[$i]}' AND G_Team2='{$team2[$i]}' AND G_Datetime BETWEEN '{$today} 00:00:00' AND '{$tomorrow} 23:59:59' ";
			$row = getRow($sql);
			if($row[0]>1){
                $json['flag'] = false;
                $json['error'] = '축구 핸디/언오버 동일경기 배팅이 불가능 합니다.';
                echo json_encode($json);
                break;
			}
		}*/
		
		$gamegubun = "sports";
		
        //장바구니 리스트에서 상한가 계산하기
        $result = $db->Execute("select * from cartgamelist a left join gamelist b on a.G_Key=b.G_Key left join gameitem d on c.GI_Key=d.GI_Key where a.M_Key=? order by a.CGL_RegDate asc", array( $_SESSION['S_Key'] ) );
        if( $result ) {
        	if ( $result->RecordCount() > 0 ) {
        		$batting_quota = 1;
        		while ($rows = $result->FetchRow()) { 
        		
	              switch($rows['CGL_ResultChoice']){
	                case "Win"        : $cgl_result = "승"; 	$cgl_quota = $rows['CGL_QuotaWin']; 		break;
	                case "Draw"       : $cgl_result = "무"; 	$cgl_quota = $rows['CGL_QuotaDraw'];		break;
	                case "Lose"       : $cgl_result = "패"; 	$cgl_quota = $rows['CGL_QuotaLose']; 		break;
	                case "Under"      : $cgl_result = "언더"; 	$cgl_quota = $rows['CGL_QuotaUnder']; 	break;
	                case "Over"       : $cgl_result = "오버"; 	$cgl_quota = $rows['CGL_QuotaOver']; 		break;
	                case "HandiWin"   : $cgl_result = "핸디승";  $cgl_quota = $rows['CGL_QuotaHandiWin']; 	break;
	                case "HandiLose"  : $cgl_result = "핸디패";  $cgl_quota = $rows['CGL_QuotaHandiLose']; break;
	                case "Odd"        : $cgl_result = "홀"; 	$cgl_quota = $rows['CGL_QuotaOdd']; 		break;
	                case "Even"       : $cgl_result = "짝"; 	$cgl_quota = $rows['CGL_QuotaEven']; 		break;
	              };
	
	               //해당 경기가 종료 되었는가 체크
				  $bettime = date("Y-m-d H:i:s",strtotime("+".$SITECONFIG['sport_bet_endtime']." minutes",strtotime(date("Y-m-d H:i:s"))));
	              
              	  if($bettime > $rows['G_Datetime']){
                      $json['flag'] = false;
                      $json['error'] = '경기 시간이 지난 게임은 배팅이 불가능합니다.';
                      echo json_encode($json);
                      break;
                  }
	              
				  if($rows['G_State']!='Await'){
					  $lib->AlertMsg("진행중인 경기만 배팅이 가능합니다.");
				  }
				  
	              $batting_quota *= $cgl_quota;
        		}        		
        		
				$batting_quota = substr($batting_quota,0,6);
				$batting_quota = sprintf('%.2f',$batting_quota);
        		
        		
        	}
        }
        

        // 장바구니에 담긴 리스트 가져오기
        $result = $db->Execute("select * from cartgamelist a left join gamelist b on a.G_Key=b.G_Key left join gameitem d on c.GI_Key=d.GI_Key where a.M_Key=? order by a.CGL_RegDate asc", array( $_SESSION[S_Key] ) );
        if( $result ) {
          if ( $result->RecordCount() > 0 ) {
			//구매경기 등록하기전에 먼저 구매한 목록이 있는지 확인 한다.  
			
			$onebet = $top50 = $top100 = 'N';
			
			
			#구매 경기의 구매 금액이 20만원이 넘는다면 
			if($result->RecordCount()==1 && $_POST['HAF_Value_1']>=200000){
				$onebet = 'Y';
			}			
			
			#당첨금액
			$hit_money = ($batting_quota * $_POST['HAF_Value_1']);
			
			#고액 50
			if($hit_money>499999 && $hit_money <1000001){
				$top50 = 'Y';
			} else #고액 100
			if($hit_money>1000000){
				$top100 = 'Y';
			}
			
			
			#한번더 보유금액을 체크한다,.
			$ssql = "SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}";
			$srow = getRow($ssql);
			if($srow['M_Money']<1){
				$lib->AlertMSG( "보유머니가 부족합니다. 보유머니 충전 후 이용해주세요." );
			}



            // BuyGame 등록
            $record = null;
            $record['M_Key'] 						= $_SESSION['S_Key'];
			$record['M_Type']             		    = $srow['M_Type'];
            $record['BG_GameCount'] 				= $result->RecordCount();
            $record['BG_GameCountCompleteCount'] 	= 0;
            $record['BG_TotalQuota'] 				= 0;
            $record['BG_BettingPrice'] 			    = $_POST['HAF_Value_1'];
			$record['BG_MemberMoney'] 			    = $srow['M_Money'];
            $record['BG_ForecastPrice'] 			= 0;
            $record['BG_Result'] 					= 'Await';
			
			$record['BG_Gubun'] 				    = $gamegubun;
			
			
			
			/*$record[BG_OverBet] 				= $onebet;
			$record[BG_HighBet] 				= $top;*/
            $record['BG_BuyDate'] 				= date("Y-m-d H:i:s");
            $db->AutoExecute("buygame", $record, "INSERT");

            $bg_key = $db->Insert_ID();

            $batting_quota = 1;
            $forecast_price = 0;
            while ($rows = $result->FetchRow()) {
				//스타 언오버 단폴 금지
				//echo $result->RecordCount()."-".$rows[GI_Key]."-".$rows['G_Type2'];
				/*if($result->RecordCount()==1 && $rows[GI_Key]==10 && $rows['G_Type2']=='UnderOver'){
					$lib->AlertMsg("스타는 언오버 단폴 배팅이 불가능합니다.");
				}*/
              $record = null;
              $record['BG_Key']             = $bg_key;
              $record['G_Key']              = $rows['G_Key'];
			  $record['GL_Key']             = $rows['GL_Key_IDX'];
              $record['M_Key']              = $_SESSION['S_Key'];
			  $record['G_Type1']            = $rows['G_Type1'];
			  $record['G_Type2']            = $rows['G_Type2'];
              $record['BGL_QuotaWin']       = $rows['CGL_QuotaWin'];
              $record['BGL_QuotaDraw']      = $rows['CGL_QuotaDraw'];
              $record['BGL_QuotaLose']      = $rows['CGL_QuotaLose'];
			  
              $record['BGL_QuotaHandicap']  = $rows['CGL_QuotaHandicap'];
              $record['BGL_QuotaHandiWin']  = $rows['CGL_QuotaHandiWin'];
              $record['BGL_QuotaHandiLose'] = $rows['CGL_QuotaHandiLose'];
			  
              $record['BGL_QuotaUnderOver'] = $rows['CGL_QuotaUnderOver'];
              $record['BGL_QuotaUnder']     = $rows['CGL_QuotaUnder'];
              $record['BGL_QuotaOver']      = $rows['CGL_QuotaOver'];
			  
              $record['BGL_QuotaOdd']       = $rows['CGL_QuotaOdd'];
              $record['BGL_QuotaEven']      = $rows['CGL_QuotaEven'];
              $record['BGL_ResultChoice']   = $rows['CGL_ResultChoice'];
			  $record['BGL_Bet']			= $rows['G_List'];
              $record['BGL_RegDate']        = date("Y-m-d H:i:s");
			  $record['BGL_IP']          	= $_SERVER['REMOTE_ADDR'];
              $db->AutoExecute("buygamelist", $record, "INSERT");
				
			
			  #################################################################################################
			  	$bgl_key = $db->Insert_ID();
			  
			  	$que  = "INSERT INTO rjawmd SET ";
				$que .= "BGL_Key 					= {$bgl_key}, ";
				$que .= "BG_Key 					= HEX(AES_ENCRYPT({$bg_key},md5('dlffleoqkr!@#'))), ";
				$que .= "G_Key 						= HEX(AES_ENCRYPT({$rows['G_Key']},md5('dlffleoqkr!@#'))), ";
				$que .= "M_Key 						= HEX(AES_ENCRYPT({$_SESSION['S_Key']},md5('dlffleoqkr!@#'))), ";
				
				if($rows['CGL_QuotaWin']){
					$que .= "BGL_QuotaWin 			= HEX(AES_ENCRYPT({$rows['CGL_QuotaWin']},md5('dlffleoqkr!@#'))), ";
				}
				if($rows['CGL_QuotaDraw']){
					$que .= "BGL_QuotaDraw 			= HEX(AES_ENCRYPT('{$rows['CGL_QuotaDraw']}',md5('dlffleoqkr!@#'))), ";
				}
				if($rows['CGL_QuotaLose']){
					$que .= "BGL_QuotaLose 			= HEX(AES_ENCRYPT({$rows['CGL_QuotaLose']},md5('dlffleoqkr!@#'))), ";
				}
				if($rows['CGL_QuotaHandicap']){
					$que .= "BGL_QuotaHandicap 		= HEX(AES_ENCRYPT('{$rows['CGL_QuotaHandicap']}',md5('dlffleoqkr!@#'))), ";
				}
				if($rows['CGL_QuotaHandiWin']){
					$que .= "BGL_QuotaHandiWin 		= HEX(AES_ENCRYPT({$rows['CGL_QuotaHandiWin']},md5('dlffleoqkr!@#'))), ";
				}
				if($rows['CGL_QuotaHandiLose']){
					$que .= "BGL_QuotaHandiLose 	= HEX(AES_ENCRYPT({$rows['CGL_QuotaHandiLose']},md5('dlffleoqkr!@#'))), ";
				}
				if($rows['CGL_QuotaUnderOver']){
					$que .= "BGL_QuotaUnderOver 	= HEX(AES_ENCRYPT('{$rows['CGL_QuotaUnderOver']}',md5('dlffleoqkr!@#'))), ";
				}
				if($rows['CGL_QuotaUnder']){
					$que .= "BGL_QuotaUnder 		= HEX(AES_ENCRYPT({$rows['CGL_QuotaUnder']},md5('dlffleoqkr!@#'))), ";
				}
				if($rows['CGL_QuotaOver']){
					$que .= "BGL_QuotaOver 			= HEX(AES_ENCRYPT({$rows['CGL_QuotaOver']},md5('dlffleoqkr!@#'))), ";
				}
				
				$que .= "BGL_ResultChoice 			= HEX(AES_ENCRYPT('{$rows['CGL_ResultChoice']}',md5('dlffleoqkr!@#'))), ";
				$que .= "reg_date 			= NOW(), ";			  
				$que .= "BGL_IP 					= HEX(AES_ENCRYPT('{$_SERVER['REMOTE_ADDR']}',md5('dlffleoqkr!@#'))) ";
				
				//echo $que;
				setQry($que);
			  
			  
			  
			  #######################################################
			#경기 배팅 리스트에 카운트 및 구매 금액 추가한다.
			#######################################################
			
			#일반회원일 경우만 적용한다.
			if($row[M_Type]==1){
				$sql1 = "SELECT COUNT(*) FROM game_bet_list WHERE B_Game_Key = {$rows['G_Key']} ";
				$sql1_res = getRow($sql1);
				if($sql1_res[0]>0){
					$que1  = "UPDATE game_bet_list SET ";	
					
					if($rows['CGL_ResultChoice']=='Win' || $rows['CGL_ResultChoice']=='HandiWin' || $rows['CGL_ResultChoice']=='Under'){
						$que1 .= "B_Win_Cnt 	= B_Win_Cnt+1, ";
						$que1 .= "B_Win_Money 	= B_Win_Money+{$_POST['HAF_Value_1']}, ";
					}
					if($rows['CGL_ResultChoice']=='Draw'){
						$que1 .= "B_Draw_Cnt 	= B_Draw_Cnt+1, ";
						$que1 .= "B_Draw_Money 	= B_Draw_Money+{$_POST['HAF_Value_1']}, ";
					}
					if($rows['CGL_ResultChoice']=='Lose' || $rows['CGL_ResultChoice']=='HandiLose' || $rows['CGL_ResultChoice']=='Over'){
						$que1 .= "B_Lose_Cnt 	= B_Lose_Cnt+1, ";
						$que1 .= "B_Lose_Money 	= B_Lose_Money+{$_POST['HAF_Value_1']}, ";
					}
					$que1 .= "B_Game_Key 		= {$rows['G_Key']} ";
					$que1 .= " WHERE B_Game_Key 		= {$rows['G_Key']} ";
	
				} else {
					$que1  = "INSERT INTO game_bet_list SET ";
					$que1 .= "B_Game_Key 		= {$rows['G_Key']}, ";
					if($rows['CGL_ResultChoice']=='Win' || $rows['CGL_ResultChoice']=='HandiWin' || $rows['CGL_ResultChoice']=='Under'){
						$que1 .= "B_Win_Cnt 	= 1, ";
						$que1 .= "B_Win_Money 	= {$_POST['HAF_Value_1']}, ";
					}
					if($rows['CGL_ResultChoice']=='Draw'){
						$que1 .= "B_Draw_Cnt 	= 1, ";
						$que1 .= "B_Draw_Money 	= {$_POST['HAF_Value_1']}, ";
					}
					if($rows['CGL_ResultChoice']=='Lose' || $rows['CGL_ResultChoice']=='HandiLose' || $rows['CGL_ResultChoice']=='Over'){
						$que1 .= "B_Lose_Cnt 	= 1, ";
						$que1 .= "B_Lose_Money 	= {$_POST['HAF_Value_1']}, ";
					}
					$que1 .= "B_RegDate 			= NOW() ";
				}
				setQry($que1);
			}
			
			#######################################################
			
			#######################################################
			#경기 배팅 리스트에 카운트 및 구매 금액 추가한다.
			#######################################################
			
			$sql1 = "SELECT COUNT(*) FROM game_bet_list WHERE B_Game_Key = {$rows[G_Key]} ";
			$sql1_res = getRow($sql1);
			if($sql1_res[0]>0){
				$que1  = "UPDATE game_bet_list SET ";	
				
				if($rows['CGL_ResultChoice']=='Win' || $rows['CGL_ResultChoice']=='HandiWin' || $rows['CGL_ResultChoice']=='Under'){
					$que1 .= "B_Win_Cnt 	= B_Win_Cnt+1, ";
					$que1 .= "B_Win_Money 	= B_Win_Money+{$_POST['HAF_Value_1']}, ";
				}
				if($rows['CGL_ResultChoice']=='Draw'){
					$que1 .= "B_Draw_Cnt 	= B_Draw_Cnt+1, ";
					$que1 .= "B_Draw_Money 	= B_Draw_Money+{$_POST['HAF_Value_1']}, ";
				}
				if($rows['CGL_ResultChoice']=='Lose' || $rows['CGL_ResultChoice']=='HandiLose' || $rows['CGL_ResultChoice']=='Over'){
					$que1 .= "B_Lose_Cnt 	= B_Lose_Cnt+1, ";
					$que1 .= "B_Lose_Money 	= B_Lose_Money+{$_POST['HAF_Value_1']}, ";
				}
				$que1 .= "B_Game_Key 		= {$rows['G_Key']} ";
				$que1 .= " WHERE B_Game_Key 		= {$rows['G_Key']} ";

			} else {
				$que1  = "INSERT INTO game_bet_list SET ";
				$que1 .= "B_Game_Key 		= {$rows['G_Key']}, ";
				$que1 .= "B_Game_Gubun		= '{$rows['CGL_ResultChoice']}', ";
							
				if($rows['CGL_ResultChoice']=='Win' || $rows['CGL_ResultChoice']=='HandiWin' || $rows['CGL_ResultChoice']=='Under'){
					$que1 .= "B_Win_Cnt 	= 1, ";
					$que1 .= "B_Win_Money 	= {$_POST['HAF_Value_1']}, ";
				}
				if($rows['CGL_ResultChoice']=='Draw'){
					$que1 .= "B_Draw_Cnt 	= 1, ";
					$que1 .= "B_Draw_Money 	= {$_POST['HAF_Value_1']}, ";
				}
				if($rows[CGL_ResultChoice]=='Lose' || $rows['CGL_ResultChoice']=='HandiLose' || $rows['CGL_ResultChoice']=='Over'){
					$que1 .= "B_Lose_Cnt 	= 1, ";
					$que1 .= "B_Lose_Money 	= {$_POST['HAF_Value_1']}, ";
				}
				$que1 .= "B_RegDate 			= NOW() ";
			}
			setQry($que1);
			
			#######################################################
			
			
			  #################################################################################################
			  
              switch($rows['CGL_ResultChoice']){
                case "Win"        : $cgl_result = "승"; 		$cgl_quota = $rows['CGL_QuotaWin']; 		break;
                case "Draw"       : $cgl_result = "무"; 		$cgl_quota = $rows['CGL_QuotaDraw']; 		break;
                case "Lose"       : $cgl_result = "패"; 		$cgl_quota = $rows['CGL_QuotaLose']; 		break;
                case "Under"      : $cgl_result = "언더"; 		$cgl_quota = $rows['CGL_QuotaUnder'];	 	break;
                case "Over"       : $cgl_result = "오버"; 		$cgl_quota = $rows['CGL_QuotaOver']; 		break;
                case "HandiWin"   : $cgl_result = "핸디승"; 	    $cgl_quota = $rows['CGL_QuotaHandiWin']; 	break;
                case "HandiLose"  : $cgl_result = "핸디패"; 	    $cgl_quota = $rows['CGL_QuotaHandiLose'];   break;
                case "Odd"        : $cgl_result = "홀"; 		$cgl_quota = $rows['CGL_QuotaOdd']; 		break;
                case "Even"       : $cgl_result = "짝"; 		$cgl_quota = $rows['CGL_QuotaEven']; 		break;
              };

              $batting_quota *= $cgl_quota;
            };
            
            $batting_quota = substr($batting_quota,0,6);
			$batting_quota = sprintf('%.2f',$batting_quota);
            
            //if ( (int)($batting_quota * $_POST[HAF_Value_1]) > 3000000 ) $lib->AlertMSG( "적중상한가를 초과한 금액으로 배팅할 수 없습니다.." );

            $record = null;
            $record['BG_TotalQuota'] = $batting_quota;
            //if ( (int)($batting_quota * $_POST[HAF_Value_1]) > 3000000 ) $BettingPrice = 3000000;
            //else $BettingPrice = (int)($batting_quota * $_POST[HAF_Value_1]);
            $BettingPrice = ($batting_quota * $_POST['HAF_Value_1']);
            $record['BG_ForecastPrice'] = $BettingPrice;
            $where = "BG_Key=".$bg_key;
            $db->AutoExecute("buygame", $record, "UPDATE", $where);

            // 머니 차감
            $lib24c->Payment_Money( $_SESSION['S_Key'], "GameBetting", $_POST['HAF_Value_1'] , "", $bg_key,"" , "" );
            // VIP 포인트 지급
			
            // 회원 정보 획득
        	$mrow = $lib24c->Member_Info( $row['M_Key'], 'Y' );
        	if ( $mrow['M_Level'] == 1 && (int)$lib24c->point_info['Betting'] > 0)
            	$lib24c->Payment_Point( $_SESSION['S_Key'], "Betting", (int)( $_POST['HAF_Value_1'] * ($lib24c->point_info['Betting'] / 100) ) ,  "" );

            // 장바구니에서 모든 게임 삭제
            $result = $db->Execute("delete from cartgamelist where M_Key=?", array( $_SESSION['S_Key'] ) );
			
			
			#관리자 알람 만들기
			if($onebet=='Y'){
				setQry("UPDATE bet_alarm SET oneFolder = oneFolder + 1 WHERE idx = 1");
			}
			if($top50=='Y'){
				setQry("UPDATE bet_alarm SET max50 = max50 + 1 WHERE idx = 1");
			}
			if($top100=='Y'){
				setQry("UPDATE bet_alarm SET max100 = max100 + 1 WHERE idx = 1");
			}
			
			
          } else {
            $lib->AlertMSG( "구매할 게임이 없습니다." );
          }
        };

        //$lib->MovePage("/mypage/betlist/sports/",0,"parent");
        //echo '<script>parent.call_back();</script>';
      break;



	#배팅 취소 하루 3번만 가능하게..	
    case "BuyCartCancel" :
		
        // 로그인 체크
        if ( !$_SESSION[S_ID] ) $lib->AlertMSG( "정상적인 접속이 아닙니다.", "", 0, "parent");

        if ( !is_numeric($_POST[HAF_Value_1] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );
		
		$row = getRow("SELECT COUNT(*) FROM buygame WHERE BG_Key = {$_POST[HAF_Value_1]} ");
		if($row[0]>0){
		} else {
			$lib->AlertMSG( "해당 배팅 내역이 없습니다." );
			break;
		}
		
        $result1 = $db->Execute("select * from moneyinfo where M_Key=? and MI_RegDate like '".date("Y-m-d")."%' and MI_Money > 0 and MI_Type='GameBetting'", array( $_SESSION[S_Key] ) );

        //if( $result1->RecordCount() > 0 ) $lib->AlertMSG( "배팅취소는 하루에 한번만 가능합니다.");
		
		#배팅취소시 시작된 경기가 있는지 확인 한다.
		$start = 0;
		$arr = getArr("SELECT b.G_Key FROM buygame a, buygamelist b WHERE a.BG_Key = b.BG_Key AND b.BG_Key = {$_POST[HAF_Value_1]}");
		foreach($arr as $list){
			if(count($list)>0){
				if($list['G_Key']){
					$sql = "SELECT G_State FROM gamelist WHERE G_Key = {$list[G_Key]} AND G_State <> 'Await' ";
					$res = getRow($sql);
					if($res){
						$start++;
					}
				}
			}
		}
		
		if( $start > 0 ) $lib->AlertMSG( "이미 시작된 경기가 있어 배팅 취소할 수 없습니다.");
		
		
		
		#배팅 하루에 3번만 취소가능하게
		$que = "SELECT M_Bet_Cancel_Cnt FROM members WHERE M_Key = {$_SESSION[S_Key]}";
		$betrow = getRow($que);
		if($betrow[M_Bet_Cancel_Cnt]>=3){
			$lib->AlertMSG( "하루 배팅 취소는 3회까지만 가능합니다.");
			break;
		}
		
		
		
		
        $result = $db->Execute("select * from buygame where BG_Key=?", array( $_POST[HAF_Value_1] ) );

        if( $result->RecordCount() > 0 ) {
			$rows = $result->FetchRow();
			
			#취소 테이블에 데이터 복사 한다.
            $qqq  = "INSERT INTO bgd SET ";
			$qqq .= "BG_Key 				= '{$rows['BG_Key']}', ";
			$qqq .= "M_Key 					= '{$rows['M_Key']}', ";
			$qqq .= "BG_GameCount 			= '{$rows['BG_GameCount']}', ";
			$qqq .= "BG_GameCompleteCount 	= '{$rows['BG_GameCompleteCount']}', ";
			$qqq .= "BG_TotalQuota 			= '{$rows['BG_TotalQuota']}', ";
			$qqq .= "BG_BettingPrice 		= '{$rows['BG_BettingPrice']}', ";
			$qqq .= "BG_ForecastPrice 		= '{$rows['BG_ForecastPrice']}', ";
			$qqq .= "BG_Result 				= '{$rows['BG_Result']}', ";
			$qqq .= "BG_Process 			= '{$rows['BG_Process']}', ";
			$qqq .= "BG_BuyDate 			= '{$rows['BG_BuyDate']}', ";
			$qqq .= "BG_Visible 			= '{$rows['BG_Visible']}', ";
			$qqq .= "BG_Cancel 				= '{$rows['BG_Cancel']}', ";
			$qqq .= "BG_OverBet 			= '{$rows['BG_OverBet']}', ";
			$qqq .= "BG_Cancel_Date 		= NOW(), ";
			$qqq .= "BG_Admin_Cancel 		= 'N' ";
			
			$qqqres = setQry($qqq);
					
			
			$q = "DELETE FROM buygame WHERE BG_Key = {$_POST[HAF_Value_1]}";
			//echo $q;
		    $del = setQry($q);
			if($del){
				unset($qqq);
				unset($qqqres);
				
				$buygamel = "SELECT * FROM buygamelist WHERE BG_Key = {$_POST[HAF_Value_1]}";
				$bgl_arr = getArr($buygamel);
				foreach($bgl_arr as $bgl_arr){ 
					#buygamelist 를 백업 한다.
					$qqq  = "INSERT INTO bgld SET ";
					$qqq .= "BGL_Key 				= '{$bgl_arr['BGL_Key']}', ";
					$qqq .= "BG_Key 				= '{$bgl_arr['BG_Key']}', ";
					$qqq .= "G_Key 					= '{$bgl_arr['G_Key']}', ";
					$qqq .= "M_Key 					= '{$bgl_arr['M_Key']}', ";
					$qqq .= "BGL_QuotaWin 			= '{$bgl_arr['BGL_QuotaWin']}', ";
					$qqq .= "BGL_QuotaDraw 			= '{$bgl_arr['BGL_QuotaDraw']}', ";
					$qqq .= "BGL_QuotaLose 			= '{$bgl_arr['BGL_QuotaLose']}', ";
					$qqq .= "BGL_QuotaHandicap 		= '{$bgl_arr['BGL_QuotaHandicap']}', ";
					$qqq .= "BGL_QuotaHandiWin 		= '{$bgl_arr['BGL_QuotaHandiWin']}', ";
					$qqq .= "BGL_QuotaHandiLose 	= '{$bgl_arr['BGL_QuotaHandiLose']}', ";
					$qqq .= "BGL_QuotaUnderOver 	= '{$bgl_arr['BGL_QuotaUnderOver']}', ";
					$qqq .= "BGL_QuotaUnder 		= '{$bgl_arr['BGL_QuotaUnder']}', ";
					$qqq .= "BGL_QuotaOver 			= '{$bgl_arr['BGL_QuotaOver']}', ";
					$qqq .= "BGL_QuotaOdd 			= '{$bgl_arr['BGL_QuotaOdd']}', ";
					$qqq .= "BGL_QuotaEven 			= '{$bgl_arr['BGL_QuotaEven']}', ";
					$qqq .= "BGL_ResultChoice 		= '{$bgl_arr['BGL_ResultChoice']}', ";
					$qqq .= "BGL_State 				= 'Cancel', ";
					$qqq .= "BGL_IP 				= '{$bgl_arr['BG_OverBet']}', ";
					$qqq .= "BGL_CancelYN 			= 'Y' ";
					
					$qqqres = setQry($qqq);
					
					
					#배팅 리스트에서 배팅횟수 배팅 금액 뺀다.
					$sql = "UPDATE game_bet_list SET ";
					//echo $bgl_arr[BGL_ResultChoice];
					if($bgl_arr[BGL_ResultChoice]=='Win' || $bgl_arr[BGL_ResultChoice]=='HandiWin' || $bgl_arr[BGL_ResultChoice]=='Under'){
						$sql .= "B_Win_Cnt 	= B_Win_Cnt-1, ";
						$sql .= "B_Win_Money 	= B_Win_Money-{$rows['BG_BettingPrice']}, ";
					}
					if($bgl_arr[BGL_ResultChoice]=='Draw'){
						$sql .= "B_Draw_Cnt 	= B_Draw_Cnt-1, ";
						$sql .= "B_Draw_Money 	= B_Draw_Money-{$rows['BG_BettingPrice']}, ";
					}
					if($bgl_arr[BGL_ResultChoice]=='Lose' || $bgl_arr[BGL_ResultChoice]=='HandiLose' || $bgl_arr[BGL_ResultChoice]=='Over'){
						$sql .= "B_Lose_Cnt 	= B_Lose_Cnt-1, ";
						$sql .= "B_Lose_Money 	= B_Lose_Money-{$rows['BG_BettingPrice']}, ";
					}
					
					
					$sql .= " B_Game_Key = {$bgl_arr[G_Key]} ";
					$sql .= " WHERE B_Game_Key = {$bgl_arr[G_Key]} ";
					//echo $sql;
					setQry($sql);
					
				}
				
				#buygamelist을 삭제 한다.
				$q1 = "DELETE FROM buygamelist WHERE BG_Key = {$_POST['HAF_Value_1']}";
				setQry($q1);
				$lib24c->Payment_Money( $_SESSION[S_Key], "GameBetting", (-1 * $rows[BG_BettingPrice]) , "",  $_POST[HAF_Value_1],"" , "" );
	
				$mrow = $lib24c->Member_Info( $row[M_Key], 'Y' );
				if ( $mrow[M_Level] == 1 && (int)$lib24c->point_info[Betting] > 0){
					$lib24c->Payment_Point( $_SESSION[S_Key], "Betting", (int)( $rows[BG_BettingPrice] * -1 * ($lib24c->point_info[Betting] / 100) ) ,  "" );
				}
				
				
				
				
				#배팅 횟수를 추가한다.
				$betUpdate = "UPDATE members SET M_Bet_Cancel_Cnt = M_Bet_Cancel_Cnt+1 WHERE M_Key = {$_SESSION[S_Key]}";
				echo $betUpdate;
				setQry($betUpdate);
			}
		} else {
			$lib->AlertMSG( "배팅취소 시간이 초과했습니다.");
		};

       echo "<script>alert('배팅이 취소되었습니다.');parent.location.reload();</script>";
      break;
  };

  switch( $_REQUEST['action'] ) {
    case "CheckedList" :
        // 로그인 체크
        if ( !$_SESSION[S_ID] ) $lib->AlertMSG( "정상적인 접속이 아닙니다.", "", 0, "parent");

        // 장바구니에 담긴 리스트 가져오기
        $result = $db->Execute("select * from cartgamelist a left join gamelist b on a.G_Key=b.G_Key left join gameleague c on b.GL_Key=c.GL_Key left join gameitem d on c.GI_Key=d.GI_Key where a.M_Key=? order by a.CGL_RegDate asc", array( $_SESSION[S_Key] ) );
        if( $result ) {
          while ($rows = $result->FetchRow()) {			  
            echo 'GameCartCheckedList( '.$rows[G_Key].', "'.$rows[CGL_ResultChoice].'"); ';
          };
        }
      break;


    case "Loading" :
    	$_SESSION[C_Cart] = $_GET[v];
        echo $lib24c->Cart_Info();
      break;

    case "AllDeleteCart" :
        // 로그인 체크
        if ( !$_SESSION[S_ID] ) $lib->AlertMSG( "정상적인 접속이 아닙니다.", "", 0, "parent");

        // 장바구니에서 모든 게임 삭제
        $result = $db->Execute("delete from cartgamelist where M_Key=?", array( $_SESSION[S_Key] ) );

        echo $lib24c->Cart_Info( $_GET[g_price]);
      break;

    case "DeleteCart" :
        // 로그인 체크
        if ( !$_SESSION[S_ID] ) $lib->AlertMSG( "정상적인 접속이 아닙니다.", "", 0, "parent");

        if ( !is_numeric($_GET[cgl_key] ) ) $lib->AlertMSG( "정상적인 접속이 아닙니다." );

        // 장바구니에서 해당 게임 삭제
        $result = $db->Execute("delete from cartgamelist where M_Key=? and CGL_Key=?", array( $_SESSION[S_Key], $_GET[cgl_key] ) );

        echo $lib24c->Cart_Info( $_GET[g_price]);
      break;
	

    #카트에 담기
    case "InsertCart" :
        $json['flag'] = true;
        $json['error'] = "";
        $json['cart'] = "";
        $alert_msg = "";
        $g_list = $_REQUEST['g_list'];
        $g_result = $_REQUEST['g_result'];

        $add_cart = false;
        $change_cart = false;
        $game_result = array('Win', 'Draw','HandiWin', 'HandiLose', 'Lose', 'Under', 'Over', 'Odd', 'Even');        
        $gKey = $_GET['g_key'];
        $que = "SELECT COUNT(*) FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'";
		$r = getRow($que);
        error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$que." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");

			// 로그인 체크
    	if ( !$_SESSION['S_Key'] ){
            $json['flag'] = false;
            $json['error'] = "정상적인 접속이 아닙니다1.";
            echo json_encode($json);
            break;
        }

        if ( !is_numeric($_GET['g_key'] ) ){
            $json['flag'] = false;
            $json['error'] = "정상적인 접속이 아닙니다2.";
            echo json_encode($json);
            break;
        }
        if ( !in_array($_GET['g_result'], $game_result) ){
            $json['flag'] = false;
            $json['error'] = "정상적인 접속이 아닙니다3.";
            echo json_encode($json);
            break;
        }


        if(!in_array($_GET['g_key'],array(1,2,3))) {//보너스 경기가 아닐경우만 체크 한다.
            //정상적으로 발매중인지 확인
            $que = "select * from gamelist a where  a.G_Key='{$_GET['g_key']}'";
            $g_row = getRow($que);
            error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$que." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
            if ( $g_row['G_State'] != 'Await' ) {
                $json['flag'] = false;
                $json['error'] = "[".$g_row['G_Team1']." vs ".$g_row['G_Team2']."] 경기는 발매중이 아니기 때문에 구매할수 없습니다.";
                echo json_encode($json);
                break;
            }

            // 시간 지났는지 체크
            $bettime = date("Y-m-d H:i:s",strtotime("+5 minutes",strtotime(date("Y-m-d H:i:s"))));
            if($g_row['G_Datetime'] < $bettime)
            {
                $json['flag'] = false;
                $json['error'] = "[".$g_row['G_Team1']." vs ".$g_row['G_Team2']."] 시작된 경기는 구매하실 수 없습니다.";
                echo json_encode($json);
                break;
            }



            // 업데이트 해야되는 게임 인지 체크 249685
            $que = "select * from cartgamelist where M_Key='{$_SESSION['S_Key']}' and G_Key ='{$_GET['g_key']}'";
            //echo $que."\n";
            $rs = getRow($que);
            error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$que." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
            //echo $_GET['g_result'];
            //print_r($rs);
            if (!empty($rs['G_Key'])) {
                //echo $rs['CGL_ResultChoice'] ."==". $_GET['g_result'];
                if ($rs['CGL_ResultChoice'] == $_GET['g_result']) {
                    $sql = "delete from cartgamelist where M_Key='{$_SESSION['S_Key']}' and CGL_Key ='{$rs['CGL_Key']}'";
                    //echo $sql."\n";
                    $res = setQry($sql);
                    error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$sql." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
                    if (!$res) {
                        $json['flag'] = false;
                        $json['error'] = "카트에서 게임삭제시 오류발생(cart)";
                        echo json_encode($json);
                        break;
                    } else {
                        $json['flag'] = true;
                        $json['error'] = "";
                        echo json_encode($json);
                        break;
                    }
                } else {
                    //다른 쪽 클릭시 업데이트 기능
                    $change_cart = true;
                }
            }


            //축구 승/오버 배팅불가
            /*$que = "SELECT COUNT(*) FROM cartgamelist a LEFT JOIN gamelist b ON a.G_Key = b.G_Key WHERE G_GameList = '{$g_list}' AND b.GI_Key = '6046' AND M_Key = '{$_SESSION['S_Key']}' ";
            $row = getRow($que);
            if($row[0]>0){
                if(in_array($g_result,array('Win','Over'))){
                    $json['flag'] = false;
                    $json['error'] = "축구 승/오버 배팅 불가합니다.";
                    echo json_encode($json);
                    break;
                }
            }*/


            if($g_type != 'Special') {

                $que = "select * from gamelist where G_Key = '{$_GET['g_key']}'";
                //echo $que."\n";
                $row_ori = getRow($que);
                error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$que,3,"/home/trend/www/m/action/ajax_cart_action.log");
                if ($row_ori['G_Type2'] == "WDL" || $row_ori['G_Type2'] == "Handicap") {
                    if ($row_ori['G_Type2'] == "WDL") {
                        $sss = "select CGL_Key from cartgamelist where G_Datetime='{$row_ori['G_Datetime']}' and G_Team1='{$row_ori['G_Team1']}' and G_Team2='{$row_ori['G_Team2']}' and G_Key <> '{$_GET['g_key']}' and G_Type2='Handicap'";
                        $r = getRow($sss);
                        error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$sss." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
                        if ($r['CGL_Key']) {
                            $sql = "DELETE FROM cartgamelist WHERE M_Key='{$_SESSION['S_Key']}' AND CGL_Key='{$r['CGL_Key']}'";
                            //echo $sql;
                            $res = setQry($sql);
                            error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$sql." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
                            if (!$res) {
                                $json['flag'] = false;
                                $json['error'] = "카트에서 게임삭제시 오류발생(핸디묶음)";
                                break;
                            }
                        }
                    } else if ($row_ori['G_Type2'] == "Handicap") {
                        $sss = "select CGL_Key from cartgamelist where G_Datetime='{$row_ori['G_Datetime']}' and G_Team1='{$row_ori['G_Team1']}' and G_Team2='{$row_ori['G_Team2']}' and G_Key <> '{$_GET['g_key']}' and G_Type2='WDL'";
                        $r = getRow($sss);
                        error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$sss." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
                        if ($r['CGL_Key']) {
                            $sql = "DELETE FROM cartgamelist WHERE M_Key='{$_SESSION['S_Key']}' AND CGL_Key='{$r['CGL_Key']}'";
                            //echo $sql;
                            $res = setQry($sql);
                            error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$sql." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
                            if (!$res) {
                                $json['flag'] = false;
                                $json['error'] = "카트에서 게임삭제시 오류발생(핸디묶음)";
                                break;
                            }
                        }
                    }
                }
            }


            //장바구니에 담긴게 같은 경기의 같은 타입이 있다면
            if($g_result == 'Under' || $g_result == 'Over'){
                $gtype = "UnderOver";
            } else if($g_result == 'HandiWin' || $g_result == 'HandiLose'){
                $gtype = "Handicap";
            }


            if($g_type == 'Special' || $row_ori['GI_Key'] == '6046'){//스페셜일경우 한개만 선택이 가능하다
                if($change_cart != true) {
                    $que = "SELECT COUNT(*) FROM cartgamelist WHERE G_GameList = '{$g_list}' ";
                    //echo $que;
                    $rowu = getRow($que);
                    error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$que." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
                    if ($rowu[0] > 0) {
                        $sql = "DELETE FROM cartgamelist WHERE G_GameList = '{$g_list}' ";
                        //echo $sql;
                        $res = setQry($sql);
                        error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$sql." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
                        if (!$res) {
                            $json['flag'] = false;
                            $json['error'] = "카트에서 게임삭제시 오류발생(추가경기 삭제)";
                            break;
                        }
                    }
                }
            } else {
                if($change_cart != true) {
                    $que = "SELECT COUNT(*) FROM cartgamelist WHERE G_GameList = '{$g_list}' AND G_Type2 = '{$gtype}'";
                    //echo $que;
                    $rowu = getRow($que);
                    error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$que." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
                    if ($rowu[0] > 0) {
                        $sql = "DELETE FROM cartgamelist WHERE G_GameList = '{$g_list}' AND G_Type2 = '{$gtype}'";
                        //echo $sql;
                        $res = setQry($sql);
                        error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$sql." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
                        if (!$res) {
                            $json['flag'] = false;
                            $json['error'] = "카트에서 게임삭제시 오류발생(추가경기 삭제)";
                            break;
                        }
                    }
                }
            }
        } else {//보너스 게임일 경우만

            $bonus_date = date('Y-m-d H:i:s',strtotime('+30 Minute'));
            $g_row['G_Key'] = $_GET['g_key'];
            $g_row['G_Datetime']= $bonus_date;
            $g_row['G_Type1']= 'Full';
            $g_row['G_Type2']= 'WDL';
            if($_GET['g_key']==1){
                $g_row['G_Team1']= '3폴더 보너스';
                $g_row['G_QuotaWin']= '1.03';
            } else if($_GET['g_key']==2){
                $g_row['G_Team1']= '4폴더 보너스';
                $g_row['G_QuotaWin']= '1.05';
            } else if($_GET['g_key']==3) {
                $g_row['G_Team1']= '5폴더 보너스';
                $g_row['G_QuotaWin']= '1.07';
            }
            $g_row['G_QuotaDraw']= '';
            $g_row['G_QuotaLose']= '1';
            $g_row['G_Team2']= '배팅금지';
            $g_row['GI_Key']= '10000001';
            $g_row['GL_Key']= '38337';
        }//보너스 경기가 아닐경우만 체크 한다 끝


		
		unset($result);
		unset($row_ori);
		

		$ct = 0;

		
        // 카트 추가

          // 업데이트 되는 게임인지 체크
          if ( $change_cart == true ) {
            $record = null;
            $record['CGL_ResultChoice'] = $_GET['g_result'];
            $where = "M_Key = ".$_SESSION['S_Key']." and G_Key = ". $g_row['G_Key'];
			
            $db->AutoExecute("cartgamelist",$record,'UPDATE',$where);
          } else {
			if($g_row['G_Type2']=='WDL'){
				$glist = $g_row['G_Key']."||".$g_row['G_Datetime']."||".$g_row['G_Type1']."||".$g_row['G_Type2']."||".$g_row['G_Team1']."||".$g_row['G_Team2']."||".$g_row['G_QuotaWin']."||".$g_row['G_QuotaDraw']."||".$g_row['G_QuotaLose']."||".$g_row['GI_Key']."||".$g_row['GL_Key'];
			} else if($g_row['G_Type2']=='Handicap'){
				$glist = $g_row['G_Key']."||".$g_row['G_Datetime']."||".$g_row['G_Type1']."||".$g_row['G_Type2']."||".$g_row['G_Team1']."||".$g_row['G_Team2']."||".$g_row['G_QuotaHandiWin']."||".$g_row['G_QuotaHandicap']."||".$g_row['G_QuotaHandiLose']."||".$g_row['GI_Key']."||".$g_row['GL_Key'];
			} else {
				$glist = $g_row['G_Key']."||".$g_row['G_Datetime']."||".$g_row['G_Type1']."||".$g_row['G_Type2']."||".$g_row['G_Team1']."||".$g_row['G_Team2']."||".$g_row['G_QuotaUnder']."||".$g_row['G_QuotaUnderOver']."||".$g_row['G_QuotaOver']."||".$g_row['GI_Key']."||".$g_row['GL_Key'];
			}

              $sql  = "INSERT INTO cartgamelist SET ";
              $sql .= "G_Key                = '{$g_row['G_Key']}', ";
              $sql .= "G_Type1              = '{$g_row['G_Type1']}', ";
              $sql .= "G_Type2              = '{$g_row['G_Type2']}', ";
              $sql .= "GL_Key               = '{$g_row['GL_Key']}', ";
              $sql .= "G_GameLIst           = '{$g_list}', ";
              $sql .= "G_Datetime           = '{$g_row['G_Datetime']}', ";
              $sql .= "G_Team1              = '".mysql_real_escape_string(str_replace(" [오버]","",$g_row['G_Team1']))."', ";
              $sql .= "G_Team2              = '".mysql_real_escape_string(str_replace(" [언더]","",$g_row['G_Team2']))."', ";
              $sql .= "G_State              = '{$g_row['G_State']}', ";
              $sql .= "M_Key                = '{$_SESSION['S_Key']}', ";
              $sql .= "CGL_QuotaWin         = '{$g_row['G_QuotaWin']}', ";
              $sql .= "CGL_QuotaDraw        = '{$g_row['G_QuotaDraw']}', ";
              $sql .= "CGL_QuotaLose        = '{$g_row['G_QuotaLose']}', ";
              $sql .= "CGL_QuotaHandicap    = '{$g_row['G_QuotaHandicap']}', ";
              $sql .= "CGL_QuotaHandiWin    = '{$g_row['G_QuotaHandiWin']}', ";
              $sql .= "CGL_QuotaHandiLose   = '{$g_row['G_QuotaHandiLose']}', ";
              $sql .= "CGL_QuotaUnderOver   = '{$g_row['G_QuotaUnderOver']}', ";
              $sql .= "CGL_QuotaUnder       = '{$g_row['G_QuotaUnder']}', ";
              $sql .= "CGL_QuotaOver        = '{$g_row['G_QuotaOver']}', ";
              $sql .= "CGL_QuotaOdd         = '{$g_row['G_QuotaOdd']}', ";
              $sql .= "CGL_QuotaEven        = '{$g_row['G_QuotaEven']}', ";
              $sql .= "CGL_ResultChoice     = '{$_GET['g_result']}', ";
              $sql .= "G_List               = '".mysql_real_escape_string($glist)."', ";
              $sql .= "CGL_RegDate          = NOW() ";

              //echo $sql."<br>";
              $res = setQry($sql);
              error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$sql." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
              if(!$res){
                  $json['flag'] = false;
                  $json['error'] = '카드디비 입력오류';
                  echo json_encode($json);
                  break;
              }

            //$lib24c->Cart_Info( $_GET['g_price'], "경기 배당율이 변경되었습니다.");
          };

        $cnt = 0;
        $total_rate = 1;
        $que  = "SELECT COUNT(*) FROM cartgamelist a LEFT JOIN gamelist b ON a.G_Key = b.G_Key WHERE a.M_Key = '{$_SESSION['S_Key']}'";
        $ct = getRow($que);
        error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$que." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
        if($ct[0]>0) {
            $que = "SELECT * FROM cartgamelist a LEFT JOIN gamelist b ON a.G_Key = b.G_Key WHERE a.M_Key = '{$_SESSION['S_Key']}'";
            //echo $que.'\n';
            $arr = getArr($que);
            error_log('회원번호 : '.$_SESSION['S_Key'].'- 쿼리 : '.$que." 입력시간 : ".date("Y-m-d H:i:s").PHP_EOL,3,"/home/trend/www/m/action/ajax_cart_action.log");
            foreach ($arr as $arr) {
                $data['home_team'] = mb_substr($arr['G_Team1'], 0, 10, 'utf-8');
                $data['away_team'] = mb_substr($arr['G_Team2'], 0, 10, 'utf-8');
                if ($arr['CGL_ResultChoice'] == 'Win') {
                    $data['rate'] = $arr['CGL_QuotaWin'];
                    $total_rate *= $arr['CGL_QuotaWin'];
                    $data['select_type'] = '승';
                } else if ($arr['CGL_ResultChoice'] == 'Lose') {
                    $data['rate'] = $arr['CGL_QuotaLose'];
                    $total_rate *= $arr['CGL_QuotaLose'];
                    $data['select_type'] = '패';
                } else if ($arr['CGL_ResultChoice'] == 'Draw') {
                    $data['rate'] = $arr['CGL_QuotaDraw'];
                    $total_rate *= $arr['CGL_QuotaDraw'];
                    $data['select_type'] = '무';
                } else if ($arr['CGL_ResultChoice'] == 'Under') {
                    $data['rate'] = $arr['CGL_QuotaUnder'];
                    $total_rate *= $arr['CGL_QuotaUnder'];
                    $data['select_type'] = '언더';
                } else if ($arr['CGL_ResultChoice'] == 'Over') {
                    $data['rate'] = $arr['CGL_QuotaOver'];
                    $total_rate *= $arr['CGL_QuotaOver'];
                    $data['select_type'] = '오버';
                } else if ($arr['CGL_ResultChoice'] == 'HandiWin') {
                    $data['rate'] = $arr['CGL_QuotaHandiWin'];
                    $total_rate *= $arr['CGL_QuotaHandiWin'];
                    $data['select_type'] = '핸승';
                } else if ($arr['CGL_ResultChoice'] == 'HandiLose') {
                    $data['rate'] = $arr['CGL_QuotaHandiLose'];
                    $total_rate *= $arr['CGL_QuotaHandiLose'];
                    $data['select_type'] = '핸패';
                    $data['cglkey'] = $arr['CGL_Key'];
                }
                $dt[] = $data;
                $cnt++;
            }
            $BettingQuota = floor($total_rate*100);
            $BettingQuota = ($BettingQuota/100);
            $price = ($price > 0) ? $price : 5000;
            $json['total_cnt']      = $cnt;
            $json['total']          = $BettingQuota;
            $json['total_price']    = $price*$BettingQuota;
            $json['cart'] = $dt;


        } else {
            $json['total_cnt']      = 0;
        }

        echo json_encode($json);
        

       //echo $lib24c->Cart_Info( $_GET['g_price']);
      break;
  };
?>