<?php

    require dirname(__FILE__)."/../ota-config.php";
    require dirname(__FILE__)."/../lib/easydb.php";

    session_start();
    if(isset($_SESSION['login'])){      //もしログインしている場合メインページへ遷移
        header("Location: ../main");
        exit;
    }

    if(isset($_POST['user_id'])){

        //パラメタのエスケープ処理
        $user_id = htmlspecialchars($_POST['user_id']);
        $password = htmlspecialchars($_POST['password']);
        $re_password = htmlspecialchars($_POST['re_password']);
        $user_name = htmlspecialchars($_POST['user_name']);
        $user_mail = htmlspecialchars($_POST['user_mail']);

        //DBがなんだかんだあって
        $edb = new easyDB(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        
        //ユーザ名が存在するかチェック
        $res = $edb->sql_exec("SELECT * FROM user WHERE user_id = ?",$user_id);
        if(count($res)==0){

            if($password!=$re_password){    //パスワードが違う
                header("Location: ../ota-signup?e=2");
                exit;
            }

            $seed_str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPUQRSTUVWXYZ';
            $salt = substr(str_shuffle($seed_str), 0, 12);
            $salty_pwd = md5(md5($password).$salt);

            $res = $edb->sql_exec("INSERT INTO user(domain_id,user_id,salt,salty_pwd,user_name,user_mail,privilege_param) VALUES(?,?,?,?,?,?,?);","none",$user_id,$salt,$salty_pwd,$user_name,$user_mail,0);

            header("Location: ../ota-login");
            exit;

        }else{  //重複したユーザ名
            header("Location: ../ota-signup/?e=1");
            exit;
        }

    }

?>
<!doctype html>
    <head>
        <meta charset="utf-8">
        <title>新規アカウント｜Online Territory</title>
    </head>
    <body>
        <h1>新しいアカウントの作成</h1>
        <?php

            if(isset($_GET['e'])){
                $err = $_GET['e'];
                $err_msg = "";
                switch($err){
                    case 1:
                        $err_msg = "そのユーザ名は使用できません。";
                        break;
                    case 2:
                        $err_msg = "パスワードが異なっています。";
                        break;
                }
                echo("<p>$err_msg</p>");
            }

        ?>
        <p>
            <form name="signup_f" action="" method="post">
                ユーザ名&nbsp;<input type="text" name="user_id" required><br>
                Eメール&nbsp;<input type="mail" name="user_mail" required><br>
                あなたの名前&nbsp;<input type="text" name="user_name" required><br>
                パスワード&nbsp;<input type="password" name="password" required><br>
                パスワード（再入力）&nbsp;<input type="password" name="re_password" required><br>
                <input type="submit" name="sbm" value="新規作成">
            </form>
        </p>
        <p>
            アカウントをすでにお持ちであれば<a href="../ota-login">ログイン</a>してください。
        </p>
    </body>

</html>