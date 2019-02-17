<?php

    require dirname(__FILE__)."/../../lib/metadata.php";

    session_start();
    if(!isset($_SESSION['login'])){
        header("Location: ../../login");
        exit;
    }

    if(get_territory_name_byid($_GET['id'])==""){
        echo "denied";
        exit;
    }

?>
<!doctype html>
    <head>
        <meta charset="utf-8">
        <title><?php echo(get_territory_name_byid($_GET['id'])); ?>｜Online Territory</title>
    </head>
    <body>
        <h1><a href="../">&lt;&lt;</a>&nbsp;<?php echo(get_territory_name_byid($_GET['id'])); ?>の物件一覧</h1>
        <?php
            if(get_domain_id()!='none'){                //ドメイン割り当て済みの場合
        ?>
            <h2>物件の一覧</h2>
            <p>
                <?php
                    $ap_list = get_apartment_list_byid($_GET['id']);     //アパート一覧
                    
                    if(count($ap_list)==0){              //アパートがなかった場合
                        echo "区域にアパートが存在しません。";
                    }else{
                ?>
                    <table border="1">
                        <tr><th>物件名</th><th>住所</th><th colspan="2">メモ</th></tr>
                    <?php
                        foreach($ap_list as $ap){
                            echo '<tr><td><a href="../apartment?id=' . $ap['apartment_id'] . '">' . $ap['apartment_name'] . "</a></td><td>" . $ap['apartment_address'] . '</td><td>' . $ap['apartment_memo'] . '</td><td><a href="https://www.google.com/maps?q=' . $ap['apartment_latitude'] . ',' . $ap['apartment_longitude'] . '" target="_blank">地図</a></td>';
                        }
                    ?>
                    </table>
                <?php
                    }
                ?>
            </p>
            <?php
            }
        ?>
        <p>
            <b><?php echo(get_user_name()); ?></b>（<?php echo(get_domain_status()); ?>）<br>
            <a href="../logout.php">ログアウト</a>する
        </p>
    </body>
</html>