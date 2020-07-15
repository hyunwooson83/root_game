<?php
ini_set('display_errors', 1);
ini_set("memory_limit", -1);

$include_path = "/home/bs/www/bs";
include $include_path . "/lib/_lib.php";
include_once($include_path . "/include/Snoopy.class.php");

$snoopy = new Snoopy;
$key = 'e446082c-da71-4e2c-8457-9c3ae43c3c8f';
$snoopy->agent = $_SERVER['HTTP_USER_AGENT'];
if($item == 'soccer'){
    $itemno = 6046;
} else if($item == 'baseball'){
    $itemno = 154914;
} else if($item == 'basketball'){
    $itemno = 48242;
}

$url = 'http://api.oddsapi-inplay.com/bet365/'.$item.'/match?corp=' . $key . '&MI=' . $gid;
//echo $url;
$snoopy->fetch($url);
$content = $snoopy->results;
$data = json_decode($content, true);
print_r($data);
$row = $json = '';

if ($data['_results'][0]['timeStatus'] == 1) {
    $sql = "SELECT * FROM gamelist_live_market WHERE G_ID = '{$gid}'";
    //echo $sql;
    $arr = getArr($sql);
    if(count($arr)>0){
        foreach($arr as $rs){
            //승무패
            //echo $rs['type'];
            $sql = "SELECT * FROM gamelist_live WHERE G_ID = '{$gid}'";
            //echo $sql;
            $rows = getRow($sql);

            $sql = "SELECT * FROM live_gamelist WHERE G_ID = {$gid} ";
            $sql_row = getArr($sql);
            if(count($sql_row)>0){
                foreach($sql_row as $sr){
                    if($sr['G_Type1']=='Full' && $sr['G_Type2']=='WDL'){
                        $json['gkey'][0] = $sr['G_Key'];
                        $json['code'][0] = $sr['G_MainType'];
                    } else if($sr['G_Type1']=='Special' && $sr['G_Type2']=='WDL' && $sr['G_SubNum'] == 1){
                        $json['gkey'][1] = $sr['G_Key'];
                        $json['code'][1] = $sr['G_MainType'];
                    } else if($sr['G_Type1']=='Special' && $sr['G_Type2']=='WDL' && $sr['G_SubNum'] == 2){
                        $json['gkey'][2] = $sr['G_Key'];
                        $json['code'][2] = $sr['G_MainType'];
                    } else if($sr['G_Type1']=='Full' && $sr['G_Type2']=='Handicap'){
                        $json['gkey'][3] = $sr['G_Key'];
                        $json['code'][3] = $sr['G_MainType'];
                    } else if($sr['G_Type1']=='Full' && $sr['G_Type2']=='UnderOver'){
                        $json['gkey'][4] = $sr['G_Key'];
                        $json['code'][4] = $sr['G_MainType'];
                    }
                }
            }

            //echo $rows['home_korName'];
            //$json['homeName'] = (!empty($rows['home_korName']))?mysql_real_escape_string($rows['home_korName']):$rows['home_name'];
            //$json['awayName'] = (!empty($rows['away_korName']))?mysql_real_escape_string($rows['away_korName']):$rows['away_name'];
            if(strlen($rows['home_name'])>15)   $tail1 = '...';
            $json['homeName'] = mb_strcut(mysql_real_escape_string($rows['home_name']),0,15,'utf-8').$tail1;
            if(strlen($rows['away_name'])>15)   $tail2 = '...';
            $json['awayName'] = mb_strcut(mysql_real_escape_string($rows['away_name']),0,15,'utf-8').$tail2;

            $json['TM'] = $data['_results'][0]['timeMark'];

            if($rs['type']=='WDL') {

                $row['gkey']        = $sql_row['G_Key'];

                $row['wdlShow']    = 'Y';
                $row['marketType'] = 'WDL';
                $row['code']        = $rs['marketCode'];
                $row['homeRate'] = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][0]['odds'],0,4);
                $row['drawRate'] = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][1]['odds'],0,4);
                $row['awayRate'] = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][2]['odds'],0,4);
                $row['suspended'] = $data['_results'][0]['_market'][$rs['marketCode']]['suspended'];
                /*} else {
                    $row['wdlShow']    = 'N';
                    $row['marketType'] = 'WDL';
                    $row['homeRate'] = '';
                    $row['drawRate'] = '';
                    $row['awayRate'] = '';
                }*/
                $json['WDL'] = $row;
            }
            //전반 승무패

            if(!empty($data['_results'][0]['_market']['_01_01_1']['matchOdds']) && $data['_results'][0]['timeMark']=='1st'){
                $sql = "SELECT G_Key FROM live_gamelist WHERE G_ID = {$gid} AND G_Type1 ='Full' AND G_Type2 = 'Special' AND G_SubNum = 1";
                $sql_row = getRow($sql);
                $row1['gkey']        = $sql_row['G_Key'];
                $row1['1stShow']  = 'Y';
                $row1['code']    = $rs['marketCode'];
                $row1['homeRate'] = substr($data['_results'][0]['_market']['_01_01_1']['matchOdds'][0]['odds'],0,4);
                $row1['drawRate'] = substr($data['_results'][0]['_market']['_01_01_1']['matchOdds'][1]['odds'],0,4);
                $row1['awayRate'] = substr($data['_results'][0]['_market']['_01_01_1']['matchOdds'][2]['odds'],0,4);
                $row1['suspended'] = $data['_results'][0]['_market']['_01_01_1']['suspended'];
                $json['WDL1st'] = $row1;
            } else {
                $row1['1stShow']    = 'N';
                $row1['homeRate'] = '';
                $row1['drawRate'] = '';
                $row1['awayRate'] = '';
                $row1['suspended'] = true;
                $json['WDL1st'] = $row1;
            }

            if(!empty($data['_results'][0]['_market']['_01_02_10']['matchOdds']) && $data['_results'][0]['timeMark']=='2nd'){//후반 승무패
                $sql = "SELECT G_Key FROM live_gamelist WHERE G_ID = {$gid} AND G_Type1 ='Full' AND G_Type2 = 'Special' AND G_SubNum = 2";
                $sql_row = getRow($sql);
                $row2['gkey']        = $sql_row['G_Key'];
                $row2['2ndShow']  = 'Y';
                $row2['code']     = $rs['marketCode'];
                $row2['homeRate'] = substr($data['_results'][0]['_market']['_01_02_10']['matchOdds'][0]['odds'],0,4);
                $row2['drawRate'] = substr($data['_results'][0]['_market']['_01_02_10']['matchOdds'][1]['odds'],0,4);
                $row2['awayRate'] = substr($data['_results'][0]['_market']['_01_02_10']['matchOdds'][2]['odds'],0,4);
                $row2['suspended'] = $data['_results'][0]['_market']['_01_02_10']['suspended'];
                $json['WDL2nd'] = $row2;
            } else {
                $row2['2ndShow']    = 'N';
                $row2['homeRate'] = '';
                $row2['drawRate'] = '';
                $row2['awayRate'] = '';
                $row2['suspended'] = true;
                $json['WDL2nd'] = $row2;
            }
            //핸디캡
            if($rs['type']=='HANDICAP') {
                if (empty($data['_results'][0]['_market'][$rs['marketCode']]['suspended'])){
                $result_divided = abs($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][0]['option'])*100;
                    if($result_divided%50==0) {
                        $sql = "SELECT G_Key FROM live_gamelist WHERE G_ID = {$gid} AND G_Type1 ='Full' AND G_Type2 = 'Handicap'";
                        $sql_row = getRow($sql);
                        $row3['gkey']        = $sql_row['G_Key'];
                        $row3['handicapShow']    = 'Y';
                        $row3['marketType']      = 'Handicap';
                        $row3['code']        = $rs['marketCode'];
                        $row3['handiWin']        = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][0]['odds'],0,4);
                        $row3['handiWinOption']  = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][0]['option'],0,4);
                        $row3['handiLose']       = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][1]['odds'],0,4);
                        $row3['handiLoseOption'] = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][1]['option'],0,4);
                        $row3['suspended'] = $data['_results'][0]['_market'][$rs['marketCode']]['suspended'];
                    } else {
                        $row3['handicapShow']    = 'N';
                        $row3['marketType']      = 'Handicap';
                        $row3['handiWin']        = '';
                        $row3['handiWinOption']  = '';
                        $row3['handiLose']       = '';
                        $row3['handiLoseOption'] = '';
                    }
                } else {
                    $row3['handicapShow']    = 'N';
                    $row3['marketType']      = 'Handicap';
                    $row3['handiWin']        = '';
                    $row3['handiWinOption']  = '';
                    $row3['handiLose']       = '';
                    $row3['handiLoseOption'] = '';
                }
                $json['Handicap'] = $row3;
            }

            //핸디캡 기타기준점
            $handicap_other_cnt = 0;
            if($rs['type']=='HANDICAPOTHER') {
                //if (empty($data['_results'][0]['_market'][$rs['marketCode']]['suspended'])){
                for($h=0;$h<count($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds']);$h+=2) {
                    //echo $data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h]['option']."\n";
                    $result_divided = abs($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h]['option']) * 100;
                    if ($result_divided % 50 == 0) {
                        //echo $handicap_other_cnt;
                        //$row5['q'] = $data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h]['option'];
                        //$row5['code']        = $rs['marketCode'];
                        $sql = "SELECT G_Key FROM live_gamelist WHERE G_ID = {$gid} AND G_Type1 ='Full' AND G_Type2 = 'Handicap'";
                        $sql_row = getRow($sql);
                        $row5[$handicap_other_cnt]['gkey']        = $sql_row['G_Key'];
                        $row5[$handicap_other_cnt]['handicapOtherShow'] = 'Y';
                        $row5[$handicap_other_cnt]['id'] = $data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h]['id'];
                        $row5[$handicap_other_cnt]['marketType'] = 'HandicapOther';
                        $row5[$handicap_other_cnt]['handiWin'] = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h]['odds'], 0, 4);
                        $row5[$handicap_other_cnt]['handiWinOption'] = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h]['option'], 0, 4);
                        $row5[$handicap_other_cnt]['handiLose'] = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h+1]['odds'], 0, 4);
                        $row5[$handicap_other_cnt]['handiLoseOption'] = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h+1]['option'], 0, 4);
                        $row5[$handicap_other_cnt]['suspended'] = $data['_results'][0]['_market'][$rs['marketCode']]['suspended'];
                        $handicap_other_cnt++;
                    }
                }
                /*} else {
                    $row5[0]['handicapOtherShow']    = 'N';
                    $row5[0]['marketType']      = 'HandicapOther';
                    $row5[0]['handiWin']        = '';
                    $row5[0]['handiWinOption']  = '';
                    $row5[0]['handiLose']       = '';
                    $row5[0]['handiLoseOption'] = '';
                }*/
                $json['HandicapOther'] = $row5;
            }

            //언더오버
            if($rs['type']=='UNDEROVER') {
                //if (empty($data['_results'][0]['_market'][$rs['marketCode']]['suspended'])){
                $result_divided = abs($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][0]['option'])*100;
                if($result_divided%50==0) {
                    $sql = "SELECT G_Key FROM live_gamelist WHERE G_ID = {$gid} AND G_Type1 ='Full' AND G_Type2 = 'UnderOver'";
                    $sql_row = getRow($sql);
                    $row5['gkey']        = $sql_row['G_Key'];
                    $row4['marketType']      = 'UnderOver';
                    $row4['code']        = $rs['marketCode'];
                    $row4['over']        = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][0]['odds'],0,4);
                    $row4['overOption']  = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][0]['option'],0,4);
                    $row4['under']       = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][1]['odds'],0,4);
                    $row4['underOption'] = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][1]['option'],0,4);
                    $row4['suspended'] = $data['_results'][0]['_market'][$rs['marketCode']]['suspended'];
                } else {
                    $row4['a'] = $data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][0]['option']."|".$result_divided."|".$result_divided%50;
                    $row4['ouShow']    = 'N';
                    $row4['marketType']      = 'UnderOver';
                    $row4['over']        = '';
                    $row4['overOption']  = '';
                    $row4['under']       = '';
                    $row4['underOption'] = '';
                }
                /*} else {
                    $row4['handicapShow']    = 'N';
                    $row4['marketType']      = 'UnderOver';
                    $row4['over']        = '';
                    $row4['overOption']  = '';
                    $row4['under']       = '';
                    $row4['underOption'] = '';
                }*/
                $json['UnderOver'] = $row4;
            }

            //언더오버 기타기준점
            $underover_other_cnt = 0;
            if($rs['type']=='UNDEROVEROTHER') {

                //if (empty($data['_results'][0]['_market'][$rs['marketCode']]['suspended'])){
                for($h=0;$h<count($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds']);$h+=2) {
                    $result_divided = abs($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h]['option']) * 100;
                    //echo "<br>\n";
                    if ($result_divided % 50 == 0) {
                        $sql = "SELECT G_Key FROM live_gamelist WHERE G_ID = {$gid} AND G_Type1 ='Full' AND G_Type2 = 'UnderOver'";
                        $sql_row = getRow($sql);
                        $row6[$underover_other_cnt]['gkey']        = $sql_row['G_Key'];
                        $row6[$underover_other_cnt]['ouOtherShow'] = 'Y';
                        $row6[$underover_other_cnt]['id'] = $data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h]['id'];
                        $row6[$underover_other_cnt]['marketType'] = 'ouOther';
                        $row6[$underover_other_cnt]['over'] = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h]['odds'], 0, 4);
                        $row6[$underover_other_cnt]['overOption'] = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h]['option'], 0, 4);
                        $row6[$underover_other_cnt]['under'] = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h+1]['odds'], 0, 4);
                        $row6[$underover_other_cnt]['underOption'] = substr($data['_results'][0]['_market'][$rs['marketCode']]['matchOdds'][$h+1]['option'], 0, 4);
                        $row6[$underover_other_cnt]['suspended'] = $data['_results'][0]['_market'][$rs['marketCode']]['suspended'];
                        $underover_other_cnt++;
                    }
                }
                /*} else {
                    $row6[0]['ouOtherShow']    = 'N';
                    $row6[0]['marketType']      = 'ouOther';
                    $row6[0]['over']        = '';
                    $row6[0]['overOption']  = '';
                    $row6[0]['under']       = '';
                    $row6[0]['underOption'] = '';
                }*/
                $json['ouOther'] = $row6;
            }
        }
    }
} else {
    $json['timeMark'] = $data['_results'][0]['timeStatus'];
}
//$json['bet365'] = $data;

echo json_encode($json);