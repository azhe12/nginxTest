<?php
  // writer.php Is the Interface for Writers to Manage Their Stories

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
    echo '<p>';

    $query = 'select * from stories where writer = \''.
           $_SESSION['auth_user'].'\' order by created desc';
    $result = $handle->query($query);

    echo 'Your stories: ';
    echo $result->num_rows;
    echo ' (<a href="story.php">Add new</a>)';
    echo '</p><br /><br />';
    
    if ($result->num_rows) 
    {
      echo '<table>';
      echo '<tr><th>Headline</th><th>Page</th>';
      echo '<th>Created</th><th>Last modified</th></tr>';
      while ($stories = $result->fetch_assoc()) 
      {
        echo '<tr><td>';
        echo $stories['headline'];
        echo '</td><td>';
        echo $stories['page'];
        echo '</td><td>';
        echo date('M d, H:i', $stories['created']);
        echo '</td><td>';
        echo date('M d, H:i', $stories['modified']);
        echo '</td><td>';
        if ($stories['published'])
        {
          echo '[Published '.date('M d, H:i', $stories['published']).']';
        }
        else 
        {
          echo '[<a href="story.php?story='.$stories['id'].'">edit</a>] ';
          echo '[<a href="delete_story.php?story='.$stories['id'].'">delete</a>] ';
        }
        echo '[<a href="keywords.php?story='.$stories['id'].'">keywords</a>]';
        echo '</td></tr>';
      }
      echo '</table>';
    }
  }
?>
