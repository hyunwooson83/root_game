<?php
$url = "https://dhlottery.co.kr/gameInfo.do?method=gameMethod&callback=?";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 1);
//    curl_setopt($ch, CURLOPT_HEADER, 1);
//    curl_setopt($ch, CURLOPT_HEADER_OUT, 1);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


$response = curl_exec($ch);

if(!curl_errno($ch)){
    // $info = curl_getinfo($ch);

    // print_r($info);
}

$week_convert = array("sun"=>0,"mon"=>1,"tue"=>2,"wed"=>3,"tur"=>4,"fri"=>5,"sat"=>6);
$month_convert = array("jan"=>1,"feb"=>2,"mar"=>3,"apr"=>4,"may"=>5,"jun"=>6,"jul"=>7,"aug"=>8,"sep"=>9,"oct"=>10,"nov"=>11,"dec"=>12);


$tmp = explode("GMT",$response);
$ntry_time = trim($tmp[0]);

$reex = '/[0-9]+:[0-9]+:[0-9]+/i';
preg_match_all($reex,$ntry_time,$out);


$gubun = explode(":",$out[0][0]);

$row['min'] = $gubun[1];
$row['sec'] = $gubun[2];
echo json_encode($row);

?>
