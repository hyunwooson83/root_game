</div>
</body>
</html>

<form name="HiddenActionForm" style="display:none;" target="HIddenActionFrame">
  <input type="text" name="HAF_Value_0">
  <input type="text" name="HAF_Value_1">
  <input type="text" name="HAF_Value_2">
  <input type="text" name="HAF_Value_3">
  <input type="text" name="HAF_Value_4">
  <input type="text" name="HAF_Value_5">
  <input type="text" name="HAF_Value_6">
  <input type="text" name="HAF_Value_7">
  <input type="text" name="HAF_Value_8">
  <input type="text" name="HAF_Value_9">
  <input type="text" name="HAF_Value_10">
  <input type="text" name="HAF_Value_11">
  <input type="text" name="HAF_Value_12">
  <input type="text" name="HAF_Value_13">
  <input type="text" name="HAF_Value_14">
  <input type="text" name="HAF_Value_15">
  <input type="text" name="HAF_Value_16">
  <input type="text" name="HAF_Value_17">
  <input type="text" name="HAF_Value_18">
  <input type="text" name="HAF_Value_19">
  <input type="text" name="HAF_Value_20">
</form>
<iframe src="about:blank" style="display:none;width:600px;height:500px;" name="HIddenActionFrame" id="HIddenActionFrame" ></iframe>
<?
  // 액션 폴더시 HTML Header 출력하지 않음
  if ( $lib->ThisFolderName() != "action" && !eregi("popup", $lib->ThisPageName() ) && !eregi("game_register", $lib->ThisPageName() ) ) {
?>
<script>
live_message_timer();
</script>
<?
  };
?>