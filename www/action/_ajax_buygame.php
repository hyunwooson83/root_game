<?php
include_once($_SERVER['DOCUMENT_ROOT']."/include/common.php");


/*setQry("start transaction");*/
$json['flag'] = true;
$json['error'] = "";
$fail = 0;
$BettingPrice = $_REQUEST['BettingMoney'];

// 로그인 체크
if ( !$_SESSION['S_Key'] ){
    $json['flag'] = false;
    $json['error'] = '정상적인 접속이 아닙니다1';
    //echo json_encode($json);
}

if ( $meminfo['M_Money'] < $BettingPrice ){
    $json['flag'] = false;
    $json['error'] = '보유머니가 배팅할려는 금액보다 적습니다.';
    echo json_encode($json);
    break;
}

$r = getRow("SELECT M_SportYN, M_Type FROM members WHERE M_Key = {$_SESSION['S_Key']}");
if($r[0]=='N'){
    $json['flag'] = false;
    $json['error'] = '배팅 불가능한 회원입니다.';
    echo json_encode($json);
    break;
}

$count = 0;

if($meminfo['M_Money']<$BettingPrice){
    $json['flag'] = false;
    $json['error'] = '보유머니가 부족합니다. 보유머니 충전 후 배팅하세요.';
    echo json_encode($json);
    break;
}

$game_cnt = 0;
$que = "SELECT COUNT(*) FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'";
/*echo $que;*/
$row = getRow($que);
if(!$row[0]){
    $json['flag'] = false;
    $json['error'] = '구매할 게임이 없습니다[first].';
    $fail++;
    echo json_encode($json);
    break;
}

//장바구니에 담긴 게임갯수
$game_cnt = $row[0];

if($row[0]==1 && $meminfo['M_One_Stop']=='Y'){
    $json['flag'] = false;
    $json['error'] = '단폴 배팅을 하실 수 없습니다.';
    $fail++;
    echo json_encode($json);
    break;
}

if($row[0]==2 && $meminfo['M_Two_Stop']=='Y'){
    $json['flag'] = false;
    $json['error'] = '두폴 배팅을 하실 수 없습니다.';
    $fail++;
    echo json_encode($json);
    break;
}


if($BettingPrice>$LEVELLIMITED['Sports_Max_Bet_Money']){
    $json['flag'] = false;
    $json['error'] = '배팅가능한 한도를 초과하셨습니다..';
    $fail++;
    echo json_encode($json);
    break;
}

if($hitmoney>$LEVELLIMITED['Sports_Max_Hit_Mone']){
    $json['flag'] = false;
    $json['error'] = '당첨가능한 한도를 초과하셨습니다.';
    $fail++;
    echo json_encode($json);
    break;
}


@setQry("INSERT INTO cartgamelist_bak SELECT * FROM cartgamelist where M_Key = {$_SESSION['S_Key']}");

$batting_quota_chk = 1;
$bonus_is = '';
//현재 회원의 카트에 담긴 게임들을 모두 가져온다.
$wdl_cnt = $handi_cnt = 0;
$game_list_handi = $game_list_wdl = '';
$sql = "SELECT a.*, b.GI_Key  FROM cartgamelist a LEFT JOIN gamelist b ON a.G_Key = b.G_Key WHERE M_Key = '{$_SESSION['S_Key']}'";
$arr = getArr($sql);
if(count($arr)>0){
    foreach($arr as $arr) {
        if ($arr['GI_Key'] == '154914') {//현재 담긴 게임이 야구이고
            if ($arr['CGL_ResultChoice'] == 'Win' || $arr['CGL_ResultChoice'] == 'Lose') {
                $wdl_cnt++;
                $game_list_wdl = $arr['G_GameList'];
            }
            if ($arr['CGL_ResultChoice'] == 'HandiWin' || $arr['CGL_ResultChoice'] == 'HandiLose') {
                $handi_cnt++;
                $game_list_handi = $arr['G_GameList'];
            }
        }

        if($wdl_cnt>0 && $handi_cnt>0 && $game_list_handi == $game_list_wdl){
            $json['flag'] = false;
            $json['error'] = '야구 승패/핸디를 배팅하실 수 없습니다.';
            $fail++;
            echo json_encode($json);
            break;
        }


        //보너스 경기가 있고 배팅한 경기가 보너스 배당보다 적을경우 보너스 배당을 없앤다.
        if ($arr['G_Key'] == 1) {
            $bonus_is = $arr['G_Key'];
        }


        if($arr['G_Key']==1 && $game_cnt < 3){
            $json['flag'] = false;
            $json['error'] = '배팅카트에 게임이 정상적으로 담기지 않았습니다. 다시 배팅해주세요.';
            $fail++;
            echo json_encode($json);
            break;
        } else if($arr['G_Key']==2 && $game_cnt < 5){
            $json['flag'] = false;
            $json['error'] = '배팅카트에 게임이 정상적으로 담기지 않았습니다. 다시 배팅해주세요.';
            $fail++;
            echo json_encode($json);
            break;
        } else if($arr['G_Key']==3 && $game_cnt < 7){
            $json['flag'] = false;
            $json['error'] = '배팅카트에 게임이 정상적으로 담기지 않았습니다. 다시 배팅해주세요.';
            $fail++;
            echo json_encode($json);
            break;
        }
        switch($arr['CGL_ResultChoice']){
            case "Win"        : $cgl_result = "승"; 		$cgl_quota = $arr['CGL_QuotaWin']; 		break;
            case "Draw"       : $cgl_result = "무"; 		$cgl_quota = $arr['CGL_QuotaDraw']; 		break;
            case "Lose"       : $cgl_result = "패"; 		$cgl_quota = $arr['CGL_QuotaLose']; 		break;
            case "Under"      : $cgl_result = "언더"; 		$cgl_quota = $arr['CGL_QuotaUnder'];	 	break;
            case "Over"       : $cgl_result = "오버"; 		$cgl_quota = $arr['CGL_QuotaOver']; 		break;
            case "HandiWin"   : $cgl_result = "핸디승"; 	    $cgl_quota = $arr['CGL_QuotaHandiWin']; 	break;
            case "HandiLose"  : $cgl_result = "핸디패"; 	    $cgl_quota = $arr['CGL_QuotaHandiLose'];   break;
            case "Odd"        : $cgl_result = "홀"; 		$cgl_quota = $arr['CGL_QuotaOdd']; 		break;
            case "Even"       : $cgl_result = "짝"; 		$cgl_quota = $arr['CGL_QuotaEven']; 		break;
        };
        $batting_quota_chk *= $cgl_quota;

    }
}

if($batting_quota_chk > 100){
    $json['flag'] = false;
    $json['error'] = '최대 배팅 가능한 배당률은 100배 입니다.';
    $fail++;
    echo json_encode($json);
    break;
}


$sql  = "INSERT INTO buygame SET ";
$sql .= "M_Key 						= '{$_SESSION['S_Key']}',";
$sql .= "BG_GameCount 				= '{$row[0]}',";
$sql .= "BG_CompleteCount 	        = '0',";
$sql .= "BG_TotalQuota 				= '0',";
$sql .= "BG_BettingPrice 			= '{$BettingPrice}',";
$sql .= "BG_MemberMoney 			= '{$meminfo['M_Money']}',";
$sql .= "BG_ForecastPrice 			= '0',";
$sql .= "BG_Result 					= 'Await',";
$sql .= "BG_BetInfo		            = '".mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])."',";
$sql .= "BG_Gubun 				    = 'prematch', ";
$sql .= "BG_BuyDate 				= NOW()";
//echo $sql;
$res = setQry($sql);
if(!$res){
    $json['flag'] = false;
    $json['error'] = '구매내역 저장시 오류가 발생했습니다.';
    $fail++;
    echo json_encode($json);
    break;
}

//echo "fail[1]->".$fail."\n";
$bg_key = mysql_insert_id();
$batting_quota = 1;
$ForecastPrice = 0;

$que = "SELECT COUNT(*) AS cart_cnt FROM cartgamelist WHERE M_Key='{$_SESSION['S_Key']}' ";
$row_c = getRow($que);
if(!$row_c['cart_cnt']){
    $json['flag'] = false;
    $json['error'] = '카트에 담긴 게임이 없습니다.';
    echo json_encode($json);
    break;
}

$que = "SELECT a.*, b.G_State FROM cartgamelist a LEFT JOIN gamelist b ON a.G_Key=b.G_Key WHERE a.M_Key='{$_SESSION['S_Key']}' ORDER BY a.CGL_RegDate ASC";
//echo $que."<p>";
$rs = getArr($que);
if(count($rs)>0){

    foreach($rs as $rs) {

        //해당 경기가 종료 되었는가 체크
        $bettime = date("Y-m-d H:i:s",strtotime("+".$SITECONFIG['sport_bet_endtime']." minutes",strtotime(date("Y-m-d H:i:s"))));

        if($bettime > $rs['G_Datetime']){
            $json['flag'] = false;
            $json['error'] = '경기 시간이 지난 게임은 배팅이 불가능합니다.';
            echo json_encode($json);
            break;
        }

        if($rs['G_Locked'] != NULL) {//보너스 게임일때는 경기가 없다.
            if ($rs['G_Locked'] != '1') {
                $json['flag'] = false;
                $json['error'] = '시작되거나 취소,마감된 경기가 있어서 배팅이 불가능 합니다.';
                echo json_encode($json);
                break;
            }
        }


        $sql = "INSERT INTO buygamelist SET ";
        $sql .= "BG_Key              = '{$bg_key}', ";
        $sql .= "G_Key               = '{$rs['G_Key']}',";
        $sql .= "GL_Key              = '{$rs['GL_Key']}',";
        $sql .= "M_Key               = '{$_SESSION['S_Key']}',";
        $sql .= "G_Type1             = '{$rs['G_Type1']}',";
        $sql .= "G_Type2             = '{$rs['G_Type2']}',";
        $sql .= "BGL_QuotaWin        = '{$rs['CGL_QuotaWin']}',";
        $sql .= "BGL_QuotaDraw       = '{$rs['CGL_QuotaDraw']}',";
        $sql .= "BGL_QuotaLose       = '{$rs['CGL_QuotaLose']}',";

        $sql .= "BGL_QuotaHandicap   = '{$rs['CGL_QuotaHandicap']}',";
        $sql .= "BGL_QuotaHandiWin   = '{$rs['CGL_QuotaHandiWin']}',";
        $sql .= "BGL_QuotaHandiLose  = '{$rs['CGL_QuotaHandiLose']}',";

        $sql .= "BGL_QuotaUnderOver  = '{$rs['CGL_QuotaUnderOver']}',";
        $sql .= "BGL_QuotaUnder      = '{$rs['CGL_QuotaUnder']}',";
        $sql .= "BGL_QuotaOver       = '{$rs['CGL_QuotaOver']}',";

        $sql .= "BGL_QuotaOdd        = '{$rs['CGL_QuotaOdd']}',";
        $sql .= "BGL_QuotaEven       = '{$rs['CGL_QuotaEven']}',";
        $sql .= "BGL_ResultChoice    = '{$rs['CGL_ResultChoice']}',";
        $sql .= "BGL_Bet			 = '{$rs['G_List']}',";

        $sql .= "BGL_IP          	 = '{$_SERVER['REMOTE_ADDR']}',";
        if(in_array($rs['G_Key'],array(1,2,3))){
            $sql .= "BGL_State     	 = 'Success',";
        }
        $sql .= "BGL_RegDate         =  NOW() ";
        //echo $sql."<p>\n";
        $res = setQry($sql);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '구매상세내역 저장시 오류가 발생했습니다.';
            $fail++;
            //echo json_encode($json);
            //break;
            //echo "fail[2]->".$fail."\n";

            $sql1 = "DELETE FROM buygame WHERE BG_Key = '{$bg_key}'";
            setQry($sql1);
            echo json_encode($json);
            break;
        }


        $bgl_key = mysql_insert_id();

        $que  = "INSERT INTO rjawmd SET ";
        $que .= "BGL_Key 					= {$bgl_key}, ";
        $que .= "BG_Key 					= HEX(AES_ENCRYPT({$bg_key},md5('dlffleoqkr!@#'))), ";
        $que .= "G_Key 						= HEX(AES_ENCRYPT({$rs['G_Key']},md5('dlffleoqkr!@#'))), ";
        $que .= "M_Key 						= HEX(AES_ENCRYPT({$_SESSION['S_Key']},md5('dlffleoqkr!@#'))), ";

        if($rs['CGL_QuotaWin']){
            $que .= "BGL_QuotaWin 			= HEX(AES_ENCRYPT({$rs['CGL_QuotaWin']},md5('dlffleoqkr!@#'))), ";
        }
        if($rs['CGL_QuotaDraw']){
            $que .= "BGL_QuotaDraw 			= HEX(AES_ENCRYPT('{$rs['CGL_QuotaDraw']}',md5('dlffleoqkr!@#'))), ";
        }
        if($rs['CGL_QuotaLose']){
            $que .= "BGL_QuotaLose 			= HEX(AES_ENCRYPT({$rs['CGL_QuotaLose']},md5('dlffleoqkr!@#'))), ";
        }
        if($rs['CGL_QuotaHandicap']){
            $que .= "BGL_QuotaHandicap 		= HEX(AES_ENCRYPT('{$rs['CGL_QuotaHandicap']}',md5('dlffleoqkr!@#'))), ";
        }
        if($rs['CGL_QuotaHandiWin']){
            $que .= "BGL_QuotaHandiWin 		= HEX(AES_ENCRYPT({$rs['CGL_QuotaHandiWin']},md5('dlffleoqkr!@#'))), ";
        }
        if($rs['CGL_QuotaHandiLose']){
            $que .= "BGL_QuotaHandiLose 	= HEX(AES_ENCRYPT({$rs['CGL_QuotaHandiLose']},md5('dlffleoqkr!@#'))), ";
        }
        if($rs['CGL_QuotaUnderOver']){
            $que .= "BGL_QuotaUnderOver 	= HEX(AES_ENCRYPT('{$rs['CGL_QuotaUnderOver']}',md5('dlffleoqkr!@#'))), ";
        }
        if($rs['CGL_QuotaUnder']){
            $que .= "BGL_QuotaUnder 		= HEX(AES_ENCRYPT({$rs['CGL_QuotaUnder']},md5('dlffleoqkr!@#'))), ";
        }
        if($rs['CGL_QuotaOver']){
            $que .= "BGL_QuotaOver 			= HEX(AES_ENCRYPT({$rs['CGL_QuotaOver']},md5('dlffleoqkr!@#'))), ";
        }

        $que .= "BGL_ResultChoice 			= HEX(AES_ENCRYPT('{$rs['CGL_ResultChoice']}',md5('dlffleoqkr!@#'))), ";
        $que .= "reg_date 			        = NOW(), ";
        $que .= "BGL_IP 					= HEX(AES_ENCRYPT('{$_SERVER['REMOTE_ADDR']}',md5('dlffleoqkr!@#'))) ";

        /*echo $que."<br>\n";*/
        $res = setQry($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '검증내역 저장시 오류가 발생했습니다.';
            $fail++;
            //echo json_encode($json);
            //break;
            //echo "fail[3]->".$fail."\n";
        }

        #######################################################
        #경기 배팅 리스트에 카운트 및 구매 금액 추가한다.
        #######################################################

        #일반회원일 경우만 적용한다.
        if($meminfo['M_Type']==1){
            $sql1 = "SELECT COUNT(*) FROM game_bet_list WHERE B_Game_Key = {$rs['G_Key']} ";
            $sql1_res = getRow($sql1);
            if($sql1_res[0]>0){
                $que1  = "UPDATE game_bet_list SET ";

                if($rs['CGL_ResultChoice']=='Win' || $rs['CGL_ResultChoice']=='HandiWin' || $rs['CGL_ResultChoice']=='Under'){
                    $que1 .= "B_Win_Cnt 	= B_Win_Cnt+1, ";
                    $que1 .= "B_Win_Money 	= B_Win_Money+{$BettingPrice}, ";
                }
                if($rs['CGL_ResultChoice']=='Draw'){
                    $que1 .= "B_Draw_Cnt 	= B_Draw_Cnt+1, ";
                    $que1 .= "B_Draw_Money 	= B_Draw_Money+{$BettingPrice}, ";
                }
                if($rs['CGL_ResultChoice']=='Lose' || $rs['CGL_ResultChoice']=='HandiLose' || $rs['CGL_ResultChoice']=='Over'){
                    $que1 .= "B_Lose_Cnt 	= B_Lose_Cnt+1, ";
                    $que1 .= "B_Lose_Money 	= B_Lose_Money+{$BettingPrice}, ";
                }
                $que1 .= "B_Game_Key 		= {$rs['G_Key']} ";
                $que1 .= " WHERE B_Game_Key = {$rs['G_Key']} ";

            } else {
                $que1  = "INSERT INTO game_bet_list SET ";
                $que1 .= "B_Game_Key 		= {$rs['G_Key']}, ";
                if($rs['CGL_ResultChoice']=='Win' || $rs['CGL_ResultChoice']=='HandiWin' || $rs['CGL_ResultChoice']=='Under'){
                    $que1 .= "B_Win_Cnt 	= 1, ";
                    $que1 .= "B_Win_Money 	= {$BettingPrice}, ";
                }
                if($rs['CGL_ResultChoice']=='Draw'){
                    $que1 .= "B_Draw_Cnt 	= 1, ";
                    $que1 .= "B_Draw_Money 	= {$BettingPrice}, ";
                }
                if($rs['CGL_ResultChoice']=='Lose' || $rs['CGL_ResultChoice']=='HandiLose' || $rs['CGL_ResultChoice']=='Over'){
                    $que1 .= "B_Lose_Cnt 	= 1, ";
                    $que1 .= "B_Lose_Money 	= {$BettingPrice}, ";
                }
                $que1 .= "B_RegDate 			= NOW() ";
            }
            /*echo $que1."<br>\n";*/
            $res = setQry($que1);
            if(!$res){
                $json['flag'] = false;
                $json['error'] = '배팅 카운터 저장시 오류가 발생했습니다.';
                $fail++;

                //echo json_encode($json);
                //break;
                //echo "fail[3]->".$fail."\n";
            }
        }


        switch($rs['CGL_ResultChoice']){
            case "Win"        : $cgl_result = "승"; 		$cgl_quota = $rs['CGL_QuotaWin']; 		break;
            case "Draw"       : $cgl_result = "무"; 		$cgl_quota = $rs['CGL_QuotaDraw']; 		break;
            case "Lose"       : $cgl_result = "패"; 		$cgl_quota = $rs['CGL_QuotaLose']; 		break;
            case "Under"      : $cgl_result = "언더"; 		$cgl_quota = $rs['CGL_QuotaUnder'];	 	break;
            case "Over"       : $cgl_result = "오버"; 		$cgl_quota = $rs['CGL_QuotaOver']; 		break;
            case "HandiWin"   : $cgl_result = "핸디승"; 	    $cgl_quota = $rs['CGL_QuotaHandiWin']; 	break;
            case "HandiLose"  : $cgl_result = "핸디패"; 	    $cgl_quota = $rs['CGL_QuotaHandiLose'];   break;
            case "Odd"        : $cgl_result = "홀"; 		$cgl_quota = $rs['CGL_QuotaOdd']; 		break;
            case "Even"       : $cgl_result = "짝"; 		$cgl_quota = $rs['CGL_QuotaEven']; 		break;
        };

        $batting_quota *= $cgl_quota;
    }

    $BettingQuota = floor($batting_quota*100);
    $BettingQuota = ($BettingQuota/100);


    $ForecastPrice = $BettingQuota * $BettingPrice;

    $sql = "SELECT COUNT(*) FROM buygamelist WHERE BG_Key = {$bg_key}";
    $row99 = getRow($sql);
    if(!$row99[0] || $row99[0]<1){
        $json['flag'] = false;
        $json['error'] = '구매내역 저장시 오류가 발생했습니다.';
        $fail++;
        echo json_encode($json);
        break;
    }

    if($ForecastPrice>0) {
        $sql = "UPDATE buygame SET ";
        $sql .= "BG_TotalQuota      = '{$BettingQuota}', ";
        $sql .= "BG_ForecastPrice   = '{$ForecastPrice}' ";
        $sql .= " WHERE BG_Key      = '{$bg_key}' ";
        /*echo $sql."<br>\n";*/
        $res = setQry($sql);
        if (!$res) {
            $json['flag'] = false;
            $json['error'] = '배팅내역 저장시 오류가 발생했습니다[B1].';
            $fail++;
            echo json_encode($json);
            break;
        }
    } else {
        $sql1 = "DELETE FROM buygame WHERE BG_Key = {$row2['BG_Key']}";
        setQry($sql1);
        $json['flag'] = false;
        $json['error'] = '배팅내역 저장시 오류가 발생했습니다[B2].';
        $fail++;
        echo json_encode($json);
        break;
    }


    // 머니 차감
    $lib24c->Payment_Money( $_SESSION['S_Key'], "GameBetting", $BettingPrice , "", $bg_key,"" , "" );
    // VIP 포인트 지급

    // 회원 정보 획득

    if ( $meminfo['M_Level'] == 1 && (int)$lib24c->point_info['Betting'] > 0)
        $lib24c->Payment_Point( $_SESSION['S_Key'], "Betting", (int)( $BettingPrice * ($lib24c->point_info['Betting'] / 100) ) ,  "" );

    $sql = "SELECT M_Money FROM members WHERE M_Key = '{$_SESSION['S_Key']}' ";
    $row = getRow($sql);
    $money = $row['M_Money'];
    $json['money'] = $money;
    $json['game_cnt'] = $game_cnt;
    $json['bonus'] = $bonus_is;
    // 장바구니에서 모든 게임 삭제

    $result = $db->Execute("delete from cartgamelist where M_Key=?", array( $_SESSION['S_Key'] ) );
    echo json_encode($json);
}