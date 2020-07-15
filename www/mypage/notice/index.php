<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) {
        //swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }

    $tn = 'notice';


    // 게시판 확인
    $board_title = $lib24c->Check_Board($tn);


    $tb = "board ";

    $view_article = 15; // 한화면에 나타날 게시물의 총 개수
    if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
    $start = ($_GET['page']-1)*$view_article;
    $href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}";

    $where = " 1  AND B_ID='".$tn."' and B_Delete = 'N' ";

    if(!empty($search_text)){
        $where .= " AND (B_Subject LIKE '%{$search_text}%' OR B_Content LIKE '%{$search_text}%') ";
    }

    #성명으로 정렬시
    $order_by = " ORDER BY B_TopYN ASC, B_RegDate DESC ";

    $query = "SELECT COUNT(*) FROM $tb WHERE $where ";

    $row = getRow($query);
    $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함



?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(11)").addClass('active');
        });
    </script>
    <div class="sub_bg">
        <div class="sub_wrap type2">
            <div class="sub_title">
                <div class="title1">공지사항</div>
                <div class="title2">TREND NOTICE</div>
            </div>
            <ul class="sub_menu">
                <li class="on" onclick="location.href='/mypage/notice/'">공지사항</li>
                <li class="" onclick="location.href='/mypage/customer/'">1:1문의하기</li>
                <li class="" onclick="location.href='/mypage/message/'">쪽지</li>
                <!--<li onclick="location.href='/mypage/faq/'">자주묻는 질문</li>-->

            </ul>

            <div class="board_wrap board2">
                <div class="line_top"></div>
                <table class="qna_list">
                    <thead>
                    <tr>
                        <th style="width: 100px;">번호</th>
                        <th>제목</th>
                        <th style="width: 200px;">등록일</th>
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
                                    
                                    ORDER BY B_RegDate DESC
                                    
                                    
                                ";
                        //echo $que;
                        $arr = getArr($que);
                        foreach($arr as $list){

                            $s = "SELECT COUNT(*) FROM boardreply WHERE B_Key = {$list[B_Key]}";
                            //echo $s;
                            $rp = getRow($s);
                            if ( $rp[0] > 0 ) $reply = " [".$rp[0]."]";
                            else $reply = '';

                            $nick = getRow("SELECT M_NICK, M_Level FROM members WHERE M_Key = '{$list[M_Key]}'");


                            ?>
                            <tr>
                                <td><?php echo $cnt+1; //echo $total_article-$cnt-(($_GET['page']-1)*$view_article);  ?></td>
                                <td class="subject" onclick="location.href='./view/?b_key=<?php echo $list['B_Key']; ?>'"><div><?php echo $list['B_Subject']; ?></div></td>
                                <td><?php echo substr($list['B_RegDate'],0,10); ?></td>

                            </tr>
                            <!--<tr>
                                <td>2222</td>
                                <td>스포츠</td>
                                <td class="subject" onclick="location.href='/_go/renewal/qna_view.html'"><div>문의드립니다.</div></td>
                                <td>홍길동</td>
                                <td class="state"><span class="on">답변완료</span></td>
                                <td>2017-07-07</td>
                            </tr>-->
                            <?php
                            $cnt++;
                        }} else {
                        ?>
                        <tr><td colspan="7" class="text-center">등록된 게시물이 없습니다.</td></tr>
                    <?php } ?>
                    </tbody>

                </table>
                
                <!--<div class="btn_wrap">
                    <a href="/_go/renewal/qna_write.html">글쓰기</a>
                </div>-->
                <?php
                if($total_article>0) {
                    //include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
                }
                ?>
                <!--<div class="paging_box">
                    <a href="">◀</a><a href="">1</a><a href="" class="hit">2</a><a href="">3</a><a href="">4</a><a href="">5</a><a href="">6</a><a href="">7</a><a href="">8</a><a href="">9</a><a href="">10</a><a href="">▶</a>
                </div>-->
                <div class="search_box">

                    <input type="text" name="search_text" id="search_text" value="<?php echo $search_text; ?>" placeholder="검색">
                    <input type="button" value="검색" class="btn" onclick="location.href = './?search_text='+$('#search_text').val();">

                </div>
            </div>

        </div>
    </div>

<?php
include_once $root_path.'/include/footer.php';
?>