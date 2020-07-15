<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

    $que = "SELECT G_Datetime FROM gamelist_other WHERE G_Datetime > NOW() AND GL_Key = 38918 ORDER BY G_Datetime ASC LIMIT 1";
    $row = getRow($que);
    if(!empty($row['G_Datetime'])){
        $json['remind_time'] = strtotime($row['G_Datetime'])-time();
    }
    echo json_encode($json);
?>
