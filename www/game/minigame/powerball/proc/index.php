<?php
$include_path = $_SERVER['DOCUMENT_ROOT'];
include $include_path."/include/common.php";


if ( !$_SESSION['S_ID'] ) $lib->AlertMSG( "정상적인 접속이 아닙니다.", "", 0, "parent");

switch($_POST['HAF_Value_0'])
{
    #선택경기 구매
    case 'BuyCart':

        setQry("BEGIN");

        $fail = 0;
        $total_rate = 1;
        $bet_money      = $_POST['HAF_Value_1']; //배팅금액
        $hit_money      = $_POST['HAF_Value_2']; //예상당첨금액
        $game_num       = $_POST['HAF_Value_3'];//회차
        $game_gubun     = $_POST['HAF_Value_4'];//게임구분
        $choice_rate    = $_POST['HAF_Value_5'];//선택배당률
        $gkey           = $_POST['HAF_Value_6'];//경기번호
        $glkey          = $_POST['HAF_Value_7'];//리그키
        $gametime       = $_POST['HAF_Value_8'];//마감시간
        $allrate        = $_POST['HAF_Value_9'];//배당률
        $bet_gubun      = $_POST['HAF_Value_10'];//배팅구분



        #다시한번 보유 머니를 확인 한다.
        $que = "SELECT M_Money, M_ID, M_NICK FROM members WHERE M_Key = {$_SESSION['S_Key']}";
        $row = getRow($que);
        if($row['M_Money']<0){
            $lib->AlertMSG( "보유머니가 부족합니다. 보유머니 충전 후 이용해주세요1." );
            $fail++;
        }

        $nick = trim($row['M_NICK']);
        $mid = trim($row['M_ID']);

        if($row['M_Money']-$bet_money<0){
            //$lib->AlertMSG( $row[M_Money].'-'.$bet_money );
            $lib->AlertMSG( "보유머니가 부족합니다. 보유머니 충전 후 이용해주세요2." );
            $fail++;
        }


        if($bet_money<5000){
            $lib->AlertMsg('최소 배팅 금액은 5,000원 입니다.');
            $fail++;
        }

        if($choice_rate>0){
            $total_rate *= $choice_rate;
        }


        //배당률 소수 2자리로 만들기
        $total_rate = sprintf('%01.2f',$total_rate);

        //당첨금액 만들기
        $hit_money = (float)$total_rate*$bet_money;

        //배팅 가능시간 체크
        /*if($tmp[2]==1){
            #사다리일 경우만 2분 마감한다.
            $bettime = strtotime("+1 seconds",strtotime(date("Y-m-d H:i:s")));
        } else if($tmp[2]==2 || $tmp[2]==6 || $tmp[2]==5){
            #파워볼 주만지 다리다리일 경우만 1분로 마감한다.
            $bettime = strtotime("+65 seconds",strtotime(date("Y-m-d H:i:s")));
        } else if($tmp[2]==3){
            #달팽이 경우만 1분20분로 마감한다.
            $bettime = strtotime("+87 seconds",strtotime(date("Y-m-d H:i:s")));
        } else if($tmp[2]==7){
            #mgm일 경우만 10초로 마감한다.
            $bettime = strtotime("+20 seconds",strtotime(date("Y-m-d H:i:s")));
        } else {
            $bettime = strtotime("+47 seconds",strtotime(date("Y-m-d H:i:s")));
            if($tmp[2]==5 && $game_num == 480){//다리다리일경우
                $bettime = strtotime("+118 seconds",strtotime(date("Y-m-d H:i:s")));
            }
        }*/

        $gametime = strtotime($gametime);
        $bettime = strtotime("+40 seconds",strtotime(date("Y-m-d H:i:s")));

        //echo $bettime."->".$gametime;
        if($bettime > $gametime) $lib->AlertMsg("배팅가능한 시간이 아닙니다. 다음 게임을 이용해주세요.".$gametime.":".$bettime);

        //$que = "SELECT COUNT(*) FROM buygame WHERE  ";
        #마지막 배팅시간을 구한다.
        $sql = "SELECT BG_BuyDate FROM buygame WHERE M_Key = {$_SESSION['S_Key']} ORDER BY BG_BuyDate DESC LIMIT 1";
        //echo $que."<br>";
        $sql_row = getRow($sql);
        //echo (int)strtotime($row[BG_BuyDate]);
        $last_bet_time = time()-(int)strtotime($sql_row['BG_BuyDate']);
        //$lib->AlertMsg($que);
        if($last_bet_time < 3){
            $lib->AlertMsg("3초이내 배팅은 불가능 합니다..");
        }

        unset($sql);
        unset($sql_row);

        $que  = "INSERT INTO buygame SET ";
        $que .= "M_Key		 		= {$_SESSION['S_Key']}, ";
        if($game_gubun=='PB'){
            $que .= "BG_GameGubun		= 2, ";
        } else if($game_gubun=='PBL'){
            $que .= "BG_GameGubun		= 1, ";
        } else  if($game_gubun=='dal'){
            $que .= "BG_GameGubun		= 3, ";
        } else  if($game_gubun=='soccerp'){
            $que .= "BG_GameGubun		= 4, ";
        } else if($game_gubun=='ladder'){
            $que .= "BG_GameGubun		= 5, ";
        } else if($game_gubun=='dice2'){
            $que .= "BG_GameGubun		= 6, ";
        } else if($game_gubun=='mgmodd'){
            $que .= "BG_GameGubun		= 7, ";
        }

        $que .= "BG_TotalQuota 		= {$total_rate}, ";
        $que .= "BG_BettingPrice 	= {$bet_money}, ";
        $que .= "BG_MemberMoney 	= {$row['M_Money']}, ";
        $que .= "BG_ForecastPrice 	= {$hit_money}, ";
        $que .= "BG_Result 			= 'Await', ";
        $que .= "BG_Gubun 			= '{$game_gubun}', ";
        $que .= "BG_BuyDate 		= NOW() ";
        //echo $que."<br>";
        $res = setQry($que) or die(mysql_error());
        $id = mysql_insert_id();
        if(!$res){
            $lib->AlertMsg('구매내역 저장시 문제가 발생했습니다. 다시 시도해주세요1.');
            $fail++;
        }


        #구매 배당률이 잘못된 경우
        if($total_rate == '1.00'){
            $lib->AlertMsg('구매내역 저장시 배당률 문제가 발생했습니다. 다시 시도해주세요2.');
            $fail++;
        }

        //미니게임 배당률
        $rate = explode("|",$allrate);

        $sql  = "INSERT INTO buygamelist SET ";
        $sql .= "M_Key		 			= {$_SESSION['S_Key']}, ";
        $sql .= "BG_Key		 			= {$id}, ";
        $sql .= "G_Key		 			= '{$gkey}', ";
        $sql .= "GL_Key		 			= '{$glkey}', ";
        if($_POST['HAF_Value_4']=='PB'){
            switch ($bet_gubun) {
                case 'Small':
                    $choice = 'Small';
                    $sql .= "BGL_QuotaSmall		 	= '{$rate[0]}', ";
                    $sql .= "BGL_QuotaMiddle		= '{$rate[1]}', ";
                    $sql .= "BGL_QuotaBig		 	= '{$rate[2]}', ";
                    break;
                case 'Middle':
                    $choice = 'Middle';
                    $sql .= "BGL_QuotaSmall		 	= '{$rate[0]}', ";
                    $sql .= "BGL_QuotaMiddle		= '{$rate[1]}', ";
                    $sql .= "BGL_QuotaBig		 	= '{$rate[2]}', ";
                    break;
                case 'Big':
                    $choice = 'Big';
                    $sql .= "BGL_QuotaSmall		 	= '{$rate[0]}', ";
                    $sql .= "BGL_QuotaMiddle		= '{$rate[1]}', ";
                    $sql .= "BGL_QuotaBig		 	= '{$rate[2]}', ";
                    break;
                case 'Even':
                    $choice = 'Even';
                    $sql .= "BGL_Odd		 		= {$rate[0]}, ";
                    $sql .= "BGL_Even		 		= {$rate[1]}, ";
                    break;
                case 'Odd':
                    $choice = 'Odd';
                    $sql .= "BGL_Odd		 		= {$rate[0]}, ";
                    $sql .= "BGL_Even		 		= {$rate[1]}, ";
                    break;
                case 'Under':
                    $choice = 'Under';
                    $sql .= "BGL_QuotaUnder	 		= {$rate[0]}, ";
                    $sql .= "BGL_QuotaOver	 		= {$rate[1]}, ";
                    break;
                case 'Over':
                    $choice = 'Over';
                    $sql .= "BGL_QuotaUnder	 		= {$rate[0]}, ";
                    $sql .= "BGL_QuotaOver	 		= {$rate[1]}, ";
                    break;
                case 'OddUnder':
                    $choice = 'Under';
                    $sql .= "BGL_QuotaUnder	 		= {$rate[0]}, ";
                    $sql .= "BGL_QuotaOver	 		= {$rate[1]}, ";
                    break;
                case 'OddOver':
                    $choice = 'Over';
                    $sql .= "BGL_QuotaUnder	 		= {$rate[0]}, ";
                    $sql .= "BGL_QuotaOver	 		= {$rate[1]}, ";
                    break;
                case 'EvenUnder':
                    $choice = 'Under';
                    $sql .= "BGL_QuotaUnder	 		= {$rate[0]}, ";
                    $sql .= "BGL_QuotaOver	 		= {$rate[1]}, ";
                    break;
                case 'EvenOver':
                    $choice = 'Over';
                    $sql .= "BGL_QuotaUnder	 		= {$rate[0]}, ";
                    $sql .= "BGL_QuotaOver	 		= {$rate[1]}, ";
                    break;
                case 'OddSmall':
                    $choice = 'Small';
                    $sql .= "BGL_QuotaSmall		 	= '{$rate[0]}', ";
                    $sql .= "BGL_QuotaMiddle		= '{$rate[1]}', ";
                    $sql .= "BGL_QuotaBig		 	= '{$rate[2]}', ";
                    break;
                case 'OddMiddle':
                    $choice = 'Middle';
                    $sql .= "BGL_QuotaSmall		 	= '{$rate[0]}', ";
                    $sql .= "BGL_QuotaMiddle		= '{$rate[1]}', ";
                    $sql .= "BGL_QuotaBig		 	= '{$rate[2]}', ";
                    break;
                case 'OddBig':
                    $choice = 'Big';
                    $sql .= "BGL_QuotaSmall		 	= '{$rate[0]}', ";
                    $sql .= "BGL_QuotaMiddle		= '{$rate[1]}', ";
                    $sql .= "BGL_QuotaBig		 	= '{$rate[2]}', ";
                    break;
                case 'EvenSmall':
                    $choice = 'Small';
                    $sql .= "BGL_QuotaSmall		 	= '{$rate[0]}', ";
                    $sql .= "BGL_QuotaMiddle		= '{$rate[1]}', ";
                    $sql .= "BGL_QuotaBig		 	= '{$rate[2]}', ";
                    break;
                case 'EvenMiddle':
                    $choice = 'Middle';
                    $sql .= "BGL_QuotaSmall		 	= '{$rate[0]}', ";
                    $sql .= "BGL_QuotaMiddle		= '{$rate[1]}', ";
                    $sql .= "BGL_QuotaBig		 	= '{$rate[2]}', ";
                    break;
                case 'EvenBig':
                    $choice = 'Big';
                    $sql .= "BGL_QuotaSmall		 	= '{$rate[0]}', ";
                    $sql .= "BGL_QuotaMiddle		= '{$rate[1]}', ";
                    $sql .= "BGL_QuotaBig		 	= '{$rate[2]}', ";
                    break;
                default :
                    $choice = $bet_gubun;
                    for($i=0;$i<=9;$i++){
                        $sql .= "BGL_Section{$i}	= '{$rate[0]}', ";
                    }
                    break;

            }
        } else if($game_gubun=='PBL') {
            //echo $bet_gubun;
            switch ($bet_gubun) {
                case 'Right':
                    $choice = 'Even';
                    break;
                case 'Left':
                    $choice = 'Odd';
                    break;
                case 'Line4':
                    $choice = 'Even';
                    break;
                case 'Line3':
                    $choice = 'Odd';
                    break;
                case 'Even':
                    $choice = 'Even';
                    break;
                case 'Odd':
                    $choice = 'Odd';
                    break;
                case 'Under':
                    $choice = 'Odd';
                    break;
                case 'Over':
                    $choice = 'Even';
                    break;
                case 'L3E':
                    $choice = 'Odd';
                    break;
                case 'L4O':
                    $choice = 'Even';
                    break;

                case 'R3O':
                    $choice = 'Odd';
                    break;
                case 'R4E':
                    $choice = 'Even';
                    break;

            }
        }

        if($choice==''){
            $lib->AlertMsg('$choice=>'.$choice);
            $fail++;
        }

        $bet = $gkey."||".$glkey."||".$gametime."||".$bet_money."||".$hit_money."||".$game_num."||".$game_gubun."||".$choice_rate."||".$allrate."||".$bet_gubun;


        $sql .= "BGL_ResultChoice   		= '{$choice}', " ;
        $sql .= "BGL_State   				= 'Await', " ;
        $sql .= "BGL_Start   				= '{$start}', " ;
        $sql .= "BGL_End   					= '{$end}', " ;
        $sql .= "BGL_Num	   				= '{$_POST['HAF_Value_3']}', " ;
        //$sql .= "BGL_Bet					= '{$_POST['HAF_Value_2']}', ";
        $sql .= "BGL_Bet					= '{$bet}', ";
        $sql .= "BGL_RegDate				= NOW(), ";
        $sql .= "BGL_IP				   		= '".$_SERVER['REMOTE_ADDR']."' " ;
        echo $sql."<br>";

        $sql_res = setQry($sql);
        if(!$sql_res){
            //$lib->AlertMsg('구매내역 입력시 오류가 발생했습니다1.');
            $fail++;
        }

        // 머니 차감
        $lib24c->Payment_Money( $_SESSION['S_Key'], "GameBetting", $_POST['HAF_Value_1'] , "", $id,"" , "" );

        // 회원 정보 획득
        $mrow = $lib24c->Member_Info( $row['M_Key'], 'Y' );
        if ( $mrow['M_Level'] == 1 && (int)$lib24c->point_info['Betting'] > 0)
            $lib24c->Payment_Point( $_SESSION['S_Key'], "Betting", (int)( $_POST['HAF_Value_1'] * ($lib24c->point_info['Betting'] / 100) ) ,  "" );

        if($fail>0){
            setQry("ROLLBACK");
        } else {
            setQry("COMMIT");
            //echo '<script>parent.game_result_rollback();</script>';
            //swal_reload('배팅이 정상적으로 완료되었습니다.');
            /*if(in_array($_POST['HAF_Value_4'],array('power','soccerp','dice2','ladder','mgmodd'))) {
                echo '<script>parent.location.reload();</script>';
            } else {
                echo '<script>parent.parent.location.reload();</script>';
            }*/
           /* echo '<link rel="stylesheet" href="/css/sweetalert.css" />';
            echo '<script src="/js/sweetalert.js"></script>';
            echo '<script>swal("","배팅이 완료되었습니다.","success");parent.location.reload();</script>';*/
        }
        break;


    #스타 업데이트 & 공지 업데이트
    case 'deleteBetList':
        $json['flag'] = true;
        $json['error'] = '';
        $que = "UPDATE buygame SET BG_Visible = '0' WHERE BG_Key = '{$bgkey}' ";
        $res = setQry($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '삭제시 업데이트 오류';
        }
        //echo $que;
        echo json_encode($json);
        break;
}
?>