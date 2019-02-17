<?php
    //（ドメイン内の）区域管理者
    if($_SESSION['prv']>0){     //0は権限なしなので
        
        echo "<hr>";
        echo "<h2>区域の管理</h2>";

        include "territory_admin_menu.php";

        //ドメイン管理者
        if($_SESSION['prv']==2){
            
            echo "<hr>";
            echo "<h2>ドメインの管理</h2>";

            include "domain_admin_menu.php";

        }

    }

?>