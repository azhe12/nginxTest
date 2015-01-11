<?php
require_once("./person.php");

echo "cookie:\n";
var_dump($_COOKIE);

session_save_path("./save_session");
session_start();

if (empty($_SESSION['login'])) {
    $_SESSION['login'] = 'true';

    echo "first\n";
} else {
    $person = new Person(24);
    $_SESSION['person'] = $person;
    echo "not first\n";
}
?>
