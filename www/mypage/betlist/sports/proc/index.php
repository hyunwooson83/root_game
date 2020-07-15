<?php
$include_path = $_SERVER['DOCUMENT_ROOT'];
include $include_path."/include/common.php";

print_r($_POST);
switch($_POST['HAF_Value_0'])
{
    case 'deleteBetList':

        $json['flag'] = true;
        $json['error'] = '';
        $cnt = 0;
        $sql = "SELECT * FROM buygamelist WHERE BG_Key IN ({$bgkey})";
        $arr = getArr($sql);
        foreach($arr as $rs){
            if($rs['BGL_State']=='Await' ){
                $cnt++;
            }
        }
        if(!$cnt) {
            $que = "UPDATE buygame SET BG_Visible = '0' WHERE BG_Key = '{$bgkey}' ";
            $res = setQry($que);
            if (!$res) {
                $json['flag'] = false;
                $json['error'] = '삭제시 업데이트 오류';
            }
        } else {
            $json['flag'] = false;
            $json['error'] = '"진행중"인 배팅내역은 삭제하실 수 없습니다.';
        }
        //echo $que;
        echo json_encode($json);
    break;

    case 'bettingCancel':
        setQry("BEGIN");
        $json['flag'] = true;
        $json['error'] = '';
        $fail = 0;
        #회원배팅취소가능 횟수가 0이상일 경우만
        if($meminfo['M_Bet_Cancel_Cnt']>0){
            $que = "SELECT * FROM buygamelist WHERE BG_Key = '{$bgkey}'";
            //echo $que."<br>";
            $arr = getArr($que);
            foreach($arr as $list){
                error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 구매내역 리스트 : '.$list['BGL_Bet'].PHP_EOL,3,"/home/trend/www/log/bet_cancel.log");
                $sql = "INSERT INTO bgld SELECT * FROM buygamelist WHERE BGL_Key = {$list['BGL_Key']}";
                //echo $sql."<br>";
                $res = setQry($sql);
                if(!$res){
                    $fail++;
                    $json['error'] = "구매내역 상세 데이터 복사에 문제가 발생했습니다[1].";
                }

                $sql = "DELETE FROM buygamelist WHERE BGL_Key = {$list['BGL_Key']} ";
                //echo $sql."<br>";
                $res = setQry($sql);
                if(!$res){
                    $fail++;
                    $json['error'] = "구매내역 상세 데이터 복사에 문제가 발생했습니다[2].";
                }
            }

            $log = '';
            if($fail==0) {
                $que = "SELECT * FROM buygame WHERE BG_Key = '{$bgkey}'";
                //echo $que."<br>";
                $money = getRow($que);

                if ($money['BG_Key'] == '') {
                    $fail++;
                    $json['flag'] = false;
                    $json['error'] = "구매내역이 없습니다.";
                }
                //배팅취소 시간을 체크한다.
                //echo $SITECONFIG['member_bet_cancel_time'];
                $betCancelTime = strtotime($money['BG_BuyDate'] . " +" . $SITECONFIG['member_bet_cancel_time'] . " minute");
                $end_time = $betCancelTime - time();

                if ($end_time < 0 && $SITECONFIG['member_bet_cancel_YN'] == 'Y') {
                    $fail++;
                    $json['flag'] = false;
                    $json['error'] = "배팅취소시간이 경과하여 배팅을 취소하실수 없습니다.";
                }


                $log .= "구매번호 : ".$money['BG_Key'];
                $log .= " / 경기갯수 : ".$money['BG_GameCount'];
                $log .= " / 총배당률 : ".$money['BG_TotalQuota'];
                $log .= " / 배팅금액 : ".$money['BG_BettingPrice'];
                $log .= " / 보유금액 : ".$money['BG_MemberMoney'];
                $log .= " / 당첨금액 : ".$money['BG_ForecastPrice'];
                $log .= " / 구매일자 : ".$money['BG_Result'];
                $log .= " / 배팅구분 : ".$money['BG_Gubun'];
                $log .= " / 브라우저 : ".$money['BG_BetInfo'];

                error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 구매내역 : '.$log.PHP_EOL,3,"/home/trend/www/log/bet_cancel.log");
                #구매내역 삭제 테이블에 현재 삭제하려는 데이터를 복사한다.
                $sql = "INSERT INTO bgd (SELECT * FROM buygame WHERE BG_Key = {$bgkey})";
                //echo $sql."<br>";
                $res = setQry($sql);
                if (!$res) {
                    $fail++;
                    $json['error'] = "구매내역 데이터 복사에 문제가 발생했습니다[1].";
                }

                $sql = "DELETE FROM buygame WHERE BG_Key = '{$bgkey}'";
                //echo $sql."<br>";
                $res = setQry($sql);
                if (!$res) {
                    $fail++;
                    $json['error'] = "구매내역 데이터 복사에 문제가 발생했습니다[2].";
                }
                //'Charge','Refund','PointConvert','GameBetting','GameCancel','Quota','Other','Recovery','Await','RefundCancel','ChargeWait','Admin','Recover','RefundAwait','CasinoCharge','CasinoRefund'

                $que = "INSERT INTO moneyinfo SET ";
                $que .= "M_Key          = {$_SESSION['S_Key']}, ";
                $que .= "MI_Type        = 'MemberBetCancel', ";
                $que .= "BG_Key         = {$bgkey}, ";
                $que .= "MI_Money       = {$money['BG_BettingPrice']}, ";
                $que .= "MI_Prev_Money  = {$meminfo['M_Money']}, ";
                $que .= "MI_Memo        = '회원배팅 취소로 머니지급함.', ";
                $que .= "MI_RegDate     = NOW() ";
                //echo $que."<br>";
                $res = setQry($que);
                if (!$res) {
                    $fail++;
                }
                error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 지급로그내역 : '.$que.PHP_EOL,3,"/home/trend/www/log/bet_cancel.log");


                $que = "UPDATE members SET M_Money = M_Money + {$money['BG_BettingPrice']}, M_Bet_Cancel_Cnt = M_Bet_Cancel_Cnt - 1 WHERE M_Key = {$_SESSION['S_Key']}";
                //echo $que."<br>";
                $res = setQry($que);
                if (!$res) {
                    $fail++;
                    $json['error'] = "회원 머니지급시 문제가 발생했습니다[1].";
                }

                error_log('회원번호 : '.$_SESSION['S_Key'].' 아이디 : '.$_SESSION['S_ID'].'- 머니지급 : '.$que.PHP_EOL,3,"/home/trend/www/log/bet_cancel.log");

            }

            //echo "실패 횟수 ->"+$fail;
            if($fail>0){
                setQry('ROLLBACK');
                echo '<script>parent.roll_back(false);</script>';
            } else {
                setQry('COMMIT');
                echo '<script>parent.roll_back(true);</script>';
            }



        }
        break;
}


switch($mode){
    #배팅 취소
    case 'bettingCancel':
        setQry("BEGIN");
        $json['flag'] = true;
        $json['error'] = '';

        #회원배팅취소가능 횟수가 0이상일 경우만
        if($meminfo['M_Bet_Cancel_Cnt']>0){
            $que = "SELECT * FROM buygamelist WHERE BG_Key = '{$bgkey}'";
            //echo $que."<br>";
            $arr = getArr($que);
            foreach($arr as $list){
                $sql = "INSERT INTO bgld SELECT * FROM buygamelist WHERE BGL_Key = {$list['BGL_Key']}";
                //echo $sql."<br>";
                $res = setQry($sql);
                if(!$res){
                    $fail++;
                    $json['error'] = "구매내역 상세 데이터 복사에 문제가 발생했습니다[1].";
                }

                $sql = "DELETE FROM buygamelist WHERE BGL_Key = {$list['BGL_Key']} ";
                //echo $sql."<br>";
                $res = setQry($sql);
                if(!$res){
                    $fail++;
                    $json['error'] = "구매내역 상세 데이터 복사에 문제가 발생했습니다[2].";
                }
            }



            $que = "SELECT * FROM buygame WHERE BG_Key = '{$bgkey}'";
            //echo $que."<br>";
            $money = getRow($que);

            if($money['BG_Key']==''){
                $fail++;
                $json['flag'] = false;
                $json['error'] = "구매내역이 없습니다.";
            }
            //배팅취소 시간을 체크한다.
            //echo $SITECONFIG['member_bet_cancel_time'];
            $betCancelTime = strtotime($money['BG_BuyDate']." +".$SITECONFIG['member_bet_cancel_time']." minute");
            $end_time = $betCancelTime-time();

            if($end_time < 0 && $SITECONFIG['member_bet_cancel_YN']=='Y'){
                $fail++;
                $json['flag'] = false;
                $json['error'] = "배팅취소시간이 경과하여 배팅을 취소하실수 없습니다.";
            }


            #구매내역 삭제 테이블에 현재 삭제하려는 데이터를 복사한다.
            $sql = "INSERT INTO bgd (SELECT * FROM buygame WHERE BG_Key = {$bgkey})";
            //echo $sql."<br>";
            $res = setQry($sql);
            if(!$res){
                $fail++;
                $json['error'] = "구매내역 데이터 복사에 문제가 발생했습니다[1].";
            }

            $sql = "DELETE FROM buygame WHERE BG_Key = '{$bgkey}'";
            //echo $sql."<br>";
            $res = setQry($sql);
            if(!$res){
                $fail++;
                $json['error'] = "구매내역 데이터 복사에 문제가 발생했습니다[2].";
            }
            //'Charge','Refund','PointConvert','GameBetting','GameCancel','Quota','Other','Recovery','Await','RefundCancel','ChargeWait','Admin','Recover','RefundAwait','CasinoCharge','CasinoRefund'

            $que = "UPDATE members SET M_Money = M_Money + {$money['BG_BettingPrice']}, M_Bet_Cancel_Cnt = M_Bet_Cancel_Cnt - 1 WHERE M_Key = {$_SESSION['S_Key']}";
            //echo $que."<br>";
            $res = setQry($que);
            if(!$res){
                $fail++;
                $json['error'] = "회원 머니지급시 문제가 발생했습니다[1].";
            }

            $que  = "INSERT INTO moneyinfo SET ";
            $que .= "M_Key          = {$_SESSION['S_Key']}, ";
            $que .= "MI_Type        = 'MemberBetCancel', ";
            $que .= "BG_Key         = {$bgkey}, ";
            $que .= "MI_Money       = {$money['BG_BettingPrice']}, ";
            $que .= "MI_Prev_Money  = {$meminfo['M_Money']}, ";
            $que .= "MI_Memo        = '회원배팅 취소로 머니지급함.', ";
            $que .= "MI_RegDate     = NOW() ";
            //echo $que."<br>";
            $res = setQry($que);
            if(!$res){
                $fail++;
            }


            //echo "실패 횟수 ->"+$fail;
            if($fail>0){
                setQry('ROLLBACK');
                $json['flag'] = false;
            } else {
                setQry('COMMIT');
            }

            echo json_encode($json);
        }
        break;
}

?>