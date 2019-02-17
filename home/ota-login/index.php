<?php

    require dirname(__FILE__)."/../ota-config.php";
    require dirname(__FILE__)."/../lib/easydb.php";

    session_start();
    if(isset($_SESSION['login'])){      //もしログインしている場合メインページへ遷移
        header("Location: ../ota-main");
        exit;
    }

    //以下，ログインしていない場合の処理
    if(isset($_POST['user_id'])){

        //パラメタのエスケープ処理
        $user_id = htmlspecialchars($_POST['user_id']);
        $password = htmlspecialchars($_POST['password']);

        //DBがなんだかんだあって
        $edb = new easyDB(DB_HOST,DB_USER,DB_PASS,DB_NAME);

        //ユーザ名が存在するかチェック
        $res = $edb->sql_exec("SELECT * FROM user WHERE user_id = ?",$user_id);

        //返却値が1より小さいということはレコードがない
        if(count($res)<1){
            echo("ユーザ名が存在しません");
        }else{

            //パスワードのチェック

            //DBからの情報
            $salt = $res[0]['salt'];
            $salty_pwd = $res[0]['salty_pwd'];

            //ハッシュにかける
            $hashed = md5(md5($password).$salt);
            if($hashed!=$salty_pwd){    //パスワードが違う
                echo("入力内容に誤りがあります");
            }else{
                //ログイン中の命綱
                $_SESSION['login'] = $res[0]['user_id'];
                $_SESSION['prv'] = $res[0]['privilege_param'];
                header("Location: ../ota-main/");
                exit;
            }
        }
    }

?>
<!doctype html>
    <head>
        <meta charset="utf-8">
        <title>ログイン｜Online Territory</title>
    </head>
    <body>
        <h1>ログイン</h1>
        <p>
            <form name="login_f" action="" method="post">
                ユーザ名&nbsp;<input type="text" name="user_id" required><br>
                パスワード&nbsp;<input type="password" name="password" required><br>
                <input type="submit" name="sbm" value="ログイン">
            </form>
        </p>
        <p>
            アカウントをすでにお持ちではありませんか。<br>
            <a href="../ota-signup">新しいアカウントの作成</a>
        </p>
    </body>

</html>