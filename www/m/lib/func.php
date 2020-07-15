<?php
#로그인 확인
if(!function_exists('loginChk')){
    function loginChk($type)
    {
        if($type == 'frame'){
            if(!isset($_SESSION['S_Key'])){
                msg('로그인 인증이 종료되었거나, 로그인 하지 않으셨습니다.');
                echo "<script>parent.location.reload();</script>";
            }
        } else {
            if(!isset($_SESSION['S_Key'])){
                //msgMove('회원전용 페이지 입니다. 로그인 후 이용해주세요.','/login/');
                move('/intro/');
            }
        }
    }
}
#관리자 로그인 확인
if(!function_exists('adminChk')){
    function adminChk()
    {
        if(!$_SESSION['loginId'] || $_SESSION['loginLevel']>5){
            msg(iconv('CP949','UTF-8','로그인 후 이용해주세요.'));
            move('/admin/login.php');
        } else if($_SESSION['loginLevel']<5 && $_SESSION['loginLevel']>2){
            //move('/admin/?pg=view&menu=shop&type=shop');
        } else {
            //move('/admin/?pg=list_member&menu=member&type=company');
        }
    }
}
#한개 구하기
function getRow($que)
{
    $res = mysql_query($que) or die(mysql_error());
    $row = mysql_fetch_array($res);
    return $row;
}

//여러개 구하기
function getArr($que)
{
    $cnt = 0;
    $res = mysql_query($que);
    while($arr=mysql_fetch_array($res)){
        $tmp[$cnt] = $arr;
        $cnt++;
    }
    return $tmp;

}

#쿼리 입력
function setQry($que)
{
    $res = mysql_query($que);
    return $res;
}

#메세지이동
function msgMove($msg,$url)
{
    echo "<script>alert('".$msg."'); location.href = '".$url."';</script>";
}

#메세지
function msg($msg)
{
    echo "<script>alert('".$msg."');</script>";
}

#페이지 이동
function move($url)
{
    echo "<script>location.href='".$url."';</script>";
}

#이전 페이지
function back()
{
    echo "<script>window.history.back(1);</script>";
}

#iframe reload
function ifrm_reload()
{
    echo "<script>parent.location.reload();</script>";
}

#팝업 닫기
function pop_close()
{
    echo "<script>window.close();</script>";
}

#글자형 변화
function change_chr($str)
{
    return iconv('utf-8','cp949',$star);
}

#한글 자르기 utf-8 현재 사용중
function utf8_cutstr($str,$len,$tail='') {
    $c = substr(str_pad(decbin(ord($str{$len})),8,'0',STR_PAD_LEFT),0,2);
    if ($c == '10')
        for (;$c != '11' && $c{0} == 1;$c = substr(str_pad(decbin(ord($str{--$len})),8,'0',STR_PAD_LEFT),0,2));
    return substr($str,0,$len) . (strlen($str)-strlen($tail) >= $len ? '..' : '');
}



#게시판 레벨 변경
function change_level($lv, $id, $top, $tb)
{

    $tmp = $lv."a";
    $length = strlen($tmp);
    $q = "SELECT level FROM $tb WHERE bid='$id' and top='$top' and
		length(level)=$length and level like '$level%' ORDER BY level DESC ";
    //echo $q;
    $d = getRow($q);

    if(!$d[0]){
        $level = $tmp;
    }else{
        $a = $d[0];
        $length = strlen($a);

        // abc일경우 ab까지만 변수에 저장
        $pre_article = substr($a,0,$length-1);

        // 마지막 글자만 별도로 저장 abc -> c만 저장
        $now_article = substr($a, $length-1,1);

        //  ord는 문자를 술자로 변환, chr 숫자를 문자로 변환
        $ch = ord($now_article);
        $ch ++;
        $now_article = chr($ch);

        $level = $pre_article.$now_article;
    }
    return $level;

}

#파일업로드
function file_upload($file,$file_tmp, $path)
{
    $ext = explode(".",$file);
    $fname = md5(uniqid($file)).'.'.$ext[1];
    $res = move_uploaded_file($file_tmp,$path.$fname);
    if($res){
        return $fname;
    }
}




#회원정보 가져오기
function get_member_info($id)
{
    $que = "SELECT * FROM members WHERE M_Key = '{$id}'";
    //echo $que;
    $row = getRow($que);
    return $row;
}



#안전 디비 입력
function mysql_safe($str)
{
    $sql = mysql_real_escape_string($str);
    return $sql;
}

#안전 데이터 입력
function html_safe($str, $type)
{
    if($type == 'a'){//영문만
        if(ctype_alpha($str)){
            return htmlentities($str);
        }
    }
    if($type == 'an'){//영문숫자
        if(ctype_alnum($str)){
            return htmlentities($str);
        }
    }
    if($type == 'd'){//숫자
        if(ctype_digit($str)){
            return htmlentities($str);
        }
    }
}

#썸네일 만들기
function make_thumbnail($source_file, $_width, $_height, $object_file)
{
    list($img_width,$img_height, $type) = getimagesize($source_file);
    if ($type==1) $img_sour = imagecreatefromgif($source_file);
    else if ($type==2 ) $img_sour = imagecreatefromjpeg($source_file);
    else if ($type==3 ) $img_sour = imagecreatefrompng($source_file);
    else if ($type==15) $img_sour = imagecreatefromwbmp($source_file);
    else return false;
    if ($img_width > $img_height) {
        $width = round($_height*$img_width/$img_height);
        $height = $_height;
    } else {
        $width = $_width;
        $height = round($_width*$img_height/$img_width);
    }
    if ($width < $_width) {
        $width = round(($height + $_width - $width)*$img_width/$img_height);
        $height = round(($width + $_width - $width)*$img_height/$img_width);
    } else if ($height < $_height) {
        $height = round(($width + $_height - $height)*$img_height/$img_width);
        $width = round(($height + $_height - $height)*$img_width/$img_height);
    }
    $x_last = round(($width-$_width)/2);
    $y_last = round(($height-$_height)/2);
    if ($img_width < $_width || $img_height < $_height) {
        $img_last = imagecreatetruecolor($_width, $_height);
        $x_last = round(($_width - $img_width)/2);
        $y_last = round(($_height - $img_height)/2);

        imagecopy($img_last,$img_sour,$x_last,$y_last,0,0,$width,$height);
        imagedestroy($img_sour);
        $white = imagecolorallocate($img_last,255,255,255);
        imagefill($img_last, 0, 0, $white);
    } else {
        $img_dest = imagecreatetruecolor($width,$height);
        imagecopyresampled($img_dest, $img_sour,0,0,0,0,$width,$height,$img_width,$img_height);
        $img_last = imagecreatetruecolor($_width,$_height);
        imagecopy($img_last,$img_dest,0,0,$x_last,$y_last,$width,$height);
        imagedestroy($img_dest);
    }
    if ($object_file) {
        if ($type==1) imagegif($img_last, $object_file, 100);
        else if ($type==2 ) imagejpeg($img_last, $object_file, 100);
        else if ($type==3 ) @imagepng($img_last, $object_file, 100);
        else if ($type==15) imagebmp($img_last, $object_file, 100);
    } else {
        if ($type==1) imagegif($img_last);
        else if ($type==2 ) imagejpeg($img_last);
        else if ($type==3 ) @imagepng($img_last);
        else if ($type==15) imagebmp($img_last);
    }
    imagedestroy($img_last);
    return true;
}


function getUserName($uid)
{
    $row = getRow("SELECT userNick FROM member WHERE userId =  '$uid'");
    return $row['userNick'];
}






#사용자 접속 통계
function static_rate()
{

    $sid = (!$_SESSION[login_id]) ? session_id() : $_SESSION[login_id] ;
    $que = "SELECT COUNT(*) FROM static_rate1 WHERE ip = '".$_SERVER['REMOTE_ADDR']."' AND FROM_UNIXTIME(reg_date,'%Y%m%d')=DATE_FORMAT(NOW(),'%Y%m%d')";
    //echo $que;
    $row = getRow($que);
    if(!$row[0]){
        $que = "INSERT INTO static_rate1 SET ";
        $que .= "ip 		= '".$_SERVER['REMOTE_ADDR']."', ";
        $que .= "url 		= '".$_SERVER['HTTP_REFERER']."', ";
        $que .= "host 		= '".get_host($_SERVER['HTTP_REFERER'])."', ";
        $que .= "os 		= '".get_os()."', ";
        $que .= "brow 		= '".get_brow()."', ";
        $que .= "sid 		= '".$sid."', ";
        $que .= "reg_date 	= ".time();
        //echo $que;
        @setQry($que) or die(mysql_error());
    }

    #오늘 날짜에 ip를 구한다.
    $arr = getArr("SELECT ip FROM static_rate1 WHERE FROM_UNIXTIME(reg_date,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') GROUP BY ip");
    for($i=0;$i<count($arr);$i++){
        $ip[$i] = $arr[$i][ip];
    }

    #오늘 날짜의 방문기록을 구한다.
    $row1 = getRow("SELECT reg_date FROM visit_rate WHERE reg_date = DATE_FORMAT(NOW(),'%Y-%m-%d')");
    if(!$row1[reg_date]){//오늘날짜에 데이터가 없으면
        $que = "INSERT INTO visit_rate SET rate1 = 1, reg_date = NOW()";
    } else {
        if(!in_array($_SERVER['REMOTE_ADDR'],$ip)){
            $que = "UPDATE visit_rate SET rate1 = rate1+1 WHERE reg_date = DATE_FORMAT(NOW(),'%Y-%m-%d')";
            //echo $que;
        }
    }
    @setQry($que);



    #시간대별 통계
    visit_time($ip);

    #일별 통계
    visit_day($ip);

    #월별 통계
    visit_month($ip);

    #요일별 통계
    visit_week($ip);


}

#도메인 구분
function get_host($host)
{

    $value = "";
    $tmp = explode("/",$host);

    $cnt = count($tmp);
    if($cnt>0){
        if($cnt>4){
            $value .= $tmp[0]."//";
        }
        for($i=1;$i<3;$i++){
            $value .= $tmp[$i];
        }
    }
    //echo $value;
    return $value;
}

#시간대별 접속 통계
function visit_time($ip)
{
    $t = date("H",time());
    $row1 = getRow("SELECT reg_date FROM visit_time WHERE reg_date = DATE_FORMAT(NOW(),'%Y-%m-%d')");
    //echo $row1[reg_date];
    if(!$row1[reg_date]){
        for($i=0;$i<=23;$i++){
            if($i == $t){
                $que = "INSERT INTO visit_time SET time$i = 1, reg_date = NOW() ";
            }
        }
    } else {
        for($i=1;$i<=23;$i++){
            if(!in_array($_SERVER['REMOTE_ADDR'],$ip) && $i==$t){
                $que = "UPDATE visit_rate SET  time$i= time$i+1 WHERE reg_date = DATE_FORMAT(NOW(),'%Y-%m-%d')";
            }
        }
    }
    //echo $que;
    setQry($que);
}


#일별 접속 통계
function visit_day($ip)
{
    $t = date("d",time());
    $row1 = getRow("SELECT reg_date FROM visit_day WHERE reg_date = DATE_FORMAT(NOW(),'%Y-%m-%d')");
    if(!$row1[reg_date]){
        for($i=1;$i<=31;$i++){
            if($i == $t){
                $que = "INSERT INTO visit_day SET day$i = 1, reg_date = NOW() ";
            }
        }
    } else {
        for($i=1;$i<=31;$i++){
            if(!in_array($_SERVER['REMOTE_ADDR'],$ip) && $i==$t){
                $que = "UPDATE visit_day SET  day$i= day$i+1 WHERE reg_date = DATE_FORMAT(NOW(),'%Y-%m-%d')";
            }
        }
    }
    @setQry($que);

}

#월별 접속 통계
function visit_month($ip)
{
    $t = date("m",time());
    $row1 = getRow("SELECT reg_date FROM visit_month WHERE DATE_FORMAT(reg_date,'%Y') = DATE_FORMAT(NOW(),'%Y')");
    if(!$row1[reg_date]){
        for($i=1;$i<=12;$i++){
            if($i == $t){
                $que = "INSERT INTO visit_month SET month$i = 1, reg_date = NOW() ";
            }
        }
    } else {
        for($i=1;$i<=12;$i++){
            if(!in_array($_SERVER['REMOTE_ADDR'],$ip) && $i==$t){
                $que = "UPDATE visit_month SET  month$i= month$i+1 WHERE DATE_FORMAT(reg_date,'%Y') = DATE_FORMAT(NOW(),'%Y')";
                //echo $que,"<br>";
            }
        }
    }
    @setQry($que);

}

#요일별 접속 통계
function visit_week($ip)
{
    $t = date("w",time());
    $row1 = getRow("SELECT reg_date FROM visit_week WHERE reg_date = DATE_FORMAT(NOW(),'%Y-%m-%d')");
    if(!$row1[reg_date]){
        for($i=0;$i<=6;$i++){
            if($i == $t){
                $que = "INSERT INTO visit_week SET week$i = 1, reg_date = NOW() ";
            }
        }
    } else {
        for($i=0;$i<=6;$i++){
            if(!in_array($_SERVER['REMOTE_ADDR'],$ip) && $i==$t){
                $que = "UPDATE visit_week SET  week$i= week$i+1 WHERE reg_date = DATE_FORMAT(NOW(),'%Y-%m-%d')";
            }
        }
    }
    @setQry($que);

}

#브라우저 구분
function get_brow()
{
    if(preg_match("/compatible; MSIE/i", $_SERVER['HTTP_USER_AGENT']))
    {
        return "Explorer";
    }
    if(preg_match("/Chrome/",$_SERVER['HTTP_USER_AGENT']))
    {
        return "Chrome";
    }
    if(preg_match("/Safari/",$_SERVER['HTTP_USER_AGENT']))
    {
        return "Safari";
    }
    if(preg_match("/Firefox/",$_SERVER['HTTP_USER_AGENT']))
    {
        return "Firefox";
    }
}

#브라우저 구분
function get_os()
{
    if(preg_match("/Windows NT [0-9]\.[0-9]/i", $_SERVER['HTTP_USER_AGENT']))
    {
        return "Windows";
    }
    if(preg_match("/Android/",$_SERVER['HTTP_USER_AGENT']))
    {
        return "Android";
    }
    if(preg_match("/Safari/",$_SERVER['HTTP_USER_AGENT']))
    {
        return "Safari";
    }
    if(preg_match("/Firefox/",$_SERVER['HTTP_USER_AGENT']))
    {
        return "Firefox";
    }
}

#방문현황
function visit_rate($gubun)
{
    if($gubun == 1){
        $que = "SELECT * FROM static_rate1 WHERE FROM_UNIXTIME(reg_date,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') GROUP BY sid ";
        //echo $que;
        $arr = getArr($que);
        return count($arr);
    }
    if($gubun == 2){
        $que = "SELECT COUNT(*) FROM member WHERE FROM_UNIXTIME(reg_date,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') ";
        //echo $que;
        $row = getRow($que);
        return ($row[0])?$row[0]:0;
    }
    if($gubun == 3){
        $que = "SELECT COUNT(*) FROM member WHERE FROM_UNIXTIME(reg_date,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') AND email_yn = 'y' ";
        //echo $que;
        $row = getRow($que);
        return ($row[0])?$row[0]:0;
    }
}

#방문현황
function total_rate($gubun)
{
    if($gubun == 1){
        $que = "SELECT COUNT(*) FROM static_rate1 GROUP BY sid ";
        //echo $que;
        $arr = getArr($que);
        return count($arr);
    }
    if($gubun == 2){
        $que = "SELECT COUNT(*) FROM member WHERE `status` = '3' ";
        //echo $que;
        $row = getRow($que);
        return ($row[0])?$row[0]:0;
    }

    #일평균 방문자수
    if($gubun == 3){
        $total = 0;
        $arr = getArr("SELECT * FROM visit_day WHERE 1 ");
        $cnt = count($arr);
        for($i=0;$i<count($arr);$i++){
            for($j=1;$j<=31;$j++){
                $total += $arr[$i][day.$j];
            }
        }
        echo round($total/$cnt);
    }
}


#정산처리 대상자 검색
function get_pay_people($g_key,$result)
{
    $success = 0;
    $fail = 0;
    $que = "SELECT * FROM buygamelist a, buygame b WHERE a.G_Key = {$g_key} AND a.BG_Key = b.BG_Key";
    //echo $que;
    $row = getRow($que);
    for($i=0;$i<$row[BG_GameCount ];$i++){
        $sql = "SELECT COUNT(*) FROM buygamelist WHERE BGL_State = 'Await'";
        $srow = getRow($sql);
        if($srow[0]>0){
        } else {
            $success++;
        }
    }
    return $success;
}

#취소나 적특이 있을경우 배당률을 변경한다.
function change_rate($bg_key)
{
    $success = 0;
    $fail = 0;
    $cancel = 0;
    $cnt = 0;
    $que = "SELECT * FROM buygame WHERE BG_Key = {$bg_key}";
    //echo $que."<br>";
    $row = getRow($que);
    if($row[BG_Key]){
        $result = "";
        $sql = "SELECT * FROM buygamelist WHERE BG_Key = {$row[BG_Key]}";
        //echo $sql."<br>";
        $arr = getArr($sql);
        if(count($arr)>0){
            foreach($arr as $list){
                $rate = "";
                //echo $list['BGL_State'];
                if($list['BGL_State']!='Cancel'){
                    if($list['BGL_ResultChoice']=='Win'){
                        $rate = $list[BGL_QuotaWin];
                    } else if($list['BGL_ResultChoice']=='Draw'){
                        $rate = $list[BGL_QuotaDraw];
                    } else if($list['BGL_ResultChoice']=='Lose'){
                        $rate = $list[BGL_QuotaLose];
                    } else if($list['BGL_ResultChoice']=='Under'){
                        $rate = $list[BGL_QuotaUnder];
                    } else if($list['BGL_ResultChoice']=='Over'){
                        $rate = $list[BGL_QuotaOver];
                    } else if($list['BGL_ResultChoice']=='HandiWin'){
                        $rate = $list[BGL_QuotaHandiWin];
                    } else if($list['BGL_ResultChoice']=='HandiLose'){
                        $rate = $list[BGL_QuotaHandiLose];
                    } else if($list['BGL_ResultChoice']=='Odd'){
                        $rate = $list[BGL_QuotaOdd];
                    } else if($list['BGL_ResultChoice']=='Even'){
                        $rate = $list[BGL_QuotaEven];
                    }
                    //echo $rate."<br>";
                    $result[$cnt] = $rate;
                    $cnt++;
                } else {
                    $result[$cnt] = 1;
                    $cnt++;
                }
            }


            $r = 1;
            for($i=0;$i<count($result);$i++){
                $r *= $result[$i];
            }


            if(strlen($r)>3){
                $r = substr($r,0,4);
            }

            return $r;
            /*$finish = $row[BG_BettingPrice]*$r;
            $q1 = "UPDATE buygame SET BG_TotalQuota = '".substr($r,0,4)."', BG_ForecastPrice = {$finish} WHERE BG_Key = {$row[BG_Key]}";
            //echo $q1."<br><br>";
            setQry($q1)*/;
            #취소되거나 적특된 경기가 있다면 배당률을 업데이트 한다.

        }
    }

}

#현재 게임에 배팅한 내역을 구한다.
function get_betting_cnt($g_key)
{
    $que = "SELECT COUNT(*) FROM buygame a, buygamelist b, members c WHERE a.M_Key = c.M_Key AND a.BG_Key = b.BG_Key AND c.M_Type = '1' AND G_Key = {$g_key}";
    //echo $que;
    $row = getRow($que);
    if($row[0]>0){
        return $row[0];
    }
}


#회원배팅금액 차감 - 경기복원시
function minus_member_money($mkey, $money)
{
    $que = "UPDATE members SET M_Money = M_Money-".$money." WHERE M_Key = {$mkey}";
    //echo $que."<br>";
    $res = setQry($que);
    return $res;
}


#포인트 입금 출금
function process_point($point_type)
{
    switch ( $point_type ) {
        case 'Join'         : $msg = "회원 가입 축하 포인트 지급"; $point = setting_point($m_key, "MemberJoin"); break;
        case 'RecJoin'      : $msg = "회원 추천 포인트 지급"; $point = setting_point($m_key, "RecJoin"); break;
        case 'Betting'      :
            if($point > 0) $msg = "배팅 환급 포인트 지급";
            else $msg = "배팅 취소로인한 환급 포인트 회수";
            //if ( $point == 0 ) $this->lib->AlertMSG("포인트 지급에 오류가 있어 처리되지 않았습니다.");
            break;
        case 'BettingFail'      : $msg = "배팅 실패 환급 포인트 지급 - ".$msg;
            //if ( $point == 0 ) $this->lib->AlertMSG("포인트 지급에 오류가 있어 처리되지 않았습니다.");
            break;
        case 'RecBetting'   : $msg = "추천인 배팅 실패 포인트 - ".$msg;
            //if ( $point == 0 ) $this->lib->AlertMSG("포인트 지급에 오류가 있어 처리되지 않았습니다.");
            break;
        case 'BoardWrite'   : $msg = "글작성 포인트 지급"; $point = setting_point($m_key, "BoardWrite"); break;
        case 'BoardDelete'  : $msg = "글삭제 포인트 차감"; $point = setting_point($m_key, "BoardDelete"); break;
        case 'Charge'       : $msg = "머니 충전 포인트 지급";
            //if ( $point == 0 ) $this->lib->AlertMSG("포인트 지급에 오류가 있어 처리되지 않았습니다.");
            break;
        case 'PointConvert' : $msg = "포인트 -> 머니 변경 포인트 차감";
            //if ( $point == 0 ) $this->lib->AlertMSG("포인트 지급에 오류가 있어 처리되지 않았습니다.");
            break;
        case 'reply'        : $msg = "댓글 포인트 지급"; $point = setting_point($m_key, "reply"); break;
        case 'replydelete'        : $msg = "댓글 포인트 삭제"; $point = -1 * setting_point($m_key, "reply"); break;
        case 'Other'        :
            //if ( $point == 0 || !$msg ) $this->lib->AlertMSG("포인트 입출금에 오류가 있어 처리되지 않았습니다.");
            break;
        default : $this->lib->AlertMSG("포인트 지급에 오류가 있어 처리되지 않았습니다."); break;
    }



}


#포인트 설정 구하기
function setting_point($m_key, $gubun)
{
    $lv = get_member_info($p_key);
    $que = "select * from pointconfig where level='{$lv['M_Level']}'";
    $tmp = getRow($result);
    return $tmp[$gubun];
}

#배팅이 없는 경기 처리
function no_betting()
{
    $que = "SELECT * FROM gamelist WHERE G_PayProcess = '2' AND G_State = 'Stop'";
    $arr = getArr($que);
    if(count($arr)>0){
        foreach($arr as $list){
            $sql = "SELECT COUNT(*) FROM buygamelist WHERE G_Key = {$list[G_Key]}";
            $row = getRow($sql);
            if($row[0]>0){
            } else {
                $sqll = "UPDATE gamelist SET G_PayProcess = '3', G_State = 'End' WHERE G_Key = {$list[G_Key]}";
                setQry($sqll);
            }
        }
    }
}

#경기마감
function UpdateGameState()
{
    $now = date("Y-m-d H:i:s");
    $que = "UPDATE gamelist a, gameleague b SET a.G_State = 'Stop' WHERE a.G_Datetime < '{$now}' AND a.G_PayProcess = '1' AND a.GL_Key = b.GL_Key AND b.GL_Gubun = '1' ";
    //echo $que;
    //setQry($que);

    $que = "UPDATE gamelist_power a, gameleague b SET a.G_State = 'Stop' WHERE a.G_Datetime < '{$now}' AND a.G_PayProcess = '1' AND a.GL_Key = b.GL_Key AND b.GL_Gubun = '1' ";
    //echo $que;
    //setQry($que);
}


#보유머니
function get_user_money()
{
    $que = "SELECT SUM(M_Money) FROM members WHERE M_Admin = 'N' AND M_State = 'Normal' AND M_Type = '1'";
    $row = getRow($que);
    echo number_format($row[0]);
}

#개인 충전 합계, 환전합계, 충전 횟수, 환전횟수
function get_personal_request($mkey)
{
    $row1 = getRow("SELECT SUM(R_Money) AS ctotal, COUNT(*) AS cnt FROM requests WHERE M_Key = {$mkey} AND R_Type1 = 'Charge' AND R_State = 'Done' AND R_Type2 = 'Money'");

    $row2 = getRow("SELECT SUM(R_Money) AS ctotal, COUNT(*) AS cnt FROM requests WHERE M_Key = {$mkey} AND R_Type1 = 'Refund' AND R_State = 'Done'");
    //print_r($row1);
    $row['ctotal'] 	= ($row1[ctotal]>0)?$row1[ctotal]:0;
    $row['ccnt'] 	= ($row1[cnt]>0)?$row1[cnt]:0;
    $row['ftotal'] 	= ($row2[ctotal]>0)?$row2[ctotal]:0;
    $row['fcnt'] 	= ($row2[cnt]>0)?$row2[cnt]:0;
    return $row;
}

#개인 충전 합계, 환전합계, 충전 횟수, 환전횟수
function get_personal_request_new($mkey)
{

    $row1 = getRow("SELECT SUM(R_Money) AS ctotal, COUNT(*) AS cnt FROM requests WHERE M_Key = {$mkey} AND R_Type1 = 'Charge' AND R_State = 'Done' AND R_Type2 = 'Money' AND R_ResultDate > '2017-03-20 00:00:00'");

    $row2 = getRow("SELECT SUM(R_Money) AS ctotal, COUNT(*) AS cnt FROM requests WHERE M_Key = {$mkey} AND R_Type1 = 'Refund' AND R_State = 'Done' AND R_ResultDate > '2017-03-20 00:00:00'");
    //print_r($row1);
    $row['ctotal'] 	= ($row1[ctotal]>0)?$row1[ctotal]:0;
    $row['ccnt'] 	= ($row1[cnt]>0)?$row1[cnt]:0;
    $row['ftotal'] 	= ($row2[ctotal]>0)?$row2[ctotal]:0;
    $row['fcnt'] 	= ($row2[cnt]>0)?$row2[cnt]:0;
    return $row;
}


#회원의 포인트 정책을 구한다.
function get_member_point($mkey)
{
    $que = "SELECT * FROM members a, pointconfig b WHERE a.M_Level = b.level AND a.M_Key = {$mkey}";
    //echo $que."<br>";
    $row = getRow($que);
    if($row[BettingFail]){
        return $row[BettingFail];
    }
}


#경기수정시 배당 및 경기정보 변경처리
function changeAllBetting($gkey)
{

    $que = "SELECT * FROM buygame a, buygamelist b WHERE a.BG_Key = b.BG_Key AND b.G_Key = {$gkey}";
    //echo $que;
    $arr = getArr($que);
    if(count($arr)>0){
        foreach($arr as $list){
            $sql = "SELECT * FROM buygamelist WHERE BG_Key = {$list[BG_Key]}";
            $sqlArr = getArr($sql);
            if(count($sqlArr)>0){
                $total = 0;
                foreach($sqlArr as $sa){

                    switch($sa['BGL_ResultChoice']){
                        case 'Win':
                            $rate = $sa[BGL_QuotaWin];
                            break;
                        case 'Draw':
                            $rate = $sa[BGL_QuotaDraw];
                            break;
                        case 'Lose':
                            $rate = $sa[BGL_QuotaLose];
                            break;

                        case 'HandiWin':
                            $rate = $sa[BGL_QuotaHandiWin];
                            break;
                        case 'HandiLose':
                            $rate = $sa[BGL_QuotaHandiLose];
                            break;

                        case 'Under':
                            $rate = $sa[BGL_QuotaUnder];
                            break;

                        case 'Over':
                            $rate = $sa[BGL_QuotaOver];
                            break;

                        case 'Odd':
                            $rate = $sa[BGL_QuotaOdd];
                            break;

                        case 'Even':
                            $rate = $sa[BGL_QuotaEven];
                            break;
                    }

                    $total += $rate;
                }

                $bp = $list[BG_BettingPrice]*$total;
                $que1 = "UPDATE buygame SET BG_TotalQuota = {$total}, BG_ForecastPrice = {$bp} WHERE BG_Key = {$list[BG_Key]}";
                //echo $que1;
                setQry($que1);
            }
        }
    }
}


#해당 경기에 배당 내역을 구한다.
function get_bet_list($skey, $gubun)
{

    /*case "1" : $game_type = "사다리 홀/짝"; $type = 1; break;
  case "2" : $game_type = "사다리 3줄/4줄"; $type = 2; break;
  case "3" : $game_type = "사다리 좌/우"; $type = 3; break;

  case "4" : $game_type = "파워볼 소/중/대"; $type = 4; break;
  case "5" : $game_type = "파워볼 구간합계"; $type = 5; break;
  case "6" : $game_type = "파워볼"; $type = 6; break;
  case "7" : $game_type = "파워볼 홀/짝"; $type = 7; break;

  case "8" : $game_type = "언더/오버"; $type = 8; break;
  case "9" : $game_type = "연타/미연타"; $type = 9; break;
  case "10" : $game_type = "타이/노타이"; $type = 10; break;*/


    $total = $odd = $even = $SA = $SB = $SC = $SD = $SE = $SF = $PA = $PB = $PC = $PD = $small = $middle = $big = 0;
    $que = "SELECT * FROM buygame a, buygamelist b, members c WHERE a.M_Key = c.M_Key AND a.BG_Key = b.BG_Key AND b.G_Key = {$skey} AND c.M_Type = 1 ";
    //echo $que."<br>";
    $arr = getArr($que);
    if(count($arr)>0){
        foreach($arr as $list)
        {
            #사다리 홀/짝
            if($gubun == 1){
                $total++;
                if($list['BGL_ResultChoice']=='Odd'){
                    $odd++;
                } else if($list['BGL_ResultChoice']=='Even'){
                    $even++;
                }
            } else if($gubun == 2){//좌/우
                $total++;
                if($list['BGL_ResultChoice']=='Odd'){
                    $odd++;
                } else if($list['BGL_ResultChoice']=='Even'){
                    $even++;
                }
            } else if($gubun == 3){//풀/핸디캡
                $total++;
                if($list['BGL_ResultChoice']=='Odd'){
                    $odd++;
                } else if($list['BGL_ResultChoice']=='Even'){
                    $even++;
                }
            } else if($gubun == 4){//풀/핸디캡
                $total++;
                if($list['BGL_ResultChoice']=='Odd'){
                    $odd++;
                } else if($list['BGL_ResultChoice']=='Even'){
                    $even++;
                }
            } else if($gubun == 5){//풀/핸디캡
                $total++;
                if($list['BGL_ResultChoice']=='Odd'){
                    $odd++;
                } else if($list['BGL_ResultChoice']=='Even'){
                    $even++;
                }
            } else if($gubun == 6){//풀/핸디캡
                $total++;
                if($list['BGL_ResultChoice']=='Odd'){
                    $odd++;
                } else if($list['BGL_ResultChoice']=='Even'){
                    $even++;
                }
            } else if($gubun == 7){//풀/핸디캡
                $total++;
                if($list['BGL_ResultChoice']=='Odd'){
                    $odd++;
                } else if($list['BGL_ResultChoice']=='Even'){
                    $even++;
                }
            } else if($gubun == 8){//풀/핸디캡
                $total++;
                if($list['BGL_ResultChoice']=='Odd'){
                    $odd++;
                } else if($list['BGL_ResultChoice']=='Even'){
                    $even++;
                }
            } else if($gubun == 9){//풀/핸디캡
                $total++;
                if($list['BGL_ResultChoice']=='Odd'){
                    $odd++;
                } else if($list['BGL_ResultChoice']=='Even'){
                    $even++;
                }
            } else if($gubun == 10){//풀/핸디캡
                $total++;
                if($list['BGL_ResultChoice']=='Odd'){
                    $odd++;
                } else if($list['BGL_ResultChoice']=='Even'){
                    $even++;
                }
            } else if($gubun == 11){//스/핸디캡
                $total++;
                if($list['BGL_ResultChoice']=='Small'){
                    $small++;
                } else if($list['BGL_ResultChoice']=='Middle'){
                    $middle++;
                } else if($list['BGL_ResultChoice']=='Big'){
                    $big++;
                }
            } else if($gubun == 12){//스/언더오버
                $total++;
                if($list['BGL_ResultChoice']=='SA'){
                    $SA++;
                } else if($list['BGL_ResultChoice']=='SB'){
                    $SB++;
                } else if($list['BGL_ResultChoice']=='SC'){
                    $SC++;
                } else if($list['BGL_ResultChoice']=='SD'){
                    $SD++;
                } else if($list['BGL_ResultChoice']=='SE'){
                    $SE++;
                } else if($list['BGL_ResultChoice']=='SF'){
                    $SF++;
                }
            } else if($gubun == 13){//스/언더오버
                $total++;
                if($list['BGL_ResultChoice']=='PA'){
                    $PA++;
                } else if($list['BGL_ResultChoice']=='PB'){
                    $PB++;
                } else if($list['BGL_ResultChoice']=='PC'){
                    $PC++;
                } else if($list['BGL_ResultChoice']=='PD'){
                    $PD++;
                }
            } else if($gubun == 14){//풀/핸디캡
                $total++;
                if($list['BGL_ResultChoice']=='Odd'){
                    $odd++;
                } else if($list['BGL_ResultChoice']=='Even'){
                    $even++;
                }
            } else if($gubun == 15){//스/핸디캡
                $total++;
                if($list['BGL_ResultChoice']=='Small'){
                    $small++;
                } else if($list['BGL_ResultChoice']=='Middle'){
                    $middle++;
                } else if($list['BGL_ResultChoice']=='Big'){
                    $big++;
                }
            } else if($gubun == 16){//스/언더오버
                $total++;
                if($list['BGL_ResultChoice']=='SA'){
                    $SA++;
                } else if($list['BGL_ResultChoice']=='SB'){
                    $SB++;
                } else if($list['BGL_ResultChoice']=='SC'){
                    $SC++;
                } else if($list['BGL_ResultChoice']=='SD'){
                    $SD++;
                } else if($list['BGL_ResultChoice']=='SE'){
                    $SE++;
                } else if($list['BGL_ResultChoice']=='SF'){
                    $SF++;
                }
            } else if($gubun == 17){//스/언더오버
                $total++;
                if($list['BGL_ResultChoice']=='PA'){
                    $PA++;
                } else if($list['BGL_ResultChoice']=='PB'){
                    $PB++;
                } else if($list['BGL_ResultChoice']=='PC'){
                    $PC++;
                }
            } else if($gubun == 18){//승/무/패
                $total++;
                if($list['BGL_ResultChoice']=='Small'){
                    $small++;
                } else if($list['BGL_ResultChoice']=='Middle'){
                    $middle++;
                } else if($list['BGL_ResultChoice']=='Big'){
                    $big++;
                }
            } else if($gubun == 19){//골수
                $total++;
                if($list['BGL_ResultChoice']=='SA'){
                    $SA++;
                } else if($list['BGL_ResultChoice']=='SB'){
                    $SB++;
                } else if($list['BGL_ResultChoice']=='SC'){
                    $SC++;
                } else if($list['BGL_ResultChoice']=='SD'){
                    $SD++;
                } else if($list['BGL_ResultChoice']=='SE'){
                    $SE++;
                }
            } else if($gubun == 20){//홈승
                $total++;
                if($list['BGL_ResultChoice']=='SA'){
                    $SA++;
                } else if($list['BGL_ResultChoice']=='SB'){
                    $SB++;
                } else if($list['BGL_ResultChoice']=='SC'){
                    $SC++;
                } else if($list['BGL_ResultChoice']=='SD'){
                    $SD++;
                } else if($list['BGL_ResultChoice']=='SE'){
                    $SE++;
                } else if($list['BGL_ResultChoice']=='SF'){
                    $SF++;
                }
            } else if($gubun == 21){//원정
                $total++;
                if($list['BGL_ResultChoice']=='SA'){
                    $SA++;
                } else if($list['BGL_ResultChoice']=='SB'){
                    $SB++;
                } else if($list['BGL_ResultChoice']=='SC'){
                    $SC++;
                } else if($list['BGL_ResultChoice']=='SD'){
                    $SD++;
                } else if($list['BGL_ResultChoice']=='SE'){
                    $SE++;
                } else if($list['BGL_ResultChoice']=='SF'){
                    $SF++;
                }
            } else if($gubun == 22){//무
                $total++;
                if($list['BGL_ResultChoice']=='Small'){
                    $small++;
                } else if($list['BGL_ResultChoice']=='Middle'){
                    $middle++;
                } else if($list['BGL_ResultChoice']=='Big'){
                    $big++;
                }
            } else if($gubun == 23){//언더오버
                $total++;
                if($list['BGL_ResultChoice']=='Odd'){
                    $odd++;
                } else if($list['BGL_ResultChoice']=='Even'){
                    $even++;
                }
            }

        }
    }

    if($gubun ==1 ){
        if($total>0){
            echo "전체[".$total."] 홀 [".$odd."] 짝 [".$even."]";
        }
    } else if($gubun == 2){//풀/언더오버
        if($total>0){
            echo "전체 [".$total."] 좌 [".$odd."] 우 [".$even."]";
        }
    } else if($gubun == 3){//풀/핸디캡
        if($total>0){
            echo "전체 [".$total."] 3줄 [".$odd."] 4줄 [".$even."]";
        }
    } else if($gubun == 4){//풀/핸디캡
        if($total>0){
            echo "전체 [".$total."] 좌3짝 [".$odd."] 좌4홀 [".$even."]";
        }
    } else if($gubun == 5){//풀/핸디캡
        if($total>0){
            echo "전체 [".$total."] 우3홀 [".$odd."] 우4짝 [".$even."]";
        }
    } else if($gubun == 6){//풀/핸디캡
        if($total>0){
            echo "전체".$total." 1.5 언더".$odd." 1.5 오버".$even."]";
        }
    } else if($gubun == 7){//풀/핸디캡
        if($total>0){
            echo "전체".$total." 2 언더".$odd." 2 오버".$even."]";
        }
    } else if($gubun == 8){//풀/핸디캡
        if($total>0){
            echo "전체".$total." 연타".$odd." 미연타".$even."]";
        }
    } else if($gubun == 9){//풀/핸디캡
        if($total>0){
            echo "전체".$total." 타이".$odd." 노타이".$even."]";
        }
    } else if($gubun == 10){//풀/핸디캡
        if($total>0){
            echo "전체 [".$total."] 홀 [".$odd."] 짝 [".$even."]";
        }
    } else if($gubun == 11){//스/핸디캡
        if($total>0){
            echo "전체 [".$total."] 소 [".$small."] 중 [".$middle."] 대 [".$big."]";
        }
    } else if($gubun == 12){//스/언더오버
        if($total>0){
            echo "전체 [".$total."] SA [".$SA."] SB [".$SB."] SC [".$SC."] SD [".$SD."] SE [".$SE."] SF [".$SF."]";
        }
    } else if($gubun == 13){//스/언더오버
        if($total>0){
            echo "전체 [".$total."] PA [".$PA."] PB [".$PB."] PC [".$PC."] PD [".$PD."]";
        }
    } else if($gubun == 14){//스/언더오버
        if($total>0){
            echo "전체 [".$total."] 네팽 [".$odd."] 드팽 [".$even."]";
        }
    } else if($gubun == 15){//스/언더오버
        if($total>0){
            echo "전체 [".$total."] 네팽 [".$small."] 임팽 [".$middle."] 드팽 [".$big."]";
        }
    } else if($gubun == 16){//스/언더오버
        if($total>0){
            echo "전체".$total." [네임]".$SA." [네드]".$SB." [임네]".$SC." [임드]".$SD." [드네]".$SE." [드임]".$SF;
        }
    } else if($gubun == 17){//스/언더오버
        if($total>0){
            echo "전체".$total." [네임]".$PA." [네드]".$PB." [임드]".$PC;
        }
    } else if($gubun == 18){//스/언더오버
        if($total>0){
            echo "전체".$total." 승 [".$small."] 무 [".$middle."] 패 [".$big."]";
        }
    } else if($gubun == 19){//스/언더오버
        if($total>0){
            echo "전체 [".$total."] 0골 [".$SA."] 1골 [".$SB."] 2골 [".$SC."] 3골 [".$SD."] 4골 [".$SE."]";
        }
    } else if($gubun == 20){//스/언더오버
        if($total>0){
            echo "전체 [".$total."] 1-0 [".$SA."] 2-0 [".$SB."] 2-1 [".$SC."] 3-0 [".$SD."] 3-1 [".$SE."] 4-0 [".$SF."]";
        }
    } else if($gubun == 21){//스/언더오버
        if($total>0){
            echo "전체 [".$total."] 0-1 [".$SA."] 0-2 [".$SB."] 1-2 [".$SC."] 0-3 [".$SD."] 1-3 [".$SE."] 0-4 [".$SF."]";
        }
    } else if($gubun == 22){//스/언더오버
        if($total>0){
            echo "전체".$total." 0-0 [".$small."] 1-1 [".$middle."] 2-2 [".$big."]";
        }
    } else if($gubun == 23){//스/언더오버
        if($total>0){
            echo "전체 [".$total."] 언더 [".$odd."] 오버 [".$even."]";
        }
    }
    return $total;
}



#해당 경기의 배팅 금액을 구한다.
function get_bet_money($gkey,$gubun, $type, $t='n')
{
    //echo $gubun."-".$type;
    if($gubun == 1){
        if(in_array($type,array(1,2,3,4,5,24,25,26,27,28)) ){
            $w='Odd';
        } else if($type==11){
            $w='Small';
        } else if($type==12){
            $w='SA';
        } else if($type==13){
            $w='PA';
        } else if($type==15){
            $w='Small';
        } else if($type==16){
            $w='SA';
        } else if($type==17){
            $w='PA';
        }
    } else if($gubun == 2){//풀/언더오버
        if(in_array($type,array(1,2,3,4,5,24,25,26,27,28)) ){
            $w='Even';
        } else if($type==11){
            $w='Middle';
        } else if($type==12){
            $w='SB';
        } else if($type==13){
            $w='PB';
        } else if($type==15){
            $w='Middle';
        } else if($type==16){
            $w='SB';
        } else if($type==17){
            $w='PB';
        }
    } else if($gubun == 3){//풀/핸디캡
        if($type==11){
            $w='Big';
        } else if($type==12){
            $w='SC';
        } else if($type==13){
            $w='PC';
        } else if($type==15){
            $w='Big';
        } else if($type==16){
            $w='SC';
        } else if($type==17){
            $w='PC';
        }
    } else if($gubun == 4){//스/핸디캡
        if($type==12){
            $w='SD';
        } else if($type==13){
            $w='PD';
        } else if($type==16){
            $w='SD';
        }
    } else if($gubun == 5){//스/언더오버
        if($type==12){
            $w='SE';
        } else if($type==16){
            $w='SE';
        }
    } else if($gubun == 6){//스/언더오버
        if($type==12){
            $w='SF';
        } else if($type==16){
            $w='SF';
        }
    }

    //echo $w;
    $bmoney = 0;
    $tmoney = 0;

    $que = "SELECT BG_Key FROM gamelist a, buygamelist b, members c WHERE a.G_Key = {$gkey} AND a.G_Key = b.G_Key AND b.M_Key = c.M_Key AND c.M_Type = '1'";
    //echo $que;
    $arr = getArr($que);
    if(count($arr)>0){
        foreach($arr as $list){
            if($list[BG_Key]!=''){
                $que = "SELECT COUNT(*) FROM buygamelist WHERE BG_Key = {$list[BG_Key]} AND BGL_State = 'Fail' ";
                //echo $que;
                $row = getRow($que);
                if($row[0]>0){
                } else {
                    //echo $list[BG_Key]."<br>";
                    $sql = "
									SELECT 
										(SELECT BG_BettingPrice FROM buygame WHERE BG_Key = A.BG_Key) bp, 
										(SELECT BG_ForecastPrice FROM buygame WHERE BG_Key = A.BG_Key) bf 
									FROM 
										(SELECT * FROM buygamelist WHERE BGL_ResultChoice = '{$w}' AND BG_Key = {$list[BG_Key]} AND G_Key = {$gkey}) A
								";
                    //echo $sql."<br>";
                    $row1 = getRow($sql);
                    if($row1[bp]>0){
                        $bmoney += $row1[bp];
                        $tmoney += $row1[bf];

                        //echo "<p>";

                    }
                }
            }
        }
    }
    if($bmoney>0){
        echo number_format($bmoney)."(".number_format($tmoney).")";
    }
}




#회원 접속수
function get_member_access_cnt($mkey)
{
    $row = getRow("SELECT COUNT(*) FROM member_ip WHERE M_ID = '{$mkey}'");
    echo $row[0];
}


#관리자 아이피 확인
function confirm_ip($ip)
{
    $que = "SELECT COUNT(*) FROM aib WHERE ip = '{$ip}'";
    //echo $que;
    $row = getRow($que);
    if($row[0]>0){
        return true;
    } else {
        return false;
    }
}





#복원 낙첨 포인트 차감
function recover_recom_point($bp, $mkey)
{
    $que = "SELECT * FROM members WHERE M_Key = {$mkey} AND M_Level > 5";
    $row = getRow($que);
    if($row['M_Recom']){//추천인이 있다면

        $sql = "SELECT * FROM pointconfig WHERE `level` = {$row[M_Level]}";
        $sql_row = getRow($sql);
        $pi_point = floor(($bp*$row[RecBetting])/100);

        $pi  = "INSERT INTO pointinfo SET ";
        $pi .= "M_Key 		= {$row[M_Key]}, ";
        $pi .= "PI_Type 	= 'Other', ";
        $pi .= "PI_Point 	= -{$pi_point}, ";
        $pi .= "PI_Memo 	= '경기 결과 복원 - 추천인 배팅 실패 포인트 차감 [".$row['M_Recom']." Recover_Chu_Bet_Fail]', ";
        $pi .= "PI_RegDate 	= NOW() ";

        $res = setQry($pi);
        if($res){
            $sql = "UPDATE members SET M_Point = M_Point - {$pi_point} WHERE M_Key = {$mkey}";
            setQry($sql);
        }
    }
}


#추천인 낙첨 포인트
function recom_point($bp, $mkey, $bgkey)
{

    $gi = "SELECT GI_Key FROM  buygamelist a, gamelist b, gameleague c  WHERE a.G_Key = b.G_Key AND b.GL_Key = c.GL_Key AND a.BG_Key = {$bgkey}";
    $gi_row = getRow($gi);
    //if(!in_array($gi_row[GI_Key],array(20,10))){
    $que = "SELECT * FROM members WHERE M_Key = {$mkey} AND M_Level > 5";
    //echo $que."<br>";
    $row = getRow($que);
    if($row['M_Recom']){//추천인이 있다면

        #추천인의 레벨을 구한다.
        //$q = "SELECT * FROM members WHERE M_ID = '{$row['M_Recom']}' AND M_Key != {$mkey}";
        $q = "SELECT * FROM members WHERE M_Recom = '{$row['M_Recom']}'";
        //echo $q."<br>";
        $ml = getRow($q);
        //echo $row['M_Recom'];
        //echo $ml[M_Level];
        if($ml[M_Level]>5 && $mkey != $ml[M_Key]){
            //print_r($ml);
            $sql = "SELECT * FROM pointconfig WHERE `level` = {$ml[M_Level]}";
            //echo $sql."<br>";
            $sql_row = getRow($sql);
            $pi_point = floor(($bp*$sql_row[RecBetting])/100);
            //echo "bp->".$bp." point -> ".$pi_point." percent -> ".$sql_row[BettingFail]."<br><br>";

            $msg  = iconv("cp949","utf-8","추천인 배팅 실패 포인트 지급 [");
            $msg .= $row['M_ID'];
            $msg .= iconv("cp949","utf-8","]");

            $pi  = "INSERT INTO pointinfo SET ";
            $pi .= "M_Key 		= {$ml[M_Key]}, ";
            $pi .= "PI_Type 	= 'ChuBetFail', ";
            $pi .= "PI_Point 	= {$pi_point}, ";
            $pi .= "PI_Memo 	= '".$msg."', ";
            $pi .= "Chu_Key		= '{$mkey}', ";
            $pi .= "BG_Key		= '{$bgkey}', ";
            $pi .= "PI_RegDate 	= NOW() ";
            //echo $pi."<br>";
            $res = setQry($pi) or die(mysql_error());
            if($res){
                setQry("UPDATE recover SET Chu_Bet_Fail_Point = {$pi_point}, Chu_Key = {$ml[M_Key]} WHERE BG_Key = {$bgkey}");
                $sql = "UPDATE members SET M_Point = M_Point + {$pi_point} WHERE M_Key = {$ml[M_Key]}";
                //echo $sql;
                setQry($sql) or die(mysql_error());
            }
        }
    }
    //}
}


#추천인 배팅 포인트
function recom_bet_point($bp, $mkey, $bgkey)
{
    $que = "SELECT * FROM members WHERE M_Key = {$mkey} AND M_Level > 5";
    //echo $que."<br>";
    $row = getRow($que);
    if($row['M_Recom']){//추천인이 있다면

        #추천인의 레벨을 구한다.
        $q = "SELECT * FROM members WHERE M_ID = '{$row['M_Recom']}'";
        //echo $q."<br>";
        $ml = getRow($q);
        //echo $row['M_Recom'];
        //echo $ml[M_Level];
        if($ml[M_Level]>5){
            $m = getRow("SELECT M_Key FROM members WHERE M_ID = '{$rows['M_Recom']}'");
            $sql = "SELECT * FROM pointconfig WHERE `level` = {$ml[M_Level]}";
            //echo $sql."<br>";
            $sql_row = getRow($sql);
            $pi_point = floor(($bp*$sql_row[ChuBetting])/100);
            //echo "bp->".$bp." point -> ".$pi_point." percent -> ".$sql_row[BettingFail]."<br><br>";
            $pi  = "INSERT INTO pointinfo SET ";
            $pi .= "M_Key 		= {$ml[M_Key]}, ";
            $pi .= "PI_Type 	= 'ChuBetting', ";
            $pi .= "PI_Point 	= {$pi_point}, ";
            $pi .= "PI_Memo 	= '추천인 배팅 포인트 지급 [".$row['M_ID']."] Chu_Bet', ";
            $pi .= "Chu_Key		= '{$mkey}', ";
            $pi .= "BG_Key		= '{$bgkey}', ";
            $pi .= "PI_RegDate 	= NOW() ";
            //echo $pi."<br>";
            $res = setQry($pi);
            if($res){
                $sql = "UPDATE recover SET Chu_Bet_Point = {$pi_point}, Chu_Key = {$ml[M_Key]} WHERE BG_Key = {$bgkey}";
                //echo $sql."<br>";
                setQry($sql);

                $sql = "UPDATE members SET M_Point = M_Point + {$pi_point} WHERE M_Key = {$ml[M_Key]}";
                //echo $sql;
                setQry($sql);
            }
        }
    }
}


#접근 아이피

function reg_ip()
{
    $que = "INSERT INTO access_ip SET ";
    $que .= "ip = '{$_SERVER['REMOTE_ADDR']}', ";
    $que .= "url = '{$_SERVER['HTTP_REFERER']}', ";
    $que .= "gubun = 'I', ";
    $que .= "regdate = NOW() ";
    //echo $que;
    setQry($que);
}


#현재 접속자 구하기
function get_access_count()
{
    //$que = "SELECT COUNT(*) FROM members WHERE M_Access_YN = 'Y' AND M_Type = '1' AND M_Level > 4 ";
    $que = "SELECT * FROM member_access_cnt WHERE idx = 1";
    //echo $que;
    $row = getRow($que);
    //print_r($row);
    //$arr = explode(",",$row['id42']);
    echo $row[id42];


    //$que = "SELECT COUNT(M_Key) FROM members WHERE M_Access_YN = 'Y'";
    //$row = getRow($que);
    //echo $row[0];
}

#마이너스 회원 확인
function minus_member()
{
    $que = "SELECT COUNT(*) FROM members WHERE M_Money < 0 AND M_Type ='1' AND M_Level > 5 AND M_Level < 10";
    $row = getRow($que);
    echo $row[0];;
}


#회원 접속 정보 업데이트
function set_members_update()
{

    $que = "UPDATE members SET M_LastAccessDate = NOW() WHERE M_Key = {$_SESSION[S_Key]} AND M_Type ='1' ";

    //echo $que;
    $res = setQry($que);
    if($res){
        $sql = "UPDATE members SET M_Access_YN = 'Y' WHERE M_Key = {$_SESSION[S_Key]}";
        setQry($sql);
    }

}

#해킹 여부 검사하기
function confirm_hacking($bgl_key)
{
    $que = "SELECT * FROM buygamelist WHERE BGL_Key = {$bgl_key}";
    $row = getRow($que);

    $que = "
				SELECT 
					AES_DECRYPT(UNHEX(BGL_QuotaWin),MD5('dlffleoqkr!@#')) as BGL_QuotaWin,
					AES_DECRYPT(UNHEX(BGL_QuotaDraw),MD5('dlffleoqkr!@#')) as BGL_QuotaDraw,
					AES_DECRYPT(UNHEX(BGL_QuotaLose),MD5('dlffleoqkr!@#')) as BGL_QuotaLose,
					
					AES_DECRYPT(UNHEX(BGL_QuotaHandicap),MD5('dlffleoqkr!@#')) as BGL_QuotaHandicap,
					AES_DECRYPT(UNHEX(BGL_QuotaHandiWin),MD5('dlffleoqkr!@#')) as BGL_QuotaHandiWin,
					AES_DECRYPT(UNHEX(BGL_QuotaHandiLose),MD5('dlffleoqkr!@#')) as BGL_QuotaHandiLose,
					
					AES_DECRYPT(UNHEX(BGL_QuotaUnderOver),MD5('dlffleoqkr!@#')) as BGL_QuotaUnderOver,
					AES_DECRYPT(UNHEX(BGL_QuotaUnder),MD5('dlffleoqkr!@#')) as BGL_QuotaUnder,
					AES_DECRYPT(UNHEX(BGL_QuotaOver),MD5('dlffleoqkr!@#')) as BGL_QuotaOver,
					AES_DECRYPT(UNHEX(BGL_ResultChoice),MD5('dlffleoqkr!@#')) as BGL_ResultChoice
					 
				FROM rjawmd WHERE BGL_Key = {$bgl_key}";
    //echo $que."<br>";
    $row1 = getRow($que);

    ####배당률 변경 여부 확인 하기##########################################################################################################
    if(in_array($row['BGL_ResultChoice'],array('Win','Lose','Draw'))){
        if($row[BGL_QuotaWin]==$row1[BGL_QuotaWin] && $row[BGL_QuotaDraw]==$row1[BGL_QuotaDraw] && $row[BGL_QuotaLose]==$row1[BGL_QuotaLose]){

        } else {
            echo "RATE(WC)";
        }
    }

    if(in_array($row['BGL_ResultChoice'],array('HandiWin','HandiLose'))){
        if($row[BGL_QuotaHandicap]==$row1[BGL_QuotaHandicap] && $row[BGL_QuotaHandiWin]==$row1[BGL_QuotaHandiWin] && $row[BGL_QuotaHandiLose]==$row1[BGL_QuotaHandiLose]){

        } else {
            echo "RATE(HC)";
        }
    }


    if(in_array($row['BGL_ResultChoice'],array('Under','Over'))){
        if(trim($row[BGL_QuotaUnderOver])==$row1[BGL_QuotaUnderOver] && trim($row[BGL_QuotaUnder])==$row1[BGL_QuotaUnder] && trim($row[BGL_QuotaOver])==$row1[BGL_QuotaOver]){

        } else {
            echo "RATE(UC)";
        }
    }
    ####배당률 변경 여부 확인 하기##########################################################################################################

    ####배팅 확인 ##########################################################################################################
    if($row['BGL_ResultChoice']!=$row1['BGL_ResultChoice']){
        echo "Choice";
    }
    ####배팅 확인 ##########################################################################################################
}

#진행 배팅 금액
function get_bet_ing_money()
{

    $startDate = date("Y-m-d",strtotime("-1 day"));

    $today = date("Y-m-d");

    //$where = " AND a.BG_Result = 'Await' AND BG_BuyDate BETWEEN '{$today} 00:00:00' AND '{$today} 23:59:59' AND b.M_Type = '1'";

    $where = " AND a.BG_Result = 'Await' AND BG_BuyDate BETWEEN '{$startDate} 00:00:00' AND '{$today} 23:59:59' AND b.M_Type = '1'";

    #진행배팅금액
    $sql = "SELECT BG_Key FROM buygame a, members b WHERE a.M_Key = b.M_Key $where ORDER BY BG_BuyDate ASC";
    //echo $sql;
    $arr = getArr($sql);
    if(count($arr)>0){
        foreach($arr as $list)
        {
            $que = "SELECT COUNT(*) FROM buygamelist WHERE BG_Key = {$list[BG_Key]} AND BGL_State = 'Fail'";
            $row = getRow($que);
            if(!$row[0]){
                $bgkey[] = $list[BG_Key];
            }
        }
    }
    $bgcount = count($bgkey);

    if($bgcount==1){
        $bgk = $bgkey[0];
    } else if($bgcount > 1){
        $bgk = implode(",",$bgkey);
    }

    if($bgcount>0){
        $que = "SELECT SUM(BG_BettingPrice) AS bp, SUM(BG_ForecastPrice) as bf FROM buygame WHERE BG_Key IN ($bgk)";
        //echo $que;
        $tot = getRow($que);

        echo number_format($tot[bp])."(".number_format($tot[bf]).")";
    }
}


#진행 당첨 금액
function get_hit_money()
{
    $startDate = date("Y-m-d",time()-60*60*24);
    $today = date("Y-m-d");

    $where = " AND BG_Result = 'Await' AND DATE_FORMAT(BG_BuyDate,'%Y-%m-%d') BETWEEN '{$startDate}' AND '{$today}' AND b.M_Type = '1'";

    #진행배팅금액
    $sql = "SELECT SUM(BG_ForecastPrice) as bp FROM buygame a, members b WHERE a.M_Key = b.M_Key AND b.M_Type ='1' $where ORDER BY BG_BuyDate ASC";
    //echo $sql;
    $tot = getRow($sql);

    echo number_format($tot[0]);
}

function not_league()
{
    $que = "SELECT COUNT(*) FROM gamelist WHERE GL_Key = 0 AND G_Team1 NOT IN ('세계증시 올킬 이벤트','2.스페셜 스타 -> 언오버 조합','1.승무패 스타 -> 보너스 조합') ";
    //echo $que;
    $row = getRow($que);
    if($row[0]>0){
        echo "<span style='color:#f00; font-size:13px;'>".$row[0]."</span>";
    } else {
        echo $row[0];
    }
}

#도메인 추출
function getHostName1($url)

{

    $value = strtolower(trim($url));

    if (preg_match('/^(?:(?:[a-z]+):\/\/)?((?:[a-z\d\-]{2,}\.)+[a-z]{2,})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i', $value))

    {

        preg_match('/([a-z\d\-]+(?:\.(?:asia|info|name|mobi|com|net|org|biz|tel|xxx|kr|co|so|me|eu|cc|or|pe|ne|re|tv|jp|tw)){1,2})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i', $value, $matches);

        $host = (!$matches[1]) ? $value : $matches[1];

    }

    return $host;

}
function cmp($a, $b)
{
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}



#추천인 배팅 또는 낙첨 포인트
function get_recommend_point($rkey)
{
    $que = "SELECT SUM(PI_Point) FROM pointinfo a, members b WHERE a.M_Key = b.M_Key AND PI_Type IN ('Betting','BettingFail') AND b.M_Recom = '{$rkey}' ";
    //echo $que;
    $row = getRow($que);
    echo number_format($row[0]).'P';

}

#추천인 실시간 롤링금액
function recommend_realtime($skey,$sid, $gubun)
{
    $que = "SELECT Shop_Pay_Type, Shop_Pay_Percent FROM members WHERE M_Key = {$skey}";
    //echo $que;
    $row = getRow($que);
    //print_r($row);
    $recom_type1 = $row[Shop_Pay_Type];

    //unset($row);

    $que = "SELECT M_Key FROM members WHERE (M_Recom = '{$sid}' OR M_Shop = '{$sid}') AND M_ID != '{$sid}' AND M_Type = '1' ";
    //echo $que;
    $arr = getArr($que);
    if(count($arr)>0){
        foreach($arr as $list){
            $mkey[] = $list[M_Key];
        }
    }

    if(count($arr)>1){
        $m = implode(",",$mkey);
    } else {
        $m = $mkey[0];
    }

    //echo $gubun."-".count($arr);
    if($gubun == 1 && count($arr)>0){
        switch($row[Shop_Pay_Type])
        {
            case '1':
                $que = "SELECT SUM(BG_BettingPrice) AS bp FROM buygame WHERE M_Key IN ({$m})";
                $rows = getRow($que);
                echo number_format($rows[bp]*($row[Shop_Pay_Percent]*0.01));
                break;
            case '2':
                #낙첨
                $que = "SELECT SUM(BG_BettingPrice) AS bp FROM buygame WHERE M_Key IN ({$m}) AND BG_Result = 'Fail'";
                //echo $que;
                $rows = getRow($que);
                echo number_format($rows[bp]*($row[Shop_Pay_Percent]*0.01));
                break;

            case '3':
                break;


        }
    } else {
        echo 0;
    }
}


function get_chu_point($id, $ckey, $where)
{
    $que = "SELECT 
					SUM( PI_Point ) 
				FROM 
					pointinfo
				WHERE 
					M_Key = {$id}
				AND 
					PI_Type =  'ChuBetFail'
				AND 
					Chu_Key = {$ckey}
					{$where}
				";

    //echo $que;
    $row = getRow($que);

    return $row[0];
}

#분수를 소수로 변환
function change_ratio($num)
{
    $tmp = explode("/",$num);
    $ratio = ($tmp[0]/$tmp[1])+1;
    return number_format($ratio,2,'.','');
}

function swal_move($msg, $url){
    echo '<script>swal("","'.$msg.'","warning"); setTimeout(function(){ location.href = "/'.$url.'/";},3000);</script>';
}

function swal_reload($msg){
    echo '<script>swal("","'.$msg.'","warning"); </script>';
}

//은행계좌번호 쪽지 발송
function send_bankinfo($SITECONFIG){
    $msg_bank = '은행명 : '.$SITECONFIG['I_BankName'].'/ '.$SITECONFIG['I_BankNum'].' / '.$SITECONFIG['I_BankOwner'].'입니다. ';
    $que  = "INSERT INTO message SET ";
    $que .= "M_Key = '{$_SESSION['S_Key']}', ";
    $que .= "message = '{$msg_bank}', ";
    $que .= "regDate = NOW() ";
    $res = setQry($que);
    return $res;
}

#회원 로그인 아이피 저장 및 중복된 아이디 저장하기
function member_ip_save_and_same_chk($ip, $id){
    $que = "SELECT COUNT(*) AS cnt FROM member_ip_list WHERE I_IP = '{$ip}'";
    $ct = getRow($que);
    if($ct[0]>0){
        $que = "SELECT * FROM member_ip_list WHERE I_IP = '{$ip}'";
        //echo $que."<br>";
        $rs = getRow($que);
        $id_array = explode(",",$rs['I_ID']);
        if(!in_array($id, $id_array)){
            $id_array[] = $id;
            $scnt = count($id_array);
            $id_join2 = implode(",",$id_array);
            $sql1 = "UPDATE member_ip_list SET I_ID = '{$id_join2}', I_SameCnt = '{$scnt}', I_Update = NOW() WHERE I_IP = '{$ip}'";
            setQry($sql1);
        }
    } else {
        $sql  = "INSERT INTO member_ip_list SET ";
        $sql .= "I_IP = '{$ip}', ";
        $sql .= "I_ID = '{$id}', ";
        $sql .= "I_Regdate = NOW() ";
        //echo $sql;
        setQry($sql);
    }

}

//타입별 미니게임 배당률 가져오기
function get_minigame_rate($type, $m){

    if($type == 'PB'){
        $rate = explode("|",$m['MG_Power']);
        $rate[] = explode("|",$m['MG_PowerP']);
    } else if($type == 'PBL'){
        $rate = explode("|",$m['MG_PwLadder']);
    }

    return $rate;
}

//출석일수
function get_glkey($league, $gikey){
    $que = "SELECT * FROM gameleague WHERE GL_KEY_IDX = '{$league['idx']}'";
    echo $que;
    $row = getRow($que);
    if(!$row['GL_KEY_IDX']){
        $sql  = "INSERT INTO gameleague SET ";
        $sql .= "GI_Key             = '{$gikey}', ";
        $sql .= "GL_KEY_IDX         = '{$league['idx']}', ";
        if(!empty($league['korName'])){
            $sql .= "GL_Type_Eng    = '{$league['name']}', ";
            $sql .= "GL_Type        = '{$league['korArea']}', ";
        } else {
            $sql .= "GL_Type        = '{$league['name']}', ";
            $sql .= "GL_Type_Eng    = '{$league['name']}', ";
        }

        $sql .= "GL_Area            = '{$league['name']}', ";
        $sql .= "GL_CountryCode     = '{$league['countryCode']}', ";
        $sql .= "GL_KorArea         = '{$league['KorName']}', ";
        $sql .= "GL_SrvName         = 'noimage.png' ";
        echo $sql;
        //$res = setQry($sql);

    }
}

function incluce_extend($gtype){
    $que = "SELECT R_Type FROM game_type_code WHERE Market_Id = '{$gtype}'";
    $row = getRow($que);
    echo ($row['R_Type']=='F_OT')?'[연장포함]':'';
}

function get_level_limited($lv){
    $que = "SELECT * FROM level_limited WHERE L_Level = '{$lv}' ";
    //echo $que;
    $row = getRow($que);
    return $row;
}
?>

