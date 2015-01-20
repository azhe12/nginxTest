<?php
  include_once('db_fns.php');
  include_once('header.php');

  $handle = db_connect();

  $pages_sql = 'select * from pages order by code';
  $pages_result = $handle->query($pages_sql);

  echo '<table border="0" width="400">';
      
  while ($pages = $pages_result->fetch_assoc()) 
  {
    $story_sql = "select * from stories
                  where page = '{$pages['code']}'
                  and published is not null
                  order by published desc";
   
    $story_result = $handle->query($story_sql);
    
    if ($story_result->num_rows) 
    {
      $story = $story_result->fetch_assoc();
      echo "<tr>
            <td>
              <h2>{$pages['description']}</h2>
              <p>{$story['headline']}</p>
              <p align='right' class='morelink'>
                <a href='page.php?page={$pages['code']}'>
                Read more {$pages['code']} ...
                </a>
              </p>
            </td>
            <td width='100'>";
      if ($story['picture'])
      {
        echo '<img src="resize_image.php?image=';
        echo urlencode($story[picture]);
        echo '&max_width=80&max_height=60"  />';
      }
      echo '</td></tr>';
    }
  }
  echo '</table>';

  include_once('footer.php');
?>