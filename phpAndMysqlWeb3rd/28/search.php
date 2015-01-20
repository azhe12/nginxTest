<?php

  include_once('db_fns.php');
  include_once('header.php');

  $handle = db_connect();

  if ($_REQUEST['keyword']) 
  {
    $keywords = split(' ', $_REQUEST['keyword']);
    $num_keywords = count($keywords);
    for ($i=0; $i<$num_keywords; $i++) 
    {
      if ($i)
      {
        $keywords_string .= "or k.keyword = '".$keywords[$i]."' ";
      }
      else
      {
        $keywords_string .= "k.keyword = '".$keywords[$i]."' ";
      }
    }
    
    $query = "select s.id,
                     s.headline,
                     10 * sum(k.weight) / $num_keywords as score
              from stories s, keywords k
              where s.id = k.story
                    and ($keywords_string)
                    and published is not null
              group by s.id, s.headline
              order by score desc, s.id desc";


    $result = $handle->query($query);
  }
  echo '<h2>Search results</h2>';

  if ($result  && $result->num_rows) 
  {
    echo '<table>';
    while ($matches = $result->fetch_assoc()) 
    {
      echo "<tr><td><a href='page?story={$matches['id']}'>
             {$matches['headline']}
             </td><td>";
      echo floor($matches['score']).'%';
      echo '</td></tr>';
    }
    echo '</table>';
  }
  else 
  {
    echo 'No matching stories found';
  }
  include_once('footer.php');
?>