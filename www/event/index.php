<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';


    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) {
        swal_move('로그인이 필요한 페이지 입니다.', 'login');
    }


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



    $tn = 'board';
    // 로그인 체크
    if ( !$_SESSION['S_Key'] ) $lib->AlertMSG( "로그인이 필요한 페이지 입니다.","/" );
    if($_GET['tn']=='board')	move('./board_list2.php?tn=board');
    $mem = getRow("SELECT * FROM members WHERE M_Key = {$_SESSION['S_Key']}");

    $tb = "board a LEFT JOIN members b ON a.M_Key = b.M_Key ";

    $view_article = 15; // 한화면에 나타날 게시물의 총 개수
    if (!$_GET['page']) $_GET['page'] = 1; // 현재 페이지 지정되지 않았을 경우 1로 지정
    $start = ($_GET['page']-1)*$view_article;
    $href = "&tn={$_GET['tn']}&tn1={$_GET['tn1']}&search_text={$_GET['search_text']}&tb={$_GET['tb']}";

    if($tn=='board'){
        $where = " 1 AND b.M_Key = {$_SESSION['S_Key']} AND B_ID='".$tn."' and B_Delete = 'N'";
    } else {
        $where = " 1 ";
    }


    #성명으로 정렬시
    $order_by = " ORDER BY B_TopYN ASC, B_RegDate DESC ";

   $query = "SELECT COUNT(*) FROM {$tb} WHERE {$where}   ";
    $row = getRow($query);
    $total_article = $row[0]; // 현재 쿼리한 게시물의 총 개수를 구함
?>
    <script>
        $(document).ready(function(){
            $(".sub_header > .top2 > li:nth-child(11)").addClass('active');
        });
    </script>
    <!-- 머니화면 -->
    <section class="p-0 bg-warning">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 p--15 d-flex d-block-xs  justify-content-center aos-init aos-animate" data-aos="fade-in" data-aos-delay="150">
                    <div class="col-12 col-lg-9 mb-9">
                        <div class="tile-box bg-red">
                            <div class="tile-header">
                                <ul>
                                    <li class="point"><strong>⊙</strong> 커뮤니티</li>
                                    <li><strong>⊙</strong> 커뮤니티 게시판 입니다.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-12 d-flex d-block-xs  justify-content-center" style="min-height: 600px;">

                    <div class="col-12 col-lg-9 mb-9">

                        <style>
                            table.table > thead > tr > th { font-size:12px;}
                            table.table > tbody > tr > td { font-size:12px;}
                            table.table > button { font-size:12px;}
                        </style>
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">

                            <tr>
                                <th width="10%">번호</th>
                                <?php if($tn == 'customer'){ ?>
                                <th width="10%">분류</th>
                                <?php } ?>
                                <th width="*">제목</th>
                                <?php if($tn == 'customer'){ ?>
                                <th width="10%">답변여부</th>
                                <?php } else { ?>
                                <th width="10%">작성자</th>
                                <?php } ?>
                                <th width="20%">등록일</th>
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
                                        LIMIT 
                                            $start, $view_article
                                        
                                    ";

                                $arr = getArr($que);
                                foreach($arr as $list){

                                    ?>
                                    <tr>
                                        <td><?=($total_article-$cnt-(($_GET['page']-1)*$view_article))?></td>
                                        <?php if($tn == 'customer'){ ?>
                                        <td>파워볼</td>
                                        <?php } ?>
                                        <td class="subject"><a href="./view/?tn=<?php echo $tn; ?>&b_key=<?php echo $list['B_Key']; ?>"><?php echo $list['B_Subject']; ?></a></td>
                                        <?php if($tn == 'customer'){ ?>
                                        <td><?php echo ($list['B_Answer'])?'<span class="font-blue">답변완료</span>':'<span class="font-red">답변안됨</span>'; ?></td>
                                        <?php } else { ?>
                                            <td><?php echo $list['M_NICK']; ?></td>
                                        <?php } ?>
                                        <td><?php echo substr($list['B_RegDate'],0,10); ?></td>
                                    </tr>
                                    <?php $cnt++;}} else { ?>
                                <tr><td colspan="6" class="text-center">현재 등록된 데이터가 없습니다.</td></tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="btn_wrap">
                            <button type="button" class="btn btn-info btn-sm" onclick="location.href='./write/';">글쓰기</button>
                        </div>

                    </div>


                </div>
                <div class="col-12 col-lg-12 p--15 d-flex d-block-xs  justify-content-center">
                    <div class="col-12 col-lg-9 mb-9 d-flex d-block-xs  justify-content-center bg-white">
                        <?php
                        if($total_article>0) {
                            include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/page.php");
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- 머니화면끝-->


<?php
include_once $root_path.'/include/footer.php';
?>