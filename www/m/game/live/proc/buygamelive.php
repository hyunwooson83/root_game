<?php
ini_set('display_errors', 1);
ini_set("memory_limit", -1);
$include_path = "/home/trend/www";

include $include_path . "/include/common.php";
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
        if ( empty($_SESSION['S_Key']) ){
            $json['flag'] = false;
            $json['error'] = '정상적인 접속이 아닙니다1';
            $fail++;
        }


        $que = "SELECT * FROM members WHERE M_Key = '{$_SESSION['S_Key']}'";
        //echo $que;
        $mem = getRow($que);

        if ( $mem['M_Money'] < $BettingPrice ){
            $json['flag'] = false;
            $json['error'] = '보유머니가 배팅할려는 금액보다 적습니다.';
            $fail++;
        }

        $r = getRow("SELECT M_SportYN, M_Type FROM members WHERE M_Key = {$_SESSION['S_Key']}");
        if($r[0]=='N'){
            $json['flag'] = false;
            $json['error'] = '배팅 불가능한 회원입니다.';
            $fail++;
        }

        $count = 0;

        if($mem['M_Money']<$BettingPrice){
            $json['flag'] = false;
            $json['error'] = '보유머니가 부족합니다. 보유머니 충전 후 배팅하세요.';
            $fail++;
        }






        //구매내역을 만든다.
        $sql = "INSERT INTO buygame_live SET ";
        $sql .= "M_Key 						= '{$_SESSION['S_Key']}',";
        $sql .= "BG_CompleteCount 	        = '0',";
        $sql .= "BG_BettingPrice 			= '{$BettingMoney}',";
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




        $home_rate = $draw_rate = $away_rate = $rate = $hwrate = $hlrate = $over = $under = $total_rate = 1;
        $option = $hwoption = $hloption = $ooption = $uoption = $cnt = 0;
        $que = "SELECT * FROM cartgamelist_live WHERE M_Key = '{$_SESSION['S_Key']}'";
        //echo $que."<br>";
        $rows = getArr($que);
        if(count($rows[0])>0){
            foreach($rows as $rows){
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
                //득점이 되고 1분30초에 배팅을 하면 취소처리한다. 끝

                /*$que = "SELECT COUNT(*) FROM buygamelist_live WHERE BGL_Num = '{$rows['gid']}' AND M_Key = '{$_SESSION['S_Key']}'";
                //echo $que;
                $row9 = getRow($que);
                if($row9[0]>2){
                    $json['flag'] = false;
                    $json['error'] = '동일한 경기에 두번이상 배팅하실 수 없습니다.';
                    $fail++;
                }*/


                $que = "SELECT * FROM buygamelist_live a LEFT JOIN live_gamelist b ON a.G_Key = b.G_Key WHERE a.M_Key = '{$_SESSION['S_Key']}' AND b.GI_Key = 154914 AND a.G_Key = '{$rows['G_Key']}' ";
                //echo $que;
                $row = getRow($que);
                if($row['G_Type2']=='WDL' && ($choice == 'HandiWin' || $choice == 'HandiLose')){
                    $json['flag'] = false;
                    $json['error'] = '야구 승패와 핸디는 같이 배팅할 수 없습니다.';
                    $fail++;
                } else if($row['G_Type2']=='Handicap' && ($choice == 'Win' || $choice == 'Lose')){
                    $json['flag'] = false;
                    $json['error'] = '야구 승패와 핸디는 같이 배팅할 수 없습니다.';
                    $fail++;
                }

                switch($rows['gikey']){
                    case '6046':
                        $item = 'soccer';
                        break;
                    case '154914':
                        $item = 'baseball';
                        break;
                }
                $choice = $rows['select_type'];

                //코드 번호로 배당을 불러온다.
                $url = 'http://api.oddsapi-inplay.com/bet365/'.$item.'/match?corp=' . $key . '&MI='.$rows['gid'];
                //echo $url;
                $snoopy->fetch($url);
                $content = $snoopy->results;
                $data = json_decode($content, true);
                /*print_r($data);*/
               // echo $data['result'];
                if($data['result']==1) {
                    $mcode = $rows['code'];
                    if(empty($type1))   $type1 = 'Full';
                    if ($data['_results'][0]['timeStatus'] == 3 || $data['_results'][0]['timeMark'] == 'FT') {
                        $sql = "UPDATE live_gamelist SET G_State = 'Stop', status = '{$data['_results'][0]['timeStatus']}' AND G_ID  = '{$rows['gid']}'";
                        setQry($sql);
                        $json['flag'] = false;
                        $json['error'] = '경기가 종되었습니다. 배팅하실 수 없습니다.';
                        $fail++;
                    } else {
                        if ($data['result'] == 1 && $data['_results'][0]['timeStatus'] == 1) {
                            //echo $rows['select_type'];
                            switch ($rows['select_type']) {
                                case 'Win':
                                case 'Draw':
                                case 'Lose':
                                    $type2 = 'WDL';

                                    if (!empty($data['_results'][0]['_market'][$mcode]['type']) && $data['_results'][0]['_market'][$mcode]['suspended'] == false) {

                                        if ($item == 'soccer') {
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
                                        } else if ($item == 'baseball') {
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
                                case 'Win1':
                                case 'Draw1':
                                case 'Lose1':
                                    $type2 = 'WDL';

                                    if (!empty($data['_results'][0]['_market'][$mcode]['type']) && $data['_results'][0]['_market'][$mcode]['suspended'] == false) {

                                        if ($item == 'soccer') {
                                            $home_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                            $draw_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                            $away_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][2]['odds'];
                                            if ($choice == 'Win1') {
                                                $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                            } else if ($choice == 'Draw1') {
                                                $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                            } else if ($choice == 'Lose1') {
                                                $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][2]['odds'];
                                            }
                                        } else if ($item == 'baseball') {
                                            $home_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                            $away_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                            if ($choice == 'Win') {
                                                $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                            } else if ($choice == 'Lose') {
                                                $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                            }
                                        }
                                    } else {
                                        $json['flag'] = false;
                                        $json['error'] = '구매 경기중 배팅할 수 없는 경기가 있습니다.';
                                        $fail++;
                                    }
                                    break;
                                case 'Win2':
                                case 'Draw2':
                                case 'Lose2':
                                    $type2 = 'WDL';

                                    if (!empty($data['_results'][0]['_market'][$mcode]['type']) && $data['_results'][0]['_market'][$mcode]['suspended'] == false) {

                                        if ($item == 'soccer') {
                                            $home_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                            $draw_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                            $away_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][2]['odds'];
                                            if ($choice == 'Win2') {
                                                $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                            } else if ($choice == 'Draw2') {
                                                $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                            } else if ($choice == 'Lose2') {
                                                $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][2]['odds'];
                                            }
                                        } else if ($item == 'baseball') {
                                            $home_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                            $away_rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                            if ($choice == 'Win') {
                                                $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][0]['odds'];
                                            } else if ($choice == 'Lose') {
                                                $rate = $data['_results'][0]['_market'][$mcode]['matchOdds'][1]['odds'];
                                            }
                                        }
                                    } else {
                                        $json['flag'] = false;
                                        $json['error'] = '구매 경기중 배팅할 수 없는 경기가 있습니다.';
                                        $fail++;
                                    }
                                    break;
                                case 'HandiWin':
                                case 'HandiLose':
                                    $type2 = 'Handicap';
                                    //echo "갯수는 - ['.$mcode.']".count($data['_results'][0]['_market'][$mcode]['matchOdds'])."<p>";
                                    if ($rows['other'] == 'Y') {
                                        $cd = explode("_",$mcode);
                                        $mcode = "_".$cd[1]."_".$cd[2]."_".($cd[3]+1);
                                        if($data['_results'][0]['_market'][$mcode]['suspended'] == false) {
                                            for ($h = 0; $h < count($data['_results'][0]['_market'][$mcode]['matchOdds']); $h += 2) {
                                                //echo $data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['id']."==".$id;
                                                if ($data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['id'] == $rows['game_id']) {

                                                    $hwrate = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['odds'];
                                                    $hwoption = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['option'];
                                                    $hlrate = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h + 1]['odds'];
                                                    $hloption = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h + 1]['option'];
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
                                            $json['flag'] = false;
                                            $json['error'] = '구매 경기중 배팅할 수 없는 경기가 있습니다.';
                                            $fail++;
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
                                            $json['flag'] = false;
                                            $json['error'] = '구매 경기중 배팅할 수 없는 경기가 있습니다.';
                                            $fail++;
                                        }


                                    }
                                    break;
                                case 'Over':
                                case 'Under':
                                    $type2 = 'UnderOver';
                                    if ($rows['other'] == 'Y') {
                                        $cd = explode("_",$mcode);
                                        $mcode = "_".$cd[1]."_".$cd[2]."_".($cd[3]+1);
                                        //echo "갯수는 - ['.$mcode.']".count($data['_results'][0]['_market'][$mcode]['matchOdds'])."<p>";
                                        //echo $rows['game_id'];
                                        if($data['_results'][0]['_market'][$mcode]['suspended'] == false) {
                                            for ($h = 0; $h < count($data['_results'][0]['_market'][$mcode]['matchOdds']); $h += 2) {
                                                //echo $data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['id']."==".$rows['game_id'];
                                                if ($data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['id'] == $rows['game_id']) {

                                                    $over = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['odds'];
                                                    $ooption = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h]['option'];
                                                    $under = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h + 1]['odds'];
                                                    $uoption = $data['_results'][0]['_market'][$mcode]['matchOdds'][$h + 1]['option'];
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
                                            $json['flag'] = false;
                                            $json['error'] = '구매 경기중 배팅할 수 없는 경기가 있습니다.';
                                            $fail++;
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
                                            $json['flag'] = false;
                                            $json['error'] = '구매 경기중 배팅할 수 없는 경기가 있습니다.';
                                            $fail++;
                                        }
                                    }
                                    break;
                            }

                            $total_rate *= $rate;

                            //281262||2020-05-28 03:10:00||Full||WDL||스탈 미엘레츠||레흐 포즈난||4.25||3.78||1.79||6046||15194

                            if ($choice == 'HandiWin') {
                                $hoption = $hwoption;
                            } else {
                                $hoption = $hloption;
                            }

                            $bet = $rows['G_Key'] . '||' . $home_rate . $hwrate . $over . '||' . $draw_rate . $hoption . $ooption . '||' . $away_rate . $hlrate . $under;
                            $sql = "INSERT INTO buygamelist_live SET ";
                            $sql .= "BG_Key              = '{$bgkey}', ";
                            $sql .= "G_Key               = '{$rows['G_Key']}',";
                            $sql .= "GL_Key              = '{$rs['GL_Key']}',";
                            $sql .= "M_Key               = '{$_SESSION['S_Key']}',";
                            $sql .= "G_Type1             = '{$rows['type1']}',";
                            $sql .= "G_Type2             = '{$rows['type2']}',";
                            $sql .= "BGL_Num            = '{$rows['gid']}',";

                            if($type2 == 'WDL') {
                                $sql .= "BGL_QuotaWin        = '" . substr($home_rate, 0, 4) . "',";
                                $sql .= "BGL_QuotaDraw       = '" . substr($draw_rate, 0, 4) . "',";
                                $sql .= "BGL_QuotaLose       = '" . substr($away_rate, 0, 4) . "',";
                            }

                            if($type2 == 'Handicap') {
                                $sql .= "BGL_QuotaHandiWin   = '" . substr($hwrate, 0, 4) . "', ";
                                if ($rows['select_type'] == 'HandiWin') {
                                    $sql .= "BGL_QuotaHandicap   = '{$hwoption}',";
                                } else {
                                    $sql .= "BGL_QuotaHandicap   = '{$hloption}', ";
                                }
                                $sql .= "BGL_QuotaHandiLose  = '" . substr($hlrate, 0, 4) . "',";
                            }


                            if($type2 == 'UnderOver') {
                                $sql .= "BGL_QuotaUnderOver  = '" . substr($ooption, 0, 4) . "',";
                                $sql .= "BGL_QuotaUnder      = '{$under}',";
                                $sql .= "BGL_QuotaOver       = '" . substr($over, 0, 4) . "',";
                            }
                            $sql .= "BGL_QuotaOdd        = '',";
                            $sql .= "BGL_QuotaEven       = '',";
                            if(in_array($rows['select_type'],array('Win1','Win2'))) {
                                $sql .= "BGL_ResultChoice    = 'Win',";
                            } else if(in_array($rows['select_type'],array('Draw1','Draw2'))) {
                                $sql .= "BGL_ResultChoice    = 'Draw',";
                            } else if(in_array($rows['select_type'],array('Lose1','Lose2'))) {
                                    $sql .= "BGL_ResultChoice    = 'Lose',";
                            } else {
                                $sql .= "BGL_ResultChoice    = '{$rows['select_type']}',";
                            }

                            $sql .= "BGL_Bet			 = '{$bet}',";
                            $sql .= "BGL_IP          	 = '{$_SERVER['REMOTE_ADDR']}',";
                            $sql .= "BGL_State     	     = 'Await',";
                            $sql .= "BGL_Start     	     = '{$rows['grade']}',";
                            //$sql .= "BGL_Bet             = '{$bet}', ";
                            $sql .= "BGL_RegDate         =  NOW() ";
                            //echo $sql."<p>";
                            $res = setQry($sql);
                            if (!$res) {
                                $fail++;
                            }

                            $cnt++;
                        } else {
                            $fail++;
                        }//if ($data['result'] == 1 && $data['_results'][0]['timeStatus'] == 1) {
                    }
                } else {
                    $fail++;
                }//if($data['result']==1) {
            }//foreach($rows as $rows){
        }//if(count($rows[0])>0){

        $BettingQuota = floor($total_rate*100);
        $BettingQuota = ($BettingQuota/100);


        $ForecastPrice = $BettingQuota * $BettingMoney;

        $sql = "SELECT M_Money FROM members WHERE M_Key = '{$_SESSION['S_Key']}'";
        $mem = getRow($sql);

        //구매내역 업데이트
        $sql = "UPDATE buygame_live SET ";
        $sql .= "BG_TotalQuota      = '{$BettingQuota}', ";
        $sql .= "BG_GameCount       = '{$cnt}', ";
        $sql .= "BG_MemberMoney     = '{$mem['M_Money']}', ";
        $sql .= "BG_ForecastPrice   = '{$ForecastPrice}' ";
        $sql .= " WHERE BG_Key      = '{$bgkey}' ";
        //echo $sql."<br>\n";
        $res = setQry($sql);
        if (!$res) {
            $json['flag'] = false;
            $json['error'] = '배팅내역 저장시 오류가 발생했습니다[B1].';
            $fail++;
        }



        $sql = "INSERT INTO moneyinfo SET ";
        $sql .= "M_Key          = '{$_SESSION['S_Key']}', ";
        $sql .= "MI_Type        = 'GameBetting', ";
        $sql .= "PI_Key         = '', ";
        $sql .= "BG_Key         = '{$bgkey}', ";
        $sql .= "R_Key          = '', ";
        $sql .= "MI_Money       = '-{$BettingMoney}', ";
        $sql .= "MI_Prev_Money  = '{$mem['M_Money']}', ";
        $sql .= "MI_Memo        = '게임을 구매하였습니다.', ";
        $sql .= "MI_RegDate     = NOW() ";
        //echo $sql;
        $res = setQry($sql);
        if (!$res) {
            $fail++;
        }

        $sql = "UPDATE members SET M_Money = M_Money - {$BettingMoney} WHERE M_Key = '{$_SESSION['S_Key']}'";
        //echo $sql."<br>";
        $res = setQry($sql);
        if (!$res) {
            $fail++;
        }

        if ($fail > 0) {
            setQry('ROLLBACK');
            $json['flag'] = false;
            //$json['error'] = '구매내역 저장시 문제가 발생했습니다.';
            echo json_encode($json);
        } else {
            setQry('COMMIT');
            echo json_encode($json);
        }

        break;
}
