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

//$where = " 1 AND M_Level < 8 AND M_Level > 2 AND SUBSTRING(M_ShopTop,1,{$meminfo['M_Shop_Level']}) = '{$mtop}' AND M_ID != '{$meminfo['M_ID']}'";
$where = " 1 AND M_Level < 8 AND M_Level > 2 AND SUBSTRING(M_ShopTop,1,{$meminfo['M_Shop_Level']}) = '{$mtop}' ";

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
                <a href="./" class="active">총판현황</a>
                <a href="./analysis/?type=day">일별통계</a>
                <a href="./analysis/?type=month">월별통계</a>
                <!--<a href="/_go/renewal/mypage/recom_add.html">나를추천한회원</a>-->
                <!--<a href="/_go/renewal/mypage/recom_list.html">하부회원보기</a>-->
                <a href="/mypage/branch/bet/sports/?mkey=<?php echo $meminfo['M_Key'];?>&level=<?php echo $meminfo['M_Shop_Level'];?>">배팅내역</a>
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


                <div class="b_title2">
                    <span><a href="#second">하부총판[매장]현황</a></span> | 해당 총판[매장]의 요율을 설정할 수 있습니다.


                </div>
                <div class="btn_wrap">
                    <a href="javascript:;" style="width: 300px;" onclick="window.open('./add_branch.php?type=<?php echo $meminfo['M_ShopPayType'];?>&percent=<?php echo $meminfo['M_ShopPrecent'];?>','','left=400, top=100,width=400, height=900, scrollbar=no');">하부총판[매장]추가</a>
                </div>
                <table class="table-black big">
                    <thead>
                    <tr>
                        <td>번호</td>
                        <td width="5%">레벨</td>
                        <td width="*">매장아이디</td>
                        <td width="14%">요율(%)</td>
                        <td width="11%">보유머니</td>
                        <td width="11%">보유포인트</td>
                        <td>보유회원수</td>
                        <td>회원가입일</td>
                        <td>상태</td>
                        <td>관리</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $cnt = 0;
                    $que = "SELECT  *  FROM {$tb} WHERE {$where} {$order_by}  LIMIT {$start},{$view_article} ";
                    //echo $que;
                    $arr = getArr($que);
                    if(count($arr)>0){
                        foreach($arr as $rs) {
                            switch($rs['M_Shop_Level']){
                                case '1':
                                    $lv = '7LV';
                                    break;
                                case '2':
                                    $lv = '6LV';
                                    break;
                                case '3':
                                    $lv = '5LV';
                                    break;
                                case '4':
                                    $lv = '4LV';
                                    break;
                            }
                            ?>
                            <tr>
                                <td><?=($total_article-$cnt-(($_GET['page']-1)*$view_article))?></td>
                                <td><?php echo $lv;?></td>
                                <td style="text-align: left;"><?php echo get_parent_shop($rs['M_ID'],$rs['M_Shop_Level']);?></td>
                                <td><font class="mypage-grnfont" ><input type="text" id="id_<?php echo $cnt;?>" style=" text-align: right; padding-right:5px; border: none;" value="<?php echo $rs['M_ShopPrecent'];?>" size="5"></font> </td>
                                <td><font class="mypage-grnfont"><?php echo number_format($rs['M_Money']);?></font></td>
                                <td><font class="mypage-orgfont"><?php echo number_format($rs['M_Point']); ?></font></td>
                                <td><input type="button" onclick="location.href='./?mmid=<?php echo $rs['M_ID'];?>';" style="cursor: pointer;" value="<?php echo get_branch_member_cnt($rs['M_ID']);?>명"></td>
                                <td><?php echo substr($rs['M_RegistDate'],0,10);?></td>
                                <td><font class="mypage-redfont"><?php echo ($rs['M_Level']<10)?'정상':'정지'; ?></font></td>
                                <td><input type="button" name="percent_modify" value="수정" class="modify" data-mkey="<?php echo $rs['M_Key'];?>" data-cnt="<?php echo $cnt;?>"></td>
                            </tr>
                            <?php $cnt++; }} else { ?>
                        <tr><td colspan="10">등록된 매장이 없습니다.</td></tr>
                    <?php } ?>
                    </tbody>
                </table>

                <div class="paging_box">
                    <?php
                    if($total_article>0) {
                        include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
                    }
                    ?>
                </div>


                <?php if(!empty($mmid) ){ ?>
                    <div class="b_title2" id="second">
                        <span><?php echo $mmid;?> - 매장별 회원목록 </span>
                    </div>

                    <table class="table-black big">
                        <thead>
                        <tr>
                            <td>번호</td>
                            <td width="14%">아이디</td>
                            <td width="14%">닉네임</td>
                            <td width="11%">보유머니</td>
                            <td width="11%">보유포인트</td>
                            <td>회원가입일</td>
                            <td>최근로그인</td>
                            <td>정산내역</td>
                            <td>배팅내역</td>
                            <td>상태</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $cnt = 0;

                        $where = " 1 ";
                        if($meminfo['M_Shop_Level']==4){
                            $where .= " AND (M_Recom = '{$meminfo['M_ID']}' OR M_Recom_Code = '{$meminfo['M_ID']}') ";
                        }
                        if(!empty($mmid)){
                            $where .= " AND (M_Recom = '{$mmid}' OR M_Recom_Code = '{$mmid}') ";
                        }

                        /*if($grmc > 0){
                            $where .= " OR M_Recom = '{$meminfo['M_ID']}' ";
                        }*/
                        $que = "SELECT  *  FROM members WHERE  {$where} ";

                        //echo $que;
                        $arr = getArr($que);
                        if(count($arr)>0){
                            foreach($arr as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $cnt+1; ?></td>
                                    <td><?php echo $row['M_ID'];?></td>
                                    <td><?php echo $row['M_NICK'];?></td>
                                    <td><font class="mypage-grnfont"><?php echo number_format($row['M_Money']);?></font></td>
                                    <td><font class="mypage-orgfont"><?php echo number_format($row['M_Point']); ?></font></td>
                                    <td><?php echo substr($row['M_RegistDate'],0,10);?></td>
                                    <td><?php echo substr($row['M_LastAccessDate'],0,10);?></td>
                                    <td><a href="./?mmid=<?php echo $mmid;?>&search_id=<?php echo $row['M_ID'];?>" class="choose" style="background: linear-gradient(to top, #d0520b, #f77a32, #f9955b);color: #fff;margin: 5px; padding-left:5px; padding-right:5px;">보기</a></td>
                                    <td><a href="./bet/sports/?schText=<?php echo $row['M_ID'];?>&startDate=<?php echo date('Y-m-d',strtotime('-7 day'));?>&endDate=<?php echo date('Y-m-d');?>" class="choose" style="background: linear-gradient(to top, #d0520b, #f77a32, #f9955b);color: #fff;margin: 5px; padding-left:5px; padding-right:5px;">보기</a></td>
                                    <td><font class="mypage-redfont"><?php echo ($row['M_Level']<10)?'정상':'정지'; ?></font></td>

                                </tr>
                                <?php $cnt++; }} else { ?>
                            <tr><td colspan="9">등록된 회원이 없습니다.</td></tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php
                }
                if(!empty($search_id)){
                    $mem = getRow("SELECT * FROM members WHERE M_ID = '{$search_id}'");
                    $end_day = date('t');

                    if(empty($_REQUEST['startDate']))   $startDate = date('Y-m-').'01';
                    if(empty($_REQUEST['endDate']))     $endDate = date('Y-m-').$end_day;

                    ?>
                    <div class="b_title2" style="margin-top: 30px;" id="third">
                        <div style="float: left; margin:10px; font-size:22px;">
                            <span><?php echo $mem['M_NICK'];?> 회원님 정산상세 </span>
                        </div>
                        <form action="./" method="get">
                            <input type="hidden" name="mmid" value="<?php echo $mmid;?>">
                            <input type="hidden" name="search_id" value="<?php echo $search_id;?>">
                            <div style="float: right;">
                                <span>시작일 <input type="text" name="startDate" id="startDate" value="<?php echo  $startDate;?>"> ~ 종료일  <input type="text" name="endDate" id="endDate" value="<?php echo $endDate;?>"></span>
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
                        for($i=$startDate;$i<=$endDate;$i++){
                            $data = person_analysis($search_id, $i,$meminfo['M_ShopPayType'] );
                            $charge_total += $data['charge'];
                            $refund_total += $data['refund'];
                            $total_total += $data['total'];
                            $bpoint_total += $data['bpoint'];
                            $bet_total += $data['bet'];
                            $hit_total += $data['hit'];
                            $fail_total += $data['fail'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo $i;?>
                                </td>
                                <td style="text-align: right;"><?php echo number_format($data['charge']);?></td>
                                <td style="text-align: right;"><?php echo number_format($data['refund']); ?></td>
                                <td style="text-align: right;"><?php echo number_format($data['total']); ?></td>
                                <td style="text-align: right;"><?php echo number_format($data['bpoint']); ?></td>
                                <td style="text-align: right;"><?php echo number_format($data['bet']);?></td>
                                <td style="text-align: right;"><?php echo number_format($data['hit']);?></td>
                                <td style="text-align: right;"><?php echo number_format($data['fail']);?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td>총 합계</td>
                            <td style="text-align: right;"><?php echo number_format($charge_total);?>원</td>
                            <td style="text-align: right;"><?php echo number_format($refund_total);?>원</td>
                            <td style="text-align: right;"><?php echo number_format($total_total);?>원</td>
                            <td style="text-align: right;"><?php echo number_format($bpoint_total);?>원</td>
                            <td style="text-align: right;"><?php echo number_format($bet_total);?>원</td>
                            <td style="text-align: right;"><?php echo number_format($hit_total);?>원</td>
                            <td style="text-align: right;"><?php echo number_format($fail_total);?>원</td>
                        </tr>
                        </tbody>
                    </table>

                <?php } ?>
            </div> <!-- board_wrap -->

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
        $('#startDate, #endDate').datepicker();
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
            var scrollPosition = $("#third").offset().top;
            console.log(scrollPosition)
            $("html, body").animate({
                scrollTop: scrollPosition
            }, 500);
        },1000);

        <?php }  if(!empty($mmid) && empty($search_id)){ ?>
        setTimeout(function(){
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
