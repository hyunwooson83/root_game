<div class="left_con" style="display: block;">
    <h1>종목별 경기목록</h1>
    <div style="width: 100%; height:50px;" >

    </div>
    <table>
        <form name="sf" method="get">
            <tr style="height:40px;">
                <input type="hidden" name="search_item" id="search_item" value="<?php echo $search_item;?>">
                <input type="hidden" name="glkey" id="glkey">
                <td colspan="2" style="text-align: center;"><input type="text" name="search_text" value="<?php echo $search_text;?>" placeholder="팀명입력"><input type="submit" name="search_btn" value="검색"></td>
            </tr>
        </form>
        <?php
        $item_array = array(6046,48242,154914,154830,35232);
        $item_name = array('icon_soccer','icon_basketball','icon_baseball','icon_volley','icon_hockey');
        $item_name_kor = array('축구','농구','야구','배구','아이스하키');
        for($i=0;$i<count($item_array);$i++){
            ?>
            <tr class="show-sub-div show-sub-page" data-key="<?php echo $item_array[$i];?>">
                <td class="left-league-name"> <img src="/img/<?php echo $item_name[$i];?>.png" style="width:18px;"> <?php echo $item_name_kor[$i]; ?></td>
                <td class="right-league-cnt padR10">
                    <?php
                    $gicode = "";
                    if($item_name_kor[$i]=='축구') {
                        echo ($item_info['cnt'][6046] > 0) ? $item_info['cnt'][6046] : '0';
                        $gicode = $item_array[$i];
                    } else if($item_name_kor[$i]=='농구') {
                        echo ($item_info['cnt'][48242] > 0) ? $item_info['cnt'][48242] : '0';
                        $gicode = $item_array[$i];
                    } else if($item_name_kor[$i]=='야구') {
                        echo ($item_info['cnt'][154914] > 0) ? $item_info['cnt'][154914] : '0';
                        $gicode = $item_array[$i];
                    } else if($item_name_kor[$i]=='배구') {
                        echo ($item_info['cnt'][154830] > 0) ? $item_info['cnt'][154830] : '0';
                        $gicode = $item_array[$i];
                    } else if($item_name_kor[$i]=='아이스하키') {
                        echo ($item_info['cnt'][35232] > 0) ? $item_info['cnt'][35232] : '0';
                        $gicode = $item_array[$i];
                    }
                    ?>
                </td>
            </tr>
            <!-- 서브 페이지 시작 -->
            <tr id="sub_page_<?php echo $item_array[$i];?>" class="<?php echo ($search_item==$item_array[$i])?'d-show':'d-hide';?>" >
                <td colspan="2">
                    <table>
                        <?php
                        $que = "SELECT COUNT( DISTINCT (
                                            inPlayMatchIdx
                                            ) ) AS cnt, GL_Type, GL_Key_IDX, GL_SrvName FROM gamelist a
                                            LEFT JOIN gameitem c ON a.GI_Key = c.GI_Key
                                            LEFT JOIN gameleague b ON a.GL_Key = b.GL_Key_IDX
                                            WHERE 1
                                            AND G_Locked = '1'
                                            AND G_Datetime > NOW( )
                                            AND G_QuotaWin >0
                                            AND a.GI_Key = '{$gicode}'
                                            AND c.GI_State = 'Normal'
                                            AND b.GL_State = 'Normal'
                                            GROUP BY GL_Key_IDX ";
                        $lg = getArr($que);
                        if(count($lg)>0){
                            foreach($lg as $lg){
                                ?>
                                <tr class="search-league" data-glkey="<?php echo $lg['GL_Key_IDX'];?>" data-gikey="<?php echo $gicode;?>">
                                    <td class="left-league-name padL15 <?php echo ($lg['GL_Key_IDX']==$glkey)?'text-color-yellow':'';?>"><img src="/img/league/<?php echo (!empty($lg['GL_SrvName']))?$lg['GL_SrvName']:'noimage.png';?>" style="width:18px;"> <?php echo $lg['GL_Type']; ?></td>
                                    <td class="right-league-cnt padR10 <?php echo ($lg['GL_Key_IDX']==$glkey)?'text-color-yellow':'';?>"><?php echo $lg['cnt']; ?></td>
                                </tr>
                            <?php }} ?>
                    </table>
                </td>
            </tr>
        <?php }//} ?>
        <!-- 서브 페이지 끝 -->
    </table>

</div>