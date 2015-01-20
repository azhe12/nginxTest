<?php
  include_once('include_fns.php');

  $handle = db_connect();
  $story = $_REQUEST['story'];
  $keyword = $_REQUEST['keyword'];
  if(check_permission($_SESSION['auth_user'], $story))
  {
    $query = "delete from keywords where story = $story and keyword = '$keyword'";

    $handle->query($query);
  }
  header("Location: keywords.php?story=$story");
?>
