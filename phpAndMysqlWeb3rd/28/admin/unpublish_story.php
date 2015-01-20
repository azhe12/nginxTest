<?php
  // unpublish_story.php -- action a release, go back to release.php

  include_once('include_fns.php');

  $story = $_REQUEST['story'];

  if(check_permission($_SESSION['auth_user'], $story))
  {
    $handle = db_connect();
    $query = "update stories set published = null
              where id = $story";
    $result = $handle->query($query);
  }
  header('Location: '.$_SERVER['HTTP_REFERER']);
?>
