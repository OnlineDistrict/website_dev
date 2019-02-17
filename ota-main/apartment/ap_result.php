<?php

    //メタデータ用
    require dirname(__FILE__)."/../../lib/metadata.php";

    //ログインチェック
    session_start();
    if(!isset($_SESSION['login'])){
        header("Location: ../../login");
        exit;
    }

    $edb = new easyDB(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $res = $edb->sql_exec("SELECT room_visit_num FROM room WHERE domain_id = ? AND apartment_id = ? AND room_id = ?",get_domain_id(), $_GET['apid'], $_GET['rmid']);

    //これまでの訪問回数（今回訪問したので++）
    $room_visit_num = $res[0]['room_visit_num'] + 1;
    $res = $edb->sql_exec("UPDATE room SET room_visit_num = ? WHERE domain_id = ? AND apartment_id = ? AND room_id = ?",$room_visit_num,get_domain_id(), $_GET['apid'], $_GET['rmid']);

    if($_GET['result']==1){
        $res = $edb->sql_exec("UPDATE room SET room_param = 1 WHERE domain_id = ? AND apartment_id = ? AND room_id = ?",get_domain_id(), $_GET['apid'], $_GET['rmid']);
    }

    header("Location: ../apartment?id=" . $_GET['apid']);
    exit;


?>