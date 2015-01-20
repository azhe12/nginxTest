<?php
  // delete_story.php 

  include_once('include_fns.php');

  $handle = db_connect();

  $story = $_REQUEST['story'];
  if(check_permission($_SESSION['auth_user'], $story))
  {
    $query = "delete from stories where id = $story";
    $result = $handle->query($query);
  }
  header('Location: '.$_SERVER['HTTP_REFERER']);
?> 