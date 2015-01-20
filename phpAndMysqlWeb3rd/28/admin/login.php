<?php
  include_once('include_fns.php');

  if ( (!isset($_REQUEST['username'])) || (!isset($_REQUEST['password'])) ) 
  {
    echo 'You must enter your username and password to proceed';
    exit;
  }

  $username = $_REQUEST['username'];
  $password = $_REQUEST['password'];

  if (login($username, $password)) 
  {
    $_SESSION['auth_user'] = $username;
    header('Location: '.$_SERVER['HTTP_REFERER']);
  }
  else 
  {
    echo 'The password you entered is incorrect';
    exit;
  }
?>
