<?php
ini_set('display_errors', 1);
ini_set("memory_limit", -1);

$include_path = "/home/bs/www/bs";
include $include_path . "/lib/_lib.php";
include_once($include_path . "/include/Snoopy.class.php");

$snoopy = new Snoopy;
$key = 'e446082c-da71-4e2c-8457-9c3ae43c3c8f';
$snoopy->agent = $_SERVER['HTTP_USER_AGENT'];

switch($mode){
    case 'gameSelected'://선태괸 경기 배당률
        $url = 'http://api.oddsapi-inplay.com/bet365/soccer/match?corp=' . $key . '&MI='.$gid;
        //echo $url;
        $snoopy->fetch($url);
        $content = $snoopy->results;
        $data = json_decode($content, true);
        //print_r($data);
        if($data['result']==1){
            $row['gid']             = $gid;
            $row['sportsId']        = $data['_results'][0]['sportsId'];
            $row['sportsName']      = $data['_results'][0]['sportsName'];
            $row['timeStatus']      = $data['_results'][0]['timeStatus'];
            $row['timeM']           = $data['_results'][0]['timeM'];
            $row['timeS']           = $data['_results'][0]['timeS'];
            $row['timeMark']        = $data['_results'][0]['timeMark'];
            $row['timeKorMark']     = $data['_results'][0]['timeKorMark'];
            $row['homeScore']       = $data['_results'][0]['homeScore'];
            $row['awayScore']       = $data['_results'][0]['awayScore'];
            $row['markets']         = $data['_results'][0]['_market'];

            $list[] = $row;

            if($data['_results'][0]['timeStatus'] == 3 || $data['_results'][0]['timeMark'] == 'FT'){
                $sql = "UPDATE gamelist_live SET G_State = 'Stop', status = '{$data['_results'][0]['timeStatus']}' AND G_ID  = '{$rs['G_ID']}'";
                //setQry($sql);
            }

        }

        echo json_encode($list);
        break;
    case 'gameLiveScore': //라이브스코어
        $que = "SELECT * FROM gamelist_live WHERE G_State = 'Await' AND status = '1' AND matchDateTime < NOW()  ";
        //echo $que;
        $arr = getArr($que);
        if(count($arr)>0) {
            foreach ($arr as $rs) {
                $url = 'http://api.oddsapi-inplay.com/bet365/soccer/match?corp=' . $key . '&MI=' . $rs['G_ID'];
                //echo $url;
                $snoopy->fetch($url);
                $content = $snoopy->results;
                $data = json_decode($content, true);
                //print_r($data);
                if ($data['result'] == 1) {
                    //echo count($data['result'][0]['_results']);
                    //if(count($data['result'][0]['_results'])>0) {

                    $row['gid'] = $rs['G_ID'];
                    $row['sportsId'] = $data['_results'][0]['sportsId'];
                    $row['sportsName'] = $data['_results'][0]['sportsName'];
                    $row['timeStatus'] = $data['_results'][0]['timeStatus'];
                    $row['timeM'] = $data['_results'][0]['timeM'];
                    $row['timeS'] = $data['_results'][0]['timeS'];
                    $row['timeMark'] = $data['_results'][0]['timeMark'];
                    $row['timeKorMark'] = $data['_results'][0]['timeKorMark'];
                    $row['homeScore'] = $data['_results'][0]['homeScore'];
                    $row['awayScore'] = $data['_results'][0]['awayScore'];


                    $list[] = $row;

                    if ($data['_results'][0]['timeStatus'] == 3 || $data['_results'][0]['timeMark'] == 'FT') {
                        $sql = "UPDATE gamelist_live SET G_State = 'Stop', status = '{$data['_results'][0]['timeStatus']}' AND G_ID  = '{$rs['G_ID']}'";
                        //setQry($sql);
                    }
                }
            }
        }

        echo json_encode($list);
//}
        break;

    //라이브게임 구매하기
    case 'buyGameLive':

        setQry('BEGIN');
        $json['flag'] = true;
        $json['error'] = '';

        // 로그인 체크
        if ( !$mkey ){
            $json['flag'] = false;
            $json['error'] = '정상적인 접속이 아닙니다1';
            echo json_encode($json);
        }


        $que = "SELECT * FROM members WHERE M_Key = '{$mkey}'";
        //echo $que;
        $mem = getRow($que);

        if ( $mem['M_Money'] < $BettingPrice ){
            $json['flag'] = false;
            $json['error'] = '보유머니가 배팅할려는 금액보다 적습니다.';
            echo json_encode($json);
            return;
        }

        $r = getRow("SELECT M_SportYN, M_Type FROM members WHERE M_Key = {$mkey}");
        if($r[0]=='N'){
            $json['flag'] = false;
            $json['error'] = '배팅 불가능한 회원입니다.';
            echo json_encode($json);
            return;
        }

        $count = 0;

        if($mem['M_Money']<$BettingPrice){
            $json['flag'] = false;
            $json['error'] = '보유머니가 부족합니다. 보유머니 충전 후 배팅하세요.';
            echo json_encode($json);
            return;
        }

        //기타기준점 배팅할경우
        if($other == 'Y'){
            $cd = explode("_",$mcode);
            $mcode = "_".$cd[1]."_".$cd[2]."_".($cd[3]+1);
        }

        /*$que = "SELECT COUNT(*) FROM buygamelist_live WHERE BGL_Num = '{$gid}' AND M_Key = '{$mkey}'";
        $row9 = getRow($que);
        if($row9[0]>2){
            $json['flag'] = false;
            $json['error'] = '동일한 경기에 두번이상 배팅하실 수 없습니다.';
            echo json_encode($json);
            break;
        }*/


        $que = "SELECT * FROM buygamelist_live a LEFT JOIN live_gamelist b ON a.G_Key = b.G_Key WHERE a.M_Key = '{$mkey}' AND b.GI_Key = 154914 AND a.G_Key = '{$gkey}' ";
        //echo $que;
        $row = getRow($que);
        if($row['G_Type2']=='WDL' && ($choice == 'HandiWin' || $choice == 'HandiLose')){
            $json['flag'] = false;
            $json['error'] = '야구 승패와 핸디는 같이 배팅할 수 없습니다.';
            echo json_encode($json);
            break;
        } else if($row['G_Type2']=='Handicap' && ($choice == 'Win' || $choice == 'Lose')){
            $json['flag'] = false;
            $json['error'] = '야구 승패와 핸디는 같이 배팅할 수 없습니다.';
            echo json_encode($json);
            break;
        }


        $que = "SELECT * FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'";
        $rows = getArr($que);
        if(count($rows[0])>0){
            foreach($rows as $rows){

            }
        }

        //득점이 되고 1분30초에 배팅을 하면 취소처리한다.
        if($item != 'basketball') {
            $que = "SELECT * FROM gamelist_live_score WHERE G_ID = '{$gid}' ORDER BY regdate DESC LIMIT 1";
            //echo $que;
            $goal = getRow($que);
            if (!empty($goal['regdate']) && ($goal['h_score'] != 0 || $goal['a_score'] != 0)) {
                $goal_time = strtotime($goal['regdate']) + 100;//득정되고 1분30초 이내면 배팅안됨
                if (mktime() <= $goal_time) {
                    $json['flag'] = false;
                    $json['error'] = '득점 후 1분30초 이내에는 배팅을 하실수 없습니다.';
                    echo json_encode($json);
                    break;
                }
            }
        }



        //코드 번호로 배당을 불러온다.
        $url = 'http://api.oddsapi-inplay.com/bet365/'.$type.'/match?corp=' . $key . '&MI='.$gid;
        //echo $url;
        $snoopy->fetch($url);
        $content = $snoopy->results;
        $data = json_decode($content, true);
        /*print_r($data);*/
        if($data['result']==1) {

            if(empty($type1))   $type1 = 'Full';
            if ($data['_results'][0]['timeStatus'] == 3 || $data['_results'][0]['timeMark'] == 'FT') {
                $sql = "UPDATE live_gamelist SET G_State = 'Stop', status = '{$data['_results'][0]['timeStatus']}' AND G_ID  = '{$rs['G_ID']}'";
                setQry($sql);
                $json['flag'] = false;
                $json['error'] = '경기가 종되었습니다. 배팅하실 수 없습니다.';
                echo json_encode($json);
                break;
            } else {
                if ($data['result'] == 1 && $data['_results'][0]['timeStatus'] == 1) {
                    switch ($choice) {
                        case 'Win':
                        case 'Draw':
                        case 'Lose':
                            $type2 = 'WDL';

                            if (!empty($data['_results'][0]['_market'][$mcode]['type']) && $data['_results'][0]['_market'][$mcode]['suspended']==false) {

                                if($type == 'soccer') {
                                    $home_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                    $draw_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                    $away_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][2]['odds'];
                                    if ($choice == 'Win') {
                                        $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                    } else if ($choice == 'Draw') {
                                        $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                    } else if ($choice == 'Lose') {
                                        $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][2]['odds'];
                                    }
                                } else if($type == 'baseball'){
                                    $home_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                    $away_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                    if ($choice == 'Win') {
                                        $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                    } else if ($choice == 'Lose') {
                                        $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                    }
                                }
                            } else {
                                $fail++;
                            }
                            break;
                        case 'HandiWin':
                        case 'HandiLose':
                            $type2 = 'Handicap';
                            if($other == 'Y'){
                                for($h=0;$h<count($data['_results'][0]['_market'][$mcode]['matchOdds']);$h+=2) {
                                    //echo $data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['id']."==".$id;
                                    if($data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['id']==$id) {

                                        $hwrate = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['odds'];
                                        $hwoption = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['option'];
                                        $hlrate = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h+1]['odds'];
                                        $hloption = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h+1]['option'];
                                        if ($choice == 'HandiWin') {
                                            $rate = $hwrate;
                                            $option = $hwoption;
                                        } else if ($choice == 'HandiLose') {
                                            $rate = $hlrate;
                                            $option = $hloption;
                                        }
                                    }
                                }
                            } else {
                                if (!empty($data['_results'][0]['_market'][$mcode]['type']) && $data['_results'][0]['_market'][$mcode]['suspended'] == false) {
                                    $hwrate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                    $hwoption = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['option'];
                                    $hlrate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                    $hloption = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['option'];
                                    if ($choice == 'HandiWin') {
                                        $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                        $option = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['option'];
                                    } else if ($choice == 'HandiLose') {
                                        $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                        $option = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['option'];
                                    }
                                } else {
                                    $fail++;
                                }


                            }
                            break;
                        case 'Over':
                        case 'Under':
                            $type2 = 'UnderOver';
                            if($other == 'Y'){

                                for($h=0;$h<count($data['_results'][0]['_market'][$mcode]['matchOdds']);$h+=2) {
                                    //echo $data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['id']."==".$id;
                                    if($data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['id']==$id) {

                                        $over = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['odds'];
                                        $ooption = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['option'];
                                        $under = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h+1]['odds'];
                                        $uoption = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h+1]['option'];
                                        if ($choice == 'Over') {
                                            $rate = $over;
                                            $option = $ooption;
                                        } else if ($choice == 'Under') {
                                            $rate = $under;
                                            $option = $uoption;
                                        }
                                    }
                                }
                            } else {
                                if (!empty($data['_results'][0]['_market'][$mcode]['type']) && $data['_results'][0]['_market'][$mcode]['suspended'] == false) {
                                    $over = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                    $ooption = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['option'];
                                    $under = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                    $uoption = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['option'];
                                    if ($choice == 'Over') {
                                        $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                        $option = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['option'];
                                    } else if ($choice == 'Under') {
                                        $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                        $option = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['option'];
                                    }
                                } else {
                                    $fail++;
                                }
                            }
                            break;
                    }


                    $rate = substr($rate, 0, 4);
                    $forecast = $rate * $BettingPrice;
                    $sql = "INSERT INTO buygame_live SET ";
                    $sql .= "M_Key 						= '{$mkey}',";
                    $sql .= "BG_GameCount 				= '1',";
                    $sql .= "BG_CompleteCount 	        = '0',";
                    $sql .= "BG_TotalQuota 				= '{$rate}',";
                    $sql .= "BG_BettingPrice 			= '{$BettingPrice}',";
                    $sql .= "BG_MemberMoney 			= '{$mem['M_Money']}',";
                    $sql .= "BG_ForecastPrice 			= '{$forecast}',";
                    $sql .= "BG_Result 					= 'Await',";
                    $sql .= "BG_Gubun 				    = 'live', ";
                    $sql .= "BG_BuyDate 				= NOW()";
                    //echo $sql;
                    $res = setQry($sql);
                    if (!$res) {
                        $json['flag'] = false;
                        $json['error'] = '구매내역 저장시 오류가 발생했습니다.';
                        $fail++;
                    }
                    $bgkey = mysql_insert_id();

                    //281262||2020-05-28 03:10:00||Full||WDL||스탈 미엘레츠||레흐 포즈난||4.25||3.78||1.79||6046||15194

                    if ($choice == 'HandiWin') {
                        $hoption = $hwoption;
                    } else {
                        $hoption = $hloption;
                    }

                    $bet = $gkey.'||'.$home_rate.$hwrate.$over.'||'.$draw_rate.$hoption.$ooption.'||'.$away_rate.$hlrate.$under;
                    $sql = "INSERT INTO buygamelist_live SET ";
                    $sql .= "BG_Key              = '{$bgkey}', ";
                    $sql .= "G_Key               = '{$gkey}',";
                    $sql .= "GL_Key              = '{$rs['GL_Key']}',";
                    $sql .= "M_Key               = '{$mkey}',";
                    $sql .= "G_Type1             = '{$type1}',";
                    $sql .= "G_Type2             = '{$type2}',";
                    $sql .= "BGL_Num            = '{$gid}',";

                    $sql .= "BGL_QuotaWin        = '" . substr($home_rate, 0, 4) . "',";
                    $sql .= "BGL_QuotaDraw       = '" . substr($draw_rate, 0, 4) . "',";
                    $sql .= "BGL_QuotaLose       = '" . substr($away_rate, 0, 4) . "',";
                    $sql .= "BGL_QuotaHandiWin   = '" . substr($hwrate, 0, 4) . "', ";
                    if ($choice == 'HandiWin') {
                        $sql .= "BGL_QuotaHandicap   = '{$hwoption}',";
                    } else {
                        $sql .= "BGL_QuotaHandicap   = '{$hloption}', ";
                    }
                    $sql .= "BGL_QuotaHandiLose  = '" . substr($hlrate, 0, 4) . "',";

                    $sql .= "BGL_QuotaUnderOver  = '" . substr($ooption, 0, 4) . "',";
                    $sql .= "BGL_QuotaUnder      = '{$under}',";
                    $sql .= "BGL_QuotaOver       = '" . substr($over, 0, 4) . "',";

                    $sql .= "BGL_QuotaOdd        = '',";
                    $sql .= "BGL_QuotaEven       = '',";
                    $sql .= "BGL_ResultChoice    = '{$choice}',";
                    $sql .= "BGL_Bet			 = '{$bet}',";
                    $sql .= "BGL_IP          	 = '{$_SERVER['REMOTE_ADDR']}',";
                    $sql .= "BGL_State     	     = 'Await',";
                    $sql .= "BGL_Start     	     = '{$grade}',";
                    //$sql .= "BGL_Bet             = '{$bet}', ";
                    $sql .= "BGL_RegDate         =  NOW() ";
                    //echo $sql;
                    $res = setQry($sql);
                    if (!$res) {
                        $fail++;
                    }

                    $sql = "INSERT INTO moneyinfo SET ";
                    $sql .= "M_Key          = '{$mkey}', ";
                    $sql .= "MI_Type        = 'GameBetting', ";
                    $sql .= "PI_Key         = '', ";
                    $sql .= "BG_Key         = '{$bgkey}', ";
                    $sql .= "R_Key          = '', ";
                    $sql .= "MI_Money       = '-{$BettingPrice}', ";
                    $sql .= "MI_Prev_Money  = '{$mem['M_Money']}', ";
                    $sql .= "MI_Memo        = '게임을 구매하였습니다.', ";
                    $sql .= "MI_RegDate     = NOW() ";
                    //echo $sql;
                    $res = setQry($sql);
                    if (!$res) {
                        $fail++;
                    }

                    $sql = "UPDATE members SET M_Money = M_Money - {$BettingPrice} WHERE M_Key = '{$mkey}'";
                    $res = setQry($sql);
                    if (!$res) {
                        $fail++;
                    }

                    if ($fail > 0) {
                        setQry('ROLLBACK');
                        $json['flag'] = false;
                        $json['error'] = '구매내역 저장시 문제가 발생했습니다.';
                        echo json_encode($json);
                    } else {
                        setQry('COMMIT');
                        echo json_encode($json);
                    }
                }
            }
        }
        break;
}
