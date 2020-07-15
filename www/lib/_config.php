<?php
if(defined("_config_included")) return;
	define("_config_included",true);
	
	/*define("_DOMAIN_",$_SERVER['HTTP_HOST']);*/
	define("_PATH_",'/home/trend/www/');
	define("_DIR_",dirname(__FILE__).'/');
	define("_ADMIN_",'_admin/');
	define("_URI_","");	
	define("_CORE_",str_replace($_SERVER['DOCUMENT_ROOT'],"",dirname(dirname(__FILE__))));
	
	define("_ERROR_REPORTING","on");
	
	define("_CORE_PATH_",_PATH_._CORE_);
	define("_ADMIN_PATH_",_PATH_._ADMIN_);

	define("_DATA_PATH_",str_replace("core","",_CORE_PATH_)."DATA/file");
	define("_DATA_URI_", _URI_."/DATA/file");

	define("_TMP_PATH_",str_replace("core","",_CORE_PATH_)."DATA/tmp");
	define("_TMP_URI_", str_replace("core","",_CORE_)."DATA/tmp");	
	
	/*******************************************************************************
	 * 기�? php ?�정
	 ******************************************************************************/
	$phpversion = phpversion();
	if($phpversion > 5){
		date_default_timezone_set('Asia/Seoul');
	}
	if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get"))
	@date_default_timezone_set(@date_default_timezone_get());
	
	$today = date("Y-m-d",time());	
	
	
	include_once(_PATH_.'lib/db.php');
	include_once(_PATH_.'lib/func.php');
?>