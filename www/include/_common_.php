<?
	session_start();
	header("Content-Type: text/html; charset=utf-8");
	$path = "D:/APM_Setup/htdocs/1.ozbet";

  define('FILE_FOLDER'        , $path."\\uploads\\");
  //define('FILE_FOLDER'        , $path."/uploads/");

  include("$path/adodb5/adodb.inc.php");
  include("$path/include/mclen.lib.class.php");
  include("$path/include/mclen.lib24c.class.php");
  include("$path/editor/popups/class.RainFile.php");
  print_r($_SERVER);
  $dsn = 'mysql://root:aiyonet2008com@localhost/24cbet2_2';
  $db = NewADOConnection($dsn);
  if (!$db) die("Connection failed");

  $db->debug = false;

  $db->Execute("SET NAMES utf8");

  $lib = new MCLEN_LIB( $db );
  $lib24c = new MCLEN_LIB24C( $db, $lib );
  $rainedit = new RainFile("$path/editor/uploads","$path/editor/uploads");
  
  //해당 IP 가 banlist 에 있는가 확인
  $ibresult = $db->Execute("select * from ipbans");
  $ibrow = $ibresult->FetchRow();
    $is_intercept_ip = false;
    $pattern = explode(",", trim($ibrow[banlist]));
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
  if ( $_SESSION[S_Key] ) $lib24c->Member_Info( $_SESSION[S_Key] );

  // 현재 접속자 수 카운트
  $lib24c->GetAccessUser();

  // 게임 종료 업데이트( 5분 간격 )
  $lib24c->UpdateGameState();
?>
