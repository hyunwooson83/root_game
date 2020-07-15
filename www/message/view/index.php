<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/include/header.php";



    $sql = "UPDATE message SET readDate = NOW() WHERE idx = '{$idx}'";
    echo $sql;
    setQry($sql);
    


    switch($_GET['tn'])
    {
        case 'notice':
            $title = "공지사항";
            break;
        case 'board':
            $title = "게시판";
            break;
        case 'event':
            $title = "이벤트";
            break;
        case 'customer':
            $title = "고객센터";
            break;
    }

?>
    <style>
        .live_frame_wrap {
            margin-bottom: 5px;
            width: 828px;
            height: 639px;
            border: 1px solid #d6d6d6;
        }

        body { font-size:12px; }
        .name, .rate {width:49%; display: inline-block;}
    </style>

    <!-- 머니화면 -->
    <section class="p-0 bg-warning">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 p--15 d-flex d-block-xs  justify-content-center aos-init aos-animate" data-aos="fade-in" data-aos-delay="150">
                    <div class="col-12 col-lg-9 mb-9">
                        <div class="tile-box bg-red">
                            <div class="tile-header">
                                <ul>
                                    <li class="point"><strong>⊙</strong> 입금 시 반드시 회원님 성함으로 입금바랍니다.</li>
                                    <li><strong>⊙</strong> 은행 점검시간을 확인하신 후 해당시간에는 입금이 지연될 수 있으니 점검시간을 피해 신청해 주시기 바랍니다.</li>
                                    <li><strong>⊙</strong> 입금계좌는 수시로 변경되오니 반드시 계좌번호문의 신청을 통해 계좌번호를 확인 후 입금하여 주시기 바랍니다.</li>
                                    <li><strong>⊙</strong> 자세한 문의사항은 고객센터를 이용해 주시기 바랍니다.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-12 d-flex d-block-xs  justify-content-center mrg10B">
                    <div class="col-12 col-lg-9 mb-9">
                        <div class="card b-0 shadow-primary-xs shadow-primary-md-hover transition-all-ease-250 transition-hover-top rounded overflow-hidden">
                            <div class="card-footer bg-transparent b-0 d-flex justify-content-center">
                                <table class="table table-bordered text-center">
                                    <tr>
                                        <td class="font-black bg-gray" >
                                            <span class="name font-bold">닉네임 : <?php echo $meminfo['M_NICK']; ?></span>
                                            <span class="rate font-bold">LV.<?php echo $disp_mb_lv[$_SESSION['S_Level']];?></span>
                                        </td>
                                        <td class="font-white bg-black">보유머니</td>
                                        <td><?php echo number_format($meminfo['M_Money']); ?>원</td>
                                        <td class="font-white bg-black">보유포인트</td>
                                        <td><?php echo number_format($meminfo['M_Point']); ?>P</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-12 d-flex d-block-xs  justify-content-center">

                    <div class="col-12 col-lg-9 mb-9">
                        <table class="table table-bordered table-striped table-condensed">
                            <colgroup>
                                <col width="10%">
                                <col width="*">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>제목</th>
                                    <td><?php echo $rs['B_Subject']; ?></td>
                                </tr>
                                <tr>
                                    <th>작성자</th>
                                    <td><?php echo $meminfo['M_NICK']; ?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle;">내용</th>
                                    <td><?php echo nl2br($rs['B_Content']); ?></td>
                                </tr>
                                <?php if($rs['B_Answer']!=''){ ?>
                                <tr>
                                    <th style="vertical-align: middle;">답변</th>
                                    <td><?php echo nl2br($rs['B_Answer']); ?></td>
                                </tr>
                            <?php } ?>
                            </thead>
                        </table>

                    </div>
                </div>
                <div class="col-12 col-lg-12 p--15 d-flex d-block-xs  justify-content-center">
                    <button type="button" class="btn btn-info cancel">목록</button>
                    <button type="button" class="btn btn-danger done">수정</button>
                    <button type="button" class="btn btn-warning delete">삭제</button>
                </div>
            </div>
        </div>
    </section>
    <!-- 머니화면끝-->

    <style>
        .btn-bet-area { height:100px; font-weight: bold;}
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.done').on('click',function(){
                location.href = '../modify/?tn=<?php echo $tn; ?>&b_key=<?php echo $b_key; ?>';
            });
            $('.cancel').on('click',function(){
                location.href = '/mypage/customer/';
            });
            $('.delete').on('click',function(){
                Action_Write();
            });
        });


    </script>

    <script>

        function Action_Write() {
            var f = document.HiddenActionForm;

          
            if ( confirm("게시물을 삭제 하시겠습니까?") ) {
                f.HAF_Value_0.value = "BoardDelete";
                f.HAF_Value_1.value = "<?php echo $b_key;?>";
                f.HAF_Value_2.value = '<?php echo $tn;?>';

                f.method = "POST";
                f.action = "/action/board_action.php";
                f.submit();

            };
        };
    </script>
<?php
include_once $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>