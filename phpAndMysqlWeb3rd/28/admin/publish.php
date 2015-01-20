<?php
  include_once('include_fns.php');

  if (!check_auth_user()) 
  {
    login_form();
  }
  else 
  {
    $handle = db_connect();

    $writer = get_writer_record($_SESSION['auth_user']);

    echo '<p>Welcome, '.$writer['full_name'];
    echo ' (<a href="logout.php">Logout</a>) (<a href="index.php">Menu</a>) (<a href="../">Public Site</a>) </p>';
    
    $query = "select * from stories s, writer_permissions wp
              where wp.writer = '{$_SESSION['auth_user']}' and
                    s.page = wp.page
              order by modified desc";
    $result = $handle->query($query);

    echo '<h1>Editor admin</h1>';

    echo '<table>';
    echo '<tr><th>Headline</th><th>Last modified</th></tr>';
    while ($story = $result->fetch_assoc()) 
    {
      echo '<tr><td>';
      echo $story['headline'];
      echo '</td><td>';
      echo date('M d, H:i', $story['modified']);
      echo '</td><td>';
      if ($story[published]) 
      {
        echo '[<a href="unpublish_story.php?story='.$story['id'].'">unpublish</a>] ';
      }
      else 
      {
        echo '[<a href="publish_story.php?story='.$story['id'].'">publish</a>] ';
        echo '[<a href="delete_story.php?story='.$story['id'].'">delete</a>] ';
      }
      echo '[<a href="story.php?story='.$story['id'].'">edit</a>] ';

      echo '</td></tr>';
    }
    echo '</table>';
  }
?>