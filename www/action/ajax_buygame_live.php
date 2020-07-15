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
        return;
    }

    $r = getRow("SELECT M_SportYN, M_Type FROM members WHERE M_Key = {$_SESSION['S_Key']}");
    if($r[0]=='N'){
        $json['flag'] = false;
        $json['error'] = '배팅 불가능한 회원입니다.';
        echo json_encode($json);
        return;
    }

    $count = 0;

    if($meminfo['M_Money']<$BettingPrice){
        $json['flag'] = false;
        $json['error'] = '보유머니가 부족합니다. 보유머니 충전 후 배팅하세요.';
        echo json_encode($json);
        return;
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
        return;
    }

    //장바구니에 담긴 게임갯수
    $game_cnt = $row[0];



    if($BettingPrice>$LEVELLIMITED['Sports_Max_Bet_Money']){
        $json['flag'] = false;
        $json['error'] = '배팅가능한 한도를 초과하셨습니다..';
        $fail++;
        echo json_encode($json);
        return;
    }

    if($hitmoney>$LEVELLIMITED['Sports_Max_Hit_Mone']){
        $json['flag'] = false;
        $json['error'] = '당첨가능한 한도를 초과하셨습니다.';
        $fail++;
        echo json_encode($json);
        return;
    }


    //야구는 승패/핸디 묶음 배팅이 안되게 구매내역에서 확인하기
    $sql  = "INSERT INTO buygame SET ";
    $sql .= "M_Key 						= '{$_SESSION['S_Key']}',";
    $sql .= "BG_GameCount 				= '1',";
    $sql .= "BG_CompleteCount 	        = '0',";
    $sql .= "BG_TotalQuota 				= '0',";
    $sql .= "BG_BettingPrice 			= '{$BettingPrice}',";
    $sql .= "BG_MemberMoney 			= '{$meminfo['M_Money']}',";
    $sql .= "BG_ForecastPrice 			= '0',";
    $sql .= "BG_Result 					= 'Await',";
    $sql .= "BG_Gubun 				    = 'live', ";
    $sql .= "BG_BuyDate 				= NOW()";
    //echo $sql;
    $res = setQry($sql);
    if(!$res){
        $json['flag'] = false;
        $json['error'] = '구매내역 저장시 오류가 발생했습니다.';
        $fail++;
        echo json_encode($json);
        return;
    }

    //해당 경기가 종료 되었는가 체크
    $bettime = date("Y-m-d H:i:s",strtotime("+".$SITECONFIG['sport_bet_endtime']." minutes",strtotime(date("Y-m-d H:i:s"))));

    if($bettime > $rs['G_Datetime']){
        $json['flag'] = false;
        $json['error'] = '경기 시간이 지난 게임은 배팅이 불가능합니다.';
        echo json_encode($json);
        return;
    }

    if($rs['G_Locked'] != NULL) {//보너스 게임일때는 경기가 없다.
        if ($rs['G_Locked'] != '1') {
            $json['flag'] = false;
            $json['error'] = '시작되거나 취소,마감된 경기가 있어서 배팅이 불가능 합니다.';
            echo json_encode($json);
            return;
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
    }

    // 머니 차감
    $lib24c->Payment_Money( $_SESSION['S_Key'], "GameBetting", $BettingPrice , "", $bg_key,"" , "" );


    $sql = "SELECT M_Money FROM members WHERE M_Key = '{$_SESSION['S_Key']}' ";
    $row = getRow($sql);
    $money = $row['M_Money'];
    $json['money'] = $money;
    $json['game_cnt'] = $game_cnt;
    $json['bonus'] = $bonus_is;

