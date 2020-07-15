<?
	$tmp = $lib24c->member_info[M_Level];
	
	if($tmp == 0)
		$tmp = "일반";
	else if($tmp == 1)
		$tmp = "VIP";
	else if($tmp == 2)
		$tmp = "실버";
	else if($tmp == 3)
		$tmp = "골드";
	else if($tmp == 4)
		$tmp = "VVIP";
?>
<script type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-49090309-1', 'bwins2014.com');
  ga('send', 'pageview');

</script>
<div id="login">
<table width="1050" border="0" align="right" cellpadding="0" cellspacing="0">
  <tr>
    <td style="padding-top:5px"><div align="right"><strong><font color="ff94b8">
      <?=$lib24c->member_info[M_NICK];?></font>
    </strong> 님 환영합니다.&nbsp;&nbsp; <img src="../images/button/btn_logout.gif" align="absmiddle" OnClick="Javascript:LogOut();" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/button/icon_01.gif" align="absmiddle"> <font color="#000000">머니 : 
      <strong>
      <?=number_format($lib24c->member_info[M_Money]);?>
      원
      </trong> 
      <img src="../images/button/icon_02.gif" align="absmiddle"> 포인트 : <strong>
        <?=number_format($lib24c->member_info[M_Point]);?>
        원</strong> <img src="../images/button/icon_03.gif" align="absmiddle"> 레벨 : <strong>
          <?=$tmp?>
          </strong> </font><img src="../images/common/memo_icon.gif" width="12" height="12" align="absmiddle" />(<a href="#"><strong onclick="MM_openBrWindow('../popup/receive_memo.php','내쪽지함','scrollbars=yes,width=618,height=460,top=200,left=250')">
          <?=$lib24c->GetNewMemo($lib24c->member_info[M_ID])?>
      </strong></a>)</div></td>
    <!--<td width="51"><? if($lib24c->member_info[M_Admin]=='Y') { ?><img src="../images/button/admin_btn.gif" width="49" height="23" OnClick="Javascript:location.href='/admin/';" style="cursor:pointer;"><? } ?></td>-->
    </tr>
</table>
<?
	if($lib24c->GetNewMemo($lib24c->member_info[M_ID]) > 0) {
?>
<!--<embed src="/images/memo.swf" hidden="true" autostart="1" volume="0" loop="0"  ></embed> -->
<?
	}
?>
</div>
