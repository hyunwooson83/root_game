<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';


// 로그인 체크
if ( !$_SESSION['S_Key'] ) {
    //swal_move('로그인이 필요한 페이지 입니다.', 'login');
} else {
}
?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(11)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">1:1문의</div>
                <div class="title2">GOBET Q&A</div>
            </div>
            <ul class="sub_menu">
                <li class="on" onclick="location.href='/_go/renewal/qna_list.html'">1:1문의하기</li>
                <li onclick="location.href='/_go/renewal/faq_list.html'">자주묻는 질문</li>
                <li onclick="location.href='/_go/renewal/notice_list.html'">공지사항</li>
            </ul>
            <div class="qna_cate">
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
            </div>

            <div class="board_wrap board2">
                <div class="line_top"></div>
                <table class="qna_list">
                    <thead>
                    <tr>
                        <th>번호</th>
                        <th>분류</th>
                        <th>제목</th>
                        <th>작성자</th>
                        <th>상태</th>
                        <th>등록일</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>2222</td>
                        <td class="gr">[스포츠북]</td>
                        <td class="subject" onclick="location.href='/_go/renewal/qna_view.html'"><div>문의드립니다.</div></td>
                        <td>홍길동</td>
                        <td class="state"><span class="off">답변대기</span></td>
                        <td>2017-07-07</td>
                    </tr>
                    <tr>
                        <td>2222</td>
                        <td class="gr">[스포츠북]</td>
                        <td class="subject" onclick="location.href='/_go/renewal/qna_view.html'"><div>문의드립니다.</div></td>
                        <td>홍길동</td>
                        <td class="state"><span class="on">답변완료</span></td>
                        <td>2017-07-07</td>
                    </tr>
                    <tr>
                        <td>2222</td>
                        <td class="gr">[스포츠북]</td>
                        <td class="subject" onclick="location.href='/_go/renewal/qna_view.html'">문의드립니다</td>
                        <td>홍길동</td>
                        <td class="state"><span class="on">답변완료</span></td>
                        <td>2017-07-07</td>
                    </tr>
                    <tr>
                        <td>2222</td>
                        <td class="gr">[스포츠북]</td>
                        <td class="subject" onclick="location.href='/_go/renewal/qna_view.html'">문의드립니다</td>
                        <td>홍길동</td>
                        <td class="state"><span class="on">답변완료</span></td>
                        <td>2017-07-07</td>
                    </tr>
                    <tr>
                        <td>2222</td>
                        <td class="gr">[스포츠북]</td>
                        <td class="subject" onclick="location.href='/_go/renewal/qna_view.html'">문의드립니다</td>
                        <td>홍길동</td>
                        <td class="state"><span class="on">답변완료</span></td>
                        <td>2017-07-07</td>
                    </tr>
                    <tr>
                        <td>2222</td>
                        <td class="gr">[스포츠북]</td>
                        <td class="subject" onclick="location.href='/_go/renewal/qna_view.html'">문의드립니다</td>
                        <td>홍길동</td>
                        <td class="state"><span class="on">답변완료</span></td>
                        <td>2017-07-07</td>
                    </tr>
                    <tr>
                        <td>2222</td>
                        <td class="gr">[스포츠북]</td>
                        <td class="subject" onclick="location.href='/_go/renewal/qna_view.html'">문의드립니다</td>
                        <td>홍길동</td>
                        <td class="state"><span class="on">답변완료</span></td>
                        <td>2017-07-07</td>
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