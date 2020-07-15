<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';
?>

    <div class="casino_bg">
        <div class="casino_wrap">
            <ul id="slot_ul">

            </ul>
        </div>
    </div>


    <div style="clear:both;"></div>

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
            data : 'slot_type=<?php echo $slot_type; ?>&mode=getGameList',
            success : function(data){
                var html = '';
                for(var i=0;i<data.games.length;i++){
                    html += '<li style="float:left; margin:10px; cursor:pointer;" >';
                    html += '<div class="game-id" data-game_id="'+data.games[i].id+'">';
                    html += '<img src="'+data.games[i].img+'" style="width:150px" title="'+data.games[i].tKR+'">';
                    html += '</div>';
                    html += '<span style="font-size:12px; color:#fff; text-align: center; display: block;">'+data.games[i].tKR+'</span>';
                }
                $('#slot_ul').append(html);
            }
        });
        $("#iframe_casino").load(function(){ //iframe 컨텐츠가 로드 된 후에 호출됩니다.
            var frame = $(this).get(0);
            var doc = (frame.contentDocument) ? frame.contentDocument : frame.contentWindow.document;
            $(this).height(doc.body.scrollHeight);
        });
        $(document).on('click','.game-id',function(){
            var id = $(this).data('game_id');
            $.ajax({
                type : 'post',
                url : './proc/',
                dataType : 'json',
                data : 'mode=gameExec&game_id='+id,
                success : function(data){
                    if(data!=''){
                        window.open(data.gameUrl,'','width=800, height=800');
                        //$("#iframe_casino").attr('src',data.gameUrl);
                    }
                }
            });
        });
    });
</script>
    <script src="/m/js/utf8.js"></script>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php'); ?>