<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';
    $board_title = $lib24c->Check_Board('board');
?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(10)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">1:1문의</div>
                <div class="title2">TREND QNA</div>
            </div>

            <div class="sub-box">

                <div class="board_wrap">
                    <div class="qna_cate">
                        <a class="none on">계좌문의는 충전 페이지에서 계좌문의 버튼을 눌러주세요.</a>
                    </div>
                    <select name="qna_option" id="qna_option" style="padding: 20px; font-weight: 100; font-size: 20px; color: #FFF; background: #3e3e3e; border: 1px solid #3e3e3e; border-radius: 12px; margin-bottom: 10px;" >
                        <option value="">분류선택</option>
                        <option value="스포츠">스포츠</option>
                        <option value="미니게임">미니게임</option>
                        <option value="가상게임">가상게임</option>
                        <option value="카지노" <?php echo ($rs['B_Subject']=='카지노')?'selected':''; ?>>카지노</option>
                        <option value="회원문의">회원문의</option>
                        <option value="정산문의">정산문의</option>
                        <option value="기타문의">기타문의</option>
                    </select>
                    <input type="text" name="b_subject" id="b_subject" class="sub_board_write_title" placeholder="제목을 입력해주세요." />

                    <div class="sub_board_view_con">

                        <h2 class="sub_board_write_info">
                            글내용 입력
                            <font>
                                욕설, 상대방 비방글, 타사이트 언급, 홍보 등은 경고없이 삭제되며 사이트 이용에 제한을 받을 수 있습니다.
                            </font>
                        </h2>
                        <textarea class="board_write_contents" name="b_content" id="summernote" placeholder="내용을 입력해주세요."></textarea>
                    </div>

                    <div class="btn_box btn_box_center">
                        <a href="javascript:;" class="btn_green"  OnClick="javascript:Action_Write();">등록</a>
                        <a href="../list/" class="btn_gray">취소</a>
                    </div>

                </div> <!-- board_wrap -->

            </div> <!-- sub-box -->


        </div>

    </div> <!-- sub_wrap -->
    </div> <!-- sub_bg -->
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#summernote').summernote({
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                ],
                lang: 'ko-KR',
                placeholder: '내용을 입력하세요.',
                tabsize: 2,
                height: 300
            });
        });
    </script>
    <script>

        function Action_Write() {
            var f = document.HiddenActionForm;

            var _subject  = document.getElementById( "b_subject" );
            var _type     = document.getElementById( "b_type" );
            var _content = document.getElementById( "summernote" );
            var _option = $('#qna_option option:selected').val();

            if ( _subject.value.trim() == "" ) {
                swal("","제목을 입력해 주세요.","warning");
                _subject.focus();
                return;
            }

            if(_content.value.trim() == ''){
                swal('','내용을 입력해주세요.','warning');
                _content.focus();
                return;
            }

            if ( confirm("게시물을 등록 하시겠습니까?") ) {
                f.HAF_Value_0.value = "BoardWrite";
                f.HAF_Value_1.value = "customer";

                f.HAF_Value_2.value = _subject.value;
                f.HAF_Value_3.value = _content.value;
                f.HAF_Value_4.value = "<?=$_SERVER['HTTP_REFERER'];?>";
                f.HAF_Value_5.value = _option;

                f.method = "POST";
                f.action = "/action/board_action.php";
                f.submit();

            };
        }
    </script>
<?php
include_once $root_path.'/include/footer.php';
?>