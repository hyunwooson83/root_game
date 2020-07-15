<?php
    session_save_path($_SERVER['DOCUMENT_ROOT'].'/session');
    session_start();

    ini_set('display_errors',1);
    header("Content-Type: text/html; charset=utf-8");

    $path = $_SERVER['DOCUMENT_ROOT'];

    include("$path/adodb5/adodb.inc.php");
    include("$path/include/mclen.lib.class.php");
    include("$path/include/mclen.lib24c.class.php");
    include_once($path."/lib/_lib.php");


    if($_SESSION['pcmode']=='Y'){
        //move('/main/');
    }
    $dsn = 'mysql://trend:jun1126k!@localhost/trend';
    $db = NewADOConnection($dsn);
    if (!$db) die("Connection failed");

    $db->debug = false;

    $db->Execute("SET NAMES utf8");

    $lib = new MCLEN_LIB( $db );

    $lib24c = new MCLEN_LIB24C( $db, $lib );


    //해당 IP 가 banlist 에 있는가 확인
    $ibresult = $db->Execute("select * from ipbans");
    $ibrow = $ibresult->FetchRow();


    $is_intercept_ip = false;
    $pattern = explode(",", trim($ibrow['banlist']));
    for ($i=0; $i<count($pattern); $i++) {
        $pattern[$i] = trim($pattern[$i]);
        if (empty($pattern[$i]))
            continue;

        $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
        $pat = "/^{$pattern[$i]}/";
        $is_intercept_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
        if ($is_intercept_ip)
            die ("접근 불가합니다.");
    }


    // 로그인시 회원정보 로드
    if ( !empty($_SESSION['S_Key']) ) $lib24c->Member_Info( $_SESSION['S_Key'] );

    // 현재 접속자 수 카운트
    //$lib24c->GetAccessUser();

    // 게임 종료 업데이트( 5분 간격 )
    //$lib24c->UpdateGameState();

    //환경설정 파일
    $result = $db->Execute("select * from siteconfig");
    $SITECONFIG = $result->FetchRow();


    $LEVELLIMITED = get_level_limited($_SESSION['S_Level']);
    //회원기본정보
    $result = $db->Execute("select * from members WHERE M_Key = '{$_SESSION['S_Key']}'");
    $meminfo = $result->FetchRow();

    //보여주기 레벨 표시
    $disp_mb_lv = array(9=>1,8=>2,7=>3,6=>4,5=>5,4=>6,3=>7);

    //미니게임 배당률
    $que = "SELECT * FROM minigame_rate WHERE MG_Idx = 1";
    $MINIGAME_RATE = getRow($que);

    //요일
    $DISPWEEK = array(0=>"일",1=>"월",2=>"화",3=>"수",4=>"목",5=>"금",6=>"토");

    //경기별 이미지
    $GAMEITEM = array(1=>'soccer',16=>'baseball',18=>'basketball',91=>'volleyball',17=>'hockey');

    $GAME_TYPE_TEXT = array('WDL'=>'승무패','UnderOver'=>'언오버','Handicap'=>'핸디캡');

    $ITEMICON = array(6046=>'soccer',154914=>'baseball',48242=>'basketball',154830=>'volleyball',35232=>'hockey',10000001=>'bonus');

    $mobile_action_base = "";
    $mAgent = array("iPhone","iPod","Android","Blackberry","Opera Mini", "Windows ce", "Nokia", "sony" );
    $chkMobile = false;
    for($i=0; $i<sizeof($mAgent); $i++){
        if(stripos( $_SERVER['HTTP_USER_AGENT'], $mAgent[$i] )){
            $chkMobile = true;
            break;
        }
    }

$mar = getRow("SELECT B_Subject FROM board WHERE B_ID = 'Notice' AND B_Type = 'Marquee' ORDER BY B_Key DESC LIMIT 1");
$MARQUEE = $mar['B_Subject'];

$CASINOCODE = array(1=>'VIVO',21=>'마이크로게이밍',28=>'드림게임',45=>'에볼루션',8=>'플레메틱',24=>'하바네로',33=>'제네시스',36=>'플레이선');
?>