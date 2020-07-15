<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';
if($meminfo['M_CasinoYN']=='N'){
    echo '<script>swal("","카지노를 이용하실 수 없습니다.","warning"); setTimeout(function(){ location.href="/m/main/";},2000);</script>';
}
?>
<div class="introduce_list_wrap casino" style="width: 100% !important;">



</div>

<script>
    $(document).ready(function(){
        $(".introduce_list > li").each(function(){
            if( $(this).attr("data-num")==$("#sub_num").val() ){
                $(this).children("em").addClass('on');
            }
        });

        $.ajax({
            type : 'post',
            url : './proc/',
            dataType : 'json',
            data : 'gcode=<?php echo $gcode;?>',
            success : function(data){
                //if(data.flag == true){
                $('#iframe_casino').prop('src',data.lobbyUrl);
                window.open(data.lobbyUrl,'','width=100%, height=100%');
                //}
            }
        });

    });
</script>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>