<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

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
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(12)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">카지노 배팅내역</div>
                <div class="title2">CASINO BET LIST</div>
            </div>
            <div class="sub-box">

                <div class="mypage_menu2">
                    <a href="/mypage/betlist/sports/">스포츠게임</a>
                    <a href="/mypage/betlist/casino/" class="active" >카지노</a>
                    <a href="/mypage/betlist/power/">미니게임</a>
                    <a href="/mypage/betlist/virtual/" >가상게임</a>
                </div>


                <div class="board_wrap">


                    <!--<div class="b_title1">
                        <span>애드워드</span>님의 <span>충전정보</span>입니다.
                    </div>
                    <ul class="recom_state cnt4">
                        <li class="no_bg">
                            <div class="text1">잔여보유머니</div>
                            <var></var>
                            <div class="text2"><?php /*echo number_format($meminfo['M_Money']);*/?>원</div>
                            <a href="/money/charge/">보유머니 충전</a>
                        </li>
                        <li>
                            <div class="text1">오늘 충전합계</div>
                            <var></var>
                            <div class="text2 white"><?php /*echo number_format($row['SP']);*/?>원</div>
                        </li>
                        <li>
                            <div class="text1">당월 충전합계</div>
                            <var></var>
                            <div class="text2 white"><?php /*echo number_format($row['MP']);*/?>원</div>
                        </li>
                        <li>
                            <div class="text1">전월 충전합계</div>
                            <var></var>
                            <div class="text2 white"><?php /*echo number_format($row['PP']);*/?>원</div>
                        </li>
                    </ul>-->

                    <div class="mypage-day-search">
                        <div class="title">조회기간</div>
                        <div class="input_box">
                            <span class="active" data-day="<?php echo date("Y-m-d"); ?>">오늘</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-7 day")); ?>">1주일</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-15 day")); ?>">15일</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-1 month")); ?>">1개월</span>
                            <span data-day="<?php echo date("Y-m-d",strtotime("-3 month")); ?>">3개월</span> &nbsp;
                            <input type="text" name="startDate" id="startDate" value="<?php echo $startDate; ?>">&nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;<input type="text" name="endDate" id="endDate" value="<?php echo $endDate; ?>">&nbsp;
                            <code class="view">조회하기</code>
                        </div>
                    </div>

                    <table class="table-black table-mypage-moneylist big">
                        <thead>
                        <tr>
                            <td>번호</td>
                            <td>게임명</td>
                            <td>배팅금액[당첨금액]</td>
                            <td>처리일시</td>
                            <td>결과</td>
                            <td>처리상태</td>
                        </tr>
                        </thead>
                        <tbody>
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

                                }
                        ?>
                        <tr>
                            <td><?=$cnt+1?></td>
                            <td><?php echo $CASINOCODE[$data['history'][$i]['thirdParty']]; ?></td>
                            <td style="text-align: right;"><?php echo number_format($data['history'][$i]['amount']); ?>원</td>
                            <td style="text-align: center;"><font class="mypage-grnfont"><?php echo $data['history'][$i]['transTime']; ?></font></td>
                            <td><font class="mypage-<?php echo ($result=='WIN')?'grn':'red';?>font"><?php echo $result; ?></font></td>
                            <td><font class="mypage-grnfont">정상</font></td>
                        </tr>
                        <?php
                                $cnt++;
                            }
                            } else {
                            ?>
                        <tr><td colspan="7" class="text-center">등록된 내역이 없습니다.</td></tr>
                        <?php } ?>
                        </tbody>
                    </table>


                </div> <!-- board_wrap -->
                <div class="paging_box">
                    <a href="?page=<?php echo $prevPage;?>&amp;startDate=<?php echo $startDate;?>&amp;endDate=<?php echo $endDate;?>">◀</a>
                    <a href="?page=<?php echo $nextPage;?>&amp;startDate=<?php echo $startDate;?>&amp;endDate=<?php echo $endDate;?>">▶</a>

                </div>

            </div> <!-- sub-box -->

        </div> <!-- sub_wrap -->

    </div> <!-- sub_bg -->

    <script>
        $(document).ready(function(){
            $('code.view').on('click',function(){
                location.href = './?startDate='+$('#startDate').val()+'&endDate='+$('#endDate').val();
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

            $('.del').on('click',function(){
                var rkey = $(this).data('rkey');
                $.ajax({
                    type: 'get',
                    url: './proc/',
                    dataType: 'json',
                    data: {
                        'mode': 'chargeListDel'
                        , 'rkey': rkey
                    },
                    success: function (data) {
                        if (data.flag == true) {
                            swal('','충전내역이 삭제되었습니다.','success');
                            setTimeout(function(){ location.reload();},3000);
                        } else {
                            swal('', data.error, 'warning');
                        }
                    }
                });
            });
        });
    </script>

<?php
include_once $root_path.'/include/footer.php';
?>