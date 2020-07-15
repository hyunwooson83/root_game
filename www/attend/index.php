<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    $thisyear = date('Y'); // 4자리 연도
    $thismonth = date('n'); // 0을 포함하지 않는 월
    $today = date('j'); // 0을 포함하지 않는 일

    //------ $year, $month 값이 없으면 현재 날짜
    $year = isset($_GET['year']) ? $_GET['year'] : $thisyear;
    $month = isset($_GET['month']) ? $_GET['month'] : $thismonth;
    $day = isset($_GET['day']) ? $_GET['day'] : $today;

    $_REQUEST['year'] = $year;
    $_REQUEST['month'] = $month;

    $this_month = date("m");
    $que = "SELECT COUNT(*) AS attend_cnt FROM attend WHERE DATE_FORMAT(A_Date,'%m') = '{$this_month}' AND M_Key = '{$_SESSION['S_Key']}'";
    $attend = getRow($que);

    $que = "SELECT * FROM attend WHERE DATE_FORMAT(A_Date,'%m') = '{$this_month}' AND M_Key = '{$_SESSION['S_Key']}'";
    $arr = getArr($que);
    if(count($arr)>0) {
        foreach ($arr as $list) {
            $chk_date[] = $list['A_Date'];
        }
    }
    if(count($chk_date)>0){
        $lst = implode(",", $chk_date);
    }

    $prev_month = $month - 1;
    $next_month = $month + 1;
    $prev_year = $next_year = $year;
    if ($month == 1) {
        $prev_month = 12;
        $prev_year = $year - 1;
    } else if ($month == 12) {
        $next_month = 1;
        $next_year = $year + 1;
    }
    $preyear = $year - 1;
    $nextyear = $year + 1;

    $predate = date("Y-m-d", mktime(0, 0, 0, $month - 1, 1, $year));
    $nextdate = date("Y-m-d", mktime(0, 0, 0, $month + 1, 1, $year));

    // 1. 총일수 구하기
    $max_day = date('t', mktime(0, 0, 0, $month, 1, $year)); // 해당월의 마지막 날짜
    //echo '총요일수'.$max_day.'<br />';

    // 2. 시작요일 구하기
    $start_week = date("w", mktime(0, 0, 0, $month, 1, $year)); // 일요일 0, 토요일 6

    // 3. 총 몇 주인지 구하기
    $total_week = ceil(($max_day + $start_week) / 7);

    // 4. 마지막 요일 구하기
    $last_week = date('w', mktime(0, 0, 0, $month, $max_day, $year));



    $today_attend = date("Y-m-d");
    $que = "SELECT SUM(MI_Money) AS chargeMoney FROM moneyinfo WHERE M_Key = '{$_SESSION['S_Key']}' AND MI_Type = 'Charge' AND DATE_FORMAT(MI_RegDate,'%Y-%m-%d') = '{$today_attend}'";
    $att = getRow($que);

?>

<div class="sub-box attend_bg">
    <div class="attend_wrap">
        <div class="attend_title">
            <div class="text1">출석부</div>
            <div class="text2">ATTENDANCE</div>
            <span class="text3"></span>
        </div>
        <div class="attend_calendar">
            <div class="calendar_top">
                <a class="prev"></a>
                <div class="now"><span><?php echo ($month<10)?'0'.$month:$month; ?></span><code><?php echo $year;?></code></div>
                <a class="next"></a>
            </div>
            <?php
                $day=1;
            ?>
            <ul class="calendar_head">
                <li class="name red">일</li>
                <li class="name">월</li>
                <li class="name">화</li>
                <li class="name">수</li>
                <li class="name">목</li>
                <li class="name">금</li>
                <li class="name blue">토</li>
            </ul>
            <ul>
                <?php
                for($i=1;$i<=$start_week;$i++){
                    ?>
                    <li class=" prev "></li>
                <?php } ?>
                <?php

                if($month<10){
                    $month = '0'.$month;
                }
                $today = $year."-".$month."-";

                for($i=1;$i<=$max_day;$i++){

                    $day = ($i<10)?'0'.$i:$i;
                    $todayday = $today.$day;
                    if(count($chk_date)>0) {
                        if (in_array($todayday, $chk_date)) {
                            $chk_class = "ok";
                        } else {
                            $chk_class = "prev";
                        }
                    } else {
                        $chk_class = "white";
                    }
                    ?>
                    <li class=" <?php echo $chk_class; ?> "><span><?php echo $i; ?></span></li>
                <?php } ?>
            </ul>
        </div>
        <div class="attend_ment">
            <span><?php echo $year; ?>년 <?php echo $month; ?>월은 총 <code><?php echo $attend['attend_cnt']; ?>일</code> 출석 하였습니다.</span>
            <a class="cal_btn" id="checkIn"></a>
        </div>
    </div> <!-- ATTEND WRAP -->
</div> <!-- SUB-BOX -->






<script>
    $('#checkIn').on('click',function(){
        var jsonurl = './proc/';
        var todayChargeMoney = <?php echo $SITECONFIG['attend_money'];?>;
        var mychargemoney = <?php echo ($att['chargeMoney']>0)?$att['chargeMoney']:0;?>;

        if(todayChargeMoney > mychargemoney){
            swal('','당일충전 금액이 '+todayChargeMoney+' 이상 회원만 출석체크가 가능합니다.','warning');
        } else {
            $.ajax({
                type: "POST",
                url: jsonurl,
                dataType: "JSON",
                data: {
                    "month": '<?php echo date("m"); ?>'
                },
                success: function (data) {
                    if (data.flag) {
                        swal({
                            text: "출석체크를 완료하였습니다.",
                            type: "success",
                            confirmButtonText: "확인",
                        })
                            .then((isConfirm) => {
                                if (isConfirm) {
                                    location.reload(true);
                                }
                            });
                    } else {
                        swal({
                            text: "이미 출석체크 하셨습니다.",
                            type: "info",
                            confirmButtonText: "확인",
                        })
                            .then((isConfirm) => {
                                if (isConfirm) {
                                    location.reload(true);
                                }
                            });
                    }
                },
                complete: function (data) {
                },
                error: function (xhr, status, error) {
                    var err = status + ' \r\n' + error;
                }
            });
        }
    });
</script>
<?php
include_once $root_path.'/include/footer.php';
?>

