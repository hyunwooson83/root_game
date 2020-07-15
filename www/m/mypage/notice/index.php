<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/header.php';

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
    <div id="sub_wrap">

        <div class="sub_title">
            <h1>
                <span>공지사항</span>
                <em>Notice</em>
            </h1>

        </div>

        <div class="sub_board">

            <table class="board_old">
                <thead>
                <tr>
                    <td>제목</td>
                    <td width="25%">글쓴이</td>
                    <td width="13%">작성일</td>
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
                <tr onclick="location.href='./view/?b_key=<?php echo $list['B_Key']; ?>'">
                    <td class="notice"><var>공지</var><span><?php echo $list['B_Subject']; ?></span></td>
                    <td><code class="admin">텍사스</code></td>
                    <td class="dtime"><?php echo date("m-d",strtotime($list['B_RegDate'])); ?></td>
                </tr>
        <?php
        $cnt++;
    }} else {
    ?>
    <tr><td colspan="3" class="text-center">등록된 게시물이 없습니다.</td></tr>
<?php } ?>
                </tbody>
            </table>

            <?php
            if($total_article>0) {
                //include_once($_SERVER['DOCUMENT_ROOT'] . "/m/lib/page.php");
            }
            ?>

            

        </div>

    </div> <!-- Sub Wrap -->

<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/m/include/footer.php';
?>