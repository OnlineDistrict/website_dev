<?php

    require dirname(__FILE__)."/../../lib/metadata.php";

    session_start();
    if(!isset($_SESSION['login'])){
        header("Location: ../login");
        exit;
    }

    //区域セットの新規作成
    if(isset($_POST['territory_name'])){
        $edb = new easyDB(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        $res = $edb->sql_exec("INSERT INTO territory(domain_id,territory_id,territory_name) VALUES(?,?,?)",get_domain_id(),next_trerritory_num(),$_POST['territory_name']);
    }

?>
<!doctype html>
    <head>
        <meta charset="utf-8">
        <title>新しい区域の追加｜Online Territory</title>
    </head>
    <body>
        <h1>新しい区域の追加</h1>

        <h2>区域セットの新規作成</h2>
        <form name="new_territory" action="" method="post">
            区域名&nbsp;<input type="text" name="territory_name" required>&nbsp;<button type="submit">新規追加</button>
        </form>

        <hr>

        <h2>物件の新規作成</h2>
        <form name="new_apartment" action="" method="post">
            <h3>基本情報</h3>
            物件名&nbsp;<input type="text" name="apartment_name" required><br>
            住所&nbsp;<input type="text" name="apartment_address" required><br>
            所属する区域&nbsp;
            <select>
                <?php
                    //ドメイン内の区域一覧を吸い出す


                ?>
            </select><br>
            GPS座標&nbsp;緯度<input type="text" name="apartment_latitude" required>&nbsp;経度<input type="text" name="apartment_longtitude" required><br>
            
            <h3>部屋の情報</h3>
            部屋番号を半角カンマ区切りで入力してください。（例:&nbsp;管理人室,101,102,103...）<br>
            羅列&nbsp;<input type="text" name="room_numbers" required><br><br>
            <button type="submit">新規追加</button>
        </form>

        <hr>

        <p>
            <b><?php echo(get_user_name()); ?></b>（<?php echo(get_domain_status()); ?>）<br>
            <a href="../../logout.php">ログアウト</a>する
        </p>
    </body>
</html>