<?php
$include_path = $_SERVER['DOCUMENT_ROOT'];
include $include_path."/include/common.php";


if ( !$_SESSION['S_ID'] ) $lib->AlertMSG( "정상적인 접속이 아닙니다.", "", 0, "parent");

    list($microtime, $timestamp) = explode(' ', microtime());
    $time = $timestamp . substr($microtime, 2, 3);

    $que = "SELECT * FROM members WHERE M_Key = '{$_SESSION['S_Key']}' ";
    //echo $que;
    $row = getRow($que);
    if(empty($row['M_CasinoID'])) {
        $user_id = make_casino_account();
    } else {
        $user_id = $row['M_CasinoID'];
    }

    if($user_id != false && !empty($game_type)) {
        $private = "C7F4CAD22CFEA245E98A6E790D4F72F0lang=kr&operatorID=beanpole&platform=html5&thirdPartyCode={$game_type}&time={$time}&userID={$user_id}&vendorID=0";
        $hash_code = md5($private);

        $ch = curl_init(); // 리소스 초기화

        $url = "http://api.krw.ximaxgames.com/wallet/api/getLobbyUrl";

        // 옵션 설정
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post 형태로 데이터를 전송할 경우
        $postdata = array(
            'lang' => 'kr'
            , 'operatorID' => 'beanpole'
            , 'platform' => 'html5'
            , 'thirdPartyCode' => $game_type
            , 'time' => $time
            , 'userID' => $user_id
            , 'vendorID' => '0'
            , 'hash' => $hash_code
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
        $output = curl_exec($ch); // 데이터 요청 후 수신

        curl_close($ch);  // 리소스 해제
        $json['flag'] = true;
        $json['error'] = '';
        $json['data'] = $output;
        echo $output;
    }

?>