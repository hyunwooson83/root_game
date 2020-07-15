<?php
  CLASS MCLEN_LIB24C
  {
    var $used         = null;
    var $db           = null;
    var $lib          = null;
    var $config_24c   = null;
    var $point_info   = null;
    var $member_info  = null;
    var $access_user  = null;
    var $today_join   = null;

    function MCLEN_LIB24C( &$db = "", &$lib = "" ) {
      if( $db ) {
        $this->db = $db;
        // 사이트 설정 로드
        $result = $this->db->Execute('select * from siteconfig');
        $this->config_24c = $result->FetchRow();
        // 포인트 설정 로드
        $result = $this->db->Execute("select * from pointconfig where level='".$this->member_info[M_Level]."'");
        $this->point_info = $result->FetchRow();

      }
      if( $lib ) $this->lib = $lib;
    }

   /* function GetAccessUser() {
      $this->db->Execute("UPDATE members SET M_LastAccessDate = '".date("Y-m-d H:i:s")."' WHERE M_Key = ".$_SESSION[S_Key]);
      $result = $this->db->Execute("SELECT count(M_Key) as AccessCount FROM members WHERE M_LastAccessDate > '".date("Y-m-d H:i:s", time() - 600 )."'");
      $row = $result->FetchRow();
      $this->access_user = $row[AccessCount];
      $result = $this->db->Execute("SELECT count(M_Key) as JoinCount FROM members WHERE M_RegistDate >= '".date("Y-m-d" )."'");
      $row = $result->FetchRow();
      $this->today_join = $row[JoinCount];
    }*/

    function CalcEndGameCount() {
    	//정산이 안된 고객의 배팅 중 경기 결과가 입력된 내용을 업데이트 처리 한다.
    	$result = $this->db->Execute('SELECT * FROM buygame WHERE BG_Result=? ', array('Await') );
    	if($result) {
    		while($rows = $result->FetchRow()) {
    			//해당 BG_Key 에 속한 게임들의 결과 값이 반영되었는가 읽어온다.
    			$BG_Key = $rows[BG_Key];
    			$cres = $this->db->Execute('select COUNT(*) as CNT from buygamelist a left join gamelist b ON a.G_Key=b.G_Key WHERE a.BG_Key=? AND a.BGL_State<>?', array($BG_Key,'Await'));
    			if($cres) {
    				$crow = $cres->FetchRow();
    				$BG_GameCompleteCount = $crow['CNT'];
    				if($BG_GameCompleteCount == '') $BG_GameCompleteCount = 0;
    				
    				//해당 데이터로 업데이트
    				$this->db->Execute('UPDATE buygame SET BG_GameCompleteCount=? WHERE BG_Key=?', array($BG_GameCompleteCount, $BG_Key) );
    			}
    		}
    	}
    }
    
    function UpdateGameState() {
      $chk_time = 60 * 1;
      if ( $this->config_24c[UpdateGame] + $chk_time < time() ) {
        $this->db->Execute("UPDATE gamelist SET G_State='Stop' WHERE G_State='Await' and G_Datetime < '".date("Y-m-d H:i:s")."'");

        $record = null;
        $record[UpdateGame] = time();
        $where = "1";
        $this->db->AutoExecute("siteconfig",$record,'UPDATE', $where);
      };
    }

    function GetBettingState( $bg_key ) {
      $query = "select * from buygame where BG_Key = ". $bg_key . " and M_Key = ". $this->member_info[M_Key];
      $result = $this->db->Execute($query);
      $rows = $result->FetchRow();

      return $rows;
    }

    function GetBettingHtml( $bg_key, $is_my = 1 ) {

      if($is_my == 1)
      	$query = "select * from buygame where BG_Key = ".$bg_key." and M_Key = ". $this->member_info[M_Key];
      else
      	$query = "select * from buygame where BG_Key = ".$bg_key;
    	
      $result = $this->db->Execute($query);
      $rows = $result->FetchRow();

      $html_src = "";

      switch ( $rows[BG_Result] ) {
        case "Success" : $real_forecastprice = $rows[BG_ForecastPrice]; $g_resulticon = '<span class="yellow">적중</span> <img src="../images/win_icon.gif" width="19" height="22" align="absmiddle">'; break;
        case "Fail" : $real_forecastprice = 0; $g_resulticon = '<span class="text_blue1">미적중</span> <img src="../images/lose_icon.gif" width="19" height="22" align="absmiddle">'; break;
        case "Await" : $real_forecastprice = 0; $g_resulticon = '<strong>진행중</strong> <img src="../images/ongoing_icon.gif" width="22" height="21" align="absmiddle">'; break;
        case "Cancel" : $real_forecastprice = $rows[BG_BettingPrice]; $g_resulticon = '<span class="yellow">적중</span> <img src="../images/win_icon.gif" width="19" height="22" align="absmiddle">'; break;
      };

      $result_gamelist = $this->db->Execute("select * from buygamelist a left join gamelist b on a.G_Key=b.G_Key left join gameleague c on b.GL_Key=c.GL_Key left join gameitem d on c.GI_Key=d.GI_Key where a.BG_Key=? order by a.BG_Key desc", array( $rows[BG_Key] ) );

      $html_src .= '<div class="basiclisttable"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><th width="81">경기일시</th><th width="93">리그</th><th width="161">승(홈팀)</th><th width="50">무/기준</th><th width="161">패(원정팀)</th><th width="50">언더/홀</th><th width="50">오버/짝</th><th>배팅팀</th><th width="56">경기결과</th><th width="">상태</th></tr>';

      while ($game_rows = $result_gamelist->FetchRow()) {
        $g_resultchoice = $this->Convert_Game_Result($game_rows[BGL_ResultChoice]);

        switch( $game_rows[G_State] ) {
          case 'Await'  : $g_state = "진행"; break;
          case 'Stop'   : $g_state = "종료"; break;
          case 'End'    :
            $re1 = $this->Convert_Game_Result($game_rows[G_ResultWDL]);
            $re2 = $this->Convert_Game_Result($game_rows[G_ResultHandicap]);
            $re3 = $this->Convert_Game_Result($game_rows[G_ResultUnderOver]);
            $re4 = $this->Convert_Game_Result($game_rows[G_ResultOddEven]);
            $g_state = $re1.$re2.$re3.$re4;
          	$g_result = $this->Convert_Game_Result($game_rows[BGL_State]);

            $g_state .= " [".$game_rows[G_ResultScoreWin].":".$game_rows[G_ResultScoreLose]."]";
          break;
          case 'Cancel' : $g_state = "취소";  $g_result = "적중"; break;
          case 'Delete' : $g_state = "삭제"; break;
        };

        if ( $game_rows[BGL_State] == 'Await' ) $g_result = '';

        $html_src .= '<tr><td align="center">'.(date("[m/d H:i]", strtotime($game_rows[G_Datetime]))).'</td><td><img src="/action/upload_image.php?f_key='.$game_rows[GI_Image].'" width="16" height="16">'.$game_rows[GL_Type].'</td><td><div class="line3">'.$game_rows[G_Team1].'</div><div class="line4">'.$game_rows[BGL_QuotaWin].$game_rows[BGL_QuotaHandiWin].'</div></td><td align="center">'.$game_rows[BGL_QuotaDraw].$game_rows[BGL_QuotaHandicap].$game_rows[BGL_QuotaUnderOver].'</td><td><div class="line3">'.$game_rows[G_Team2].'</div><div class="line4">'.$game_rows[BGL_QuotaLose].$game_rows[BGL_QuotaHandiLose].'</div></td><td align="center">'.$game_rows[BGL_QuotaUnder].$game_rows[BGL_QuotaOdd].'</td><td align="center">'.$game_rows[BGL_QuotaOver].$game_rows[BGL_QuotaEven].'</td><td align="center"><span class="yellow">'.$g_resultchoice.'</span></td><td align="center"><span class="yellow">'.$g_state.'</span></td><td align="center">'.$g_result.'</td></tr>';
      };

      $html_src .= '<tr><td colspan="10"><div class="board_result"><ul><li>배팅일 : <strong>'.$rows[BG_BuyDate].'</strong></li><li>총배당률 : <strong>'.$rows[BG_TotalQuota].'</strong></li><li>배팅금액 : <strong>'.number_format($rows[BG_BettingPrice]).'</strong></li><li>예상적중금 : <strong>'.number_format($rows[BG_ForecastPrice]).'</strong></li><li>적중금액 : <strong>'.number_format($real_forecastprice).'원</strong></li><li>'.$g_resulticon.'</li></ul></div></td></tr></table></div>';
      return $html_src;
    }

    function Convert_Game_Result ( $str ) {
      switch( $str ){
        case "Win"        : $result = "승"; break;
        case "Draw"       : $result = "무"; break;
        case "Lose"       : $result = "패"; break;
        case "HandiWin"   : $result = "핸디승"; break;
        case "HandiLose"  : $result = "핸디패"; break;
        case "Under"      : $result = "언더"; break;
        case "Over"       : $result = "오버"; break;
        case "Odd"        : $result = "홈"; break;
        case "Even"       : $result = "원정"; break;
        case "Success"    : $result = "적중"; break;
        case "Fail"       : $result = "실패"; break;
        case "Cancel"     : $result = "취소"; break;
		default           : $result = "진행"; break;
      };
      return $result;
    }



    function Cart_Info ( $price = 0 , $gkey = 0, $err_msg = "", $no_html = "" ) {
		
      $Cart_Html = "";
	
	$Cart_Html .= '
			<div id="right_wrapper">
				<div class="sidebar">
					<!-- 시계 -->
					<div class="time_con">
						<embed src="http://www.clocklink.com/clocks/5012-black.swf?TimeZone=KoreaRepublicof_Seoul&"  width="200" height="60" wmode="transparent" type="application/x-shockwave-flash">
					</div>		
      ';
	
     $Cart_Html1 = '';
	 

      // 장바구니에 담긴 게임 가져오기(변경점 포함)
      $BettingQuota = 1;
	  
	  
      $result = $this->db->Execute("select * from cartgamelist a left join gamelist b on a.G_Key=b.G_Key left join gameleague c on b.GL_Key=c.GL_Key left join gameitem d on c.GI_Key=d.GI_Key where a.M_Key=? order by a.CGL_RegDate asc", array( $_SESSION[S_Key] ) );
      if( $result ) {
         $Cart_Cnt = $result->RecordCount();
		
        	while ($rows = $result->FetchRow()) {
			
          		// 경기 상태 체크
          if ( $rows[G_State] != 'Await' ) {
            $err_msg .= "[".$rows[G_Team1]." vs ".$rows[G_Team2]."] 경기가 발매 중지되어 카트에서 삭제되었습니다.n";
            $this->db->Execute("delete from cartgamelist where CGL_Key=".$rows[CGL_Key]);
            if ( $no_html == "Y" ) $this->lib->AlertMSG( $err_msg.'\\n구매 게임을 다시 확인해 주세요.', "/","", "parent" );
          };
		 
			
			
			
          // 경기 배율 변경 체크
          if (
                $rows[G_State] == 'Await' && (
                  $rows[CGL_QuotaWin] != $rows[G_QuotaWin] ||
                  $rows[CGL_QuotaDraw] != $rows[G_QuotaDraw] ||
                  $rows[CGL_QuotaLose] != $rows[G_QuotaLose] ||
                  $rows[CGL_QuotaHandicap] != $rows[G_QuotaHandicap] ||
                  $rows[CGL_QuotaHandiWin] != $rows[G_QuotaHandiWin] ||
                  $rows[CGL_QuotaHandiLose] != $rows[G_QuotaHandiLose] ||
                  $rows[CGL_QuotaUnderOver] != $rows[G_QuotaUnderOver] ||
                  $rows[CGL_QuotaUnder] != $rows[G_QuotaUnder] ||
                  $rows[CGL_QuotaOver] != $rows[G_QuotaOver] ||
                  $rows[CGL_QuotaOdd] != $rows[G_QuotaOdd] ||
                  $rows[CGL_QuotaEven] != $rows[G_QuotaEven]
                )
             ) {
            $err_msg .= "[".$rows[G_Team1]." vs ".$rows[G_Team2]."] 경기 배율이 변경되어 카트에 담긴 배율도 수정되었습니다.";
            $record = null;
            $record[CGL_QuotaWin]       = $rows[G_QuotaWin];
            $record[CGL_QuotaDraw]      = $rows[G_QuotaDraw];
            $record[CGL_QuotaLose]      = $rows[G_QuotaLose];
            $record[CGL_QuotaHandicap]  = $rows[G_QuotaHandicap];
            $record[CGL_QuotaHandiWin]  = $rows[G_QuotaHandiWin];
            $record[CGL_QuotaHandiLose] = $rows[G_QuotaHandiLose];
            $record[CGL_QuotaUnderOver] = $rows[G_QuotaUnderOver];
            $record[CGL_QuotaUnder]     = $rows[G_QuotaUnder];
            $record[CGL_QuotaOver]      = $rows[G_QuotaOver];
            $record[CGL_QuotaOdd]       = $rows[G_QuotaOdd];
            $record[CGL_QuotaEven]      = $rows[G_QuotaEven];
            $where = "CGL_Key=".$rows[CGL_Key];
            $this->db->AutoExecute("cartgamelist",$record,'UPDATE',$where);

            $rows[CGL_QuotaWin]       = $rows[G_QuotaWin];
            $rows[CGL_QuotaDraw]      = $rows[G_QuotaDraw];
            $rows[CGL_QuotaLose]      = $rows[G_QuotaLose];
            $rows[CGL_QuotaHandicap]  = $rows[G_QuotaHandicap];
            $rows[CGL_QuotaHandiWin]  = $rows[G_QuotaHandiWin];
            $rows[CGL_QuotaHandiLose] = $rows[G_QuotaHandiLose];
            $rows[CGL_QuotaUnderOver] = $rows[G_QuotaUnderOver];
            $rows[CGL_QuotaUnder]     = $rows[G_QuotaUnder];
            $rows[CGL_QuotaOver]      = $rows[G_QuotaOver];
            $rows[CGL_QuotaOdd]       = $rows[G_QuotaOdd];
            $rows[CGL_QuotaEven]      = $rows[G_QuotaEven];

            if ( $no_html == "Y" ) $this->lib->AlertMSG( $err_msg.'\\n구매 게임을 다시 확인해 주세요.', "/","", "parent" );
          };

          $prefix = "";
          switch($rows[G_Type1]) {
            case "Half" : $prefix = "(하프)"; break;
            case "Full" : $prefix = "(풀)"; break;
            case "Special" : $prefix = "(스)"; break;
          };


          switch($rows[CGL_ResultChoice]){
            case "Win"        : $cgl_result = "승"; $cgl_quota = $rows[CGL_QuotaWin]; break;
            case "Draw"       : $cgl_result = "무"; $cgl_quota = $rows[CGL_QuotaDraw]; break;
            case "Lose"       : $cgl_result = "패"; $cgl_quota = $rows[CGL_QuotaLose]; break;
            case "HandiWin"   : $cgl_result = "핸승"; $cgl_quota = $rows[CGL_QuotaHandiWin]; break;
            case "HandiLose"  : $cgl_result = "핸패"; $cgl_quota = $rows[CGL_QuotaHandiLose]; break;
            case "Under"      : $cgl_result = "언더"; $cgl_quota = $rows[CGL_QuotaUnder]; break;
            case "Over"       : $cgl_result = "오버"; $cgl_quota = $rows[CGL_QuotaOver]; break;
            case "Odd"        : $cgl_result = "홈"; $cgl_quota = $rows[CGL_QuotaOdd]; break;
            case "Even"       : $cgl_result = "원정"; $cgl_quota = $rows[CGL_QuotaEven]; break;
          };
         $BettingQuota *= $cgl_quota;
			
			$Cart_Html1 .= '
			
			<li>
				<dl>
					<dt>'.$rows['G_Team1'].'</dt>
					<dd><span>'.$prefix.'</span><a href="javascript:;" onclick="GameCartDelete('.$rows[CGL_Key].');"><img src="../images/icons/x-blue-icon.png" alt="베팅취소" /></a></dd>
					<dt>'.$rows['G_Team2'].'</dt>
					<dd><span>'.$cgl_result.'</span>'.$cgl_quota.'</dd>
					
				</dl>
			</li>
       ';
			
        	};//while ($rows = $result->FetchRow()) {
		
      };//if( $result ) {
      
      $BettingQuota = substr($BettingQuota,0,6);
	  $BettingQuota = sprintf('%.2f',$BettingQuota);
	  $price = ($price>0)?$price:5000;
	  $Cart_Html .= '
	  
	  <!-- 배당률 -->
        <div class="cart_cost">
            <div class="header">
				<span>게임선택</span>
				<p>배팅카트고정<input type="checkbox" id="gostop" onclick="cart_status(this);"  /></p>
			</div>
            <dl>
                <dt>예상배당률</dt>
                <dd id="BettingQuota">'.$BettingQuota.'</dd>
            </dl>
            <dl>
                <dt>예상배당금</dt>
                <dd id="BettingQuotaMoney">'.number_format((int)($price*$BettingQuota)).'</dd>
            </dl>
            <dl>
                <dt>배팅금액</dt>
                <dd><span style="float:right;"><a href="javascript:;"  onclick="GameCartAllDelete();">삭제</a></span><input name="BettingMoney" type="text" id="BettingMoney" style="width:80px; margin:0; float:right;" value="'.$price.'" onkeyup="javascript:is_onlynumeric( this.value, this );CalcCart(this.value);" /></dd>
            </dl>
        </div>
		<!-- 베팅선택게임 리스트 -->
        <div class="b_g_list">
            <ul>
';	
	  
	 
	
	$Cart_Html1 .= '</ul>
            <p class="btn_betting"><a href="javascript:;" OnClick="GameCartBuy('.$Cart_Cnt.');" class="ui-button_red">베팅하기</a></p>
        </div>               
    </div>                    
    <div class="clear"></div>    
</div>';


     
      	
     
      //<!--input name="cart" type="checkbox" id="cart" checked> 이동카트사용 -->

	  
	  return $Cart_Html.$Cart_Html1;
    }

    function Get_GameList_Option() {
      $opt = "";
      $result = $this->db->Execute("select * from gamelist a left join gameleague b on a.GL_Key=b.GL_Key left join gameitem c on b.GI_Key=c.GI_Key where a.G_State='Await' order by a.G_Datetime asc");
      if ($result) {
        while ($rows = $result->FetchRow()) {
          $game_time = date("[m/d H:i]", strtotime($rows[G_Datetime]));
          $opt .= "<option value='".$rows[G_Key]."'>".$game_time."/".$rows[GL_Type]."/".$rows[G_Team1]." vs ".$rows[G_Team2]."</option>";
        };
      };
      return $opt;
    }

    function Get_GameList_Latest( $g_type2, $cnt = 5 ) {
      $game_list = "";
      $result = $this->db->Execute("select * from gamelist a left join gameleague b on a.GL_Key=b.GL_Key left join gameitem c on b.GI_Key=c.GI_Key where a.G_Type2=? order by a.G_Datetime asc limit 0,?", array( $g_type2, $cnt ) );
      if ($result) {
        while ($rows = $result->FetchRow()) {
          $game_time = date("[m/d H:i]", strtotime($rows[G_Datetime]));
          $game_list .= "<li>".$game_time."/".$rows[GL_Type]."/".$rows[G_Team1]." vs ".$rows[G_Team2]."</li>";
        };
      };

      return $game_list;
    }

    function Get_GameItem_Option() {
      $opt = "";
      $result = $this->db->Execute("select * from gameitem");
      if ($result) {
        while ($rows = $result->FetchRow()) {
          $opt .= "<option value='".$rows[GI_Key]."'>".$rows[GI_Type]."</option>";
        };
      };
      return $opt;
    }

    function Get_GameItem_Option2($sel) {
      $opt = "";
      $result = $this->db->Execute("select * from gameitem");
      if ($result) {
        while ($rows = $result->FetchRow()) {
        	$s = '';
        	if($rows[GI_Key] == $sel) $s = ' selected';
          $opt .= "<option value='".$rows[GI_Key]."' $s>".$rows[GI_Type]."</option>";
        };
      };
      return $opt;
    }
    
    function Get_GameLeague_Option() {
      $opt = "";
      $result = $this->db->Execute("select * from gameleague a left join gameitem b on a.GI_Key=b.GI_Key WHERE GL_State='Normal' order by b.GI_Key asc");
      if ($result) {
        while ($rows = $result->FetchRow()) {
          $opt .= "<option value='".$rows[GL_Key]."'>[".$rows[GI_Type]."] ".$rows[GL_Type]."</option>";
        };
      };
      return $opt;
    }

    function Get_GameLeague_Option2() {
      $opt = "";
      $result = $this->db->Execute("select * from gameleague a left join gameitem b on a.GI_Key=b.GI_Key WHERE GL_State='Normal' order by b.GI_Type ASC,a.GL_Type ASC");
      if ($result) {
        while ($rows = $result->FetchRow()) {
          //$opt .= "<option value='".$rows[GL_Key]."'>".$rows[GL_Type]." [".$rows[GI_Type]."]</option>";
          $opt .= "<option value='".$rows[GL_Key]."'>[".$rows[GI_Type]."] ".$rows[GL_Type]."</option>";
        };
      };
      return $opt;
    }
    
    function Admin_Left( $file_name ) {
      switch ($file_name) {
      	case 'index_log.php' :
        case 'index.php' : 
		case 'index_rst.php' : 	return 'left_menu01.php';
          break;

        case 'money.php' :
        case 'trade_history.php' : return 'left_menu02.php';
          break;

        case 'game.php' :
        case 'item.php' :
        case 'league.php' : return 'left_menu03.php';
          break;

        case 'betting.php' :
        case 'betting_ord.php' :
		case 'betting_view.php' :
        case 'game_betting.php' : return 'left_menu04.php';
          break;

        case 'calculation.php' :
        case 'setting.php' :
        case 'point.php' :
        case 'ipban.php' :
        case 'live_tv.php' : return 'left_menu05.php';
          break;

        case 'board_list.php' :
        case 'board_modify.php' :
        case 'board_view.php' :
        case 'board_write.php' : return 'left_menu06.php';
          break;
      };
    }

    function Get_Request_Info( $m_id = "", $start_date = "", $end_date = "" ) {
      $request_money = null;

      if ( $m_id ) {
        if ( $start_date && $end_date )
        	$result = $this->db->Execute("select sum(a.R_Money) as Charge_Money from requests a left join members b on a.M_Key=b.M_Key where a.R_Type1='Charge' and a.R_State!='Cancel' and b.M_ID=? and R_RegDate between ? and ?", array( $m_id,$start_date,$end_date ) );
        else
        	$result = $this->db->Execute("select sum(a.R_Money) as Charge_Money from requests a left join members b on a.M_Key=b.M_Key where a.R_Type1='Charge' and a.R_State!='Cancel' and b.M_ID=?", array( $m_id ) );
        $row = $result->FetchRow();
        $request_money[Charge] = $row[Charge_Money];

        if ( $start_date && $end_date )
        	//$result = $this->db->Execute("select sum(a.R_Point) as Charge_Point from requests a left join members b on a.M_Key=b.M_Key where a.R_Type1='Charge' and a.R_State!='Cancel' and b.M_ID=? and R_RegDate between ? and ?", array( $m_id,$start_date,$end_date ) );
			$result = $this->db->Execute("select sum(a.PI_Point) as pointinfo from pointinfo a left join members b on a.M_Key=b.M_Key where a.PI_Type='PointConvert' and b.M_ID=? and PI_RegDate between ? and ?", array( $m_id,$start_date,$end_date ) );
        else
        	//$result = $this->db->Execute("select sum(a.R_Point) as Charge_Point from requests a left join members b on a.M_Key=b.M_Key where a.R_Type1='Charge' and a.R_State!='Cancel' and b.M_ID=?", array( $m_id ) );
			$result = $this->db->Execute("select sum(a.PI_Point) as Charge_Point from pointinfo a left join members b on a.M_Key=b.M_Key where a.PI_Type='PointConvert' and b.M_ID=?", array( $m_id ) );
        $row = $result->FetchRow();
        //$request_money[Charge] += $row[Charge_Point];
        $request_money[Charge_Point] = $row[Charge_Point];

        if ( $start_date && $end_date )
        	$result = $this->db->Execute("select sum(a.R_Money) as Refund_Money from requests a left join members b on a.M_Key=b.M_Key where a.R_Type1='Refund' and a.R_State!='Cancel' and b.M_ID=? and R_RegDate between ? and ?", array( $m_id,$start_date,$end_date ) );
        else
        	$result = $this->db->Execute("select sum(a.R_Money) as Refund_Money from requests a left join members b on a.M_Key=b.M_Key where a.R_Type1='Refund' and a.R_State!='Cancel' and b.M_ID=?", array( $m_id ) );
        $row = $result->FetchRow();
        $request_money[Refund] = $row[Refund_Money];

        if ( $start_date && $end_date ) $where = " and R_RegDate between '$start_date' and '$end_date'";
        else $where = "";
        
        $result = $this->db->Execute("select count(R_Key) as Charge_Count from requests a left join members b ON a.M_Key=b.M_Key where R_Type1='Charge' and R_State!='Cancel' AND b.M_ID='".$m_id."' ".$where);
        $row = $result->FetchRow();
        $request_money[Charge_Count] = $row[Charge_Count];

        $result = $this->db->Execute("select count(R_Key) as Refund_Count from requests a left join members b ON a.M_Key=b.M_Key where R_Type1='Refund' and R_State!='Cancel' AND b.M_ID='".$m_id."' ".$where);
        $row = $result->FetchRow();
        $request_money[Refund_Count] = $row[Refund_Count];
        
      } else {
        if ( $start_date && $end_date ) $where = " and R_RegDate between '$start_date' and '$end_date'";
        else $where = "";

        $result = $this->db->Execute("select sum(R_Money) as Charge_Money from requests where R_Type1='Charge' and R_State!='Cancel'".$where);
        $row = $result->FetchRow();
        $request_money[Charge] = $row[Charge_Money];

//echo $where;
        $result = $this->db->Execute("select sum(PI_Point) as Charge_Point from pointinfo where PI_Type='PointConvert' "."and PI_RegDate between '$start_date' and '$end_date'");
        $row = $result->FetchRow();
        //$request_money[Charge] += $row[Charge_Point];
        $request_money[Charge_Point] = $row[Charge_Point];

        $result = $this->db->Execute("select sum(R_Money) as Refund_Money from requests where R_Type1='Refund' and R_State!='Cancel'".$where);
        $row = $result->FetchRow();
        $request_money[Refund] = $row[Refund_Money];

        $result = $this->db->Execute("select count(R_Key) as Charge_Count from requests where R_Type1='Charge' and R_State!='Cancel'".$where);
        $row = $result->FetchRow();
        $request_money[Charge_Count] = $row[Charge_Count];

        $result = $this->db->Execute("select count(R_Key) as Refund_Count from requests where R_Type1='Refund' and R_State!='Cancel'".$where);
        $row = $result->FetchRow();
        $request_money[Refund_Count] = $row[Refund_Count];
      };

      return $request_money;
    }

      function Get_Request_Info2( $m_id = "", $start_date = "", $end_date = "" ) {
      $request_money = null;

      if ( $m_id ) {
        if ( $start_date && $end_date )
        	$result = $this->db->Execute("select sum(a.R_Money) as Charge_Money from requests a left join members b on a.M_Key=b.M_Key where a.R_Type1='Charge' and a.R_State!='Cancel' and b.M_BankOwner=? and R_RegDate between ? and ?", array( $m_id,$start_date,$end_date ) );
        else
        	$result = $this->db->Execute("select sum(a.R_Money) as Charge_Money from requests a left join members b on a.M_Key=b.M_Key where a.R_Type1='Charge' and a.R_State!='Cancel' and b.M_BankOwner=?", array( $m_id ) );
        $row = $result->FetchRow();
        $request_money[Charge] = $row[Charge_Money];

        if ( $start_date && $end_date )
        	$result = $this->db->Execute("select sum(a.R_Point) as Charge_Point from requests a left join members b on a.M_Key=b.M_Key where a.R_Type1='Charge' and a.R_State!='Cancel' and b.M_BankOwner=? and R_RegDate between ? and ?", array( $m_id,$start_date,$end_date ) );
        else
        	$result = $this->db->Execute("select sum(a.R_Point) as Charge_Point from requests a left join members b on a.M_Key=b.M_Key where a.R_Type1='Charge' and a.R_State!='Cancel' and b.M_BankOwner=?", array( $m_id ) );
        $row = $result->FetchRow();
        $request_money[Charge] += $row[Charge_Point];

        if ( $start_date && $end_date )
        	$result = $this->db->Execute("select sum(a.R_Money) as Refund_Money from requests a left join members b on a.M_Key=b.M_Key where a.R_Type1='Refund' and a.R_State!='Cancel' and b.M_BankOwner=? and R_RegDate between ? and ?", array( $m_id,$start_date,$end_date ) );
        else
        	$result = $this->db->Execute("select sum(a.R_Money) as Refund_Money from requests a left join members b on a.M_Key=b.M_Key where a.R_Type1='Refund' and a.R_State!='Cancel' and b.M_BankOwner=?", array( $m_id ) );
        $row = $result->FetchRow();
        $request_money[Refund] = $row[Refund_Money];

        if ( $start_date && $end_date ) $where = " and R_RegDate between '$start_date' and '$end_date'";
        else $where = "";
        
        $result = $this->db->Execute("select count(R_Key) as Charge_Count from requests a left join members b ON a.M_Key=b.M_Key where R_Type1='Charge' and R_State!='Cancel' AND b.M_BankOwner='".$m_id."' ".$where);
        $row = $result->FetchRow();
        $request_money[Charge_Count] = $row[Charge_Count];

        $result = $this->db->Execute("select count(R_Key) as Refund_Count from requests a left join members b ON a.M_Key=b.M_Key where R_Type1='Refund' and R_State!='Cancel' AND b.M_BankOwner='".$m_id."' ".$where);
        $row = $result->FetchRow();
        $request_money[Refund_Count] = $row[Refund_Count];
        
      } else {
        if ( $start_date && $end_date ) $where = " and R_RegDate between '$start_date' and '$end_date'";
        else $where = "";

        $result = $this->db->Execute("select sum(R_Money) as Charge_Money from requests where R_Type1='Charge' and R_State!='Cancel'".$where);
        $row = $result->FetchRow();
        $request_money[Charge] = $row[Charge_Money];

        $result = $this->db->Execute("select sum(R_Point) as Charge_Point from requests where R_Type1='Charge' and R_State!='Cancel'".$where);
        $row = $result->FetchRow();
        $request_money[Charge] += $row[Charge_Point];

        $result = $this->db->Execute("select sum(R_Money) as Refund_Money from requests where R_Type1='Refund' and R_State!='Cancel'".$where);
        $row = $result->FetchRow();
        $request_money[Refund] = $row[Refund_Money];

        $result = $this->db->Execute("select count(R_Key) as Charge_Count from requests where R_Type1='Charge' and R_State!='Cancel'".$where);
        $row = $result->FetchRow();
        $request_money[Charge_Count] = $row[Charge_Count];

        $result = $this->db->Execute("select count(R_Key) as Refund_Count from requests where R_Type1='Refund' and R_State!='Cancel'".$where);
        $row = $result->FetchRow();
        $request_money[Refund_Count] = $row[Refund_Count];
      };

      return $request_money;
    }    
    function Check_Board( $tn ) {
        $result = $this->db->Execute('select * from boardlist where BL_Name=?', array($tn));
        if ( $result->RecordCount() != 1 ) $this->lib->AlertBack( "존재하지 않는 게시판 입니다." );
        $row = $result->FetchRow();
        return $row[BL_Title];
    }

    function Check_Auth_Board( $b_key ) {
      if ( !is_numeric($b_key) ) $this->lib->AlertBack( "정상적인 접속이 아닙니다." );

      // 게시물 권한 체크
      $result = $this->db->Execute('select * from board where B_Delete=? and M_Key=? and B_Key=?' , array( 'N', $this->member_info[M_Key], $b_key ) );
      if ( $result->RecordCount() == 1 || $_SESSION[S_Admin] == 'Y' ) return "Y";
    }

    function Check_Auth_BoardReply( $br_key ) {
      if ( !is_numeric($br_key) ) $this->lib->AlertBack( "정상적인 접속이 아닙니다." );

      // 덧글 권한 체크
      $result = $this->db->Execute('select * from boardreply where BR_Delete=? and M_Key=? and BR_Key=?' , array( 'N', $this->member_info[M_Key], $br_key ) );
      if ( $result->RecordCount() == 1 || $_SESSION[S_Admin] == 'Y' ) return "Y";
    }

    function Get_Board_Notice( $tn ) {
      $result = $this->db->Execute("select * from board a left join members b on a.M_Key=b.M_Key where B_ID=? and B_Type != 'Normal' and a.B_Delete='N' order by B_Type desc", array( $tn ) );
      return $result;
    }
	
	function Get_Board_Notice_m( $tn ) {
      $result = $this->db->Execute("select * from Z_mugbang_board a left join members b on a.M_Key=b.M_Key where B_ID=? and B_Type != 'Normal' and a.B_Delete='N' order by B_Type desc", array( $tn ) );
      return $result;
    }
	
	

    function Get_Board_Notice_Latest( $cnt, $cut_str = 25, $url, $style_class = "" ) {
      $result = $this->db->Execute("select * from board where B_Type != 'Normal' and B_Delete='N' order by B_RegDate desc limit 0,?", array( $cnt ) );
      $board_list = "<ul>\n";
      if( $result ) {
        while ($rows = $result->FetchRow()) {
          $url = $url."?tn=".$rows[B_ID]."&b_key=".$rows[B_Key];
          $subject = $this->lib->Str_Cut( $rows[B_Subject], $cut_str, "..." );
          $board_list .= "<li><a href='".$url."'";
          if ( $style_class ) $board_list .= "class='".$style_class."'";
          $board_list .= ">".$subject."</a></li>\n";
        };
      };
      $board_list .= "</ul>\n";

      return $board_list;
    }

    function Get_Board_Latest( $b_id, $cnt, $cut_str = 25, $url, $style_class = "" ) {
      $result = $this->db->Execute("select * from board where B_Type = 'Normal' and B_ID=? and B_Delete='N' order by B_Key desc limit 0,?", array( $b_id, $cnt ) );
      $board_list = "<ul>\n";
      if( $result ) {
        while ($rows = $result->FetchRow()) {
          $url = $url."?tn=".$rows[B_ID]."&b_key=".$rows[B_Key];
          $subject = $this->lib->Str_Cut( $rows[B_Subject], $cut_str, "..." );
          $board_list .= "<li><a href='".$url."'";
          if ( $style_class ) $board_list .= "class='".$style_class."'";
          $board_list .= ">".$subject."</a></li>\n";;
        };
      };
      $board_list .= "</ul>\n";

      return $board_list;
    }

    function Get_Board_Latest_All( $b_id, $cnt, $cut_str = 25, $url, $style_class = "", $m_key = "" ) {
      if ( !$m_key ) $result = $this->db->Execute("select * from board where B_ID=? and B_Delete='N' order by B_Key desc limit 0,?", array( $b_id, $cnt ) );
      else $result = $this->db->Execute("select * from board where B_ID=? and B_Delete='N' and M_Key=? order by B_Key desc limit 0,?", array( $b_id, $m_key, $cnt ) );
      $board_list = "<ul>\n";
      if( $result ) {
        while ($rows = $result->FetchRow()) {
          $url = $url."?tn=".$rows[B_ID]."&b_key=".$rows[B_Key];
          $subject = $this->lib->Str_Cut( $rows[B_Subject], $cut_str, "..." );
          $board_list .= "<li><a href='".$url."'";
          if ( $style_class ) $board_list .= "class='".$style_class."'";
          $board_list .= ">".$subject."</a></li>\n";;
        };
      };
      $board_list .= "</ul>\n";

      return $board_list;
    }

    function Get_Board_Read( $b_key ) {
      if ( !is_numeric($b_key) ) $this->lib->AlertBack( "정상적인 접속이 아닙니다." );

      // 게시물 데이터 가져오기
      $result = $this->db->Execute('select * from board a left join members b on a.M_Key=b.M_Key where a.B_Delete=? and a.B_Key=?', array('N', $b_key));
      if ( $result->RecordCount() != 1 ) $this->lib->AlertBack( "존재하지 않는 게시물 입니다." );
      $row = $result->FetchRow();

      // 게시물 조회수 증가
      $record = null;
      $record['B_Count'] = $row['B_Count'] + 1;
      $record['B_ReadDate'] = date("Y-m-d H:i:s");
      if ( $_SESSION['S_Admin'] == 'Y' && $row['B_AdminRead'] == 'N' ) $record['B_AdminRead'] = 'Y';
      $where = "B_Key=". $b_key;
      $this->db->AutoExecute("board", $record, 'UPDATE', $where);

      return $row;
    }
	
	function Get_Board_Read_m( $b_key ) {
      if ( !is_numeric($b_key) ) $this->lib->AlertBack( "정상적인 접속이 아닙니다." );

      // 게시물 데이터 가져오기
      $result = $this->db->Execute('select * from Z_mugbang_board a left join members b on a.M_Key=b.M_Key where a.B_Delete=? and a.B_Key=?', array('N', $b_key));
      if ( $result->RecordCount() != 1 ) $this->lib->AlertBack( "존재하지 않는 게시물 입니다." );
      $row = $result->FetchRow();

      // 게시물 조회수 증가
      $record = null;
      $record[B_Count] = $row[B_Count] + 1;
      if ( $_SESSION[S_Admin] == 'Y' && $row[B_AdminRead] == 'N' ) $record[B_AdminRead] = 'Y';
      $where = "B_Key=". $b_key;
      $this->db->AutoExecute("board", $record, 'UPDATE', $where);

      return $row;
    }

    function Get_Board_Reply( $b_key ) {
      if ( !is_numeric($b_key) ) $this->lib->AlertBack( "정상적인 접속이 아닙니다." );

      $result = $this->db->Execute('select * from boardreply a left join members b on a.M_Key=b.M_Key where a.BR_Delete=? and a.B_Key=?', array('N', $b_key));
      if ( $result->RecordCount() > 0 ) return $result;
    }


    function Member_Info( $m_key , $rt = 'N') {
      $result = $this->db->Execute('select * from members where M_Key=?', array( $m_key) );
      if ( $rt == 'N' ) {
        $this->member_info = $result->FetchRow();
        $this->member_info['M_CP_Arr'] = explode("-", $this->member_info['M_CP']);
      } else {
        return $result->FetchRow();
      };
    }
	#$lib24c->Payment_Money( $mrow[M_Key], "Charge", $_POST[HAF_Value_2], "", "", $row[R_Key], "" );
	#회원번호, Await, 
    function Payment_Money( $m_key, $money_type, $money = 0 , $pi_key = "", $bg_key = "", $r_key = "" , $msg = "" ) {

      if ( !is_numeric( $money ) && !$m_key ) $this->lib->AlertMSG("머니 입출금에 오류가 있어 처리되지 않았습니다.");
      switch( $money_type ) {
        case 'Charge'       : $msg = "머니충전.";
            if ( $money == 0 || !$r_key ) $this->lib->AlertMSG("머니 입출금에 오류가 있어 처리되지 않았습니다.");
         break;
        case 'RefundAwait'       : $msg = "머니환전요청";
            if ( $money == 0 || !$r_key ) $this->lib->AlertMSG("머니 입출금에 오류가 있어 처리되지 않았습니다.");
            $money = $money * -1;
        case 'Refund'       : $msg = "머니환전완료";
          if ( $money == 0 || !$r_key ) $this->lib->AlertMSG("머니 입출금에 오류가 있어 처리되지 않았습니다.");
          $money = $money * -1;
        break;
		case 'Await'       : $msg = "대기전환.";
            if ( $money == 0 || !$r_key ) $this->lib->AlertMSG("머니 입출금에 오류가 있어 처리되지 않았습니다.");
            $money = $money * -1;
        break;
        case 'PointConvert' : $msg = "포인트전환.";
            if ( $money == 0 || !$r_key  || !$pi_key ) $this->lib->AlertMSG("머니 입출금에 오류가 있어 처리되지 않았습니다.");
        break;
        case 'GameBetting'  : 
            if ( $money == 0 || !$bg_key ) $this->lib->AlertMSG("머니 입출금에 오류가 있어 처리되지 않았습니다.[GameBetting]");
			if($money > 0) {
				$msg = "게임을 구매하였습니다.";
			} else {
				$msg = "배팅취소환불.";
			}
 			$money = $money * -1;
        break;
        case 'GameCancel'   : $msg = "경기 취소로 인해 환불 되었습니다.";
            if ( $money == 0 || !$bg_key ) $this->lib->AlertMSG("머니 입출금에 오류가 있어 처리되지 않았습니다.");
        break;
        case 'Quota'        : $msg = "배팅 적중 배당금.";
            if ( $money == 0 || !$bg_key ) $this->lib->AlertMSG("머니 입출금에 오류가 있어 처리되지 않았습니다.");
        break;
        case 'Other'        :
            if ( $money == 0 || !$msg ) $this->lib->AlertMSG("머니 입출금에 오류가 있어 처리되지 않았습니다.");
        break;
        default : $this->lib->AlertMSG("머니 입출금에 오류가 있어 처리되지 않았습니다.");
      };
      
      //회원정보 가져오기
 	  $this->member_info($m_key);

      $prev_money = $this->member_info['M_Money'];

      $cur_money = $this->member_info['M_Money'] + $money;
      $record = null;
      $record['M_Money'] = $cur_money;

      if($money_type == 'Refund') {
        $record['M_Refund_Money'] = $this->member_info['M_Refund_Money'] + abs($money);
      }
      $where = "M_Key = ".$m_key;
      $this->db->AutoExecute("members", $record, 'UPDATE', $where);

      if($money_type != 'RefundAwait') {//머니 환전 요청일 경우는 머니로그에 기록을 하지 않고 회원 머니만 차감한다.
        // 머니 로그 기록
        $record = null;
        $record['M_Key'] = $m_key;
        $record['MI_Type'] = $money_type;
        $record['PI_Key'] = $pi_key;
        $record['BG_Key'] = $bg_key;
        $record['R_Key'] = $r_key;
        $record['MI_Money'] = $money;
        $record['MI_Prev_Money'] = $prev_money;
        $record['MI_Cur_Money'] = $cur_money;
        $record['MI_Memo'] = $msg;

        $record['MI_RegDate'] = date("Y-m-d H:i:s");
        //$this->db->AutoExecute("moneyinfo",$record,'INSERT');
        $this->db->AutoExecute("moneyinfo", $record, 'INSERT');
      }

      return $m_key;
    }

	function level_return($l_key){
		$result = $this->db->Execute("select M_level from member where level='".$this->member_info[M_Level]."'");
        $this->point_info = $result->FetchRow();
	}

	function point_return($p_key, $p_field){
      $this->member_info($p_key);
		$result = $this->db->Execute("select * from pointconfig where level='".$this->member_info[M_Level]."'");
        $tmp = $result->FetchRow();
		return $tmp[$p_field];
	}

    function Payment_Point( $m_key, $point_type, $point = 0 , $msg = "" ) {

	//echo $point,"->".$m_key;
      if ( !is_numeric( $point ) && !$m_key ) $this->lib->AlertMSG("포인트 지급에 오류가 있어 처리되지 않았습니다.");

      switch ( $point_type ) {
        case 'Join'         : $msg = "회원 가입 축하 포인트 지급"; $point = $this->point_return($m_key, "MemberJoin"); break;
        case 'RecJoin'      : $msg = "회원 추천 포인트 지급"; $point = $this->point_return($m_key, "RecJoin"); break;
        case 'Betting'      : 
			if($point > 0) $msg = "배팅 환급 포인트 지급";
			else $msg = "배팅 취소로인한 환급 포인트 회수";
            //if ( $point == 0 ) $this->lib->AlertMSG("포인트 지급에 오류가 있어 처리되지 않았습니다.");
          break;
        case 'BettingFail'      : $msg = "배팅 실패 환급 포인트 지급 - ".$msg;
            //if ( $point == 0 ) $this->lib->AlertMSG("포인트 지급에 오류가 있어 처리되지 않았습니다.");
          break;
        case 'RecBetting'   : $msg = "추천인 배팅 실패 포인트 - ".$msg; $point = $this->point_return($m_key, "RecBetting"); break;
            //if ( $point == 0 ) $this->lib->AlertMSG("포인트 지급에 오류가 있어 처리되지 않았습니다.");
          break;
        case 'BoardWrite'   : $msg = "글작성 포인트 지급"; $point = $this->point_return($m_key, "BoardWrite"); break;
        case 'BoardDelete'  : $msg = "글삭제 포인트 차감"; $point = $this->point_return($m_key, "BoardDelete"); break;
        case 'Charge'       : $msg = "머니 충전 포인트 지급";
            //if ( $point == 0 ) $this->lib->AlertMSG("포인트 지급에 오류가 있어 처리되지 않았습니다.");
          break;
        case 'PointConvert' : $msg = "포인트 -> 머니 변경 포인트 차감";
            //if ( $point == 0 ) $this->lib->AlertMSG("포인트 지급에 오류가 있어 처리되지 않았습니다.");
          break;
        case 'Reply'        : $msg = "댓글 포인트 지급"; $point = $this->point_return($m_key, "reply"); break;
        case 'ReplyDelete'        : $msg = "댓글 포인트 삭제"; $point = $this->point_return($m_key, "replyd"); break;
		case 'Other'        :
            //if ( $point == 0 || !$msg ) $this->lib->AlertMSG("포인트 입출금에 오류가 있어 처리되지 않았습니다.");
          break;
        default : $this->lib->AlertMSG("포인트 지급에 오류가 있어 처리되지 않았습니다."); break;
      };
      // 포인트 로그 기록
      $p_key = 0;
      if($point != "" && $msg != "")
      {
      		$this->member_info($m_key);      	
      		
      		//이미 실패로 등록한게 있나 체크
      	  	if($point_type == 'BettingFail' || $point_type == 'RecBetting' )
      	  	{
      	  		$res_tmp = $this->db->execute("SELECT * FROM pointinfo WHERE PI_MEMO='$msg'");
      	  		if($res_tmp->RecordCount() > 0)
      	  			return 0;
      	  	}
      	  	
			$result = $this->db->Execute("select * from members where M_Key='".$m_key."'");
       		$row = $result->FetchRow();
			
			#보유 포인트가 차감포인트 보다 많을때만 실행한다.. 마이너스 차감 안되게
			
			
				$sql  = "INSERT INTO pointinfo SET ";
				$sql .= "M_Key = {$m_key}, ";
				$sql .= "PI_Type = '{$point_type}',";
				$sql .= "PI_Memo = '{$msg}',";			
				$sql .= "PI_Point = {$point},";
				$sql .= "PI_RegDate = NOW()";
				
				//echo $sql;
				
				
				setQry($sql);
				
				$p_key = mysql_insert_id();
				/*$record = null;
				$record[M_Key]      = $m_key;
				$record[PI_Type]    = $point_type;
				$record[PI_Point]   = $point;
				$record[PI_Memo]    = $msg;
				$record[PI_RegDate] = date("Y-m-d H:i:s");
				$this->db->AutoExecute("pointinfo",$record,'INSERT');
				$p_key = $this->db->Insert_ID();*/
	
			  // 포인트 지급 처리
			  //if ( !$this->member_info ) $this->member_info($m_key);
			  $this->member_info($m_key);
	
			  $record = null;
	
			
	
			  $record[M_Point] = $row[M_Point] + $point;
			  $where = "M_Key = ".$m_key;
			  $this->db->AutoExecute("members", $record, 'UPDATE', $where);
			
      }

      return $p_key;
    }
	

    function GetNewMemo($mkey) {
    	$result = $this->db->Execute("select count(*) as cnt from message where M_Key='{$mkey}' AND readDate = '0000-00-00 00:00:00'");
        $row = $result->FetchRow();
        return $row['cnt'];
    }

    function GetAllMemo($m_id) {
    	$result = $this->db->Execute("select count(*) as cnt from memolist where R_ID='$m_id'");
        $row = $result->FetchRow();
        return $row[cnt];
    }

    function GetSendMemo($m_id) {
    	$result = $this->db->Execute("select count(*) as cnt from memolist where S_ID='$m_id'");
        $row = $result->FetchRow();
        return $row[cnt];
    }

    function SendMemo($S_ID,$R_ID,$M_CONTENTS)
    {
    	$result = $this->db->Execute("select count(*) as cnt from members where M_ID='$R_ID'");
        $row = $result->FetchRow();
        if($row[cnt] == 0)  return;

    	$SQL = "
			INSERT INTO
			  `memolist`
			(
			  `S_ID`,
			  `R_ID`,
			  `M_CONTENTS`,
			  `M_READ`,
			  `M_REGDATE`
			)
			VALUE (
			  '$S_ID',
			  '$R_ID',
			  '$M_CONTENTS',
			  0,
			  NOW()
			)
		";

		$this->db->Execute($SQL);
    }

    function DeleteMemo($seq,$M_ID)
    {
    	$this->db->Execute("delete from memolist WHERE seq=? AND (R_ID='$M_ID' OR S_ID='$M_ID')", $seq);
    }

    function Get_Memo_List( $R_ID,$mode="SEND" ) {
    	if($mode == "SEND")
      		$result = $this->db->Execute("select * from memolist WHERE S_ID=? ORDER BY M_REGDATE DESC", $R_ID );
      	else
      		$result = $this->db->Execute("select * from memolist WHERE R_ID=? ORDER BY M_REGDATE DESC", $R_ID );
      	return $result;
    }

    function GET_Memo($M_ID,$seq)
    {
    	$result = $this->db->Execute("select * from memolist WHERE seq=? AND (R_ID='$M_ID' OR S_ID='$M_ID')", $seq);
        if ( $result->RecordCount() == 0 ) $this->lib->AlertBack( "정상적인 접속이 아닙니다." );
        $row = $result->FetchRow();

        //만약 읽은 유져와 받는 유져가 동일하고 READ 가 0 이면 업데이트
        if($row[M_READ] == 0 && $row[R_ID] == $M_ID)
        {
        	$record = null;
        	$record[M_READ] = 1; $record[M_READDATE] = date("Y-m-d H:i:s");
        	$where = "seq = ".$seq;
        	$this->db->AutoExecute("memolist", $record, 'UPDATE', $where);
        }

        return $row;
    }

    function UpdateMemberInfo($data, $m_key)
    {
    	$where = "M_Key = ".$m_key;
    	$this->db->AutoExecute("members", $data, 'UPDATE', $where);
    }
    
    function GameResult($G_Key,$G_Home,$G_Away)
    {
        // 게임 결과 체크
        
        $result = $this->db->Execute('select * from gamelist where G_Key=?', array($G_Key));
        $rows = $result->FetchRow();
    	
        // 1. 승무패
        $wdl = "";
        $wdl2= "";
        if ( $rows[G_QuotaWin] && $rows[G_QuotaLose] ) {
          if ( $G_Home == $G_Away )
          {
          	$wdl = "Draw";
          	if((int)$rows[G_QuotaDraw] == 1)
          	{
          		$wdl2 = "Cancel";
          	}
          }
          else if ( $G_Home > $G_Away ) $wdl = "Win";
          else $wdl = "Lose";
        };

        // 2. 핸디캡
        $handicap = "";
        if ( $rows[G_QuotaHandicap] && $rows[G_QuotaHandiWin] && $rows[G_QuotaHandiLose] ) {
          if ( $G_Home + $rows[G_QuotaHandicap] > $G_Away ) $handicap = "HandiWin";
          else if ( $G_Home + $rows[G_QuotaHandicap] < $G_Away ) $handicap = "HandiLose";
          else $handicap = "Cancel";
        };

        // 3. 언더/ 오버
        $underover = "";
        if ( $rows[G_QuotaUnderOver] ) {
          $cval = $G_Home + $G_Away;
          if($cval > $rows[G_QuotaUnderOver])
          	$underover = "Over";
          elseif($cval < $rows[G_QuotaUnderOver])
          	$underover = "Under";
          else
          	$underover = "Cancel";
        };

        // 4. 홀짝
        $oddeven = "";
        if ( $rows[G_QuotaOdd] && $rows[G_QuotaEven] ) {
          if ( $G_Home > $G_Away ) $oddeven = "Odd";
          else $oddeven = "Even";
        };
        
		
        // 언더오버 적중특례 계산
        if($underover == "Cancel")
        {
        	$result = $this->db->Execute("select * from buygamelist where G_Key=?", array( $G_Key ) );
	        $update_buygame_success = "";
	        $update_buygame_fail    = "";
	        $update_bg_key          = "";
	        $update_bg_key_by_fail  = "";
	        if ( $result ) {
	          while ($rows = $result->FetchRow()) {
	            $update_buygame_success .= $rows[BGL_Key].", ";
	            $update_bg_key .= $rows[BG_Key].", ";
	          };
	        };
	
	        $update_buygame_success = trim(substr( $update_buygame_success, 0, -2 ));
	        $update_bg_key          = trim(substr( $update_bg_key, 0, -2 ));
	        $update_bg_key_by_fail  = trim(substr( $update_bg_key_by_fail, 0, -2 ));
	        
	        // 적중특례 게임 업데이트 및 배당률 변경
	        if ( $update_buygame_success != "" ) {
	          $record = null;
	          $record[BGL_QuotaWin]       = "1.00";
	          $record[BGL_QuotaDraw]      = "1.00";
	          $record[BGL_QuotaLose]      = "1.00";
	          $record[BGL_QuotaHandicap]  = "";
	          $record[BGL_QuotaHandiWin]  = "";
	          $record[BGL_QuotaHandiLose] = "";
	          $record[BGL_QuotaUnderOver] = "";
	          $record[BGL_QuotaUnder]     = "";
	          $record[BGL_QuotaOver]      = "";
	          $record[BGL_QuotaOdd]       = "";
	          $record[BGL_ResultChoice]	= 'Draw';
	          $record[BGL_State]          = 'Cancel';
	          $where = "BGL_Key in (".$update_buygame_success.")";
	          $this->db->AutoExecute("buygamelist",$record,'UPDATE', $where);
	        };

	        if ( $update_bg_key != "" ) {
	          // 적중특례 게임 전체 배당률 변경 및 게임 결과 카운트 업데이트
	          $tmp_key = explode(",", $update_bg_key);
	          for( $i=0 ; $i < count($tmp_key) ; $i++ ) {
	            $result = $this->db->Execute("select * from buygamelist where BG_Key=?", array( trim($tmp_key[$i]) ) );
	            $quota = 1;
	            while( $bgl_row = $result->FetchRow() ) {
	              $quota *= $bgl_row["BGL_Quota".$bgl_row[BGL_ResultChoice]];
	            };
	
	            $result = $this->db->Execute("select * from buygame where BG_Key=?", array( trim($tmp_key[$i]) ) );
	            $bg_row = $result->FetchRow();
	
	            $record = null;
	            $record[BG_TotalQuota]        = $quota;
	            $BettingPrice = $quota * $bg_row[BG_BettingPrice];	//최종 정산 값
	            $record[BG_ForecastPrice]     = $BettingPrice;
	            $where = "BG_Key=".trim($tmp_key[$i]);
	            $this->db->AutoExecute("buygame", $record, "UPDATE", $where);
	          };
	        };
        }
        //언더오버 적중 특례 계산 끝
        
        //핸디캡 적중 특례 계산
        if($handicap == "Cancel")
        {
        	$result = $this->db->Execute("select * from buygamelist where G_Key=?", array( $G_Key ) );
	        $update_buygame_success = "";
	        $update_buygame_fail    = "";
	        $update_bg_key          = "";
	        $update_bg_key_by_fail  = "";
	        if ( $result ) {
	          while ($rows = $result->FetchRow()) {
	            $update_buygame_success .= $rows[BGL_Key].", ";
	            $update_bg_key .= $rows[BG_Key].", ";
	          };
	        };
	
	        $update_buygame_success = trim(substr( $update_buygame_success, 0, -2 ));
	        $update_bg_key          = trim(substr( $update_bg_key, 0, -2 ));
	        $update_bg_key_by_fail  = trim(substr( $update_bg_key_by_fail, 0, -2 ));
	        
	        // 적중특례 게임 업데이트 및 배당률 변경
	        if ( $update_buygame_success != "" ) {
	          $record = null;
	          $record[BGL_QuotaWin]       = "1.00";
	          $record[BGL_QuotaDraw]      = "1.00";
	          $record[BGL_QuotaLose]      = "1.00";
	          $record[BGL_QuotaHandicap]  = "";
	          $record[BGL_QuotaHandiWin]  = "";
	          $record[BGL_QuotaHandiLose] = "";
	          $record[BGL_QuotaUnderOver] = "";
	          $record[BGL_QuotaUnder]     = "";
	          $record[BGL_QuotaOver]      = "";
	          $record[BGL_QuotaOdd]       = "";
	          $record[BGL_ResultChoice]	= 'Draw';
	          $record[BGL_State]          = 'Cancel';
	          $where = "BGL_Key in (".$update_buygame_success.")";
	          $this->db->AutoExecute("buygamelist",$record,'UPDATE', $where);
	        };

	        if ( $update_bg_key != "" ) {
	          // 적중특례 게임 전체 배당률 변경 및 게임 결과 카운트 업데이트
	          $tmp_key = explode(",", $update_bg_key);
	          for( $i=0 ; $i < count($tmp_key) ; $i++ ) {
	            $result = $this->db->Execute("select * from buygamelist where BG_Key=?", array( trim($tmp_key[$i]) ) );
	            $quota = 1;
	            while( $bgl_row = $result->FetchRow() ) {
	              $quota *= $bgl_row["BGL_Quota".$bgl_row[BGL_ResultChoice]];
	            };
	
	            $result = $this->db->Execute("select * from buygame where BG_Key=?", array( trim($tmp_key[$i]) ) );
	            $bg_row = $result->FetchRow();
	
	            $record = null;
	            $record[BG_TotalQuota]        = $quota;
	            $BettingPrice = $quota * $bg_row[BG_BettingPrice];	//최종 정산 값
	            $record[BG_ForecastPrice]     = $BettingPrice;
	            $where = "BG_Key=".trim($tmp_key[$i]);
	            $this->db->AutoExecute("buygame", $record, "UPDATE", $where);
	          };
	        };
        }
        //핸디캡 적중 특례 계산 끝

        //승무패 야구 적중 특례
        if($wdl2 == "Cancel")
        {
        	$result = $this->db->Execute("select * from buygamelist where G_Key=?", array( $G_Key ) );
	        $update_buygame_success = "";
	        $update_buygame_fail    = "";
	        $update_bg_key          = "";
	        $update_bg_key_by_fail  = "";
	        if ( $result ) {
	          while ($rows = $result->FetchRow()) {
	            $update_buygame_success .= $rows[BGL_Key].", ";
	            $update_bg_key .= $rows[BG_Key].", ";
	          };
	        };
	
	        $update_buygame_success = trim(substr( $update_buygame_success, 0, -2 ));
	        $update_bg_key          = trim(substr( $update_bg_key, 0, -2 ));
	        $update_bg_key_by_fail  = trim(substr( $update_bg_key_by_fail, 0, -2 ));
	        
	        // 적중특례 게임 업데이트 및 배당률 변경
	        if ( $update_buygame_success != "" ) {
	          $record = null;
	          $record[BGL_QuotaWin]       = "1.00";
	          $record[BGL_QuotaDraw]      = "1.00";
	          $record[BGL_QuotaLose]      = "1.00";
	          $record[BGL_QuotaHandicap]  = "";
	          $record[BGL_QuotaHandiWin]  = "";
	          $record[BGL_QuotaHandiLose] = "";
	          $record[BGL_QuotaUnderOver] = "";
	          $record[BGL_QuotaUnder]     = "";
	          $record[BGL_QuotaOver]      = "";
	          $record[BGL_QuotaOdd]       = "";
	          $record[BGL_ResultChoice]	= 'Draw';
	          $record[BGL_State]          = 'Cancel';
	          $where = "BGL_Key in (".$update_buygame_success.")";
	          $this->db->AutoExecute("buygamelist",$record,'UPDATE', $where);
	        };

	        if ( $update_bg_key != "" ) {
	          // 적중특례 게임 전체 배당률 변경 및 게임 결과 카운트 업데이트
	          $tmp_key = explode(",", $update_bg_key);
	          for( $i=0 ; $i < count($tmp_key) ; $i++ ) {
	            $result = $this->db->Execute("select * from buygamelist where BG_Key=?", array( trim($tmp_key[$i]) ) );
	            $quota = 1;
	            while( $bgl_row = $result->FetchRow() ) {
	              $quota *= $bgl_row["BGL_Quota".$bgl_row[BGL_ResultChoice]];
	            };
	
	            $result = $this->db->Execute("select * from buygame where BG_Key=?", array( trim($tmp_key[$i]) ) );
	            $bg_row = $result->FetchRow();
	
	            $record = null;
	            $record[BG_TotalQuota]        = $quota;
	            $BettingPrice = $quota * $bg_row[BG_BettingPrice];	//최종 정산 값
	            $record[BG_ForecastPrice]     = $BettingPrice;
	            $where = "BG_Key=".trim($tmp_key[$i]);
	            $this->db->AutoExecute("buygame", $record, "UPDATE", $where);
	          };
	        };
        }
        //승무패 적중 특례 계산 끝

        // 게임 결과 등록/수정
        $record = Null;
        $record[G_ResultWDL]        = $wdl;
        $record[G_ResultHandicap]   = $handicap;
        $record[G_ResultUnderOver]  = $underover;
        $record[G_ResultOddEven]    = $oddeven;
        $record[G_ResultScoreWin]   = $G_Home;
        $record[G_ResultScoreLose]  = $G_Away;
        $record[G_State]  = "Stop";
        $where = "G_Key=".$G_Key;
        $this->db->AutoExecute("gamelist",$record,'UPDATE', $where);
    	
    }
	
	
	
	
	
	
			     #############################################################################################################
  ####################   적중특례 적용
  #############################################################################################################
  function refund($G_Key,$gubun)
    {
        // 게임 결과 체크       
        $result = $this->db->Execute('select * from gamelist where G_Key=?', array($G_Key));
        $rows = $result->FetchRow();        
        
		
        // 언더오버 적중특례 계산
        if($gubun == "UnderOver")
        {
        	$result = $this->db->Execute("select * from buygamelist where G_Key=?", array( $G_Key ) );
	        $update_buygame_success = "";
	        $update_buygame_fail    = "";
	        $update_bg_key          = "";
	        $update_bg_key_by_fail  = "";
	        if ( $result ) {
	          while ($rows = $result->FetchRow()) {
	            $update_buygame_success .= $rows[BGL_Key].", ";
	            $update_bg_key .= $rows[BG_Key].", ";
	          };
	        };
	
	        $update_buygame_success = trim(substr( $update_buygame_success, 0, -2 ));
	        $update_bg_key          = trim(substr( $update_bg_key, 0, -2 ));
	        $update_bg_key_by_fail  = trim(substr( $update_bg_key_by_fail, 0, -2 ));
	        
	        // 적중특례 게임 업데이트 및 배당률 변경
	        if ( $update_buygame_success != "" ) {
	          $record = null;
	          $record[BGL_QuotaWin]       = "1.00";
	          $record[BGL_QuotaDraw]      = "1.00";
	          $record[BGL_QuotaLose]      = "1.00";
	          $record[BGL_QuotaHandicap]  = "";
	          $record[BGL_QuotaHandiWin]  = "";
	          $record[BGL_QuotaHandiLose] = "";
	          $record[BGL_QuotaUnderOver] = "";
	          $record[BGL_QuotaUnder]     = "";
	          $record[BGL_QuotaOver]      = "";
	          $record[BGL_QuotaOdd]       = "";
	          $record[BGL_ResultChoice]	= 'Draw';
	          $record[BGL_State]          = 'Cancel';
	          $where = "BGL_Key in (".$update_buygame_success.")";
	          $this->db->AutoExecute("buygamelist",$record,'UPDATE', $where);
	        };

	        if ( $update_bg_key != "" ) {
	          // 적중특례 게임 전체 배당률 변경 및 게임 결과 카운트 업데이트
	          $tmp_key = explode(",", $update_bg_key);
	          for( $i=0 ; $i < count($tmp_key) ; $i++ ) {
	            $result = $this->db->Execute("select * from buygamelist where BG_Key=?", array( trim($tmp_key[$i]) ) );
	            $quota = 1;
	            while( $bgl_row = $result->FetchRow() ) {
	              $quota *= $bgl_row["BGL_Quota".$bgl_row[BGL_ResultChoice]];
	            };
	
	            $result = $this->db->Execute("select * from buygame where BG_Key=?", array( trim($tmp_key[$i]) ) );
	            $bg_row = $result->FetchRow();
	
	            $record = null;
	            $record[BG_TotalQuota]        = $quota;
	            $BettingPrice = $quota * $bg_row[BG_BettingPrice];	//최종 정산 값
	            $record[BG_ForecastPrice]     = $BettingPrice;

	            $where = "BG_Key=".trim($tmp_key[$i]);
	            $this->db->AutoExecute("buygame", $record, "UPDATE", $where);
	          };
	        };
        }
        //언더오버 적중 특례 계산 끝
        
        //핸디캡 적중 특례 계산
        if($gubun == "Handicap")
        {
        	$result = $this->db->Execute("select * from buygamelist where G_Key=?", array( $G_Key ) );
	        $update_buygame_success = "";
	        $update_buygame_fail    = "";
	        $update_bg_key          = "";
	        $update_bg_key_by_fail  = "";
	        if ( $result ) {
	          while ($rows = $result->FetchRow()) {
	            $update_buygame_success .= $rows[BGL_Key].", ";
	            $update_bg_key .= $rows[BG_Key].", ";
	          };
	        };
	
	        $update_buygame_success = trim(substr( $update_buygame_success, 0, -2 ));
	        $update_bg_key          = trim(substr( $update_bg_key, 0, -2 ));
	        $update_bg_key_by_fail  = trim(substr( $update_bg_key_by_fail, 0, -2 ));
	        
	        // 적중특례 게임 업데이트 및 배당률 변경
	        if ( $update_buygame_success != "" ) {
	          $record = null;
	          $record[BGL_QuotaWin]       = "1.00";
	          $record[BGL_QuotaDraw]      = "1.00";
	          $record[BGL_QuotaLose]      = "1.00";
	          $record[BGL_QuotaHandicap]  = "";
	          $record[BGL_QuotaHandiWin]  = "";
	          $record[BGL_QuotaHandiLose] = "";
	          $record[BGL_QuotaUnderOver] = "";
	          $record[BGL_QuotaUnder]     = "";
	          $record[BGL_QuotaOver]      = "";
	          $record[BGL_QuotaOdd]       = "";
	          $record[BGL_ResultChoice]	= 'Draw';
	          $record[BGL_State]          = 'Cancel';
	          $where = "BGL_Key in (".$update_buygame_success.")";
	          $this->db->AutoExecute("buygamelist",$record,'UPDATE', $where);
	        };

	        if ( $update_bg_key != "" ) {
	          // 적중특례 게임 전체 배당률 변경 및 게임 결과 카운트 업데이트
	          $tmp_key = explode(",", $update_bg_key);
	          for( $i=0 ; $i < count($tmp_key) ; $i++ ) {
	            $result = $this->db->Execute("select * from buygamelist where BG_Key=?", array( trim($tmp_key[$i]) ) );
	            $quota = 1;
	            while( $bgl_row = $result->FetchRow() ) {
	              $quota *= $bgl_row["BGL_Quota".$bgl_row[BGL_ResultChoice]];
	            };
	
	            $result = $this->db->Execute("select * from buygame where BG_Key=?", array( trim($tmp_key[$i]) ) );
	            $bg_row = $result->FetchRow();
	
	            $record = null;
	            $record[BG_TotalQuota]        = $quota;
	            $BettingPrice = $quota * $bg_row[BG_BettingPrice];	//최종 정산 값
	            $record[BG_ForecastPrice]     = $BettingPrice;
	            $where = "BG_Key=".trim($tmp_key[$i]);
	            $this->db->AutoExecute("buygame", $record, "UPDATE", $where);
	          };
	        };
        }
        //핸디캡 적중 특례 계산 끝

        //승무패 야구 적중 특례
        if($gubun == "WDL")
        {
        	$result = $this->db->Execute("select * from buygamelist where G_Key=?", array( $G_Key ) );
	        $update_buygame_success = "";
	        $update_buygame_fail    = "";
	        $update_bg_key          = "";
	        $update_bg_key_by_fail  = "";
	        if ( $result ) {
	          while ($rows = $result->FetchRow()) {
	            $update_buygame_success .= $rows[BGL_Key].", ";
	            $update_bg_key .= $rows[BG_Key].", ";
	          };
	        };
	
	        $update_buygame_success = trim(substr( $update_buygame_success, 0, -2 ));
	        $update_bg_key          = trim(substr( $update_bg_key, 0, -2 ));
	        $update_bg_key_by_fail  = trim(substr( $update_bg_key_by_fail, 0, -2 ));
	        
	        // 적중특례 게임 업데이트 및 배당률 변경
	        if ( $update_buygame_success != "" ) {
	          $record = null;
	          $record[BGL_QuotaWin]       = "1.00";
	          $record[BGL_QuotaDraw]      = "1.00";
	          $record[BGL_QuotaLose]      = "1.00";
	          $record[BGL_QuotaHandicap]  = "";
	          $record[BGL_QuotaHandiWin]  = "";
	          $record[BGL_QuotaHandiLose] = "";
	          $record[BGL_QuotaUnderOver] = "";
	          $record[BGL_QuotaUnder]     = "";
	          $record[BGL_QuotaOver]      = "";
	          $record[BGL_QuotaOdd]       = "";
	          $record[BGL_ResultChoice]	= 'Draw';
	          $record[BGL_State]          = 'Cancel';
	          $where = "BGL_Key in (".$update_buygame_success.")";
	          $this->db->AutoExecute("buygamelist",$record,'UPDATE', $where);
	        };

	        if ( $update_bg_key != "" ) {
	          // 적중특례 게임 전체 배당률 변경 및 게임 결과 카운트 업데이트
	          $tmp_key = explode(",", $update_bg_key);
	          for( $i=0 ; $i < count($tmp_key) ; $i++ ) {
	            $result = $this->db->Execute("select * from buygamelist where BG_Key=?", array( trim($tmp_key[$i]) ) );
	            $quota = 1;
	            while( $bgl_row = $result->FetchRow() ) {
	              $quota *= $bgl_row["BGL_Quota".$bgl_row[BGL_ResultChoice]];
	            };
	
	            $result = $this->db->Execute("select * from buygame where BG_Key=?", array( trim($tmp_key[$i]) ) );
	            $bg_row = $result->FetchRow();
	
	            $record = null;
	            $record[BG_TotalQuota]        = $quota;
	            $BettingPrice = $quota * $bg_row[BG_BettingPrice];	//최종 정산 값
	            $record[BG_ForecastPrice]     = $BettingPrice;
	            $where = "BG_Key=".trim($tmp_key[$i]);
	            $this->db->AutoExecute("buygame", $record, "UPDATE", $where);
	          };
	        };
        }
        //승무패 적중 특례 계산 끝

        // 게임 결과 등록/수정
        $record = Null;
        $record[G_ResultWDL]        = $wdl;
        $record[G_ResultHandicap]   = $handicap;
        $record[G_ResultUnderOver]  = $underover;
        $record[G_ResultOddEven]    = $oddeven;
        $record[G_ResultScoreWin]   = $G_Home;
        $record[G_ResultScoreLose]  = $G_Away;
        $record[G_State]  = "Stop";
        $where = "G_Key=".$G_Key;
        $this->db->AutoExecute("gamelist",$record,'UPDATE', $where);
    	
	}
  
  }; // MCLEN_24CLIB_Class End
  
?>
