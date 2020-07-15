<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';
$board_title = $lib24c->Check_Board($_GET['tn']);

$rs = $lib24c->Get_Board_Read( $_GET['b_key'] );

?>
    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>1:1문의</span>
                <em>CUSTOMER</em>
            </h1>

        </div>

        <div class="sub_board">

            <div class="sub_board_write">
                <div><input type="text" name="b_subject" id="b_subject" style="background-color: #fff; color:#222;" value="<?php echo $rs['B_Subject']; ?>" placeholder="제목을 입력해주세요." /></div>
                <!--<div><a class="b_list" href="/mobile/board_write_bettinglist.html" target="_blank">베팅내역 첨부</a></div>-->
                <div class="text"><textarea name="b_content" id="summernote"  placeholder='내용을 입력해주세요.'><?php echo nl2br($rs['B_Content']); ?></textarea></div>
            </div>

            <div class="sub_board_btn">
                <a href="javascript:;"  OnClick="javascript:Action_Write();">수정</a>
                <a href="../" class="btn_gray">취소</a>
            </div>

        </div>

    </div> <!-- Sub Wrap -->
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

            if ( confirm("게시물을 수정 하시겠습니까?") ) {
                f.HAF_Value_0.value = "BoardModify";
                f.HAF_Value_1.value = "<?=$tn;?>";
                f.HAF_Value_2.value = _subject.value;
                f.HAF_Value_3.value = _content.value;
                f.HAF_Value_4.value = <?=$_GET['b_key'];?>;
                f.HAF_Value_5.value = "tn=<?php echo $tn;?>&b_key=<?php echo $b_key; ?>";
                f.HAF_Value_7.value = "";
                f.HAF_Value_8.value = _type;
                f.HAF_Value_9.value = _option
                f.method = "POST";
                f.action = "/m/action/board_action.php";
                f.submit();

            };
        }
    </script>
<?php
include_once $root_path.'/include/footer.php';
?>