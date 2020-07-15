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

    break;
    case 'gameLiveScore': //라이브스코어

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


        $url = 'http://api.oddsapi-inplay.com/bet365/'.$type.'/match?corp=' . $key . '&MI='.$gid;
        //echo $url;
        $snoopy->fetch($url);
        $content = $snoopy->results;
        $data = json_decode($content, true);
        //print_r($data);
        if($data['result']==1){


            if($data['_results'][0]['timeStatus'] == 3 || $data['_results'][0]['timeMark'] == 'FT' ||  $data['_results'][0]['timeMark'] == 'HT'){
                $sql = "UPDATE gamelist_live SET G_State = 'Stop', status = '{$data['_results'][0]['timeStatus']}' AND G_ID  = '{$rs['G_ID']}'";
                setQry($sql);
                $json['flag'] = false;
                $json['error'] = '경기가 종되었습니다. 배팅하실 수 없습니다.';
                echo json_encode($json);
                break;
            } else {
                /*if($type == 'soccer'){*/
                    //최종승무패 01 90
                    $url = 'http://api.oddsapi-inplay.com/bet365/'.$type.'/match?corp=' . $key . '&MI='.$gid;
                    //echo $url;
                    $snoopy->fetch($url);
                    $content = $snoopy->results;
                    $data = json_decode($content, true);
                    //print_r($data);

                    switch($choice) {
                        case 'Win':
                        case 'Draw':
                        case 'Lose':
                            $type2 = 'WDL';
                            if (!empty($data['_results'][0]['_market'][$mcode]['type'])) {
                                $home_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                $draw_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                $away_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][2]['odds'];
                                if($choice == 'Win'){
                                    $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                } else if($choice == 'Draw'){
                                    $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                } else if($choice == 'Lose'){
                                    $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][2]['odds'];
                                }
                            } else {
                                $fail++;
                            }
                            break;
                        case 'HandiWin':
                        case 'HandiLose':
                            $type2 = 'Handicap';
                            if (!empty($data['_results'][0]['_market'][$mcode]['type'])) {
                                $hwrate     = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                $hwoption   = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['option'];
                                $hlrate     = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                $hloption   = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['option'];
                                if($choice == 'HandiWin'){
                                    $rate   = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                    $option = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['option'];
                                } else if($choice == 'HandiLose'){
                                    $rate   = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                    $option = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['option'];
                                }
                            } else {
                                $fail++;
                            }
                            break;
                        case 'Over':
                        case 'Under':
                            $type2 = 'UnderOver';
                            if (!empty($data['_results'][0]['_market'][$mcode]['type'])) {
                                $over       = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                $ooption    = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['option'];
                                $under      = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                $uoption    = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['option'];
                                if($choice == 'Over'){
                                    $rate   = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                    $option = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['option'];
                                } else if($choice == 'Under'){
                                    $rate   = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                    $option = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['option'];
                                }
                            } else {
                                $fail++;
                            }
                            break;
                    }
                    //$json['rate'] = substr($rate,0,4);
                    $forecast = $rate * $BettingPrice;
                    $sql  = "INSERT INTO buygame_live SET ";
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
                    if(!$res){
                        $json['flag'] = false;
                        $json['error'] = '구매내역 저장시 오류가 발생했습니다.';
                        $fail++;
                    }
                    $bgkey = mysql_insert_id();


                    $sql = "INSERT INTO buygamelist_live SET ";
                    $sql .= "BG_Key              = '{$bgkey}', ";
                    $sql .= "G_Key               = '{$gkey}',";
                    $sql .= "GL_Key              = '{$rs['GL_Key']}',";
                    $sql .= "M_Key               = '{$mkey}',";
                    $sql .= "G_Type1             = 'Full',";
                    $sql .= "G_Type2             = '{$type2}',";

                    $sql .= "BGL_QuotaWin        = '{$home_rate}',";
                    $sql .= "BGL_QuotaDraw       = '{$draw_rate}',";
                    $sql .= "BGL_QuotaLose       = '{$away_rate}',";
                    $sql .= "BGL_QuotaHandiWin   = '{$hwrate}', ";
                    if($choice == 'HandiWin'){
                        $sql .= "BGL_QuotaHandicap   = '{$hwoption}',";
                    } else {
                        $sql .= "BGL_QuotaHandicap   = '{$hloption}', ";
                    }
                    $sql .= "BGL_QuotaHandiLose  = '{$hlrate}',";
                    $sql .= "BGL_QuotaUnderOver  = '{$under}',";
                    $sql .= "BGL_QuotaUnder      = '{$ooption}',";
                    $sql .= "BGL_QuotaOver       = '{$over}',";

                    $sql .= "BGL_QuotaOdd        = '',";
                    $sql .= "BGL_QuotaEven       = '',";
                    $sql .= "BGL_ResultChoice    = '{$choice}',";
                    $sql .= "BGL_Bet			 = '',";
                    $sql .= "BGL_IP          	 = '{$_SERVER['REMOTE_ADDR']}',";
                    $sql .= "BGL_State     	     = 'Await',";
                    $sql .= "BGL_RegDate         =  NOW() ";
                    //echo $sql;
                    $res = setQry($sql);
                    if(!$res){
                        $fail++;
                    }

                    $sql  = "INSERT INTO moneyinfo SET ";
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
                    if(!$res){
                        $fail++;
                    }

                    $sql = "UPDATE members SET M_Money = M_Money - {$BettingPrice} WHERE M_Key = '{$mkey}'";
                    $res = setQry($sql);
                    if(!$res){
                        $fail++;
                    }

                    if($fail>0){
                        setQry('ROLLBACK');
                        $json['flag'] = false;
                        $json['error'] = '구매내역 저장시 문제가 발생했습니다.';
                        echo json_encode($json);
                    } else {
                        setQry('COMMIT');
                        echo json_encode($json);
                    }


                /*} else if($type == 'baseball'){
                    //최종승무패 01 90
                    $url = 'http://api.oddsapi-inplay.com/bet365/baseball/match?corp=' . $key . '&MI='.$gid;
                    //echo $url;
                    $snoopy->fetch($url);
                    $content = $snoopy->results;
                    $data = json_decode($content, true);
                    //print_r($data);
                    //echo $gubun;
                    switch($choice) {
                        case 'Win':
                        case 'Draw':
                        case 'Lose':
                            $type2 = 'WDL';
                            if (!empty($data['_results'][0]['_market'][$mcode]['type'])) {
                                $home_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                $draw_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                $away_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][2]['odds'];
                                if($choice == 'Win'){
                                    $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                } else if($choice == 'Draw'){
                                    $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                } else if($choice == 'Lose'){
                                    $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][2]['odds'];
                                }
                            } else {
                                $fail++;
                            }
                            break;
                        case 'HandiWin':
                        case 'HandiLose':
                            echo $type2 = 'Handicap';
                            if (!empty($data['_results'][0]['_market'][$mcode]['type'])) {
                                //print_r($data['_results'][0]['_market'][$mcode]['matchOdds']);
                                $hwrate     = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                $hwoption   = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['option'];
                                $hlrate     = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                $hloption   = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['option'];
                                if($choice == 'HandiWin'){
                                    $rate   = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                    $option = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['option'];
                                } else if($choice == 'HandiLose'){
                                    $rate   = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                    $option = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['option'];
                                }
                            } else {
                                $fail++;
                            }
                            break;
                        case 'Over':
                        case 'Under':
                            $type2 = 'UnderOver';
                            if (!empty($data['_results'][0]['_market'][$mcode]['type'])) {

                                $over       = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                $ooption    = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['option'];
                                $under      = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                $uoption    = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['option'];
                                if($choice == 'Over'){
                                    $rate   = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                    $option = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['option'];
                                } else if($choice == 'Under'){
                                    $rate   = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                    $option = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['option'];
                                }
                            } else {
                                $fail++;
                            }
                            break;
                    }
                    //$json['rate'] = substr($rate,0,4);
                    $forecast = $rate * $BettingPrice;
                    $sql  = "INSERT INTO buygame_live SET ";
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
                    if(!$res){
                        $json['flag'] = false;
                        $json['error'] = '구매내역 저장시 오류가 발생했습니다.';
                        $fail++;
                    }
                    $bgkey = mysql_insert_id();


                    $sql = "INSERT INTO buygamelist_live SET ";
                    $sql .= "BG_Key              = '{$bgkey}', ";
                    $sql .= "G_Key               = '{$gkey}',";
                    $sql .= "GL_Key              = '{$rs['GL_Key']}',";
                    $sql .= "M_Key               = '{$mkey}',";
                    $sql .= "G_Type1             = 'Full',";
                    $sql .= "G_Type2             = '{$type2}',";

                    $sql .= "BGL_QuotaWin        = '{$home_rate}',";
                    $sql .= "BGL_QuotaDraw       = '{$draw_rate}',";
                    $sql .= "BGL_QuotaLose       = '{$away_rate}',";


                    if($choice == 'HandiWin'){
                        $sql .= "BGL_QuotaHandiWin   = '{$hwrate}', ";
                        $sql .= "BGL_QuotaHandicap   = '{$hwoption}',";
                    } else {
                        $sql .= "BGL_QuotaHandiLose  = '{$hlrate}',";
                        $sql .= "BGL_QuotaHandicap   = '{$hloption}', ";
                    }


                    $sql .= "BGL_QuotaUnderOver  = '{$under}',";
                    $sql .= "BGL_QuotaUnder      = '{$ooption}',";
                    $sql .= "BGL_QuotaOver       = '{$over}',";

                    $sql .= "BGL_QuotaOdd        = '',";
                    $sql .= "BGL_QuotaEven       = '',";
                    $sql .= "BGL_ResultChoice    = '{$choice}',";
                    $sql .= "BGL_Bet			 = '',";
                    $sql .= "BGL_IP          	 = '{$_SERVER['REMOTE_ADDR']}',";
                    $sql .= "BGL_State     	     = 'Await',";
                    $sql .= "BGL_RegDate         =  NOW() ";
                    //echo $sql;
                    $res = setQry($sql);
                    if(!$res){
                        $fail++;
                    }

                    $sql  = "INSERT INTO moneyinfo SET ";
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
                    if(!$res){
                        $fail++;
                    }

                    $sql = "UPDATE members SET M_Money = M_Money - {$BettingPrice} WHERE M_Key = '{$mkey}'";
                    $res = setQry($sql);
                    if(!$res){
                        $fail++;
                    }

                    if($fail>0){
                        setQry('ROLLBACK');
                        $json['flag'] = false;
                        $json['error'] = '구매내역 저장시 문제가 발생했습니다.';
                        echo json_encode($json);
                    } else {
                        setQry('COMMIT');
                        echo json_encode($json);
                    }
                }*/
            }

        }
        break;
}
