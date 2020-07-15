<?php
    ini_set('display_errors',1);
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    print_r($_SESSION);
    $lobby = get_lobby_list();
    print_r($lobby);
    $account = make_casino_account($_SESSION['S_Key']);
    
    $lobby_url = get_lobby_url();
    
    /*$que = "SELECT M_CasinoID, M_CasinoWallet FROM members WHERE M_Key = '{$_SESSION['S_Key']}' ";
    $row = getRow($que);
    if(empty($row['M_CasinoID'])){
        $account = make_casino_account($_SESSION['S_Key']);
        
    } else {
        
    }*/
?>