<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

if ( !$_SESSION['S_Key'] ) {
    swal_move('로그인이 필요한 페이지 입니다.', 'login');
}



$tb = " buygame a LEFT JOIN buygamelist b ON a.BG_Key = b.BG_Key LEFT JOIN gamelist_other c ON b.G_Key = c.G_Key LEFT JOIN gameleague d ON c.GL_Key = d.GL_Key LEFT JOIN gameitem e ON d.GI_Key = e.GI_Key ";
$view_article = 15; // 한화면에 나타날 게시물의 총 개수
if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
$start = ($_GET['page']-1)*$view_article;
$href = "&startDate={$_GET['startDate']}&turn={$_GET['turn']}";

if(empty($startDate)) $startDate = date("Y-m-d");
if(empty($endDate))   $endDate = date("Y-m-d");

$where = " 1 AND c.GL_Key IN (16,17,18,19,20) AND DATE_FORMAT(BG_BuyDate,'%Y-%m-%d') BETWEEN '{$startDate}' AND '{$endDate}' AND BG_Visible = 1 AND b.M_Key = '{$_SESSION['S_Key']}' ";

if(!empty($turn)){
    //$where .= " AND G_Num2 = '{$turn}' ";
}

#성명으로 정렬시
$order_by = " ORDER BY BG_BuyDate DESC  ";

$query = "SELECT COUNT(*) FROM {$tb} WHERE {$where} {$order_by} ";
$row = getRow($query);
$total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함
?>
    <div id="sub_wrap">
        <div class="sub_title">
            <ul class="sub_title_category">
                <li onClick="location.href='/m/mypage/betlist/sports/'">스포츠</li>
                <!--<li onClick="location.href='/mobile/mypage/list_evolution.html'">에볼루션</li>-->
                <li onClick="location.href='/m/mypage/betlist/minigame/power/'" class="active">미니게임</li>
                <li onClick="location.href='/m/mypage/betlist/virtual/soccer/'">가상게임</li>
            </ul>
            <h1>
                <span>키노사다리 베팅내역</span>
                <em>MINIGAME BETTING LIST</em>
            </h1>
        </div>

        <div class="sub_mypage_wrap">
            <div class="type_select" onclick="slide_type();">
                <em>키노사다리</em>
                <div><span id="type_select_arr"><img src="/mobile/img/type_select_arr.png"></span></div>
            </div>
            <ul id="type_select_l" class="type_select_list">
                <li onclick="location.href='/m/mypage/betlist/minigame/power/'">파워볼</li>
                <li onclick="location.href='/m/mypage/betlist/minigame/pwladder/'">파워사다리</li>
                <!--<li onclick="location.href='/mobile/mypage/list_speedkino.html'">스피드키노</li>-->
                <li onclick="location.href='/m/mypage/betlist/minigame/kenoladder/'" class="active">키노사다리</li>
            </ul>
            <!-- 경기검색 { -->
            <div class="sub_searchbox">

                <div class="search">
                    <ul>
                        <li>
                            <input type="text" class="date" name="startDate" id="startDate" value="<?php echo $startDate; ?>"> ~ <input type="text" class="date" name="endDate" id="endDate" value="<?php echo $endDate; ?>">
                        </li>
                        <li>
                            <span class="view">검색하기</span>
                        </li>
                    </ul>

                </div>

            </div>
        </div>

        <table class="sub_board_list sub_board_list_betlist">
            <thead>
            <tr>
                <td>키노사다리 베팅내역 <dfn>1,274</dfn></td>
            </tr>
            </thead>
            <tbody>
            <?php
            if($total_article > 0){
                $cnt = 0;
                $que = "
                                    SELECT 
                                        a.*,b.*
                                    FROM 
                                        {$tb} 
                                    WHERE 
                                         {$where}
                                         {$order_by}
                                    LIMIT 
                                        $start, $view_article 
                                   
                                ";
                //echo $que;
                $rs = getArr($que);
                foreach($rs as $rs){
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


                    if(in_array($rs['GL_Key'],array(11,16))){
                        if($rs['BGL_ResultChoice']=='Odd'){
                            $betting_text = "<span style='color:dodgerblue'>홀</span>";
                        } else if($rs['BGL_ResultChoice']=='Even'){
                            $betting_text = "<span style='color:orangered'>짝</span>";
                        }
                        $betting_game_text = "홀/짝";
                    } else if(in_array($rs['GL_Key'],array(12,17))){
                        if($rs['BGL_ResultChoice']=='Odd'){
                            $betting_text = "<span style='color:dodgerblue'>좌</span>";
                        } else if($rs['BGL_ResultChoice']=='Even'){
                            $betting_text = "<span style='color:orangered'>우</span>";
                        }
                        $betting_game_text = "좌출/우출";
                    } else if(in_array($rs['GL_Key'],array(13,18))){
                        if($rs['BGL_ResultChoice']=='Odd'){
                            $betting_text = "<span style='color:dodgerblue'>3줄</span>";
                        } else if($rs['BGL_ResultChoice']=='Even'){
                            $betting_text = "<span style='color:orangered'>4줄</span>";
                        }
                        $betting_game_text = "3줄/4줄";
                    } else if(in_array($rs['GL_Key'],array(14,19))){
                        if($rs['BGL_ResultChoice']=='Odd'){
                            $betting_text = "<span style='color:dodgerblue'>좌3짝</span>";
                        } else if($rs['BGL_ResultChoice']=='Even'){
                            $betting_text = "<span style='color:orangered'>좌4홀</span>";
                        }
                        $betting_game_text = "줄출조합";
                    } else if(in_array($rs['GL_Key'],array(15,20))){
                        if($rs['BGL_ResultChoice']=='Odd'){
                            $betting_text = "<span style='color:dodgerblue'>우3홀</span>";
                        } else if($rs['BGL_ResultChoice']=='Even'){
                            $betting_text = "<span style='color:orangered'>우4짝</span>";
                        }
                        $betting_game_text = "줄출조합";
                    }


                    ?>
                    <tr>
                        <td>
                            <!-- 베팅내역 { -->
                            <div>
                                <dfn class="font_gobet">No. <?php echo $rs['BG_Key'];?> &nbsp; <span>키노사다리</span></dfn>
                                <label><span class="ing">진행중</span></label>
                            </div>
                            <fieldset>
                                <em><input type="checkbox" name="bgkey[]" class="bgkey" value="<?php echo $rs['BG_Key']; ?>"></em>
                                <dl>
                                    <dt>베팅내역</dt>
                                    <dd class="blue"><?php echo $betting_text;?></dd>
                                    <dt>적중상태</dt>
                                    <dd class="green"><?php echo $rseult_text;?></dd>
                                </dl>
                                <dl>
                                    <dt>게임회차</dt>
                                    <dd><?php echo date("m월-d일",strtotime($rs['G_Datetime']));?> - <?php echo $rs['G_Num']?>회</dd>
                                    <dt>게임구분</dt>
                                    <dd><?php echo $betting_game_text; ?></dd>
                                </dl>
                                <dl>
                                    <dt>베팅일시</dt>
                                    <dd><?php echo date("m월-d일 H:i:s",strtotime($rs['BG_BuyDate']));?></dd>
                                    <dt>배당률</dt>
                                    <dd><?php echo $rs['BG_TotalQuota'];?></dd>
                                </dl>
                                <dl>
                                    <dt>베팅금액</dt>
                                    <dd><?php echo number_format($rs['BG_BettingPrice']);?>원</dd>
                                    <dt>적중/손실</dt>
                                    <dd class="green"><?php echo number_format($rs['BG_ForecastPrice']);?>원</dd>
                                </dl>
                            </fieldset>
                            <!-- } 베팅내역 -->
                        </td>
                    </tr>

                    <?php
                    $cnt++;
                }} else {
                ?>
                <tr>
                    <td style="width: 100%;text-align: center;font-size:20px;" class="text-center">등록된 배팅내역이 없습니다.</td>
                </tr>

            <?php } ?>
            </tbody>
        </table>

        <div class="sub_board" style="border-top:none">

            <div class="sub_board_btn">
                <a href="#" class="btn_gray selectAll">전체선택</a>
                <a href="#" class="btn_gray del" data-game_state="<?php echo $game_state;?>">선택삭제</a>
            </div>
            <div class="sub_board_btn margin betting-upload">
                <a href="javascript:;" class="">선택항목 게시판에 올리기</a>
            </div>

            <?php
            if($total_article>0) {
                include_once($_SERVER['DOCUMENT_ROOT'] . "/m/lib/page.php");
            }
            ?>
        </div>

    </div>
    <script>
        $(document).ready(function(){
            $('div.sub_mypage_wrap > div.sub_searchbox > div > ul > li:nth-child(2) > span').on('click',function(){
                location.href = './?startDate='+$('#startDate').val()+'&endDate='+$('#endDate').val();
            });
            $('.input_box > span').on('click',function(){
                var startDay  = $(this).data("day");
                $('input[name="startDate"]').val(startDay);
            });

            $('.selectAll').on('click',function(){
                if($(this).text()=='전체선택'){
                    $('.bgkey').prop('checked',true);
                    $(this).text('선택해제');
                } else {
                    $('.bgkey').prop('checked',false);
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

            $('.betting-upload').on('click',function(){
                var cnt = 0;
                var bgkey = new Array();
                $('.bgkey').each(function(){
                    if($(this).is(':checked')==true){
                        bgkey[cnt] = $(this).val();
                        cnt++;
                    }
                });
                if(!cnt){
                    swal('','등록할 배팅내역을 선택해주세요.','warning');
                    return false;
                }

                swal({
                    text: "베팅정보를 등록하시겠습니까?",
                    type: "success",
                    confirmButtonText: "확인",
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type : 'post',
                            url : '/m/action/board_action.php',
                            dataType : 'json',
                            data : 'HAF_Value_0=BoardGameResultWrite&HAF_Value_1='+bgkey+'&HAF_Value_0=BoardGameResultWrite',
                            success : function(data){
                                if(data.flag == true){
                                    location.href = '/m/mypage/board/modify/mini.php?&tn=betting&b_key='+data.bkey+'&mini=Y';
                                } else {
                                    swal('',data.error,'warning');
                                }
                            }
                        });
                    }
                });

            });
        });


    </script>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php';
?>