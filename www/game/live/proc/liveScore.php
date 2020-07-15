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
$que = "SELECT * FROM gamelist_live WHERE status = '1' AND matchDateTime < NOW() AND GI_Key = '{$itemno}' AND make_yn = 'y'  ";
//echo $que;
$arr = getArr($que);
if(count($arr)>0) {
    foreach ($arr as $rs) {
        $url = 'http://api.oddsapi-inplay.com/bet365/'.$item.'/match?corp=' . $key . '&MI=' . $rs['G_ID'];
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