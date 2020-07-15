<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    /*if($meminfo['M_CasinoYN']=='N'){
        echo '<script>swal("","슬롯게임을 이용하실 수 없습니다.","warning"); setTimeout(function(){ location.href="/main/";},2000);</script>';
    }*/


    $que = "SELECT M_ID, M_CasinoTrID FROM members WHERE M_Key = '{$_SESSION['S_Key']}'";
    $row = getRow($que);
    if(empty($row['M_CasinoTrID'])){
        $trid = make_transaction_id($mid);
        if(!$trid){
            swal('','카지노 트랜잭션 아이디 생성실패입니다. 관리자에게 문의해주세요.','waraning');
        }
    }

?>
    <link rel="stylesheet" href="/css/waitMe.css" />
    <div class="casino_bg">
        <div class="casino_wrap" style="height: 500px;">
            <span id="waitBar"></span>
            <ul id="slot_ul">
                <li></li>
                <!--<li>
                    <div><img src="https://resource.fdsigaming.com/thumbnail/slot/ppNew/9870.png" style="width: 100px;"></div>
                    <span></span>
                </li>-->
            </ul>
        </div>
    </div>
    <div style="clear:both;"></div>
    <script src="/js/waitMe.min.js"></script>
    <script src="/js/utf8.js"></script>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(5)").addClass('active');
            $.ajax({
                type : 'post',
                url : './proc/',
                dataType : 'json',
                data : 'slot_type=<?php echo $gcode; ?>&mode=getGameList',
                success : function(data){
                    var html = '';
                    for(var i=0;i<data.games.length;i++){
                        html += '<li style="float:left; margin:10px; cursor:pointer;" >';
                        html += '<div class="game-id" data-game_id="'+data.games[i].id+'">';
                        html += '<img src="'+data.games[i].img+'" style="width:200px" title="'+data.games[i].tKR+'">';
                        html += '</div>';
                        html += '<span style="font-size:18px; text-align: center; display: block;">'+substr_utf8_bytes(data.games[i].tKR,0,30)+'</span>';
                    }
                    $('#slot_ul').append(html);
                    $('#waitBar').waitMe('hide');
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
        run_waitMe('bounce');
        function run_waitMe(effect){
            $('#waitBar').waitMe({
                effect: effect,
                text: '페이지를 불러오는 중입니다.',
                bg: 'rgba(255,255,255,0)',
                color:'#fff'
            });
        }
    </script>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/include/footer.php'); ?>