<style>
    .on1 { border-top:#ff7f00 solid 3px !important; }
</style>
<?php
    $link = explode("/",$_SERVER['PHP_SELF']);
?>
<table class="game-time-list">
    <colgroup>
        <col width="33.3%">
        <col width="33.3%">
        <col width="33.3%">
        <!--<col width="11%">
        <col width="11%">
        <col width="11%">
        <col width="11%">
        <col width="*">-->
    </colgroup>
    <thead>
        <tr>
            <td scope="col" style="<?php echo ($link[3]=='powerball')?'border-top:#ff7f00 solid 3px !important;':''; ?>"><a href="/game/minigame/powerball/">파워볼</a></td>
            <td scope="col" style="<?php echo ($link[3]=='pwladder')?'border-top:#ff7f00 solid 3px !important;':''; ?>"><a href="/game/minigame/pwladder/">파워사다리</a></td>
            <td scope="col" style="<?php echo ($link[3]=='kenoladder')?'border-top:#ff7f00 solid 3px !important;':''; ?>"><a href="/game/minigame/kenoladder/">키노사다리</a></td>
            <!--<td scope="col">파워프리킥</td>
            <td scope="col">스피드홈런</td>
            <td scope="col">파워스피드덩크</td>-->
            <!--<td scope="col">벳-축구</td>
            <td scope="col">벳-농구</td>
            <td scope="col">벳-야구</td>
            <td scope="col">베-크리켓</td>-->
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="<?php echo ($link[3]=='powerball')?'border-bottom:#ff7f00 solid 3px !important;':''; ?>"><span class="js-countdown-powerball" id="powerball_time_box">00:00</span></td>
            <td style="<?php echo ($link[3]=='pwladder')?'border-bottom:#ff7f00 solid 3px !important;':''; ?>"><span class="js-countdown-powerladder" id="powerball_time_box2">00:00</span></td>
            <td style="<?php echo ($link[3]=='kenoladder')?'border-bottom:#ff7f00 solid 3px !important;':''; ?>"><span class="js-countdown-kenoladder" id="keno_time_box2">00:00</span></td>
            <!--<td><span class="js-countdown-soccer" id="soccer_time_box">00:00</span></td>-->
            <!--<td><span class="js-countdown-kenoladder" id="keno_time_box2">00:00</span></td>
            <td><span class="js-countdown-dunk" id="dunk_time_box">00:00</span></td>-->
            <!--<td><span class="js-countdown-basketball" id="basketball_time_box">00:00</span></td>
            <td><span class="js-countdown-baseball" id="baseball_time_box">00:00</span></td>
            <td><span class="js-countdown-cricket" id="cricket_time_box">00:00</span></td>-->
        </tr>
    </tbody>

</table>