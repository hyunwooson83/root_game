<?php
include_once($_SERVER['DOCUMENT_ROOT']."/m/lib/_lib.php");


$clear = array();

if(ctype_alpha($_POST['mode'])){
    $clear['mode'] = $_POST['mode'];
}

if(ctype_alnum($_POST['userId'])){
    $clear['loginId'] = $_POST['userId'];
}

if(ctype_alnum($_POST['userNick'])){
    $clear['userNick'] = $_POST['userNick'];
}



switch($clear['mode'])
{
    case 'join':
        $cnt = 0;
        $que = "SELECT M_ID FROM members WHERE M_ID = '{$clear['loginId']}'";
        //echo $que;
        $row = getRow($que);
        if($row['M_ID']){
            $cnt++;
        }

        if(!$cnt){
            echo "y";
        }	else 	{
            echo "n";
        }

        break;

    case 'nick':
        $que = "SELECT M_NICK FROM members WHERE M_NICK = '{$_POST['userNick']}'";
        //echo $que;
        $row = getRow($que);
        if(!$row['M_NICK']){
            echo "y";
        } else {
            echo "n";
        }

        break;

    case 'hp':
        $que = "SELECT M_CP FROM members WHERE M_CP = '{$_POST['hp']}'";
        //echo $que;
        $row = getRow($que);
        if(!$row['M_CP']){
            echo "y";
        } else {
            echo "n";
        }

        break;


    case 'zipcode':
        $que = "SELECT * FROM zipcode WHERE dong LIKE '%$dong%'";
        //echo $que;
        $arr = getArr($que);
        if(count($arr)>0){
            foreach($arr as $list)
            {
                $data[] = array("zipcode"=>$list[zipcode],"sido"=>$list['sido'],"gugun"=>$list[gugun],"dong"=>$list[dong],"bunji"=>$list[bunji]);
            }
        }
        echo json_encode($data);
        break;


    //관심지역 찾기
    case 'searchGubun':
        $que = "SELECT DISTINCT(gugun) FROM zipcode WHERE sido = '".$_POST['sido']."'";
        //echo $que;
        $arr = getArr($que);
        if(count($arr)>0){
            foreach($arr as $list){
                $data[] = array("no"=>$_POST[no],"gugun"=>$list['gugun']);
            }
        }
        echo json_encode($data);
        break;


    #좋아요
    case 'good':

        $que = "SELECT * FROM userGoodCnt WHERE picIdx = {$_POST[idx]} AND userId = '{$_POST['id']}' AND hitId = '{$_SESSION['loginId']}'";
        //echo $que;
        $row = getRow($que);
        if($row['userId']){
            echo "already";
        } else {
            $que = "INSERT INTO userGoodCnt SET ";
            $que .= "userId = '{$_POST['id']}', ";
            $que .= "hitId = '{$_SESSION['loginId']}', ";
            $que .= "picIdx = {$_POST['idx']}, ";
            $que .= "shopId = '{$_POST['shopId']}', ";
            $que .= "designerIdx = {$_POST['designerIdx']}, ";
            $que .= "regDate = NOW(); ";
            //echo $que;
            $res = setQry($que);
            if($res){
                $rw = getRow("SELECT COUNT(*) FROM userGoodCnt WHERE userId = '{$_POST['id']}'");
                echo $rw[0];
            }
        }
        break;


    #이전 이미지 버튼 클릭시
    case 'slide':
        $arr = getArr("SELECT idx,srvFile1, srvFile2, srvFile3, srvFile4 FROM shopRegPic WHERE userId = '{$_SESSION['loginId']}' ORDER BY regDate DESC");
        for($i=0;$i<count($arr);$i++){
            if($arr[$i][idx]==$_POST[idx]){
                $current = $i;
            }
            $list[$i][idx] = $arr[$i][idx];
            $list[$i]['srvFile1'] = $arr[$i]['srvFile1'];
            $list[$i]['srvFile2'] = $arr[$i]['srvFile2'];
            $list[$i]['srvFile3'] = $arr[$i]['srvFile3'];
            $list[$i]['srvFile4'] = $arr[$i]['srvFile4'];

            /*$size[$i]['size1'] = getWH($_SERVER['DOCUMENT_ROOT']."/data/shop/user/org/".$list['srvFile1'], 140, 108);
            $size[$i]['size2'] = getWH($_SERVER['DOCUMENT_ROOT']."/data/shop/user/org/".$list['srvFile2'], 140, 108);
            $size[$i]['size3'] = getWH($_SERVER['DOCUMENT_ROOT']."/data/shop/user/org/".$list['srvFile3'], 140, 108);
            $size[$i]['size4'] = getWH($_SERVER['DOCUMENT_ROOT']."/data/shop/user/org/".$list['srvFile4'], 140, 108);				*/

        }


        if($_POST['gubun']==1){
            if($list[$current-1][idx]>0){
                //$str = $list[$current-1][idx]."^".$list[$current-1]['srvFile1']."^".$list[$current-1]['srvFile2']."^".$list[$current-1]['srvFile3']."^".$list[$current-1]['srvFile4']."^1^".$_POST[idx]."^".$size[$current-1]['size1']."^".$size[$current-1]['size2']."^".$size[$current-1]['size3']."^".$size[$current-1]['size4'];
                $str = $list[$current-1][idx]."^".$list[$current-1]['srvFile1']."^".$list[$current-1]['srvFile2']."^".$list[$current-1]['srvFile3']."^".$list[$current-1]['srvFile4']."^1^".$_POST[idx];
            } else {
                $str = "First";
            }

        }

        if($_POST['gubun']==2){
            if($list[$current+1][idx]>0){
                //$str = $list[$current+1][idx]."^".$list[$current+1]['srvFile1']."^".$list[$current+1]['srvFile2']."^".$list[$current+1]['srvFile3']."^".$list[$current+1]['srvFile4']."^2^".$_POST[idx]."^".$size[$current+1]['size1']."^".$size[$current+1]['size2']."^".$size[$current+1]['size3']."^".$size[$current+1]['size4'];
                $str = $list[$current+1][idx]."^".$list[$current+1]['srvFile1']."^".$list[$current+1]['srvFile2']."^".$list[$current+1]['srvFile3']."^".$list[$current+1]['srvFile4']."^2^".$_POST[idx];
            } else {
                $str = "End";
            }
        }
        //echo $_POST[idx];
        echo $str;

        break;

    #휴대폰 중복 여부
    case 'matching':
        $que = "SELECT userHp FROM member WHERE userHp = '{$_POST['hp']}'";
        //echo $que;
        $row = getRow($que);
        //print_r($row);
        if($row['userHp']){
            echo "Y";
        } else {
            echo "N";
        }
        break;

    #휴대폰 인증정보 만들기
    case 'makeHpCert':
        #중복확인
        $que = "SELECT userHp FROM member WHERE userHp = '{$_POST['hp']}'";
        //echo $que;
        $row = getRow($que);
        //print_r($row);
        if($row['userHp']){
            echo "already";
        } else {
            $certNum = rand(100000,999999);
            $sql = "INSERT INTO smsCert SET jumin = '{$_POST['jumin']}', hp='{$_POST['hp']}', certNum = '$certNum', regDate=NOW()";
            //echo $sql;
            $re = setQry($sql);
            if($re){
                echo "y^".$certNum."^".$_POST['hp'];
            } else {
                echo "n";
            }
        }
        break;

    #휴대폰 인증확인
    case 'confirmHp':

        $que = "SELECT certNum FROM smsCert WHERE hp = '{$_POST['hp']}' AND certNum = '{$_POST['num']}' ORDER BY regDate DESC LIMIT 1";
        //echo $que;
        $row = getRow($que);
        if($row['certNum']){
            echo "y";
        } else {
            echo "n";
        }
        break;


    #주민등록 가입여부 체크
    case 'juminChk':
        $que = "SELECT COUNT(*) FROM member WHERE jumin = '{$_POST['jumin']}'";
        $row = getRow($que);
        if($row[0]>0){
            echo "y";
        } else {
            echo "n";
        }
        break;


    //아이디 찾기
    case 'findId':
        $que = "SELECT userId FROM member WHERE userName = '{$_POST['name']}' AND jumin='{$_POST['jumin']}' ";
        //echo $que;
        $row = getRow($que);
        if($row['userId']){
            echo $row['userId'];
        } else {
            echo "N";
        }
        break;


    //비밀번호 찾기
    case 'findPw':
        $que = "SELECT userPw FROM member WHERE userName = '{$_POST['name']}' AND jumin='{$_POST['jumin']}' AND userId = '{$_POST['userId']}' ";
        //echo $que;
        $row = getRow($que);
        if($row['userPw']){
            echo $row['userPw'];
        } else {
            echo "N";
        }
        break;

    #휴대폰 번호 중복확인
    case 'findHP':
        $que = "SELECT COUNT(*) FROM members WHERE M_CP = '{$_POST['hp']}' ";
        $row = getRow($que);
        if($row[0]>0){
            echo "n";
        } else {
            echo "y";
        }
        break;
}
?>
