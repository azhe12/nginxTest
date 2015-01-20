<?php

  include_once('include_fns.php');

  $handle = db_connect();
  $story = $_REQUEST['story'];
  $keyword = $_REQUEST['keyword'];
  $weight = $_REQUEST['weight'];

  if(check_permission($_SESSION['auth_user'], $story))
  {
    $query = "insert into keywords (story, keyword, weight)
              values ($story, '$keyword', $weight)";

    $handle->query($query);
  }
  header("Location: keywords.php?story=$story");

?>
