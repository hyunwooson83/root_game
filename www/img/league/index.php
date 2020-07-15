<?php
    $root_path = '/bs';
    $include_path = $_SERVER['DOCUMENT_ROOT'].$root_path;
    include_once($include_path."/include_template/header.php");
    //include $include_path."/include/common.php";

    system('cp -r /home/bs/www/bs/game/reg/league/img/* /home/trend/www/img/league/');

    $item_array = array(11=>1,12=>16,13=>18,15=>17,14=>91);
    $offset = $_GET['offset'];

    //if ( !is_numeric($offset) && $offset ) $lib->AlertBack( "정상적인 접속이 아닙니다." );

    $tb = "gameleague A LEFT JOIN gameitem B ON A.GI_Key = B.GI_Key";
    if(empty($_GET['startDate']))	$startDate = date("Y-m-d H:i",time());
    if(empty($_GET['endDate']))	    $endDate = date("Y-m-d H:i",time());
    $startDate = $_GET['startDate'];
    $endDate   = $_GET['endDate'];


    $today = date("Y-m-d");

    $view_article = 15; // 한화면에 나타날 게시물의 총 개수
    if (!$_GET['page']) $page = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
    $start = ($page-1)*$view_article;
    $href = "&pg={$_GET['pg']}&amp;sch_type={$_GET['sch_type']}&amp;sch_text={$_GET['sch_text']}&amp;&order={$_GET['order']}&opt={$_GET['opt']}";

    $where = " 1 ";
    if($sch_item) $where .= " AND A.GI_Key = '{$sch_item}' AND GL_Key_IDX < 9999 ";
    if($sch_league) $where .= " AND A.GL_Key_IDX = '{$sch_league}'";
    if($sch_text) $where .= " AND A.GL_Type LIKE '%{$sch_text}%'";

    if($_GET['order']){
        $order_by .= $_GET['order']." ".$_GET['opt'];
    } else {
        $order_by .= " A.GI_Key ASC, A.GL_Key";
    }

    $query = "SELECT COUNT(*) FROM {$tb} WHERE {$where}  ";
    //echo $query;
    $row = getRow($query);
    $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함

    //처리버튼
    $process_btn = "";
    $process_btn .= '<button type="button" class="btn btn-default del">삭제처리</button>';
?>

<div id="page-content-wrapper">
    <div id="page-content">

        <div class="container">

            <div id="page-title">
                <h2>게임리그관리 - [관리에서 숨김을 누르시면 해당 리그에 해당하는 경기는 안보이게 됩니다]</h2>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <!--리그 등록-->
                    <form name="frm" id="frm" method="post" enctype="multipart/form-data" target="HIddenActionFrame">
                        <input type="hidden" name="mode" id="mode" value="gameleague">
                        <input type="hidden" name="glkey" id="glkey" value="">
                        <div class="form-group">
                            <div class="col-sm-12" style="margin-left:0px; padding-left:0px;">
                                <div class="row m-1">

                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <select name="item" id="item" class="form-control single">
                                                <option >종목선택</option>
                                                <?php
                                                $que = "SELECT * FROM gameitem WHERE GI_State = 'Normal'";
                                                $arr = getArr($que);
                                                foreach($arr as $arr){
                                                    ?>
                                                    <option value="<?php echo $arr['GI_Key']; ?>"><?php echo $arr['GI_Type']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" name="game_league" id="game_league" class="form-control" value="" placeholder="리그명을 입력해주세요.">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="file" name="league_img" id="league_img" class="form-control" value="" placeholder="게임종목명 입력해주세요.">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-info write">등록</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="example-box-wrapper">

                        <!--검색-->
                        <form action="./" name="searchFrm">

                            <div class="form-group">
                                <div class="col-sm-12" style="margin-left:0px; padding-left:0px;">
                                    <label class="col-sm-1 control-label text-right pad10T">검색</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <select name="sch_item" id="sch_item" class="form-control single">
                                                <option value="">종목선택</option>
                                                <?php
                                                $que = "SELECT * FROM gameitem WHERE GI_State = 'Normal'";
                                                $arr = getArr($que);
                                                foreach($arr as $arr){
                                                    ?>
                                                    <option value="<?php echo $item_array[$arr['GI_Key']]; ?>" <?php echo ($sch_item==$item_array[$arr['GI_Key']])?'selected':''; ?>><?php echo $arr['GI_Type']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <select name="sch_league" id="sch_league" class="form-control single">
                                                <option value="">리그선택</option>
                                                <?php
                                                $que = "SELECT * FROM gameleague WHERE GL_State = 'Normal' AND GI_Key = '{$sch_item}'";
                                                $arr = getArr($que);
                                                foreach($arr as $arr){
                                                ?>
                                                    <option value="<?php echo $arr['GL_Key_IDX']; ?>" <?php echo ($sch_league==$arr['GL_Key_IDX'])?'selected':''; ?>><?php echo $arr['GL_Type']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row m-1">
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <input type="text" name="sch_text" class="form-control" value="<?php echo $sch_text; ?>" placeholder="리그명을 입력해주세요.">
                                            </div>
                                        </div>
                                        <button class="btn btn-info">검색</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form name="chargeForm">
                            <input type="hidden" name="mode" value="">
                            <table class="table table-hover table-bordered">
                                <colgroup>
                                    <col width="2%">
                                    <col width="5%">
                                    <col width="5%">
                                    <col width="10%">
                                    <col width="20%">
                                    <col width="10%">
                                    <col width="5%">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all" name="allChk" value="y"></th>
                                    <th>번호</th>
                                    <th>종목명</th>
                                    <th>리그명</th>
                                    <th>이미지</th>
                                    <th>관리</th>
                                    <!--<th>관리</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?

                                $cnt = 0;
                                $que = "
                                    SELECT 
                                        *
                                    FROM 
                                        {$tb}
                                    WHERE
                                        {$where}                                             
                                    ORDER BY 
                                        {$order_by} LIMIT {$start}, {$view_article}";
                                //echo $que;
                                $arr = getArr($que);
                                if($total_article>0){
                                    foreach($arr as $rs){
                                        ?>
                                        <tr class="even <?php echo $stat_color; ?>">
                                            <td class="text-center"><input type="checkbox" class="chk" name="chk[]" value="<?php echo $rs['GL_Key'];?>"></td>
                                            <td class="text-center"><?php echo $total_article-$cnt-(($page-1)*$view_article); ?></td>
                                            <td class="text-center"><?php echo $rs['GI_Type']; ?></td>
                                            <td class="text-center"><?php echo $rs['GL_Type']; ?></td>
                                            <td class="text-center"><img src="./img/<?php echo $rs['GL_SrvName']; ?>" style="width:24px;"></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-<?php echo ($rs['GL_State']=='Normal')?'success':'danger'; ?> hide" data-glkey="<?php echo $rs['GL_Key']; ?>" ><?php echo ($rs['GL_State']=='Normal')?'보임':'숨김'; ?></button>
                                                <button type="button" class="btn btn-warning modify" data-glkey="<?php echo $rs['GL_Key']; ?>" data-gikey="<?php echo $rs['GI_Key']; ?>" data-gltype="<?php echo $rs['GL_Type']; ?>">수정</button>
                                            </td>
                                        </tr>
                                        <?php $cnt++; }} else { ?>
                                    <tr>
                                        <td colspan="11" class="text-center bold">현재 등록된 내용이 없습니다.</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <div class="paging">
                                <?php include $include_path."/include/page.php"; ?>
                            </div>

                        </form>
                        <?php echo $process_btn; ?>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>

</div>

<script>
    $('.write').on('click',function(){
        if($('#item option:selected').val() == ''){
            alert_swal('종목을 선택해주세요.','warning',0);
            return false;
        }
        if($('input[name="game_item"]').val()==''){
            alert_swal('리그명을 입력해주세요.','warning',0);
            return false;
        }
        if($('input[name="item_img"]').val()==''){
            alert_swal('이미지를 선택해주세요.','warning',0);
            return false;
        }

        var f = document.frm;
        f.action = './proc/';
        f.submit();
    });

    function call_back( msg){
        if(!msg){
            alert_swal('리그가 등록되었습니다.','success',3000);
        } else {
            alert_swal(msg,'warning',3000);
        }
    }

    $(document).ready(function(){
        $('.modify').on('click',function(){
           var glkey = $(this).data('glkey');
           var gikey = $(this).data('gikey');
           var gltype = $(this).data('gltype');
           
           $('#item option[value="'+gikey+'"]').attr('selected','selected');
           $('#game_league').val(gltype);
           $('#glkey').val(glkey);
           $('#mode').val('gameleagueModify');
        });
        
        //리그별 게임을 숨김
        $('.hide').on('click',function(){
            var glkey = $(this).data('glkey');
            if($(this).text()=='보임'){
                var title_text = "숨김";
                var gubun = 'y';
            } else {
                var title_text = "보임";
                var gubun = 'n'
            }
            swal({
                title: "리그별 경기"+title_text,
                text: "해당하는 리그에 속한 경기를 "+title_text+"으로 처리하시겠습니까?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "확인",
                cancelButtonText: "취소"
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url : './proc/',
                        type : 'post',
                        dataType : 'json',
                        data : {mode : 'leagueHideGame', glkey : glkey, gubun : gubun},
                        success : function(res){
                            if(res.flag == true){
                                swal('',title_text+'처리가 완료되었습니다.','success');
                            } else {
                                swal('',title_text+'처리시 오류가 발생했습니다. 잠시후에 다시 시도해주세요.'+res.error,'warning');
                            }
                            setTimeout(function(){ location.reload();},2000);
                        },complete:function(data){
                        },error:function(request, status, error){
                            console.log(request);
                            console.log(status);
                            console.log(error);
                        }
                    });
                }
            });
        });
        $('.del').on('click',function(){
            var cnt = 0;
            var idx_array = new Array();
            $('.chk').each(function(index){
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
                    text: "삭제를 하시겠습니까?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "확인",
                    cancelButtonText: "취소"
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url : './proc/',
                            type : 'post',
                            dataType : 'json',
                            data : {mode : 'itemDel', idx : idx_array},
                            success : function(res){
                                if(res.flag == true){
                                    swal('','삭제처리가 완료되었습니다.','success');
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
include_once($include_path."/include_template/footer.php");
?>
