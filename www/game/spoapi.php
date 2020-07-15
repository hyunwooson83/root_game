<?php
ini_set('display_errors', 1);
ini_set("memory_limit", -1);

$include_path = "/home/bs/www/bs";
include $include_path . "/lib/_lib.php";
include_once($include_path . "/include/Snoopy.class.php");

$snoopy = new Snoopy;
$key = '864f965f6b49e962e059f074af5c3d39';
$snoopy->agent = $_SERVER['HTTP_USER_AGENT'];
$url = 'http://newapi.spoapi.com/matches?apiKey='.$key.'&sportsName=soccer&progress=standBy&outputFormat=json';
//echo $url;
$snoopy->fetch($url);
$content = $snoopy->results;
$data = json_decode($content, true);
print_r($data);