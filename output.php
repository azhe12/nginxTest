<?php
session_save_path("./save_session");
session_start();


if (!empty($_SESSION['login'])) {
    ini_set("unserialize_callback_func", "my_unserialize");
    echo "already login\n";
}

function my_unserialize($className)
{
    echo "./".$className.".php";
    require_once("./".$className.".php");
}

$person = $_SESSION['person'];

$person->output();
?>
