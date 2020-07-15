<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

if($_SESSION['S_Level'] < 3){
    swal_move('총판 회원만 이용가능한 페이지 입니다.', '/');
}


if($meminfo['M_Shop_Level']<4) {
    //echo $meminfo['M_ShopTop'];
    $mtop = substr($meminfo['M_ShopTop'], 0, $meminfo['M_Shop_Level']);
} else if($meminfo['M_Shop_Level']==1){
    $mtop = substr($meminfo['M_ShopTop'], 1, 1);
}

$tb = "members";

$view_article = 8; // 한화면에 나타날 게시물의 총 개수
if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
$start = ($_GET['page']-1)*$view_article;
$href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}&startDate={$_GET['startDate']}&endDate={$_GET['endDate']}";

$where = " 1 AND M_Level < 8 AND M_Level > 2 AND SUBSTRING(M_ShopTop,1,{$meminfo['M_Shop_Level']}) = '{$mtop}' AND M_ID != '{$meminfo['M_ID']}'";

if(!empty($branch_lv))  $where .= " AND M_Shop_Level = '{$branch_lv}' ";
else                    $where .= "";
if(!empty($branch_id))  $where .= " AND M_ID = '{$branch_id}' ";


#성명으로 정렬시
$order_by = " ORDER BY M_Shop_Level ASC ";


$query = "SELECT COUNT(*) FROM {$tb} WHERE {$where}   ";
//echo $query;
$row = getRow($query);
$total_article = $row[0];



if($meminfo['M_Shop_Level']==1){
    $topid = 'TOP';
} else if($meminfo['M_Shop_Level']>1){
    $topid = $meminfo['M_ShopParentID'];
}

?>

<div class="sub_bg">
    <div class="sub_wrap type2">
        <div class="sub_title">
            <div class="title1">총판현황</div>
            <div class="title2">RECOMMEND STATE</div>
        </div>
        <div class="sub-box">
            <div class="mypage_menu2">
                <a href="/mypage/branch/">총판현황</a>
                <a href="/mypage/branch/analysis/?type=day" <?php echo ($type=='day')?' class="active"':'';?>>일별통계</a>
                <a href="/mypage/branch/analysis/?type=month" <?php echo ($type=='month')?' class="active"':'';?>>월별통계</a>
                <a href="/mypage/branch/bet/sports/?mkey=<?php echo $meminfo['M_Key'];?>&level=<?php echo $meminfo['M_Shop_Level'];?>">배팅내역</a>
                <!--<a href="/mypage/branch/bet/sports/">배팅내역</a>-->
                <!--<a>승패정산보고서</a>-->
                <!--<a href="/mypage/branch/pay/">정산내역</a>-->
            </div>

            <div class="board_wrap">
                <div class="b_title1">
                    <span>애드워드</span>님의 <span>가입일</span>은 <span>2016년 12월 01일</span>이며, <span>현재요율</span>은 <em>0.5%</em>입니다.
                </div>
                <ul class="recom_state">
                    <li>
                        <div class="text1">상위총판</div>
                        <var></var>
                        <div class="text2"><?php echo $topid;?></div>
                    </li>
                    <li>
                        <div class="text1">회원수</div>
                        <var></var>
                        <div class="text2"><?php $grmc = get_recom_member_cnt($meminfo['M_ID']); echo $grmc; ?>명</div>
                    </li>
                    <li>
                        <div class="text1">지급구분</div>
                        <var></var>
                        <div class="text2"><?php echo ($meminfo['M_ShopPayType']=='R')?'롤링':'루징';?></div>
                    </li>
                    <li>
                        <div class="text1">나의요율</div>
                        <var></var>
                        <div class="text2"><?php echo $meminfo['M_ShopPrecent'];?>%</div>
                    </li>
                    <!--<li>
                        <div class="text1">나의요율(실시간)</div>
                        <var></var>
                        <div class="text2">0.5%</div>
                    </li>-->
                    <!--<li>
                        <div class="text1">정산하기</div>
                        <var></var>
                        <div class="text2" onclick="location.href='./pay/';">정산시작</div>
                    </li>-->
                    <!--<li>
                        <div class="text1">미처리</div>
                        <var></var>
                        <div class="text2">2건</div>
                    </li>-->
                </ul>


                <!--일별통계 시작 -->
                <?php
                if($type == 'day'){
                    ?>
                    <div class="b_title2">
                        <span><a>일별통계</a></span>
                    </div>
                    <div class="btn_wrap">
                        <?php
                        $end_day = date('t');
                        if(empty($_REQUEST['bstartDate']))   $bstartDate = date('Y-m-').'01';
                        if(empty($_REQUEST['bendDate']))     $bendDate = date('Y-m-').$end_day;

                        //가져올 상점들의 아이디를 구한다.
                        $que = "SELECT  M_ID  FROM {$tb} WHERE {$where} {$order_by}  ";
                        //echo $que;
                        $arr = getArr($que);
                        if(count($arr)>0) {
                            foreach ($arr as $rs) {
                                $bid[] = $rs['M_ID'];
                            }
                        }
                        $bid[] = $_SESSION['S_ID'];
                        $bid = implode("','",$bid);

                        $end_day = date('t');
                        ?>
                        <div class="b_title2" style="margin-top: 30px;" id="">
                            <form action="./" method="get">
                                <input type="hidden" name="mmid" value="<?php echo $mmid;?>">
                                <input type="hidden" name="type" value="day">
                                <input type="hidden" name="search_id" value="<?php echo $search_id;?>">
                                <div style="float: right;">
                                    <span>시작일 <input type="text" name="bstartDate" id="bstartDate" value="<?php echo  $bstartDate;?>"> ~ 종료일  <input type="text" name="bendDate" id="bendDate" value="<?php echo $bendDate;?>"></span>
                                    <input type="submit" value="검색" >
                                </div>
                            </form>
                        </div>

                        <table class="table-black big">
                            <thead>
                            <tr>
                                <td>날짜</td>
                                <td style="width: 12%">충전</td>
                                <td style="width: 12%">환전</td>
                                <td style="width: 12%">수익</td>
                                <td>수수료</td>
                                <td style="width: 12%">배팅금액</td>
                                <td style="width: 12%">당첨금액</td>
                                <td style="width: 12%">낙첨금액</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            for($i=$bstartDate;$i<=$bendDate;$i++){
                                $data = branch_analysis($bid,$i, 'day',$meminfo['M_ShopPayType']);
                                $charge_total += $data['charge'];
                                $refund_total += $data['refund'];
                                $total_total += $data['total'];
                                $bpoint_total += $data['bpoint'];
                                $bet_total += $data['bet'];
                                $hit_total += $data['hit'];
                                $fail_total += $data['fail'];
                                ?>
                                <tr>
                                    <td><?php echo $i;?></td>
                                    <td><?php echo number_format($data['charge']);?></td>
                                    <td><?php echo number_format($data['refund']); ?></td>
                                    <td><?php echo number_format($data['total']); ?></td>
                                    <td><?php echo number_format($data['bpoint']); ?></td>
                                    <td><?php echo number_format($data['bet']);?></td>
                                    <td><?php echo number_format($data['hit']);?></td>
                                    <td><?php echo number_format($data['fail']);?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td>합계</td>
                                <td><?php echo number_format($charge_total);?>원</td>
                                <td><?php echo number_format($refund_total); ?>원</td>
                                <td><?php echo number_format($total_total); ?>원</td>
                                <td><?php echo number_format($bpoint_total); ?>원</td>
                                <td><?php echo number_format($bet_total);?>원</td>
                                <td><?php echo number_format($hit_total);?>원</td>
                                <td><?php echo number_format($fail_total);?>원</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--입렬통계끝-->

                <?php } else { ?>
                    <!--일별통계 시작 -->
                    <div class="b_title2">
                        <span><a>월별통계</a></span>
                    </div>
                    <div class="btn_wrap">
                        <?php
                        if(empty($month))  $month = date('Y');
                        $que = "SELECT  M_ID  FROM {$tb} WHERE {$where} {$order_by}  ";
                        echo $que;
                        $arr = getArr($que);
                        if(count($arr)>0) {
                            foreach ($arr as $rs) {
                                $bid[] = $rs['M_ID'];
                            }
                        }
                        $bid[] = $_SESSION['S_ID'];
                        $bid = implode("','",$bid);
                        ?>
                        <div class="b_title2" style="margin-top: 30px;" id="">
                            <form action="./" method="get">
                                <input type="hidden" name="type" value="month">
                                <input type="hidden" name="mmid" value="<?php echo $mmid;?>">
                                <input type="hidden" name="search_id" value="<?php echo $search_id;?>">
                                <div style="float: right;">
                                <span>
                                    <select name="month" id="month">
                                        <?php for($j=2020;$j<=2021;$j++){ ?>
                                            <option value="<?php echo $j;?>" <?php echo ($j==$month)?'selected':''; ?>><?php echo $j;?>년</option>
                                        <?php } ?>
                                    </select>
                                <input type="submit" value="검색" >
                                </div>
                            </form>
                        </div>

                        <table class="table-black big">
                            <thead>
                            <tr>
                                <td>날짜</td>
                                <td style="width: 12%">충전</td>
                                <td style="width: 12%">환전</td>
                                <td style="width: 12%">수익</td>
                                <td>수수료</td>
                                <td style="width: 12%">배팅금액</td>
                                <td style="width: 12%">당첨금액</td>
                                <td style="width: 12%">낙첨금액</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $charge_total = 0;
                            $refund_total = 0;
                            $total_total = 0;
                            $bpoint_total = 0;
                            $bet_total = 0;
                            $hit_total = 0;
                            $fail_total = 0;
                            for($i=1;$i<=12;$i++){
                                $mn = ($i<10)?'0'.$i:$i;
                                $month1 = $month.'-'.$mn;
                                $data = branch_analysis($bid,$month1, 'month',$meminfo['M_ShopPayType']);
                                $charge_total += $data['charge'];
                                $refund_total += $data['refund'];
                                $total_total += $data['total'];
                                $bpoint_total += $data['bpoint'];
                                $bet_total += $data['bet'];
                                $hit_total += $data['hit'];
                                $fail_total += $data['fail'];
                                ?>
                                <tr>
                                    <td><?php echo date('Y년 ').$i;?>월</td>
                                    <td><?php echo number_format($data['charge']);?></td>
                                    <td><?php echo number_format($data['refund']); ?></td>
                                    <td><?php echo number_format($data['total']); ?></td>
                                    <td><?php echo number_format($data['bpoint']); ?></td>
                                    <td><?php echo number_format($data['bet']);?></td>
                                    <td><?php echo number_format($data['hit']);?></td>
                                    <td><?php echo number_format($data['fail']);?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td>합계</td>
                                <td><?php echo number_format($charge_total);?>원</td>
                                <td><?php echo number_format($refund_total); ?>원</td>
                                <td><?php echo number_format($total_total); ?>원</td>
                                <td><?php echo number_format($bpoint_total); ?>원</td>
                                <td><?php echo number_format($bet_total);?>원</td>
                                <td><?php echo number_format($hit_total);?>원</td>
                                <td><?php echo number_format($fail_total);?>원</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
                <!--입렬통계끝-->
            </div> <!-- sub-box -->

        </div> <!-- sub_wrap -->

    </div> <!-- sub_bg -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>
    <script>
        $.datepicker.setDefaults({
            dateFormat: 'yymmdd',
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

        $(function() {
            $('#startDate, #endDate,#bstartDate, #bendDate').datepicker();
        });

    </script>




    <script>

        $(document).ready(function(){

            $('#manual-ajax').click(function(event) {

                event.preventDefault();
                this.blur(); // Manually remove focus from clicked link.
                $.get(this.href, function(html) {
                    $(html).appendTo('body').modal();
                });
            });

            $('.modify').on('click',function(){
                var mkey = $(this).data('mkey');
                var per = $('#id_'+$(this).data('cnt')).val();

                $.ajax({
                    type : 'post',
                    url : './proc/',
                    dataType : 'json',
                    data : {'mode':'changePer','mkey':mkey,'per':per},
                    success : function(data){
                        if(data.flag == true){
                            swal('','정상적으로 수정 되었습니다.','success');
                        } else {
                            swal('','등록시 오류가 발생했습니다.'+data.error,'warning');
                        }
                    }
                });
            });



            <?php if(!empty($mmid) && !empty($search_id)){ ?>
            setTimeout(function(){
                console.log('a')
                var scrollPosition = $("#third").offset().top;
                console.log(scrollPosition)
                $("html, body").animate({
                    scrollTop: scrollPosition
                }, 500);
            },1000);

            <?php }  if(!empty($mmid) && empty($search_id)){ ?>
            setTimeout(function(){
                console.log('a')
                var scrollPosition = $("#second").offset().top;
                console.log(scrollPosition)
                $("html, body").animate({
                    scrollTop: scrollPosition
                }, 500);
            },1000);

            <?php } ?>
        });
    </script>
    <?php
    include_once $root_path.'/include/footer.php';
    ?>
