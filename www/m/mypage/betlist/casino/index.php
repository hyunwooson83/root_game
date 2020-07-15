<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/header.php');

if ( !$_SESSION['S_Key'] ) {
    swal_move('로그인이 필요한 페이지 입니다.', 'login');
}

if(empty($startDate)) $startDate = date("Y-m-d");
if(empty($endDate))   $endDate = date("Y-m-d");

$startDate = $startDate;
$endDate = $endDate;


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





$startPage = $_REQUEST['startPage'];
if(empty($startPage))  $startPage = 1;
if($startPage>1){
    $curPage = $startPage - 1;
} else if($startPage == 1){
    $curPage = $startPage;
}

if($startPage > 0){
    $nextPage = $startPage + 1;
} else if($curPage == 1){
    $nextPage = '';
}

$pageSize = 30;
$stime = ' 00:00:00';
$etime = ' 23:59:59';

$st = $startDate.$stime;
$et = $endDate.$etime;

if($user_id != false) {
    ///wallet/api/getBetWinHistoryAll?endDate=2020-06-01 15:00:00&operatorID=beanpole&pageSize=5&pageStart=1&startDate=2020-06-01 17:00:00&time=1590998254883&userID=beaphppro&vendorID=0&hash=2c4a70b7b009d0cabf19d2ec3d1fe1ce
    //                                          endDate=2020-06-01 15:00:00&operatorID=beanpole&pageSize=5&pageStart=1&startDate=2020-06-01 17:00:00&thirdPartyCode=1&time=1591000019759&userID=beaphppro&vendorID=0
    $private = "C7F4CAD22CFEA245E98A6E790D4F72F0endDate={$et}&operatorID=beanpole&pageSize={$pageSize}&pageStart={$startPage}&startDate={$st}&time={$time}&userID={$user_id}&vendorID=0";
    $hash_code = md5($private);

    $ch = curl_init(); // 리소스 초기화

    $url = "http://api.krw.ximaxgames.com/wallet/api/getBetWinHistoryAll";

    // 옵션 설정
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // post 형태로 데이터를 전송할 경우
    $postdata = array(
        'endDate' => $et
    , 'operatorID' => 'beanpole'
    , 'pageSize' => $pageSize
    , 'pageStart' => $startPage
    , 'startDate' => $st
    , 'time' => $time
    , 'userID' => $user_id
    , 'vendorID' => '0'
    , 'hash' => $hash_code
    );


    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
    $output = curl_exec($ch); // 데이터 요청 후 수신

    curl_close($ch);  // 리소스 해제
    /*$json['flag'] = true;
    $json['error'] = '';
    $json['data'] = $output;
    echo $output;*/
    $data = objectToArray(json_decode($output));

    /*print_r($data);*/
}


if(!count($data['history']) && !empty($page)){
    echo '<script>alert("더이상 등록된 데이터가 없습니다.");window.history.back();</script>';
}

?>

    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>카지노 배팅내역</span>
                <em>CASINO BETTING LIST</em>
            </h1>
            <ul class="sub_title_category">
                <li onClick="location.href='/m/mypage/betlist/sports/'" class="active">스포츠</li>
                <li onClick="location.href='/m/mypage/betlist/casino/'">카지노</li>
                <li onClick="location.href='/m/mypage/betlist/minigame/power/'">미니게임</li>
                <li onClick="location.href='/m/mypage/betlist/virtual/'">가상게임</li>
            </ul>
        </div>

        <div class="sub_mypage_wrap">
            <!--<ul class="sub_member_top four">
                <li>
                    <em>잔여 보유머니</em>
                    <var></var>
                    <span class="hit"><?php /*echo number_format($meminfo['M_Money']);*/?>원</span>
                </li>
                <li>
                    <em>오늘 충전금합계</em>
                    <var></var>
                    <span class="today"><?php /*echo number_format($row['SP']);*/?>원</span>
                </li>
                <li>
                    <em>당월 충전금합계</em>
                    <var></var>
                    <span class="month"><?php /*echo number_format($row['MP']);*/?>원</span>
                </li>
                <li>
                    <em>전월 충전금합계</em>
                    <var></var>
                    <span class="give"><?php /*echo number_format($row['PP']);*/?>원</span>
                </li>
            </ul>-->

            <div class="sub_searchbox">
                <div>
                    <h1>
                        <input type="text" class="style_input date" name="startDate" id="startDate" value="<?php echo $startDate; ?>">
                        &nbsp;&nbsp;~&nbsp;&nbsp;
                        <input type="text" class="style_input date" name="endDate" id="endDate" value="<?php echo $endDate; ?>">
                        <ol>
                            <li class="active" data-day="<?php echo date("Y-m-d"); ?>">오늘</li>
                            <li data-day="<?php echo date("Y-m-d",strtotime("-7 day")); ?>">1주일</li>
                            <li data-day="<?php echo date("Y-m-d",strtotime("-15 day")); ?>">15일</li>
                        </ol>
                    </h1>
                    <div class="sub_searchbox_btnbox">
                        <a href="javascript:;" class="style_btn_confirm view">검색하기</a>
                    </div>
                </div>
            </div>

            <ul class="sub_cash_list">
                <?php
                $cnt = 0;
                $total_article = count($data['history']);


                if($pageSize > $total_article){
                    $pageSize = $total_article;
                }

                if($total_article>0){
                    for($i=0;$i<count($data['history']);$i++){
                        $result = "LOSE";
                        if($data['history'][$i]['transType'] == 'WIN' && $data['history'][$i]['amount'] > 0){
                            $result = "WIN";
                            $g_state_css = "shot";
                        }
                        ?>
                        <li>
                            <em>배팅금액[당첨금액] <b><?php echo number_format($data['history'][$i]['amount']); ?>원</b></em>
                            <em>게임명 <b><?php echo $CASINOCODE[$data['history'][$i]['thirdParty']]; ?></b></em>
                            <font>처리일시 : <?php echo $data['history'][$i]['transTime']; ?></font>
                            <span class="<?php echo $g_state_css;?>" style="font-size:12px;"><?php echo $result; ?></span>
                        </li>
                        <?php
                        $cnt++;
                    }} else {
                    ?>
                    <ul><li>등록된 내역이 없습니다.</li></ul>
                <?php } ?>
            </ul>
            <?php
            if($total_article>0) {
                include_once($_SERVER['DOCUMENT_ROOT'] . "/m/lib/page.php");
            }
            ?>

        </div>

    </div>
    <script>
        $(document).ready(function(){
            $('a.view').on('click',function(){
                location.href = './?startDate='+$('#startDate').val()+'&endDate='+$('#endDate').val();
            });
            $('.input_box > span').on('click',function(){
                var startDay  = $(this).data("day");
                $('input[name="startDate"]').val(startDay);
            });
            $('.input_box > span').on('click',function(){
                var startDay  = $(this).data("day");
                $('input[name="startDate"]').val(startDay);
            });
            $.datepicker.setDefaults({
                dateFormat: 'yy-mm-dd',
                prevText: '이전 달',
                nextText: '다음 달',
                monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                dayNames: ['일', '월', '화', '수', '목', '금', '토'],
                dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
                dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
                showMonthAfterYear: true,
                yearSuffix: '년'
            });
            $( '#startDate,#endDate').datepicker();
        });
    </script>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>