<?php
  if (!isset($_REQUEST['page'])&&!isset($_REQUEST['story']))
  {
    header('Location: index.php');
    exit;
  }

  $page = $_REQUEST['page'];
  $story = intval($_REQUEST['story']);

  include_once('db_fns.php');
  include_once('header.php');

  $handle = db_connect();
  if($story)
  {
    $query = "select * from stories 
              where id = '$story' and
                    published is not null";
  }
  else
  {
    $query = "select * from stories 
              where page = '$page' and
                    published is not null
              order by published desc";
  }
  $result = $handle->query($query);

  while ($story = $result->fetch_assoc()) 
  {
    // headline
    echo "<h2>{$story['headline']}</h2>";
    //picture
    if ($story['picture']) 
    {
      echo '<div style="float:right; margin:0px 0px 6px 6px;">';
      echo '<img src="resize_image.php?image=';
      echo urlencode($story[picture]);
      echo '&max_width=200&max_height=120"  align = right/></div>';
    }
    // byline
    $w = get_writer_record($story['writer']);
    echo '<br /><p class="byline">';
    echo $w[full_name].', ';
    echo date('M d, H:i', $story['modified']);
    echo '</p>';
    // main text
    echo $story['story_text'];  
  }
  include_once('footer.php');
?>