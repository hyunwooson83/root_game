<?php
$root_path = '/bs';
include $_SERVER['DOCUMENT_ROOT']."/bs/lib/_lib.php";
include_once  $_SERVER['DOCUMENT_ROOT']."/bs/include_template/no_header.php";

$que = "SELECT * FROM siteconfig WHERE idx = 1";
$row = getRow($que);
?>
<body>
<div id="loading">
    <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>

<style type="text/css">
    html,body {
        height: 100%;
    }
    body {
        background: #fff;
        overflow: hidden;
    }

</style>



<img src="./blurred-bg-7.jpg" class="login-img wow fadeIn" alt="" style="position: fixed !important;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;">

<div class="center-vertical">
    <div class="center-content row">

        <div class="col-md-6 center-margin">
            <div class="server-message wow bounceInDown inverse">
                <h1 style="margin-bottom:20px;">사이트 점검중</h1>
                <h2><?php echo $row['Site_Stop_Memo']; ?></h2>
            </div>
        </div>

    </div>
</div>
</body>