<?php
	include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";
	
	/*$que = "SELECT * FROM board WHERE B_ID = 'customer' AND B_Answer_Read = 'n' AND M_Key = '{$_POST[M_Key]}'  AND  B_RegDate > date_format(date_add(now(),interval -3 minute), '%Y-%m-%d %H:%i:%s')";
	//echo $que;
	$row = getRow($que);
	if($row['B_Answer']){				
		setQry("UPDATE board SET B_Answer_Read = 'y' WHERE B_Key = {$row[B_Key]} ");
		echo "board^".$row['B_Key'];

	}
	
	

	$que = "SELECT * FROM message WHERE M_Key = ".$_POST[M_Key]." AND readDate = '0000-00-00 00:00:00'  AND  regDate > date_format(date_add(now(),interval -3 minute), '%Y-%m-%d %H:%i:%s')";
	//echo $que;
	$row = getRow($que);
	if($row['M_Key']){
		echo "msg^".$row[idx];
	}*/


    $clear = array();
    if(ctype_alpha($_REQUEST['mode'])==true){

        $clear['mode'] = $_REQUEST['mode'];
    }

	switch($clear['mode']) {
        case 'sameloginchk':
            $json['flag'] = true;
            $json['error'] = '';
            $que = "SELECT token FROM members WHERE M_Key = '{$_SESSION['S_Key']}' ";
            //echo $que;
            $row = getRow($que);
            //echo $row['token']."-".$_SESSION['S_Token'];
            if (!empty($row['token'])) {
                if ($row['token'] != $_SESSION['S_Token']) {
                    //중복로그인됨
                    //msgMove('중복 로그인으로 로그아웃 되었습니다.','/login');
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/session/sess_" . $_SESSION['S_Token']);
                    unset($_SESSION['S_Key']);
                    $json['flag'] = false;
                    $json['error'] = '중복 로그인으로 로그아웃 됩니다.';
                }
            }

            echo json_encode($json);
            break;
        case 'pageWaitTimeChk':
            $json['flag'] = true;
            $json['error'] = '';
            $endtime = $_SESSION['S_LoginTime'] + 180;
            /*echo $endtime."--".time();*/
            if ($endtime < time()) {
                $json['flag'] = false;
                $json['error'] = 'logout';
            }
            echo json_encode($json);
            break;
        case 'makeCart':
            $cnt = 0;
            $json['flag'] = true;
            $json['error'] = '';
            $total_rate = 1;

            //보너스를 제외한 카트의 갯수를 구한다.
            $que = "SELECT COUNT(*) FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}' AND G_Key NOT IN (1,2,3)";
            //echo $que;
            $row = getRow($que);


            $que = "SELECT COUNT(*) FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}' AND G_Key IN (1,2,3)";
            //echo $que;
            $row1 = getRow($que);

            if($row1[0]>0) {
                if ($row[0] < 3) {
                    $sql = "DELETE FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}' AND G_Key = 1 ";
                    //echo $sql;
                    setQry($sql);
                } else if ($row[0]>3 && $row[0] < 5) {
                    $sql = "DELETE FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}' AND G_Key IN (2) ";
                    //echo $sql;
                    setQry($sql);
                } else if ($row[0]>5 && $row[0] < 7) {
                    $sql = "DELETE FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}' AND G_Key IN (3) ";
                    //echo $sql;
                    setQry($sql);
                }
            }


            //보너스 배당을 여러번 클릭하면 모두 삭제해 버린다.
            $que1 = "SELECT COUNT(*) FROM cartgamelist WHERE  M_Key = '{$_SESSION['S_Key']}' AND G_Key = 1";
            $row1 = getRow($que1);
            if($row1[0]>1){
                setQry("DELETE FROM cartgamelist WHERE  M_Key = '{$_SESSION['S_Key']}' AND G_Key = 1 ");
            }

            //보너스 배당을 여러번 클릭하면 모두 삭제해 버린다.
            $que1 = "SELECT COUNT(*) FROM cartgamelist WHERE  M_Key = '{$_SESSION['S_Key']}' AND G_Key = 2";
            $row1 = getRow($que1);
            if($row1[0]>1){
                setQry("DELETE FROM cartgamelist WHERE  M_Key = '{$_SESSION['S_Key']}' AND G_Key = 2 ");
            }

            //보너스 배당을 여러번 클릭하면 모두 삭제해 버린다.
            $que1 = "SELECT COUNT(*) FROM cartgamelist WHERE  M_Key = '{$_SESSION['S_Key']}' AND G_Key = 3";
            $row1 = getRow($que1);
            if($row1[0]>1){
                setQry("DELETE FROM cartgamelist WHERE  M_Key = '{$_SESSION['S_Key']}' AND G_Key = 3 ");
            }

            $que1 = "SELECT COUNT(*) FROM cartgamelist WHERE  M_Key = '{$_SESSION['S_Key']}' AND G_Key IN (1,2,3)";
            $row1 = getRow($que1);
            if($row1[0]>1){
                setQry("DELETE FROM cartgamelist WHERE  M_Key = '{$_SESSION['S_Key']}' AND G_Key IN (1,2,3) ");
            }

            $que = "SELECT COUNT(*) FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'";
            $ct = getRow($que);
            if ($ct[0] > 0) {
                $que = "SELECT * FROM cartgamelist  WHERE M_Key = '{$_SESSION['S_Key']}' ORDER BY CGL_RegDate ASC";

                //echo $que;
                $arr = getArr($que);
                foreach ($arr as $arr) {
                    $data['home_team'] = mb_substr($arr['G_Team1'], 0, 10, 'utf-8');
                    $data['away_team'] = mb_substr($arr['G_Team2'], 0, 10, 'utf-8');
                    if ($arr['CGL_ResultChoice'] == 'Win') {
                        $data['rate'] = $arr['CGL_QuotaWin'];
                        $total_rate *= $arr['CGL_QuotaWin'];
                        $data['select_type'] = '승';
                        $data['select'] = 'win-'.$arr['G_Key'];
                    } else if ($arr['CGL_ResultChoice'] == 'Lose') {
                        $data['rate'] = $arr['CGL_QuotaLose'];
                        $total_rate *= $arr['CGL_QuotaLose'];
                        $data['select_type'] = '패';
                        $data['select'] = 'lose-'.$arr['G_Key'];
                    } else if ($arr['CGL_ResultChoice'] == 'Draw') {
                        $data['rate'] = $arr['CGL_QuotaDraw'];
                        $total_rate *= $arr['CGL_QuotaDraw'];
                        $data['select_type'] = '무';
                        $data['select'] = 'draw-'.$arr['G_Key'];
                    } else if ($arr['CGL_ResultChoice'] == 'Under') {
                        $data['rate'] = $arr['CGL_QuotaUnder'];
                        $total_rate *= $arr['CGL_QuotaUnder'];
                        $data['select_type'] = '언더';
                        $data['select'] = 'under-'.$arr['G_Key'];
                    } else if ($arr['CGL_ResultChoice'] == 'Over') {
                        $data['rate'] = $arr['CGL_QuotaOver'];
                        $total_rate *= $arr['CGL_QuotaOver'];
                        $data['select_type'] = '오버';
                        $data['select'] = 'over-'.$arr['G_Key'];
                    } else if ($arr['CGL_ResultChoice'] == 'HandiWin') {
                        $data['rate'] = $arr['CGL_QuotaHandiWin'];
                        $total_rate *= $arr['CGL_QuotaHandiWin'];
                        $data['select_type'] = '핸승';
                        $data['select'] = 'hwin-'.$arr['G_Key'];
                    } else if ($arr['CGL_ResultChoice'] == 'HandiLose') {
                        $data['rate'] = $arr['CGL_QuotaHandiLose'];
                        $total_rate *= $arr['CGL_QuotaHandiLose'];
                        $data['select_type'] = '핸패';
                        $data['select'] = 'hlose-'.$arr['G_Key'];
                    }
                    $data['cglkey'] = $arr['CGL_Key'];
                    $dt[] = $data;
                    $cnt++;
                }
                $BettingQuota = floor($total_rate*100);
                $BettingQuota = ($BettingQuota/100);
                $price = ($price > 0) ? $price : 5000;

                $json['total_cnt']      = $cnt;
                $json['total']          = $BettingQuota;
                $json['total_price']    = $price * $BettingQuota;
                $json['cart']           = $dt;
            } else {
                $json['total_cnt'] = 0;
            }
            echo json_encode($json);
            break;
        //카트경기삭제하기
        case 'delCart':
            $json['flag'] = true;
            $json['error'] = '';

            $que = "SELECT * FROM cartgamelist WHERE CGL_Key = '{$cartKey}'";
            $row = getRow($que);
            if(!empty($row['CGL_Key'])){
                $sql = "DELETE FROM cartgamelist WHERE  CGL_Key = '{$cartKey}' ";
                $res = setQry($sql);
                if(!$res){
                    $json['flag'] = false;
                    $json['error'] = '카트에 경기삭제 오류.';
                }
            } else {
                $json['flag'] = false;
                $json['error'] = '카트에 담긴 경기가 없습니다.';
            }

            echo json_encode($json);
            break;

        case 'messageChk':
            $json['flag'] = false;
            $json['cnt'] = 0;
            if(!empty($_SESSION['S_Key'])) {
                $que = "SELECT COUNT(*) AS cnt FROM message WHERE M_Key = '{$_SESSION['S_Key']}' AND readDate = '0000-00-00 00:00:00'";
                $row = getRow($que);
                if ($row['cnt'] > 0) {
                    $json['flag'] = true;
                    $json['cnt'] = $row['cnt'];
                }
            }
            echo json_encode($json);
            break;

        #배팅카트 전체 삭제
        case 'delCartAll':
            $json['flag'] = true;
            $json['error'] = '';

            $que = "DELETE FROM cartgamelist WHERE M_Key = '{$_SESSION['S_Key']}'";
            $res = setQry($que);
            if(!$res){
                $json['flag'] = false;
                $json['error'] = '전체카트 삭제 오류.';
            }

            echo json_encode($json);
            break;
    }





	
?>