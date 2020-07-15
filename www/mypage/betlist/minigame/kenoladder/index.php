<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

if ( !$_SESSION['S_Key'] ) {
    swal_move('로그인이 필요한 페이지 입니다.', 'login');
}



$tb = " buygame a LEFT JOIN buygamelist b ON a.BG_Key = b.BG_Key LEFT JOIN gamelist_other c ON b.G_Key = c.G_Key LEFT JOIN gameleague d ON c.GL_Key = d.GL_Key ";
$view_article = 5; // 한화면에 나타날 게시물의 총 개수
if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
$start = ($_GET['page']-1)*$view_article;
$href = "&startDate={$_GET['startDate']}&turn={$_GET['turn']}";

if(empty($startDate)) $startDate = date("Y-m-d");
if(empty($endDate))   $endDate = date("Y-m-d");

$where = " 1 AND b.GL_Key IN (16,17,18,19,20) AND DATE_FORMAT(BG_BuyDate,'%Y-%m-%d') BETWEEN '{$startDate}' AND '{$endDate}' AND BG_Visible = 1 ";

if(!empty($turn)){
    //$where .= " AND G_Num2 = '{$turn}' ";
}

#성명으로 정렬시
$order_by = " ORDER BY BG_BuyDate DESC  ";

$query = "SELECT COUNT(*) FROM {$tb} WHERE {$where} {$order_by} ";
$row = getRow($query);
$total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함
?>
    <script>
        $(document).ready(function(){
            $("ol.login_st > li:nth-child(4)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">키노사다리 베팅내역</div>
                <div class="title2">KENO LADDER BETTING LIST</div>
            </div>

            <div class="sub-box">

                <div class="mypage_menu1">
                    <a href="/mypage/betlist/" class="active">베팅내역</a>
                    <a href="/mypage/charge/">충전내역</a>
                    <a href="/mypage/refund/">환전내역</a>
                    <a href="/mypage/point/exchange/list/">포인트내역</a>
                    <!--<a href="/mypage/recom/">총판관리</a>-->
                    <a href="/mypage/message/">쪽지관리</a>
                    <a href="/mypage/member/modify/">회원정보수정</a>
                </div>
                <div class="mypage_menu2">
                    <a href="/mypage/betlist/sports/">스포츠게임</a>
                    <a href="/mypage/betlist/casino/" >카지노</a>
                    <a href="/mypage/betlist/power/" class="active">미니게임</a>
                    <a href="/mypage/betlist/virtual/" >가상게임</a>
                </div>
                <div class="mypage_menu3">
                    <a href="/mypage/betlist/minigame/power/">파워볼</a>
                    <a href="/mypage/betlist/minigame/pwladder/">파워사다리</a>
                    <!--<a href="/mypage/betlist/minigame/">스피드키노</a>-->
                    <a href="/mypage/betlist/minigame/kenoladder/" class="active">키노사다리</a>
                </div>


                <div class="board_wrap">


                    <div class="mypage-day-search">
                        <div class="title">베팅기간</div>
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

                    <?php
                    if($total_article > 0){
                        $cnt = 0;
                        $que = "
                                SELECT 
                                    *
                                FROM 
                                    {$tb} 
                                WHERE 
                                     {$where}
                                     {$order_by}
                                LIMIT 
                                    $start, $view_article 
                               
                            ";

                        $arr = getArr($que);
                        foreach($arr as $rs){
                            if($rs['BG_Result']=='Success'){
                                $rseult_text = '적중';
                                $result_css = "shot";
                            } else if($rs['BG_Result']=='Fail'){
                                $rseult_text =  '미적중';
                                $result_css = "noshot";
                            } else if($rs['BG_Result']=='Cancel'){
                                $rseult_text =  '취소(적특)';
                                $result_css = "tk";
                            } else {
                                $rseult_text =  '진행중';
                                $result_css = "ing";
                            }

                            switch($rs['GL_Key']){

                            }
                            ?>
                            <table class="table-black mypage">
                                <thead>
                                <tr>
                                    <td rowspan="2">선택</td>
                                    <td>회차</td>
                                    <td width="260">승(홈)</td>
                                    <td>무</td>
                                    <td width="260">패(원정)</td>
                                    <td>점수</td>
                                    <td>선택</td>
                                    <td>결과</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="8" class="league_title">
                                        <img src="/img/league_5e1d24bfc56d4_1578968255.png" style="width:18px" /> &nbsp; <?php echo $rs['GL_Type'];?> &nbsp; - &nbsp; <?php echo date("m/d H:i",strtotime($rs['G_Datetime']));?>
                                    </td>
                                </tr>
                                <tr class="empty_line">
                                    <td rowspan="5" class="ckb_td"><input type="checkbox" class="chkbox" name="bgkey" value="<?php echo $rs['BG_Key'];?>"></td>
                                    <td colspan="7" class="empty_line"></td>
                                </tr>
                                <tr class="line">
                                    <td><?php echo $rs['G_Num'];?>회</td>
                                    <?php
                                    if($rs['GL_Key']==16){
                                        ?>
                                        <td class="<?php echo ($rs['BGL_ResultChoice']=='Odd')?'selected01':''; ?>">
                                    <span>
                                        <span>홀 [<?php echo $rs['G_Num'];?>회]</span>
                                        <em><?php echo $rs['G_QuotaOdd'];?></em>
                                    </span>
                                        </td>
                                        <td><span class="margin">VS</span></td>
                                        <td class="<?php echo ($rs['BGL_ResultChoice']=='Even')?'selected01':''; ?>">
                                    <span>
                                        <span><?php echo $rs['G_QuotaEven'];?></span>
                                        <em>[<?php echo $rs['G_Num'];?>회] 짝</em>
                                    </span>
                                        </td>
                                        <td><?php echo $rs['G_ResultScoreWin']; ?>:<?php echo $rs['G_ResultScoreLose']; ?></td>
                                        <td><?php echo ($rs['BGL_ResultChoice']=='Odd')?'홀':'짝'; ?></td>
                                        <td class="<?php echo $result_css;?>">
                                            <code>
                                                <?php
                                                echo $rseult_text;
                                                ?>
                                            </code>
                                        </td>
                                    <?php } else if($rs['GL_Key']==17){ ?>
                                        <td class="<?php echo ($rs['BGL_ResultChoice']=='Odd')?'selected01':''; ?>">
                                    <span>
                                        <span>좌 [<?php echo $rs['G_Num'];?>회]</span>
                                        <em><?php echo $rs['G_QuotaOdd'];?></em>
                                    </span>
                                        </td>
                                        <td><span class="margin">VS</span></td>
                                        <td class="<?php echo ($rs['BGL_ResultChoice']=='Even')?'selected01':''; ?>">
                                    <span>
                                        <span><?php echo $rs['G_QuotaEven'];?></span>
                                        <em>[<?php echo $rs['G_Num'];?>회] 우</em>
                                    </span>
                                        </td>
                                        <td><?php echo $rs['G_ResultScoreWin']; ?>:<?php echo $rs['G_ResultScoreLose']; ?></td>
                                        <td><?php echo ($rs['BGL_ResultChoice']=='Odd')?'좌':'우'; ?></td>
                                        <td class="<?php echo $result_css;?>">
                                            <code>
                                                <?php
                                                echo $rseult_text;
                                                ?>
                                            </code>
                                        </td>
                                    <?php } else if($rs['GL_Key']==18){ ?>
                                        <td class="<?php echo ($rs['BGL_ResultChoice']=='Odd')?'selected01':''; ?>">
                                    <span>
                                        <span>3줄 [<?php echo $rs['G_Num'];?>회]</span>
                                        <em><?php echo $rs['G_QuotaOdd'];?></em>
                                    </span>
                                        </td>
                                        <td><span class="margin">VS</span></td>
                                        <td class="<?php echo ($rs['BGL_ResultChoice']=='Even')?'selected01':''; ?>">
                                    <span>
                                        <span><?php echo $rs['G_QuotaEven'];?></span>
                                        <em>[<?php echo $rs['G_Num'];?>회] 4줄</em>
                                    </span>
                                        </td>
                                        <td><?php echo $rs['G_ResultScoreWin']; ?>:<?php echo $rs['G_ResultScoreLose']; ?></td>
                                        <td><?php echo ($rs['BGL_ResultChoice']=='Odd')?'3줄':'4줄'; ?></td>
                                        <td class="<?php echo $result_css;?>">
                                            <code>
                                                <?php
                                                echo $rseult_text;
                                                ?>
                                            </code>
                                        </td>
                                    <?php } else if($rs['GL_Key']==19){ ?>
                                        <td class="<?php echo ($rs['BGL_ResultChoice']=='Odd')?'selected01':''; ?>">
                                    <span>
                                        <span>좌3짝 [<?php echo $rs['G_Num'];?>회]</span>
                                        <em><?php echo $rs['G_QuotaOdd'];?></em>
                                    </span>
                                        </td>
                                        <td><span class="margin">VS</span></td>
                                        <td class="<?php echo ($rs['BGL_ResultChoice']=='Even')?'selected01':''; ?>">
                                    <span>
                                        <span><?php echo $rs['G_QuotaEven'];?></span>
                                        <em>[<?php echo $rs['G_Num'];?>회] 좌4홀</em>
                                    </span>
                                        </td>
                                        <td><?php echo $rs['G_ResultScoreWin']; ?>:<?php echo $rs['G_ResultScoreLose']; ?></td>
                                        <td><?php echo ($rs['BGL_ResultChoice']=='Odd')?'좌3홀':'좌4짝'; ?></td>
                                        <td class="<?php echo $result_css;?>">
                                            <code>
                                                <?php
                                                echo $rseult_text;
                                                ?>
                                            </code>
                                        </td>
                                    <?php } else if($rs['GL_Key']==20){ ?>
                                        <td class="<?php echo ($rs['BGL_ResultChoice']=='Odd')?'selected01':''; ?>">
                                    <span>
                                        <span>우3홀 [<?php echo $rs['G_Num'];?>회]</span>
                                        <em><?php echo $rs['G_QuotaOdd'];?></em>
                                    </span>
                                        </td>
                                        <td><span class="margin">VS</span></td>
                                        <td class="<?php echo ($rs['BGL_ResultChoice']=='Even')?'selected01':''; ?>">
                                    <span>
                                        <span><?php echo $rs['G_QuotaEven'];?></span>
                                        <em>[<?php echo $rs['G_Num'];?>회] 우4짝</em>
                                    </span>
                                        </td>
                                        <td><?php echo $rs['G_ResultScoreWin']; ?>:<?php echo $rs['G_ResultScoreLose']; ?></td>
                                        <td><?php echo ($rs['BGL_ResultChoice']=='Odd')?'우3홀':'우4짝'; ?></td>
                                        <td class="<?php echo $result_css;?>">
                                            <code>
                                                <?php
                                                echo $rseult_text;
                                                ?>
                                            </code>
                                        </td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <td colspan="7" class="table-left">
                                        <div>
									<span>
										베팅일시&nbsp; : &nbsp;<font><?php echo date("Y-m-d",strtotime($rs['BG_BuyDate']));?>(<?php echo $DISPWEEK[date("w",strtotime($rs['BG_BuyDate']))];?>) <?php echo date("H:i:s",strtotime($rs['BG_BuyDate']));?></font>&nbsp; / &nbsp;베팅금액&nbsp; : &nbsp;<font><?php echo number_format($rs['BG_BettingPrice']);?>원</font><BR/>
										배당률&nbsp; : &nbsp;<font><?php echo $rs['BG_TotalQuota']; ?></font>&nbsp; / &nbsp;적중예상금액&nbsp; : &nbsp;<font><?php echo number_format($rs['BG_ForecastPrice']); ?>원</font>&nbsp; / &nbsp;당첨금&nbsp; : &nbsp;<B><?php echo ($rs['BG_Result']=='Success')?number_format($rs['BG_ForecastPrice']):''; ?></B>
									</span>
                                            <em>
                                                <!--<B>베팅취소</B>-->
                                                <B>베팅삭제</B>
                                                <!--<font>내역올리기</font>-->
                                            </em>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <?php
                            $cnt++;
                        }} else {
                        ?>
                        <div style="width: 100%; display: flex; justify-content: center; margin:10px;">
                            <table><tr><td style="width: 100%;text-align: center;font-size:20px;" class="text-center">등록된 배팅내역이 없습니다.</td></tr></table>
                        </div>
                    <?php } ?>


                </div> <!-- board_wrap -->
                <?php
                if($total_article>0) {
                    include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
                }
                ?>
                <div class="betlist_bottom">
                    <div class="left">
                        <a class="choose" data-chk="N">전체선택</a>
                        <a class="del">선택삭제</a>
                    </div>
                    <div class="right">
                        <!--<a>게시판에 베팅내역 올리기</a>-->
                    </div>
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

            $('.choose').on('click',function(){
                var chk = $(this).data('chk');
                if(chk=='N'){
                    $('.chkbox').prop('checked',true);
                    $(this).data('chk','Y');
                    $(this).text('선택해제');
                } else {
                    $('.chkbox').prop('checked',false);
                    $(this).data('chk','N');
                    $(this).text('전체선택');
                }
            });
            $('.del').on('click',function(){
                var cnt = ct = 0;
                var idx_array = new Array();
                $('.chkbox').each(function(){
                    if($(this).is(':checked')==true){
                        idx_array[cnt] = $(this).val();
                        cnt++;
                    }
                });
                if(!cnt){
                    swal('','삭제할 번호를 선택해주세요.','warning');
                    return false;
                } else {
                    swal({
                        title: "삭제처리",
                        text: "배팅내역을 삭제하시겠습니까?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "확인",
                        cancelButtonText: "취소"
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url : '../proc/',
                                type : 'post',
                                dataType : 'json',
                                data : {mode : 'bettingDel', idx : idx_array},
                                success : function(res){
                                    if(res.flag == true){
                                        swal('','삭제 완료되었습니다.','success');
                                    } else {
                                        swal('','삭제처리시 오류가 발생했습니다. 잠시후에 다시 시도해주세요.'+res.error,'warning');
                                    }
                                    setTimeout(function(){ location.reload();},3000);
                                },complete:function(data){
                                },error:function(request, status, error){
                                    console.log(request);
                                    console.log(status);
                                    console.log(error);
                                }
                            });
                        }
                    });

                }
            });
        });


    </script>
<?php
include_once $root_path.'/include/footer.php';
?>