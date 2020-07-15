<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';


    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) {
        //swal_move('로그인이 필요한 페이지 입니다.', 'login');
    } else {
    }

    $tn = 'board';
    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) $lib->AlertMSG( "로그인이 필요한 페이지 입니다.","/" );
    if($_GET['tn']=='customer')	move('./board_list2.php?tn=customer');
    $mem = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");

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

    $query = "SELECT COUNT(*) FROM $tb WHERE $where AND (B_ID='".$tn."' or B_ID='".$tn1."') and B_Delete = 'N' AND B_Type = 'Normal' ";
    //echo $query;
    $row = getRow($query);
    $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함



    // 게시판 확인
    $board_title = $lib24c->Check_Board($_GET['tn']);

?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(11)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">쪽지</div>
                <div class="title2">TREND MESSAGE</div>
            </div>
            <ul class="sub_menu">
                <li class="on" onclick="location.href='/mypage/message/'">쪽지</li>
                <li class="" onclick="location.href='/mypage/qna/'">1:1문의하기</li>
                <li onclick="location.href='/mypage/faq/'">자주묻는 질문</li>
                <li onclick="location.href='/mypage/notice/'">공지사항</li>
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
                        <th>상태</th>
                        <th>등록일</th>
                        <th>확인일자</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>2222</td>
                        <td class="subject" onclick="location.href='/_go/renewal/qna_view.html'"><div>문의드립니다.</div></td>
                        <td>홍길동</td>
                        <td class="state"><span class="off">확인전</span></td>
                        <td>2017-07-07</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>2222</td>
                        <td class="subject" onclick="location.href='/_go/renewal/qna_view.html'"><div>문의드립니다.</div></td>
                        <td>홍길동</td>
                        <td class="state"><span class="on">확인완료</span></td>
                        <td>2017-07-07</td>
                        <td>2020-01-20</td>
                    </tr>
                    </tbody>

                </table>
                <div class="line_bottom"></div>
                <div class="btn_wrap">
                    <a>계좌문의</a>
                    <a href="/_go/renewal/qna_write.html">문의등록</a>
                </div>
                <div class="paging_box">
                    <a href="">◀</a><a href="">1</a><a href="" class="hit">2</a><a href="">3</a><a href="">4</a><a href="">5</a><a href="">6</a><a href="">7</a><a href="">8</a><a href="">9</a><a href="">10</a><a href="">▶</a>
                </div>
                <div class="search_box">
                    <input type="text" placeholder="검색"><input type="button" value="검색" class="btn">
                </div>
            </div>

        </div>
    </div>

<?php
include_once $root_path.'/include/footer.php';
?>