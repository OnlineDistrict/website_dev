<?php
    $t_list = get_territory_list();     //区域一覧
    
    if(count($t_list)==0){              //区域がなかった場合
        echo "ドメインに区域が存在しません。";
    }else{                              //区域が存在する場合
?>
    <ul type="disc">
    <?php
        foreach($t_list as $t){         //区域の一覧を表示
            echo '<li><a href="territory?id=' . $t['territory_id'] . '">' . $t['territory_name'] . "</a></li>";
        }
    ?>
    </ul>
<?php
    }
?>