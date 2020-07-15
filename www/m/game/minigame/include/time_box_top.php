<style>
    .active { border-top:#ff7f00 solid 3px !important; }
</style>
<?php
    $link = explode("/",$_SERVER['PHP_SELF']);
?>
<div class="nmenu_cate2 mini" style="margin-left:1.5em">
    <dl class="betlist betlist3">
        <dd onclick="location.href='/m/game/minigame/powerball/'" class="<?php echo ($link[4]=='powerball')?'active':''; ?>"><img src="/m/img/minigame_title1.png" /><h1 /><span id="powerball_time_box">00:00</span></h1></dd>
        <dd onclick="location.href='/m/game/minigame/pwladder/'" class="<?php echo ($link[4]=='pwladder')?'active':''; ?>"><img src="/m/img/minigame_title2.png" /><h1 /><span id="powerball_time_box2">00:00</span></h1></dd>
        <!--<dd onclick="location.href='/mobile/minigame/kino_speed.html'"><img src="/mobile/img/minigame_title3.png" /><h1 /><span>00:37</span></h1></dd>-->
        <dd onclick="location.href='/m/game/minigame/kenoladder/'" class="<?php echo ($link[4]=='kenoladder')?'active':''; ?>"><img src="/m/img/minigame_title4.png" /><h1 /><span id="keno_time_box2">00:00</span></h1></dd>
    </dl>
</div>
<em class="nmenu_cate2_left" onClick="$('.nmenu_cate2').scrollLeft(-300);"><img src="/mobile/img/mini_top_leftarr.png" /></em>
<em class="nmenu_cate2_right" onClick="$('.nmenu_cate2').scrollLeft(1000);"><img src="/mobile/img/mini_top_rightarr.png" /></em>