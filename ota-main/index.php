<?php

    require dirname(__FILE__)."/../lib/metadata.php";

    session_start();
    if(!isset($_SESSION['login'])){
        header("Location: ../login");
        exit;
    }

?>
<!doctype html>
    <head>
        <meta charset="utf-8">
        <title>ホーム｜Online Territory</title>
    </head>
    <body>
        <h1><?php echo(get_user_name()); ?>さん，ようこそ</h1>
        <?php
            if(get_domain_id()!='none'){                //ドメイン割り当て済みの場合

                //区域の一覧はみんな表示する。
                //それ以外のメニューに関しては権限を都度参照
        ?>
            <h2>区域の一覧</h2>
            <p>
                <?php
                    include "ota-temp/index_of_territory.php";
                ?>
            </p>
            <?php
                
                include "ota-temp/index_of_admin.php";

            }else{      //ドメイン未割り当て
        ?>
        <p>
            あなたはまだどのドメインにも所属していません。<br>
            参加を希望するドメインの管理者に，あなたのユーザ名を知らせてください。
        </p>

        <?php
            }
        ?>
        <p>
            <b><?php echo(get_user_name()); ?></b>（<?php echo(get_domain_status()); ?>）<br>
            <a href="./logout.php">ログアウト</a>する
        </p>
    </body>
</html>