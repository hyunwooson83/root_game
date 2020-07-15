<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';

    $que = "SELECT GI_Key, COUNT(*) FROM gamelist WHERE G_OddsState = 0 AND G_Datetime > NOW() GROUP BY GI_Key";