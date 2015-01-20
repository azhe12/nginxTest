<?php
  // keywords.php
  include_once('include_fns.php');

  if (!check_auth_user()) 
  {
     login_form();
  }
  else if(check_permission($_SESSION['auth_user'], $_REQUEST['story']))
  {
    $handle = db_connect();
    $story_code = $_REQUEST['story'];
    $story = get_story_record($story_code);
    echo "<h2>Keywords for <i>{$story['headline']}</i></h2>
          <form action='keyword_add.php' method='POST'>
          <input type=hidden name='story' value='$story_code'>
          <input size='20' name='keyword'>
          <select name='weight'>
            <option>10</option>
            <option>9</option>
            <option>8</option>
            <option>7</option>
            <option>6</option>
            <option>5</option>
            <option>4</option>
            <option>3</option>
            <option>2</option>
            <option>1</option>
          </select>
          <input type='submit' value='Add'>
          </form>";

    $query = "select * from keywords where story = $story_code
            order by weight desc, keyword";
    $result = $handle->query($query);
    if ($result->num_rows) 
    {
      echo '<table>';
      echo '<tr><th>Keyword</th><th>Weight</th></tr>';
      while ($keyword = $result->fetch_assoc()) 
      {
        echo "<tr><td> 
              {$keyword['keyword']}
              </td><td>
              {$keyword['weight']}
              </td><td>
              [<a href='keyword_delete.php?story=$story_code&keyword=";
        echo urlencode($keyword['keyword']);
        echo "'>del</a>]
             </td></tr>";
      }
      echo '</table>';
    }
  }
?>
