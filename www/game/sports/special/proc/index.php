<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

    $clear = "";
    if(ctype_alpha($_REQUEST['mode'])==true){
        $clear['mode'] = $_REQUEST['mode'];
    }

    switch($mode){
        case 'ajaxCartAction':
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

            //정상적으로 발매중인지 확인
            $que = "select * from gamelist a where  a.G_Key='{$_GET['g_key']}'";
            $g_row = getRow($que);
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

            // 업데이트 해야되는 게임 인지 체크
            $que = "select * from cartgamelist where M_Key='{$_SESSION['S_Key']}' and G_Key='{$_GET['g_key']}'";
            $rs = getRow($que);
            if ( !empty($rs['G_Key']))
            {
                //재클릭시 취소 기능        	
                if($rs['CGL_ResultChoice'] == $_GET['g_result'])
                {
                    $sql = "delete from cartgamelist where M_Key='{$_SESSION['S_Key']}' and CGL_Key='{$rs['CGL_Key']}'";
                    $res = setQry($sql);
                    $json['flag'] = false;
                    $json['error'] = "카트에서 게임삭제시 오류발생(cart)";
                    echo json_encode($json);
                    break;
                }
                else
                {
                    //다른 쪽 클릭시 업데이트 기능
                    $change_cart = true;
                }
            }

            $que = "select * from gamelist where G_Key = '{$_GET['g_key']}'";
            //echo $que."\n";
            $row_ori = getRow($que);
            if($row_ori['G_Type2'] == "WDL" || $row_ori['G_Type2'] == "Handicap"){
                if($row_ori['G_Type2'] == "WDL"){
                    $sss="select CGL_Key from cartgamelist where G_Datetime='{$row_ori['G_Datetime']}' and G_Team1='{$row_ori['G_Team1']}' and G_Team2='{$row_ori['G_Team2']}' and G_Key <> '{$_GET['g_key']}' and G_Type2='Handicap'";
                    $r = getRow($sss);
                    if($r['CGL_Key']){
                        $sql ="DELETE FROM cartgamelist WHERE M_Key='{$_SESSION['S_Key']}' AND CGL_Key='{$r['CGL_Key']}'";
                        //echo $sql;
                        $res = setQry($sql);
                        if(!$res) {
                            $json['flag'] = false;
                            $json['error'] = "카트에서 게임삭제시 오류발생(핸디묶음)";
                            break;
                        }
                    }
                } else if($row_ori['G_Type2'] == "Handicap"){
                    $sss="select CGL_Key from cartgamelist where G_Datetime='{$row_ori['G_Datetime']}' and G_Team1='{$row_ori['G_Team1']}' and G_Team2='{$row_ori['G_Team2']}' and G_Key <> '{$_GET['g_key']}' and G_Type2='WDL'";
                    $r = getRow($sss);
                    if($r['CGL_Key']){
                        $sql ="DELETE FROM cartgamelist WHERE M_Key='{$_SESSION['S_Key']}' AND CGL_Key='{$r['CGL_Key']}'";
                        //echo $sql;
                        $res = setQry($sql);
                        if(!$res) {
                            $json['flag'] = false;
                            $json['error'] = "카트에서 게임삭제시 오류발생(핸디묶음)";
                            break;
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
            $que = "SELECT COUNT(*) FROM cartgamelist WHERE G_GameList = '{$g_list}' AND G_Type2 = '{$gtype}'";
            //echo $que;
            $rowu = getRow($que);
            if($rowu[0]>0){
                $sql = "DELETE FROM cartgamelist WHERE G_GameList = '{$g_list}' AND G_Type2 = '{$gtype}'";
                //echo $sql;
                $res = setQry($sql);
                if(!$res) {
                    $json['flag'] = false;
                    $json['error'] = "카트에서 게임삭제시 오류발생(추가경기 삭제)";
                    break;
                }
            }



            unset($result);
            unset($row_ori);

            #풀언오버 배팅 안되게!!!
            $ct = 0;
            #클릭한 경기의 내용을 불러온다
            /*$que = "SELECT * FROM gamelist a, gameleague b WHERE a.GL_Key = b.GL_Key AND G_Key = {$_GET['g_key']} ";
            $row = getRow($que);
            if($row['G_Key']){
                if($row['G_Type2']=='WDL'){
                    $sql = "SELECT * FROM cartgamelist WHERE G_Datetime = '{$row['G_Datetime']}' AND G_Team1 = '{$row['G_Team1']}' AND G_Team2 = '{$row['G_Team2']}' AND G_Key <> {$row['G_Key']} AND G_Type2 = 'UnderOver' AND M_Key = {$_SESSION['S_Key']}";
                    //echo $sql."<br>";
                    $sql_row = getRow($sql);
                    //echo $sql_row[0]; 
                    if($sql_row['G_Key']){
                        $q = "DELETE FROM cartgamelist WHERE M_Key = {$_SESSION['S_Key']} AND G_Key = {$sql_row['G_Key']}";
                        //echo $q;
                        setQry($q);
                    }
                } else if($row['G_Type2']=='UnderOver'){
                    $sql = "SELECT * FROM cartgamelist WHERE G_Datetime = '{$row['G_Datetime']}' AND G_Team1 = '".str_replace(" [오버]","",$row['G_Team1'])."' AND G_Team2 = '".str_replace(" [언더]","",$row['G_Team2'])."' AND G_Key <> {$row['G_Key']} AND G_Type2 = 'WDL' AND M_Key = {$_SESSION['S_Key']}";
                    //echo $sql."<br>";
                    $sql_row = getRow($sql);
                    //echo $sql_row[0]; 
                    if($sql_row[G_Key]){
                        $q = "DELETE FROM cartgamelist WHERE M_Key = {$_SESSION['S_Key']} AND G_Key = {$sql_row['G_Key']}";
                        //echo $q;
                        setQry($q);
                    }
                }			
            }*/


            /*if($_GET['g_result']=="Draw" || $_GET['g_result']=="Under" || $_GET['g_result']=="Over"){
                if($row['G_Type2'] == "WDL" && $_GET['g_result']=="Draw"){
                    $sss="select * from gamelist where G_Datetime='{$row['G_Datetime']}' and G_Team1='{$row['G_Team1']}' and G_Team2='{$row['G_Team2']}' and G_Key <> '{$_GET['g_key']}' and G_Type2='UnderOver'";
                    $result = $db->Execute($sss);
                    while($rows = $result->FetchRow()){
                        $q = "DELETE FROM cartgamelist WHERE M_Key='{$_SESSION['S_Key']}' AND G_Key='{$rows['G_Key']}'";
                        setQry($q);
                    }
                } else if($row['{G_Type2}'] == "UnderOver"){
                    $sss="select * from gamelist where G_Datetime='{$row['G_Datetime']}' and G_Team1='{$row['G_Team1']}' and G_Team2='{$row['G_Team2']}' and G_Key <> '{$_GET['g_key']}' and G_Type2='WDL'";
                    $result = $db->Execute($sss);
                    while($rows = $result->FetchRow()){
                        $q = "DELETE FROM cartgamelist WHERE M_Key='{$_SESSION['S_Key']}' AND G_Key='{$rows['G_Key']}' AND CGL_ResultChoice='Draw'";
                        setQry($q);
                    }
                }
            }
            */

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
            if($ct[0]>0) {
                $que = "SELECT * FROM cartgamelist a LEFT JOIN gamelist b ON a.G_Key = b.G_Key WHERE a.M_Key = '{$_SESSION['S_Key']}'";
                //echo $que.'\n';
                $arr = getArr($que);
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
        break;
    }