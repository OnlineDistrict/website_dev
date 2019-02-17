<?php
    session_start();
    session_destroy();

    header("Location: ../ota-login/");
    exit;
?>