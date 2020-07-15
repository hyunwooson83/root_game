<?php
    include "../include/common.php";


    setQry("BEGIN");
    $json['flag'] = true;
    $json['error'] = "";
    $fail = 0;


    $add_cart = false;
    $change_cart = false;
    $game_result = array('Win', 'Draw','HandiWin', 'HandiLose', 'Lose', 'Under', 'Over', 'Odd', 'Even');
    $gKey = $_GET['g_key'];
    $que = "SELECT COUNT(*) FROM cartgamelist_live WHERE M_Key = '{$_SESSION['S_Key']}'";
    $r = getRow($que);

    // 로그인 체크
    if ( !$_SESSION['S_Key'] ){
        $json['flag'] = false;
        $json['error'] = "정상적인 접속이 아닙니다1.";
        $fail++;
    }

    if ( !is_numeric($_GET['g_key'] ) ){
        $json['flag'] = false;
        $json['error'] = "정상적인 접속이 아닙니다2.";
        $fail++;
    }
    if ( !in_array($_GET['g_result'], $game_result) ){
        $json['flag'] = false;
        $json['error'] = "정상적인 접속이 아닙니다3.";
        $fail++;
    }

    if($g_type != 'Special') {

        $que = "select * from gamelist where G_Key = '{$_GET['g_key']}'";
        //echo $que."\n";
        $row_ori = getRow($que);
        if ($row_ori['G_Type2'] == "WDL" || $row_ori['G_Type2'] == "Handicap") {
            if ($row_ori['G_Type2'] == "WDL") {
                $sss = "select CGL_Key from cartgamelist where G_Datetime='{$row_ori['G_Datetime']}' and G_Team1='{$row_ori['G_Team1']}' and G_Team2='{$row_ori['G_Team2']}' and G_Key <> '{$_GET['g_key']}' and G_Type2='Handicap'";
                $r = getRow($sss);
                if ($r['CGL_Key']) {
                    $sql = "DELETE FROM cartgamelist WHERE M_Key='{$_SESSION['S_Key']}' AND CGL_Key='{$r['CGL_Key']}'";
                    //echo $sql;
                    $res = setQry($sql);
                    if (!$res) {
                        $json['flag'] = false;
                        $json['error'] = "카트에서 게임삭제시 오류발생(핸디묶음)";
                        break;
                    }
                }
            } else if ($row_ori['G_Type2'] == "Handicap") {
                $sss = "select CGL_Key from cartgamelist where G_Datetime='{$row_ori['G_Datetime']}' and G_Team1='{$row_ori['G_Team1']}' and G_Team2='{$row_ori['G_Team2']}' and G_Key <> '{$_GET['g_key']}' and G_Type2='WDL'";
                $r = getRow($sss);
                if ($r['CGL_Key']) {
                    $sql = "DELETE FROM cartgamelist WHERE M_Key='{$_SESSION['S_Key']}' AND CGL_Key='{$r['CGL_Key']}'";
                    //echo $sql;
                    $res = setQry($sql);
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


    //echo $g_type;
    if($g_type == 'Special' || $row_ori['GI_Key'] == '6046'){//스페셜일경우 한개만 선택이 가능하다
        //echo $change_cart;
        if($change_cart != true) {
            $que = "SELECT COUNT(*) FROM cartgamelist WHERE G_GameList = '{$g_list}' ";
            //echo $que;
            $rowu = getRow($que);
            if ($rowu[0] > 0) {
                $sql = "DELETE FROM cartgamelist WHERE G_GameList = '{$g_list}' ";
                //echo $sql;
                $res = setQry($sql);
                if (!$res) {
                    $json['flag'] = false;
                    $json['error'] = "카트에서 게임삭제시 오류발생(추가경기 삭제)";
                    echo json_encode($json);
                    break;
                }
            }
        }
    } else {
        if($change_cart != true) {
            $que = "SELECT COUNT(*) FROM cartgamelist WHERE G_GameList = '{$g_list}' AND G_Type2 = '{$gtype}'";
            //echo $que;
            $rowu = getRow($que);
            if ($rowu[0] > 0) {
                $sql = "DELETE FROM cartgamelist WHERE G_GameList = '{$g_list}' AND G_Type2 = '{$gtype}'";
                //echo $sql;
                $res = setQry($sql);
                if (!$res) {
                    $json['flag'] = false;
                    $json['error'] = "카트에서 게임삭제시 오류발생(추가경기 삭제)";
                    break;
                }
            }
        }
    }



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
        //$sql .= "G_List               = '".mysql_real_escape_string($glist)."', ";
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
    $que  = "SELECT COUNT(*) FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'";
    $ct = getRow($que);
    if($ct[0]>0) {
        $que = "SELECT * FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'";
        $arr = getArr($que);
        foreach ($arr as $arr) {
            $data['home_team'] = mb_substr($arr['G_Team1'], 0, 10, 'utf-8');
            $data['away_team'] = mb_substr($arr['G_Team2'], 0, 10, 'utf-8');
            $data['gkey']   = $arr['G_Key'];
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
        $json['total_cnt'] = 0;
    }

    echo json_encode($json);
