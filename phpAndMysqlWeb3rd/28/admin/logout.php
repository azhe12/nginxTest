<?php
  include_once('include_fns.php');

  unset($_SESSION['auth_user']);
  session_destroy();

  header('Location: '.$_SERVER['HTTP_REFERER']);
?>
