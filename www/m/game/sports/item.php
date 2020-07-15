<div class="tgame_slide">
    <ul class="tgame_type_sports">
        <li class="<?php echo empty($search_item)?'active':'';?>" onclick="location.href = './';">
            <em><img src="/mobile/img/sub/icon_all_category.png" /></em>
            <div>전체종목</div>
            <span id="item_total"><?php echo $total_item;?></span>
            <var></var>
        </li>
        <li class="<?php echo ($search_item==6046)?'active':'';?>" onclick="location.href = './?search_item=6046';">
            <em><img src="/mobile/img/sub/icon_soccer.png" /></em>
            <div>축구</div>
            <span id="item_soccer"><?php echo ($item_info['cnt'][6046]>0)?$item_info['cnt'][6046]:'0';?></span>
            <var></var>
        </li>
        <li class="<?php echo ($search_item==48242)?'active':'';?>" onclick="location.href = './?search_item=48242';">
            <em><img src="/mobile/img/sub/icon_basketball.png" /></em>
            <div>농구</div>
            <span><?php echo ($item_info['cnt'][48242]>0)?$item_info['cnt'][48242]:'0';?></span>
            <var></var>
        </li>
        <li class="<?php echo ($search_item==154914)?'active':'';?>" onclick="location.href = './?search_item=154914';">
            <em><img src="/mobile/img/sub/icon_baseball.png" /></em>
            <div>야구</div>
            <span id="item_baseball"><?php echo ($item_info['cnt'][154914]>0)?$item_info['cnt'][154914]:'0'?></span>
            <var></var>
        </li>
        <li class="<?php echo ($search_item==154830)?'active':'';?>" onclick="location.href = './?search_item=154830';">
            <em><img src="/mobile/img/sub/icon_volleyball.png" /></em>
            <div>배구</div>
            <span id="volleyball"><?php echo ($item_info['cnt'][154830]>0)?$item_info['cnt'][154830]:'0';?></span>
            <var></var>
        </li>
        <li class="<?php echo ($search_item==35232)?'active':'';?>" onclick="location.href = './?search_item=35232';">
            <em><img src="/mobile/img/sub/icon_hockey.png" /></em>
            <div>하키</div>
            <span id="hockey"><?php echo ($item_info['cnt'][35232]>0)?$item_info['cnt'][35232]:'0';?></span>
            <var></var>
        </li>
    </ul>
</div>
<div class="tgame_guide">
    <div><img src="/mobile/img/sports_type_select.png" /></div>
</div>