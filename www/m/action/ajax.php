<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/include/common.php");
	
	switch($_POST['mode'])
	{
		case 'cartCnt':
			$que = "SELECT COUNT(*) FROM cartgamelist WHERE M_Key = '{$_SESSION[S_Key]}'";
			$row = getRow($que);
			echo $row[0];
		break;
		case 'cartCnt1':
			$que = "SELECT COUNT(*) FROM cartgamelist WHERE M_Key = '{$_SESSION[S_Key]}'";
			$row = getRow($que);
			echo $row[0]-1;
		break;
		
		case 'sameLogin':
			$que = "SELECT * FROM members WHERE M_ID = '{$_SESSION[S_ID]}'";
			$mem = getRow($que);
			
			
			//echo "디비 세선 정보 ->".$mem['token']." : 현재 로그인 세션 정보->".$_SESSION['S_Token'];
	
			if($_SESSION['S_Token'] != $mem['token']){
				unlink($_SERVER['DOCUMENT_ROOT']."/session/sess_".$_SESSION['S_Token']);
				echo 1;
			}
		break;
		
		/*레벨 최하일때 스타는 승무패는 100만원 까지 가능 
                        핸디캡은 50만원 까지 가능(500001원 배팅시 50만원까지 가능으로 메시지 원합니다)
 스타 제외 다른 경기는 단폴더 10만원가능   
100001원 부터는 단폴배팅 안된다는 메시지 해주셨으면 합니다.*/
		case 'bettingCheck':
		
			$sql = "SELECT * FROM cartgamelist a, gameleague b, gamelist d WHERE a.M_Key = {$_SESSION[S_Key]} AND d.G_Key = a.G_Key AND b.GL_Key = d.GL_Key";
			//echo $sql;
			$row = getRow($sql);
			$ct = $row[0];
			
			if($ct==1){
				$que = "SELECT * FROM cartgamelist a, gameleague b, gamelist d WHERE a.M_Key = {$_SESSION[S_Key]} AND d.G_Key = a.G_Key AND b.GL_Key = d.GL_Key  ";
				//echo $que;
				$list = getRow($que);				
				
				$gtype = "";
				$gltype = "";
				$underoverYN = "N";
				$handicapYN = "N";
					
				if($list['G_Type2']=='UnderOver'){
					$underoverYN = 'Y';
				}
				if($list['G_Type2']=='Handicap'){
					$handicapYN = 'Y';
				}
				$gtype = $list['G_Type2'];
				$gtype1 = $list['G_Type1'];
				$gltype = $list[GI_Key];
			} else {
				
					
				$que = "SELECT * FROM cartgamelist a, gameleague b, gamelist d WHERE a.M_Key = {$_SESSION[S_Key]} AND d.G_Key = a.G_Key AND b.GL_Key = d.GL_Key  ";
				//echo $que;
				$arr = getArr($que);
				
				$cnt = count($arr);
				$gtype = "";
				$gltype = "";
				$underoverYN = "N";
				$handicapYN = "N";
				if($cnt>0){
					foreach($arr as $list){
						if($list['G_Type2']=='UnderOver'){
							$underoverYN = 'Y';
						}
						if($list['G_Type2']=='Handicap'){
							$handicapYN = 'Y';
						}
						$gtype = $list['G_Type2'];
						$gtype1 = $list['G_Type1'];
						$gltype = $list[GI_Key];
					}
				}
			}
			
			//echo $list[G_Type]."->".$underoverYN;
			$price = $_POST[price];//배팅금액
			$hitmoney = (int)str_replace(",","",$_POST[hitmoney]);//적중금액
			
			#동일경기 확인하기
			//$same = getRow("SELECT COUNT(*) FROM cartgamelist a, gamelist b WHERE ");
			//echo $ct;
			
			
			$sql = "SELECT COUNT(*) AS co FROM cartgamelist a, gameleague b, gamelist d WHERE a.M_Key = {$_SESSION[S_Key]} AND d.G_Key = a.G_Key AND b.GL_Key = d.GL_Key";
			//echo $sql;
			$row = getRow($sql);
			$ladder_cnt = $row[co];
			
			if($_SESSION[S_Level]==9){//기본 레벨일 경우
				#배팅상한 금액 제한				
				if($gtype == 'WDL' && $gltype != 10){//승무패일 경우
					//배팅 금액이 100만원 적중금액 300만원이 넘을 경우
					if($gltype == 20){
						if($price > 1000000){
							echo '21';
							break;
						}
					} else if($gltype == 23){
						if($price > 1000000){
							echo '51';
							break;
						}
					} else if($gltype == 24){
						if($price > 1000000){
							echo '52';
							break;
						}
					} else {
						if($price>1000000 || $hitmoney > 3000000){
							echo '1';
							break;
						}
					}
					
					#사다리일경우 단폴 50까지 허용
					if($gltype == 20){
						if($cnt==1 && $price > 1000000){
							echo '21';
							break;
						}
					} else if($gltype == 23){
						if($price > 1000000){
							echo '51';
							break;
						}
					} else if($gltype == 24){
						if($price > 1000000){
							echo '52';
							break;
						}
					} else {
						
						#승무패 단폴 배팅 금액이 10만원 넘을때
						if($cnt==1 && $price > 500000){
							echo '2';
							break;
						}
						
					}
				} 
				
				
				if($gtype != 'WDL' &&  $gltype != 10){//승무패가 아닐경우
					//배팅 금액이 50만원 적중금액 150만원이 넘을 경우
					if($gltype == 20){
						if($price > 1000000){
							echo '21';
							break;
						}
					} else {
						if($gtype1 != 'Special' ){
							if(($gtype == 'Handicap' || $gtype == 'UnderOver') && $cnt > 1){
								if($price>1000000 || $hitmoney > 3000000 ){
									echo '871';
									break;
								}
							}
						} else {
							
							if(($gtype == 'Handicap' || $gtype == 'UnderOver') && $cnt > 1){								
								if($price>500000 || $hitmoney > 1500000 ){
									echo '97';
									break;
								}
							}
						}
					}
					#사다리일경우 단폴 50까지 허용
					if($gltype == 20){
						if($cnt==1 && $price > 1000000){
							echo '21';
							break;
						}
					} else if($gltype == 23){
						if($price > 1000000){
							echo '51';
							break;
						}
					} else if($gltype == 24){
						if($price > 1000000){
							echo '52';
							break;
						}
					} else {
						
						if($cnt==1 && $price > 500000){
							echo '2';
							break;
						}
						
					}
				} 
				
				
				if($gltype == 10){//스타일경우					
					#스타 승무패 단폴 100만원
					if($gtype=='Handicap'){
						if($cnt == 1 && $price > 1000000){
							echo '85';
							break;
						}
					} else if($gtype == 'WDL'){
					
						if($cnt == 1 && $price > 1000000){
							echo '815';
							break;
						}
					} else if($gtype == 'UnderOver'){
						#스타 언오버 단폴 50만원
						if($cnt == 1 && $price > 500000){
							echo '86';
							break;
						}
					}
					
					#스타 다폴일 경우 배팅상한 10만원 - 동일 경기일 경우
					if($cnt > 1){
						
						if($handicapYN=='Y'&&$underoverYN=='Y'&&$price>100000){
							echo '877';
							break;
						}
					}
				} 
				
				#사다리 2폴 제한
				if($gltype == 20 && $ladder_cnt == 2){
					echo '111';
					break;
				}
				
				#달팽이 2폴 제한
				if($gltype == 23 && $ladder_cnt == 2){
					echo '555';
					break;
				}
				
				#파워볼 2폴 제한
				if($gltype == 24 && $ladder_cnt == 2){
					echo '556';
					break;
				}
				
			} else if($_SESSION[S_Level]==8){
				
				#배팅상한 금액 제한				
				if($gltype == 20){
					if($price > 1000000){
						echo '21';
						break;
					}
				} else if($gltype == 23){
					if($price > 1000000){
						echo '51';
						break;
					}
				} else if($gltype == 24){
					if($price > 1000000){
						echo '52';
						break;
					}
				}
				if($gtype == 'WDL' && $gltype != 10){//승무패일 경우
					//배팅 금액이 100만원 적중금액 300만원이 넘을 경우
					if($gltype == 20){
						if($price > 1000000){
							echo '21';
							break;
						}
					} else if($gltype == 23){
						if($price > 1000000){
							echo '51';
							break;
						}
					} else if($gltype == 24){
						if($price > 1000000){
							echo '52';
							break;
						}
					} else {
						if($price>1000000 || $hitmoney > 3000000){
							echo '1';
							break;
						}
					}
					
					#사다리일경우 단폴 50까지 허용
					if($gltype == 20){
						if($cnt==1 && $price > 1000000){
							echo '21';
							break;
						}
					} else if($gltype == 23){
						if($price > 1000000){
							echo '51';
							break;
						}
					} else if($gltype == 24){
						if($price > 1000000){
							echo '52';
							break;
						}
					} else {
						#승무패 단폴 배팅 금액이 10만원 넘을때
						if($cnt==1 && $price > 500000){
							echo '2';
							break;
						}
					}
				} 
				
				if($gtype != 'WDL' &&  $gltype != 10){//승무패가 아닐경우
					//배팅 금액이 50만원 적중금액 150만원이 넘을 경우
					if($gltype == 20){
						if($price > 1000000){
							echo '21';
							break;
						}
					} else if($gltype == 23){
						if($price > 1000000){
							echo '51';
							break;
						}
					} else if($gltype == 24){
						if($price > 1000000){
							echo '52';
							break;
						}
					} else {
						if($gtype1 != 'Special' ){
							if(($gtype == 'Handicap' || $gtype == 'UnderOver') && $cnt > 1){
								if($price>1000000 || $hitmoney > 3000000 ){
									echo '871';
									break;
								}
							}
						} else {
							
							if(($gtype == 'Handicap' || $gtype == 'UnderOver') && $cnt > 1){								
								if($price>500000 || $hitmoney > 1500000 ){
									echo '97';
									break;
								}
							}
						}
					}
					#사다리일경우 단폴 50까지 허용
					if($gltype == 20){
						if($cnt==1 && $price > 1000000){
							echo '21';
							break;
						}
					} else if($gltype == 23){
						if($price > 1000000){
							echo '51';
							break;
						}
					} else if($gltype == 24){
						if($price > 1000000){
							echo '52';
							break;
						}
					} else {
						
						if($cnt==1 && $price > 500000){
							echo '2';
							break;
						}
						
					}
				} 
				
				
				if($gltype == 10){//스타일경우			
					
					#스타 승무패 단폴 100만원
					if($cnt == 1 && $handicapYN == 'Y' && $price > 1000000 && $underoverYN == 'N'){
						echo '85';
						break;
					}
					
					#스타 승무패 단폴 100만원
					if($cnt == 1 && $handicapYN == 'N' && $underoverYN=='N' && $price > 1000000 && $underoverYN == 'N'){
						echo '85';
						break;
					}
					
					#스타 언오버 단폴 50만원
					if($cnt == 1 && $handicapYN=='N' && $underoverYN=='Y' && $price > 500000){
						echo '86';
						break;
					}
					#스타 다폴일 경우 배팅상한 10만원
					if($cnt > 1){
						if($handicapYN=='Y'&&$underoverYN=='Y'&&$price>100000){
							echo '877';
							break;
						}
					}
				}
				
				#사다리 2폴 제한
				if($gltype == 20 && $ladder_cnt == 2){
					echo '111';
					break;
				}
				
				#달팽이 2폴 제한
				if($gltype == 23 && $ladder_cnt == 2){
					echo '555';
					break;
				}
				
				
				#달팽이 2폴 제한
				if($gltype == 24 && $ladder_cnt == 2){
					echo '556';
					break;
				}
			} else if($_SESSION[S_Level]==7){
				#배팅상한 금액 제한				
				if(in_array($gtype,array('WDL','Handicap','UnderOver')) && $gltype != 10 ){//승/핸/언일 경우
					//배팅 금액이 100만원 적중금액 300만원이 넘을 경우	
					
					
					if($gltype == 20){
						if($price > 1000000){
							echo '21';
							break;
						}
					} else if($gltype == 23){
						if($price > 1000000){
							echo '51';
							break;
						}
					} else if($gltype == 24){
						if($price > 1000000){
							echo '52';
							break;
						}
					
					} else {				
						if($price>1000000 || $hitmoney > 3000000){
							echo '871';
							break;
						}
					}
					#승무패 단폴 배팅 금액이 100만원 넘을때
					if($cnt==1 && $price > 1000000){
						echo '72';
						break;
					}
				} 
				if($gtype == 'Special' && $gltype != 10){//스페셜일경우
					//배팅 금액이 50만원 적중금액 150만원이 넘을 경우
					if($price>500000 || $hitmoney > 1500000){
						echo '873';
						break;
					}
					if($cnt==1 && $price > 1000000){
						echo '72';
						break;
					}
				} else if($gltype == 10){//스타일경우			
					
					#스타 승무패 단폴 200만원
					if($cnt == 1 && $handicapYN == 'Y' && $price > 2000000 && $underoverYN == 'N'){
						echo '75';
						break;
					}
					
					#스타 승무패 단폴 200만원
					if($cnt == 1 && $handicapYN == 'N' && $underoverYN=='N' && $price > 2000000 && $underoverYN == 'N'){
						echo '75';
						break;
					}
					
					#스타 언오버 단폴 50만원
					if($cnt == 1 && $handicapYN=='N' && $underoverYN=='Y' && $price > 500000){
						echo '86';
						break;
					}
					#스타 다폴일 경우 배팅상한 10만원
					if($cnt > 1){
						if($handicapYN=='Y'&&$underoverYN=='Y'&&$price>100000){
							echo '877';
							break;
						}
					}
				}
				
				
				#사다리 2폴 제한
				if($gltype == 20 && $ladder_cnt == 2){
					echo '111';
					break;
				}
				
				
				#달팽이 2폴 제한
				if($gltype == 23 && $ladder_cnt == 2){
					echo '555';
					break;
				}
				
				#달팽이 2폴 제한
				if($gltype == 24 && $ladder_cnt == 2){
					echo '556';
					break;
				}
			}
			
			#사다리 2폴 제한
			
			//echo $que."->".$cnt."->".$price."->".$_SESSION[S_Level]."->".$gltype;
		break;
		
		#사디리 스페셜 3줄 좌우 조합안되게
		case 'ladderChk':
			
			$res = 'Y';
			#현재 gkey와 시간 을 구매한 내역과 카트에 담긴 내역을 구한다.
			$start = date("Y-m-d H:i:s",strtotime("-10 minute"));
			$end = date("Y-m-d H:i:s");
			if($_POST[glkey]==1380){
				$gl_key = '(1388,1326)';
			} else if($_POST[glkey]==1388){
				$gl_key = '(1380,1326)';
			} else if($_POST[glkey]==1326){
				$gl_key = '(1380,1388)';
			}
			
			//echo $_POST[glkey];
			$que = "SELECT COUNT(*) FROM cartgamelist WHERE M_Key = '{$_SESSION[S_Key]}'  AND G_Datetime = '{$_POST[gtime]}' AND GL_Key IN {$gl_key}";
			//echo $que;
			$row = getRow($que);
			
			if($row[0]==2){
				echo $res = 'N';
			} else {
			
			
				$que = "SELECT count(*) FROM gamelist a, buygamelist b WHERE a.G_Key = b.G_Key AND a.G_Datetime = '{$_POST[gtime]}' AND b.GL_Key IN {$gl_key} AND a.GL_Key = b.GL_Key AND b.M_Key = '{$_SESSION[S_Key]}'";
				//echo $que;
				$row = getRow($que);
				
				if($row[0]==2){
					echo $res = 'N';
				}
			}
		break;
	}
?>