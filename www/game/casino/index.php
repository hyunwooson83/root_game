<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';
    if($meminfo['M_CasinoYN']=='N'){
        echo '<script>swal("","카지노를 이용하실 수 없습니다.","warning"); setTimeout(function(){ location.href="/main/";},2000);</script>';
    }
?>
    <div class="sub_wrap" style="width: 100% !important; ">
        <iframe id="iframe_casino" src="./intro.php" style="min-height: 100vh; width: 100%;" frameborder="0" scrolling="auto"></iframe>
    </div>
    <div class="wrap_mask"></div>

    <script>

        $(document).ready(function(){
            $.ajax({
                type : 'post',
                url : './proc/',
                dataType : 'json',
                data : 'game_type=<?php echo $gcode; ?>',
                success : function(data){

                    //if(data.flag == true){
                    $('#iframe_casino').prop('src',data.lobbyUrl);
                    //}
                }
            });
            $("#iframe_casino").load(function(){ //iframe 컨텐츠가 로드 된 후에 호출됩니다.
                var frame = $(this).get(0);
                var doc = (frame.contentDocument) ? frame.contentDocument : frame.contentWindow.document;
                $(this).height(doc.body.scrollHeight);
            });
        });
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(4)").addClass('active');
        });
        
        
    </script>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/include/footer.php'); ?>