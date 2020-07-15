<?php
    include_once($_SERVER['DOCUMENT_ROOT']."/m/include/common.php");

    setQry("BEGIN");

    $json['flag'] = true;
    $json['error'] = "";
    $fail = 0;
    $BettingPrice = $_REQUEST['BettingMoney'];

    // 로그인 체크
    if ( !$_SESSION['S_Key'] ){
        $json['flag'] = false;
        $json['error'] = '정상적인 접속이 아닙니다1';
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 정상적인 접속이 아닙니다1'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
        $fail++;
    }

    if ( $meminfo['M_Money'] < $BettingPrice ){
        $json['flag'] = false;
        $json['error'] = '보유머니가 배팅할려는 금액보다 적습니다.';
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 보유머니가 배팅할려는 금액보다 적습니다.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
        $fail++;
    }

    $r = getRow("SELECT M_SportYN, M_Type FROM members WHERE M_Key = {$_SESSION['S_Key']}");
    if($r[0]=='N'){
        $json['flag'] = false;
        $json['error'] = '배팅 불가능한 회원입니다.';
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 배팅 불가능한 회원입니다.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
        $fail++;
    }

    $count = 0;

    if($meminfo['M_Money']<$BettingPrice){
        $json['flag'] = false;
        $json['error'] = '보유머니가 부족합니다. 보유머니 충전 후 배팅하세요.';
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 보유머니가 부족합니다. 보유머니 충전 후 배팅하세요.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
        $fail++;
    }

    $game_cnt = 0;
    $que = "SELECT COUNT(*) FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'";
    /*echo $que;*/
    $row = getRow($que);
    error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 쿼리 : '.$que.PHP_EOL,3,"/home/trend/www/m/log/buygame.log");

    if(!$row[0]){
        $json['flag'] = false;
        $json['error'] = '구매할 게임이 없습니다[first].';
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 구매할 게임이 없습니다[first]'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
        $fail++;
    }

    //장바구니에 담긴 게임갯수
    $game_cnt = $row[0];

    if($row[0]==1 && $meminfo['M_One_Stop']=='Y'){
        $json['flag'] = false;
        $json['error'] = '단폴 배팅을 하실 수 없습니다.';
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 단폴 배팅을 하실 수 없습니다.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
        $fail++;
    }

    if($row[0]==2 && $meminfo['M_Two_Stop']=='Y'){
        $json['flag'] = false;
        $json['error'] = '두폴 배팅을 하실 수 없습니다.';
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 두폴 배팅을 하실 수 없습니다.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
        $fail++;
    }


    if($BettingPrice>$LEVELLIMITED['Sports_Max_Bet_Money']){
        $json['flag'] = false;
        $json['error'] = '배팅가능한 한도를 초과하셨습니다.';
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 배팅가능한 한도를 초과하셨습니다.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
        $fail++;
    }

    if($hitmoney>$LEVELLIMITED['Sports_Max_Hit_Mone']){
        $json['flag'] = false;
        $json['error'] = '당첨가능한 한도를 초과하셨습니다.';
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 당첨가능한 한도를 초과하셨습니다.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
        $fail++;
    }




    $batting_quota_chk = 1;
    $bonus_is = '';
    //현재 회원의 카트에 담긴 게임들을 모두 가져온다.
    $wdl_cnt = $handi_cnt = 0;
    $game_list_handi = $game_list_wdl = '';
    $sql = "SELECT a.*, b.GI_Key  FROM cartgamelist a LEFT JOIN gamelist b ON a.G_Key = b.G_Key WHERE M_Key = '{$_SESSION['S_Key']}'";
    $arr = getArr($sql);
    error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 쿼리 : '.$sql.PHP_EOL,3,"/home/trend/www/m/log/buygame.log");
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
                error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 당첨가능한 한도를 초과하셨습니다.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
                $fail++;
            }


            //보너스 경기가 있고 배팅한 경기가 보너스 배당보다 적을경우 보너스 배당을 없앤다.
            if ($arr['G_Key'] == 1) {
                $bonus_is = $arr['G_Key'];
            }


            if($arr['G_Key']==1 && $game_cnt < 3){
                $json['flag'] = false;
                $json['error'] = '배팅카트에 게임이 정상적으로 담기지 않았습니다. 다시 배팅해주세요.';
                $fail++;
                error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 배팅카트에 게임이 정상적으로 담기지 않았습니다. 다시 배팅해주세요[3].'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
            } else if($arr['G_Key']==2 && $game_cnt < 5){
                $json['flag'] = false;
                $json['error'] = '배팅카트에 게임이 정상적으로 담기지 않았습니다. 다시 배팅해주세요.';
                $fail++;
                error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 배팅카트에 게임이 정상적으로 담기지 않았습니다. 다시 배팅해주세요[5].'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
            } else if($arr['G_Key']==3 && $game_cnt < 7){
                $json['flag'] = false;
                $json['error'] = '배팅카트에 게임이 정상적으로 담기지 않았습니다. 다시 배팅해주세요.';
                $fail++;
                error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 배팅카트에 게임이 정상적으로 담기지 않았습니다. 다시 배팅해주세요.[7]'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
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
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 최대 배팅 가능한 배당률은 100배 입니다.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
        $fail++;
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
    $sql .= "BG_Gubun 				    = 'prematch', ";
    $sql .= "BG_BuyDate 				= NOW()";
    //echo $sql;
    $res = setQry($sql);
error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 쿼리 : '.$sql.PHP_EOL,3,"/home/trend/www/m/log/buygame.log");
    if(!$res){
        $json['flag'] = false;
        $json['error'] = '구매내역 저장시 오류가 발생했습니다.';
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : INSERT INTO buygame [first]'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
        $fail++;
    }

    //echo "fail[1]->".$fail."\n";
    $bg_key = mysql_insert_id();
    $batting_quota = 1;
    $ForecastPrice = 0;

    $que = "SELECT COUNT(*) AS cart_cnt FROM cartgamelist WHERE M_Key='{$_SESSION['S_Key']}' ";
    $row_c = getRow($que);
error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 쿼리 : '.$que.PHP_EOL,3,"/home/trend/www/m/log/buygame.log");
    if(!$row_c['cart_cnt']){
        $json['flag'] = false;
        $json['error'] = '카트에 담긴 게임이 없습니다.';
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 카트에 담긴 게임이 없습니다.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
        $fail++;
    }

    $que = "SELECT a.*, b.G_State FROM cartgamelist a LEFT JOIN gamelist b ON a.G_Key=b.G_Key WHERE a.M_Key='{$_SESSION['S_Key']}' ORDER BY a.CGL_RegDate ASC";
    //echo $que."<p>";
    $rs = getArr($que);
error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 쿼리 : '.$que.PHP_EOL,3,"/home/trend/www/m/log/buygame.log");
    if(count($rs)>0){

        foreach($rs as $rs) {

            //해당 경기가 종료 되었는가 체크
            $bettime = date("Y-m-d H:i:s",strtotime("+".$SITECONFIG['sport_bet_endtime']." minutes",strtotime(date("Y-m-d H:i:s"))));

            if($bettime > $rs['G_Datetime']){
                $json['flag'] = false;
                $json['error'] = '경기 시간이 지난 게임은 배팅이 불가능합니다.';
                error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 경기 시간이 지난 게임은 배팅이 불가능합니다.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
                $fail++;
            }

            if($rs['G_Locked'] != NULL) {//보너스 게임일때는 경기가 없다.
                if ($rs['G_Locked'] != '1') {
                    $json['flag'] = false;
                    $json['error'] = '시작되거나 취소,마감된 경기가 있어서 배팅이 불가능 합니다.';
                    error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 시작되거나 취소,마감된 경기가 있어서 배팅이 불가능 합니다.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
                    $fail++;
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
            error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 쿼리 : '.$sql.PHP_EOL,3,"/home/trend/www/m/log/buygame.log");
            if(!$res){
                $json['flag'] = false;
                $json['error'] = '구매상세내역 저장시 오류가 발생했습니다.';
                error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 구매상세내역 저장시 오류가 발생했습니다.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
                $fail++;
            }

            $bgl_key = mysql_insert_id();

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
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 쿼리 : '.$sql.PHP_EOL,3,"/home/trend/www/m/log/buygame.log");
        if(!$row99[0] || $row99[0]<1){
            $json['flag'] = false;
            $json['error'] = '구매내역 저장시 오류가 발생했습니다.';
            error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 구매내역 저장시 오류가 발생했습니다.'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
            $fail++;
        }


        $sql = "UPDATE buygame SET ";
        $sql .= "BG_TotalQuota      = '{$BettingQuota}', ";
        $sql .= "BG_ForecastPrice   = '{$ForecastPrice}' ";
        $sql .= " WHERE BG_Key      = '{$bg_key}' ";
        /*echo $sql."<br>\n";*/
        $res = setQry($sql);
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 쿼리 : '.$sql.PHP_EOL,3,"/home/trend/www/m/log/buygame.log");
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '배팅내역 저장시 오류가 발생했습니다[BT].';
            error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 배팅내역 저장시 오류가 발생했습니다[BT].'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
            $fail++;
        }


        $sql = "SELECT * FROM buygame WHERE BG_TotalQuota = 0 AND BG_Key = '{$bg_key}'";
        $row2 = getRow($sql);
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 쿼리 : '.$sql.PHP_EOL,3,"/home/trend/www/m/log/buygame.log");
        if($row2['BG_Key']) {
            $sql1 = "DELETE FROM buygame WHERE BG_Key = {$row2['BG_Key']}";
            setQry($sql1);
            error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 쿼리 : '.$sql1.PHP_EOL,3,"/home/trend/www/m/log/buygame.log");
            $json['flag'] = false;
            $json['error'] = '배팅내역 저장시 오류가 발생했습니다[B0].';
            error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 배팅내역 저장시 오류가 발생했습니다[B0]'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
            $fail++;
        }



        $que = "SELECT COUNT(*) FROM buygamelist WHERE BG_Key = {$bg_key}";
        //echo $que;
        $bg_count = getRow($que);
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 쿼리 : '.$que.PHP_EOL,3,"/home/trend/www/m/log/buygame.log");
        if($bg_count[0] != $row_c['cart_cnt']){
            $json['flag'] = false;
            $json['error'] = '머니로그에러[ME].';
            error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 머니로그에러[ME]'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
            $fail++;
        }

        //머니 로그 만들기
        $que  = "INSERT INTO moneyinfo SET ";
        $que .= "M_Key          = '{$_SESSION['S_Key']}', ";
        $que .= "MI_Type        = 'GameBetting', ";
        $que .= "BG_Key         = '{$bg_key}', ";
        $que .= "MI_Money       = '-".$BettingPrice."', ";
        $que .= "MI_Prev_Money  = '{$meminfo['M_Money']}', ";
        $que .= "MI_Memo        = '게임을 구매하였습니다.', ";
        $que .= "MI_RegDate = NOW() ";
        $res = setQry($que);
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 쿼리 : '.$que.PHP_EOL,3,"/home/trend/www/m/log/buygame.log");
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '머니로그에러[ME].';
            error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 머니로그에러[ME]'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
            $fail++;
        }

        $que = "UPDATE members SET M_Money = M_Money - {$BettingPrice} WHERE M_Key = {$_SESSION['S_Key']}";
        $res = setQry($que);
        error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 쿼리 : '.$que.PHP_EOL,3,"/home/trend/www/m/log/buygame.log");
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '머니업데이트[MMU].';
            error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 오류 : 머니업데이트[MMU]'.PHP_EOL,3,"/home/trend/www/m/log/buygame_error.log");
            $fail++;
        }


        //echo "실패한 갯수는 : ".$fail;
        if($fail>0){
            setQry('ROLLBACK');
            echo json_encode($json);
        } else {
            setQry('COMMIT');
            echo json_encode($json);
        }

    }
