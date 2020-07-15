<? include "../include/header.php"; ?>
<?
    // 1. 충전 신청 건수 체크
    $result = $db->Execute("select count(R_Key) as CNT from requests where R_Type1='Charge' and R_State='Await'");
    $row = $result->FetchRow();
    $Charge_Count = $row[CNT];
    
    // 2. 환전 요청 건수 체크
    $result = $db->Execute("select count(R_Key) as CNT from requests where R_Type1='Refund' and R_State='Await'");
    $row = $result->FetchRow();
    $Refund_Count = $row[CNT];
    
    // 3. 게시판 새글 건수 체크
    $result = $db->Execute("select count(B_Key) as CNT from board where B_ID='join' and B_AdminRead='N'");
    $row = $result->FetchRow();
    $Board_Count = $row[CNT];


    // 4. 고객센터 새글 건수 체크
    $result = $db->Execute("select count(B_Key) as CNT from board where B_ID='customer' and B_AdminRead='N' and B_Delete='N'");
    $row = $result->FetchRow();
    $Customer_Count = $row[CNT];

    // 4. 베팅내역 새글 건수 체크
    //$result = $db->Execute("select count(B_Key) as CNT from board where B_ID='betting' and B_AdminRead='N' and B_Delete='N'");
    //$row = $result->FetchRow();
    //$Bet_Count = $row[CNT];
    $Bet_Count = 0;
    
    $bell = '<embed src="/images/bell.swf" hidden="true" autostart="1" volume="0" loop="0"  ></embed>';    
    if ( $Charge_Count + $Refund_Count + $Board_Count + $Customer_Count + $Bet_Count < 1 ) $bell = '';
?><div><img src="images/live_message_title.gif"></div>
<ul>
<li>충전신청 : [<a href="/admin/money.php"><?=$Charge_Count;?></a>]건</li>
<li>환전신청 : [<a href="/admin/money.php"><?=$Refund_Count;?></a>]건</li>
<li>가입문의 : [<a href="/admin/join_list.php?tn=join"><?=$Board_Count;?></a>]건</li>
<li>고객센터새글 : [<a href="/admin/board_list.php?tn=customer"><?=$Customer_Count;?></a>]건</li>
<!--<li>유져배팅새글 : [<a href="/admin/board_list.php?tn=betting"><?=$Bet_Count;?></a>]건</li>-->
</ul>
<div><img src="images/live_message_bt.gif"></div>
<?=$bell;?>