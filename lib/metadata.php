<?php

    require dirname(__FILE__)."/../ota-config.php";
    require dirname(__FILE__)."/easydb.php";

    //ログインしてるかどうか（してる:ID　してない:""）
    function login_check(){
        if(isset($_SESSION['login'])){
            //DB接続〜メタデータの取得まで
            $edb = new easyDB(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            $res = $edb->sql_exec("SELECT * FROM user WHERE user_id = ?",$_SESSION['login']);

            //メタデータを返却
            return $res[0];
        }else{       
            //ログインしていないから空欄を返却
            return "";
        }
    }


    //ユーザID
    function get_user_id(){
        $meta = login_check();
        if($meta==""){
            return "";
        }else{
            return $meta['user_id'];
        }
    }

    //ユーザ名
    function get_user_name(){
        $meta = login_check();
        if($meta==""){
            return "";
        }else{
            return $meta['user_name'];
        }
    }

    //ドメインの状態（noneなら「未割り当て」と表示）
    function get_domain_status(){
        $meta = login_check();
        if($meta==""){
            return "";
        }else{
            if($meta['domain_id']=='none'){     //ドメインが未割り当てである
                return "未割り当て";
            }else{                              //割り当て済みのようなので取得して返却
                $edb = new easyDB(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                $res = $edb->sql_exec("SELECT domain_name FROM domain WHERE domain_id = ?",$meta['domain_id']);
                return $res[0]['domain_name'];
            }
        }
    }
    
    function get_territory_list(){
        $meta = login_check();
        if($meta==""){
            return "";
        }else{
            if($meta['domain_id']=='none'){     //ドメインが未割り当てである
                return array();
            }else{                              //割り当て済みのようなので取得して返却
                $edb = new easyDB(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                $res = $edb->sql_exec("SELECT territory_id, territory_name FROM territory WHERE domain_id = ?",$meta['domain_id']);
                return $res;
            }
        }
    }


    function get_domain_id(){
        $meta = login_check();
        if($meta==""){
            return "";
        }else{
            return $meta['domain_id'];
        }
    }

    function get_territory_name_byid($tid){
        $domain_id = get_domain_id();
        if($domain_id!='none'){
            $edb = new easyDB(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            $res = $edb->sql_exec("SELECT territory_name FROM territory WHERE domain_id = ? AND territory_id = ?",$domain_id, $tid);
            if($res==NULL){
                return "";
            }else{
                return $res[0]['territory_name'];
            }
        }else{
            return "";
        }
    }

    function get_apartment_list_byid($tid){
        $domain_id = get_domain_id();
        if($domain_id!='none'){
            $edb = new easyDB(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            $res = $edb->sql_exec("SELECT * FROM apartment WHERE domain_id = ? AND territory_id = ?",$domain_id, $tid);
            return $res;
        }else{
            return array();
        }
    }

    function get_apartment_name_byid($apid){
        $domain_id = get_domain_id();
        if($domain_id!='none'){
            $edb = new easyDB(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            $res = $edb->sql_exec("SELECT apartment_name FROM apartment WHERE domain_id = ? AND apartment_id = ?",$domain_id, $apid);
            if($res==NULL){
                return "";
            }else{
                return $res[0]['apartment_name'];
            }
        }else{
            return "";
        }
    }

    function get_territory_id_by_apid($apid){
        $domain_id = get_domain_id();
        if($domain_id!='none'){
            $edb = new easyDB(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            $res = $edb->sql_exec("SELECT territory_id FROM apartment WHERE domain_id = ? AND apartment_id = ?",$domain_id, $apid);
            if($res==NULL){
                return "";
            }else{
                return $res[0]['territory_id'];
            }
        }else{
            return "";
        }
    }


    function get_room_list_byid($apid){
        $domain_id = get_domain_id();
        if($domain_id!='none'){
            $edb = new easyDB(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            $res = $edb->sql_exec("SELECT * FROM room WHERE domain_id = ? AND apartment_id = ?",$domain_id, $apid);
            return $res;
        }else{
            return array();
        }
    }

    function get_room_param_string($room_param){

        $str = "";

        switch($room_param){
            case 0:
                $str = "まだ";
                break;
            case 1:
                $str = "訪問済み";
                break;
            case 2:
                $str = "訪問拒否";
                break;
            case 3:
                $str = "外国語";
                break;
        }

        return $str;
    }


    function next_territory_num(){
        
    }
?>