<?php
$root_path = '.';
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/common.php';

if($SITECONFIG['Site_Stop_YN']=='Y'){
    move('/constructor/');
}

if(!empty($_SESSION['S_Key'])){
    move('/m/login/');
} else {
    loginChk('');
}
?>