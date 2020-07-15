<?php
$root_path = '/bs';
$include_path = $_SERVER['DOCUMENT_ROOT'].$root_path;
include $include_path."/include/common.php";
$req = array();
if(ctype_alpha($_REQUEST['mode'])){
    $req['mode'] = $_REQUEST['mode'];
}

switch($req['mode']){
    case 'gameleague': //게임리그등록
        $flag = true;
        $error = '';
        
        $file_name = $_FILES['league_img']['name'];
        $sql = "SELECT COUNT(*) AS cnt FROM gameleague WHERE GL_Type = '{$game_league}'";
        //echo $sql;
        $row = getRow($sql);
        if($row['cnt']>0){
            $flag = false;
            $error = '같은 이름의 종목명이 있습니다.';
        } else {
            if (!empty($file_name)) {
                $ext = substr($file_name, -3);
                $srv_name = 'league_' . uniqid() . '_' . time() . '.' . $ext;
                $res = move_uploaded_file($_FILES['league_img']['tmp_name'], "../img/" . $srv_name);
                if ($res) {
                    $que = "INSERT INTO gameleague SET ";
                    $que .= "GI_Key         = '{$item}', ";
                    $que .= "GL_Type        = '{$game_league}', ";
                    $que .= "GL_OrgName     = '{$file_name}', ";
                    $que .= "GL_SrvName     = '{$srv_name}', ";
                    $que .= "GL_State       = 'Normal', ";
                    $que .= "GL_RegDate     = NOW() ";
                    echo $que;
                    $rs = setQry($que);
                    if (!$rs) {
                        $flag = false;
                        $error = '디비입력오류';
                    }
                }
            }
        }


        echo '<script>parent.call_back("'.$error.'");</script>';
        break;

    case 'gameleagueModify': //게임리그등록
        $flag = true;
        $error = '';

        $file_name = $_FILES['league_img']['name'];
        if (!empty($file_name)) {
            $ext = substr($file_name, -3);
            $srv_name = 'league_' . uniqid() . '_' . time() . '.' . $ext;
            $res = move_uploaded_file($_FILES['league_img']['tmp_name'], "../img/" . $srv_name);
            if ($res) {
                $que  = "UPDATE gameleague SET ";
                $que .= "GI_Key         = '{$item}', ";
                $que .= "GL_OrgName     = '{$file_name}', ";
                $que .= "GL_SrvName     = '{$srv_name}', ";
                $que .= "GL_Type        = '{$game_league}' ";
                $que .= " WHERE GL_Key  = '{$glkey}' ";
                echo $que;
                $rs = setQry($que);
                if (!$rs) {
                    $flag = false;
                    $error = '디비입력오류'.$que;
                }
            }
        }



        echo '<script>parent.call_back("'.$error.'");</script>';
        break;

    case 'itemDel'://경기리그삭제
        $json['flag'] = true;
        $json['error'] = '';
        $idx = implode(",",$idx);
        $sql = "SELECT * FROM gameleague WHERE GL_Key IN ({$idx})";
        $arr = getArr($sql);
        foreach($arr as $arr){
            @unlink('../img/'.$arr['GL_SrvName']);
        }

        $que = "DELETE FROM gameleague WHERE GL_Key IN ({$idx})";
        $res = setQry($que);
        if(!$res){

            $json['flag'] = false;
            $json['error'] = '디비입력오류';
        }

        echo json_encode($json);
        break;

    //리그별 게임 숨김
    case 'leagueHideGame':
        $json['flag'] = true;
        $json['error'] = '';
        if($gubun=='y'){
            $type = 'Hid';
        } else {
            $type = 'Normal';
        }
        $que = "UPDATE gameleague SET GL_State = '{$type}' WHERE GL_Key = '{$glkey}'";
        $res = setQry($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '디비입력오류';
        }

        echo json_encode($json);
        break;

}
?>