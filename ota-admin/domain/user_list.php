<?php

    require dirname(__FILE__)."/../../lib/metadata.php";

    session_start();
    if(!isset($_SESSION['login'])){
        header("Location: ../login");
        exit;
    }

    function get_user_list($prv){    //権限の値ごとにリストを返却
        $edb = new easyDB(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        $res = $edb->sql_exec("SELECT user_id, user_name, user_mail FROM user WHERE domain_id = ? AND privilege_param = ? ORDER BY user_id ASC",get_domain_id(), $prv);
        echo '<table border="1">';
        echo "<tr><th>ユーザID</th><th>名前</th><th>メールアドレス</th></tr>";
        foreach($res as $res_ida){      //一人分ずつにバラす
            echo "<tr><td>" . $res_ida['user_id'] . "</td><td>" . $res_ida['user_name'] . "</td><td>" . $res_ida['user_mail'] . "</td></tr>";
        }
        echo "</table>";
    }

?>
<!doctype html>
    <head>
        <meta charset="utf-8">
        <title>ユーザの一覧｜Online Territory</title>
    </head>
    <body>
        <h1><?php echo(get_domain_status()); ?>のユーザ一覧</h1>

        <h2>一般ユーザ</h2>
        <?php
            get_user_list(0);
        ?>

        <h2>区域の管理者</h2>
        <?php
            get_user_list(1);
        ?>

        <h2>ドメイン管理者</h2>
        <?php
            get_user_list(2);
        ?>

        <p>
            <b><?php echo(get_user_name()); ?></b>（<?php echo(get_domain_status()); ?>）<br>
            <a href="../../logout.php">ログアウト</a>する
        </p>
    </body>
</html>