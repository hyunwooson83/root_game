<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';


    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) {
        swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }

    $tn = 'faq';
    $tb = "board";

    $view_article = 15; // 한화면에 나타날 게시물의 총 개수
    if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
    $start = ($_GET['page']-1)*$view_article;
    $href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}";

    if($_GET['tn']=='customer'){
        $where = " 1 AND M_Key = {$_SESSION['S_Key']} ";
    } else {
        $where = " 1 ";
    }


    #성명으로 정렬시
    $order_by = " ORDER BY B_TopYN ASC, B_RegDate DESC ";

    $query = "SELECT COUNT(*) FROM $tb WHERE $where AND (B_ID='".$tn."' or B_ID='".$tn1."') and B_Delete = 'N'  ";
    //echo $query;
    $row = getRow($query);
    $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함
?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(10)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">자주하는 질문</div>
                <div class="title2">TREND FAQ</div>
            </div>
            <ul class="sub_menu">
                <li class="" onclick="location.href='/mypage/message/'">쪽지</li>
                <li class="" onclick="location.href='/mypage/customer/'">1:1문의하기</li>
                <li class="on" onclick="location.href='/mypage/faq/'">자주묻는 질문</li>
                <li class="" onclick="location.href='/mypage/notice/'">공지사항</li>
            </ul>
            <!--<div class="qna_cate">
                <a class="none on">전체</a>
                <a>스포츠북</a>
                <a>가상게임</a>
                <a>마이크로게임</a>
                <a>타이산게임</a>
                <a>올벳게임</a>
                <a>아시아게이밍</a>
                <a>플레이텍게임</a>
                <br>
                <a class="none">파워볼</a>
                <a>파워사다리</a>
                <a>스피드키노</a>
                <a>키노사다리</a>
                <a>키노사다리</a>
                <a>회원문의</a>
                <a>정산문의</a>
                <a>기타문의</a>
            </div>-->

            <div class="board_wrap board2">
                <div class="line_top"></div>
                <table class="qna_list">
                    <thead>
                    <tr>
                        <th>번호</th>                        
                        <th>제목</th>
                        <th>작성자</th>
                        <th>등록일</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if($total_article > 0){
                        $cnt = 0;
                        $que = "
                                        SELECT 
                                            *
                                        FROM 
                                            $tb 
                                        WHERE 
                                             $where
                                        AND 
                                             (B_ID='".$tn."' or B_ID='".$tn1."') and B_Delete = 'N' 
                                        ORDER BY B_RegDate DESC
                                        LIMIT 
                                            $start, $view_article
                                        
                                    ";
                        //echo $que;
                        $arr = getArr($que);
                        foreach($arr as $list){
                    ?>
                        <tr>
                            <td><?=($total_article-$cnt-(($_GET['page']-1)*$view_article))?></td>
                            <td class="subject" onclick="location.href='./view/?b_key=<?php echo $list['B_Key']; ?>'">
                                <div><?php echo $list['B_Subject']; ?></div>
                            </td>
                            <td>텍사스</td>
                            <td><?php echo substr($list['B_RegDate'],0,10); ?></td>
                        </tr>
                    <?php $cnt++; }} ?>
                    </tbody>

                </table>
                <div class="line_bottom"></div>

                <?php
                if($total_article>0) {
                    include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
                }
                ?>
            </div>

        </div>
    </div>


<?php
include_once $root_path.'/include/footer.php';
?>