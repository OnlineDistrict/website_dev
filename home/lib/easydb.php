<?php

    //DB接続をラクにするために一連の処理をクラス化

    class easyDB{
        public $mysqli;         //mysqli()
        public $count;          //SQLで何とかかんとかした行数
        public $err_msg = "";   //接続エラーの時とかここにエラーメッセージを入れておく

        //コンストラクタ
        function __construct($host,$user,$pwd,$db){

            //DBに接続を試みる（ホスト名，ユーザ名，パスワード，DB）
            $this->mysqli = new mysqli($host,$user,$pwd,$db);

            if($this->mysqli->connect_error){   //接続失敗
                //エラー内容をプロパティに書き込んで終了
                $this->err_msg = $this->mysqli->connect_error;
                exit;
            }else{
                //仕方がないのでutf-8で強制
                $this->mysqli->set_charset("utf-8");
            }
        }

        //デストラクタ
        function __destruct(){
            //接続のクローズ
            $this->mysqli->close();
        }

        function sql_exec($sql, ...$bind_ags){
            //SQL文のプリペア
            if($stmt = $this->mysqli->prepare($sql)){
                //バインドする変数の型を判別して$args_strにセット
                $args_str = $this->getArgTypeStr($bind_ags);

                //バインドする変数の型パラメタと変数たちを一つの配列にまとめる
                $stmtParams = array($args_str);                         //まず型パラメタだけ埋め込んで
                
                //引数として渡されていたバインドする変数をマージ
                foreach($bind_ags as $key=>$vals){                      //配列ある分だけ
                    $stmtParams[] = &$bind_ags[$key];                   //要素を付け足し
                }
                
                //call_user_func_array()を使用し，配列を引数の集合として関数に投げる
                call_user_func_array(array($stmt,'bind_param'),$stmtParams);

            }else{
                //エラー処理。先述のものと同様
                $this->err_msg = $this->mysqli->connect_error;
                return false;
            }
            
            //実行
            $stmt->execute();

            $resp = 0;                  //返却値の形を決定するため
            $result_array = array();    //SELECTの場合の結果を入れる配列
            $column_name = array();     //SELECTの場合，カラム名を入れる配列

            //クエリの結果をgetする（SELECT以外の場合はfalse，SELECTの場合は結果セットを返却する）
            $query_result = $stmt->get_result();

            if(!$query_result){      //SELECT関連のクエリではなかった
                $resp = 0;
            }else{                  //SELECTクエリが成功した

                //クエリ結果のフィールド情報を読み取る
                $finfo = $query_result->fetch_fields();
                foreach ($finfo as $val) {          //列の数だけループして
                    $column_name[] = $val->name;    //カラム名を読み取る（これを後に配列のキーとして用いる）
                }

                $inum = 0;
                while ($row = $query_result->fetch_array(MYSQLI_NUM)){      //選択結果を一行ずつ読み取る
                    $jnum = 0;
                    foreach ($row as $r){   //渡された一行のうち列ごとに分けて格納
                        //ここで先ほどのカラム名をキーとして指定している
                        $result_array[$inum][$column_name[$jnum]] = $r;
                        $jnum++;
                    }
                    $inum++;
                }

                //選択した行の数を格納する
                $stmt->store_result();
                $this->count = $stmt->num_rows;

                $resp = 1;
            }

            //終了
            $stmt->close();

            //先ほど吟味した内容に基づいて返却値を変える
            if($resp==0){   //trueかfalse
                return $query_result;
            }else{          //結果セット
                return $result_array;
            }
        }

        //バインドする変数の型を表すパラメタを生成
        function getArgTypeStr($vv){
            $typeC = "";    //返却用
            //配列要素を一個一個見ていく
            foreach($vv as $v){
                if(is_int($v)){           //整数
                    $typeC .= "i";
                }else if(is_double($v)){  //小数（double）
                    $typeC .= "d";
                }else{                    //あとは文字列
                    $typeC .= "s";
                }
            }
            return $typeC;
        }
        
    }
    
?>