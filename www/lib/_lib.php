<?php
/*******************************************************************************
 * include �Ǿ������� �˻�
 ******************************************************************************/
if(defined("_lib_included")) return;
	define("_lib_included",true);

/*******************************************************************************
 * ���
 ******************************************************************************/
 ##### W3C P3P �Ծ༳��
//header("P3P : CP='ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC'");
header("Content-Type: text/html; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/*******************************************************************************
 * ȯ�� ����
 ******************************************************************************/

if(file_exists(dirname(__FILE__)."/_config.php")){
	include(dirname(__FILE__)."/_config.php");
}else{
	echo "���� ������ �������� �ʽ��ϴ�.";
	exit;
}

/*-------------------------------------------------------------------------------------------------
�� �⺻��� ���� (�̺κ��� �������� ������.) */
if(isset($_ENV['OS'])) {
	if(strstr( $_ENV['OS'],'Windows')){
		define("_ROOT_",str_replace("/",'\\',$_SERVER['DOCUMENT_ROOT']).'/');
//		define("_ROOT",str_replace("\\",'/',$_SERVER['DOCUMENT_ROOT']));
	}else{
		define("_ROOT_",$_SERVER['DOCUMENT_ROOT'].'/');
	}
}else{
	define("_ROOT_",$_SERVER['DOCUMENT_ROOT'].'/');
}

/*******************************************************************************
 * ���� ������ ����
 ******************************************************************************/
if(_ERROR_REPORTING == "on"){
	error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL);
//	ini_set('error_reporting', E_ALL | E_STRICT);
	ini_set('display_error','on');
}elseif(_ERROR_REPORTING == "off"){
	ini_set('error_reporting', E_ALL | E_STRICT);
	ini_set('log_error','on');
	ini_set('error_log', _ERROR_LOG);
	set_error_handler('my_error_handler');
}

function my_error_handler($number, $string, $file, $line, $context){
	$error = "= == == == ==\nPHP ERROR\n= == == == ==\n";
	$error .= "Number: [$number] ".date("Y")."-".date("m")."-".date("d")." ".date("H").":".date("i")."-".date("s")."\n";
	$error .= "string: [$string]\n";
	$error .= "file: [$file]\n";
	$error .= "line: [$line]\n\n";
//	$error .= "context: [$context]\n\n\n";
	error_log($error, 3, _ERROR_LOG);
}

/*******************************************************************************
 * ��Ÿ php ����
 ******************************************************************************/
$phpversion = phpversion();
if($phpversion > 5){
//	date_default_timezone_set('Asia/Seoul');
}
if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get"))
@date_default_timezone_set(@date_default_timezone_get());


/*******************************************************************************
 * ���۵� �� ó��
 ******************************************************************************/
 ##### POST�� ���۵� ���� �յ� ������ �����Ѵ�.
foreach($_POST as $http_post_key => $http_post_value){
	if(!is_array($http_post_value)){
		${$http_post_key} = @trim($http_post_value);
	}else{
		${$http_post_key."[]"} = @trim($http_post_value);
	}
}

/*******************************************************************************
 * ���� ó�� ,register_globals_on�϶� ���� �� ����
 ******************************************************************************/
 ##### ª�� ȯ�溯���� �������� �ʴ´ٸ�
if (isset($HTTP_POST_VARS) && !isset($_POST)) {
	$_POST   = &$HTTP_POST_VARS;
	$_GET    = &$HTTP_GET_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_ENV    = &$HTTP_ENV_VARS;
	$_FILES  = &$HTTP_POST_FILES;

  if (!isset($_SESSION))$_SESSION = &$HTTP_SESSION_VARS;
}


@extract($_GET);
@extract($_POST);
@extract($_SESSION);


define("_THIS_PATH",realpath('.'));
define("_THIS_URI",dirname($_SERVER["SCRIPT_NAME"]));
define("_THIS_FOLDER",basename(dirname($_SERVER["SCRIPT_NAME"])));


ini_set("session.gc_maxlifetime","3600");
if(ini_get('session.auto_start') < 1){
	if (session_id() == "") @session_start();
}

?>