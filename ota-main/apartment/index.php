<?php

    require dirname(__FILE__)."/../../lib/metadata.php";

    session_start();
    if(!isset($_SESSION['login'])){
        header("Location: ../../login");
        exit;
    }

    if(get_apartment_name_byid($_GET['id'])==""){
        echo "denied";
        exit;
    }

?>
<!doctype html>
    <head>
        <meta charset="utf-8">
        <title><?php echo(get_apartment_name_byid($_GET['id'])); ?>｜Online Territory</title>
        <script>
            function putResult(room_id, result){
                window.location.href = "ap_result.php?apid=<?php echo $_GET['id'] ?>&rmid=" + room_id + "&result=" + result;
            }
        </script>
    </head>
    <body>
        <h1><a href="../territory?id=<?php echo(get_territory_id_by_apid($_GET['id'])); ?>">&lt;&lt;</a>&nbsp;<?php echo(get_apartment_name_byid($_GET['id'])); ?></h1>
        <?php
            if(get_domain_id()!='none'){                //ドメイン割り当て済みの場合
        ?>
            <p>
                <?php
                    $rm_list = get_room_list_byid($_GET['id']);     //部屋の一覧
                    
                    if(count($rm_list)==0){              //部屋情報がなかった場合
                        echo "アパートに部屋が存在しません。";
                    }else{
                ?>
                    <table border="1">
                        <tr><th colspan="2">部屋番号</th><th>訪問回数</th><th>記録</th></tr>
                    <?php
                        foreach($rm_list as $rm){

                            //会えていない場合はボタンを表示する（他の場合は何も表示しない）
                            $btn = $rm['room_param']==0 ? '<button onclick="putResult(' . $rm['room_id'] . ',1)">会えた</button><button onclick="putResult(' . $rm['room_id'] . ',0)">留守</button>':'';
                            echo "<tr><td>" . $rm['room_name'] . "</td><td>" . get_room_param_string($rm['room_param']) . "</td><td>" . $rm['room_visit_num'] . '</td><td>' . $btn . '</td></tr>';
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