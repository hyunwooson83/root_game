<?php
    ini_set("display_errors",1);
    include $_SERVER['DOCUMENT_ROOT']."/lib/_lib.php";

    $que = "SELECT * FROM siteconfig WHERE idx = 1";
    $row = getRow($que);
?>
<!-- 합쳐지고 최소화된 최신 CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

<!-- 부가적인 테마 -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

<!-- 합쳐지고 최소화된 최신 자바스크립트 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<body>
<style type="text/css">
    html,body {
        height: 100%;
    }
    body {
        background: #fff;
        overflow: hidden;
    }
    .center-margin {
        float: none !important;
        margin: 0 auto;
    }
    .server-message.inverse {
        color: rgba(255, 255, 255, .8);
    }
    .server-message {
        text-align: center;
        color: #888;
    }
    .bounceInDown {
        -webkit-animation-name: bounceInDown;
        animation-name: bounceInDown;
    }

    .server-message h1 {
        font-size: 85px;
        margin: 0;
    }

    h1, h2, h3, h4, h5, h6, #page-title > h2, #page-title > p {
        font-family: "Raleway", "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-weight: 300;
    }
    .center-vertical {
        position: relative;
        z-index: 15;
        top: 0;
        left: 0;
        display: table;
        width: 100%;
        height: 100%;
    }
    .center-vertical .center-content {
        display: table-cell;
        vertical-align: middle;
    }
    .row, .form-row {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        margin-right: -10px;
        margin-left: -10px;
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
                <h1 style="margin-bottom:20px; text-shadow: 2px 2px 2px #22252a;">사이트 점검중</h1>
                <h2><?php echo $row['Site_Stop_Memo']; ?></h2>
            </div>
        </div>
    </div>
</div>
</body>