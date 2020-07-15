<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

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
?>

    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>출석부</span>
                <em>ATTENDANCE</em>
            </h1>
        </div>

        <div class="sub_mypage_wrap">
            <div class="att_box">
                <div class="att_head">
                    <div>
                        <h1><?php echo ($month<10)?'0'.$month:$month; ?></h1>
                        <h2><?php echo $year;?></h2>
                    </div>
                    <span class="left"><img src="/mobile/img/att_left.png" /></span>
                    <span class="right"><img src="/mobile/img/att_right.png" /></span>
                </div>
                <div class="att_cal">
                    <ul class="week">
                        <li>일</li>
                        <li>월</li>
                        <li>화</li>
                        <li>수</li>
                        <li>목</li>
                        <li>금</li>
                        <li>토</li>
                    </ul>
                    <ul>
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
                                    $chk_class = "today";
                                } else {
                                    $chk_class = "pass";
                                }
                            } else {
                                $chk_class = "white";
                            }
                            ?>
                        <li class="<?php echo $chk_class; ?>"><?php echo $i; ?></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="att_btn" id="checkIn">출석하기</div>
                <h1><?php echo $year; ?>년 <?php echo $month; ?>월은 총 <span><?php echo $attend['attend_cnt']; ?></span>일 출석하였습니다.</h1>
            </div>
        </div>

    </div>
    <script>
        $('#checkIn').on('click',function(){
            var jsonurl = './proc/';

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
                            type : "success",
                            confirmButtonText: "확인",
                        })
                            .then((isConfirm)=> {
                                if (isConfirm) {
                                    location.reload(true);
                                }
                            });
                    } else {
                        swal({
                            text: "이미 출석체크 하셨습니다.",
                            type : "info",
                            confirmButtonText: "확인",
                        })
                            .then((isConfirm)=> {
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
        });
    </script>
<?php
include_once $root_path.'/include/footer.php';
?>