<?php
  // story_submit.php
  // add / modify story record

  include_once('include_fns.php');

  $handle = db_connect();

  $headline = $_REQUEST['headline'];
  $page = $_REQUEST['page'];
  $time = time();

  if ( (isset($_FILES['html']['name']) && 
       (dirname($_FILES['html']['type']) == 'text') &&
       is_uploaded_file($_FILES['html']['tmp_name']))) 
  {
    $story_text = file_get_contents($_FILES['html']['tmp_name']);
  }
  else
  {
    $story_text = $_REQUEST['story_text'];
  }

  $story_text = addslashes($story_text);

  if (isset($_REQUEST['story']) && $_REQUEST['story']!='') 
  {   // It's an update
    $story = $_REQUEST['story'];

    $query = "update stories
              set headline = '$headline', 
                  story_text = '$story_text',
                  page = '$page',
                  modified = $time
              where id = $story";
  }
  else 
  {         // It's a new story
    $query = "insert into stories 
                (headline, story_text, page, writer, created, modified)
              values 
                ('$headline', '$story_text', '$page', '".
               $_SESSION['auth_user']."', $time, $time)";
  }

  $result = $handle->query($query);

  if (!$result) 
  {
    echo "There was a database error when executing <pre>$query</pre>";
    echo mysqli_error();
    exit;
  }

  if ( (isset($_FILES['picture']['name']) && 
        is_uploaded_file($_FILES['picture']['tmp_name']))) 
  {

    if (!isset($_REQUEST['story']) || $_REQUEST['story']=='') 
    {
      $story = mysqli_insert_id($handle);
    }
    $type = basename($_FILES['picture']['type']);

    switch ($type) {
      case 'jpeg':
      case 'pjpeg':   $filename = "images/$story.jpg";
                      move_uploaded_file($_FILES['picture']['tmp_name'], 
                                         '../'.$filename);
                      $query = "update stories
                                set picture = '$filename'
                                where id = $story";
                      $result = $handle->query($query);
                      break;
      default:        echo 'Invalid picture format: '.
                            $_FILES['picture']['type'];
    }
  }

  header('Location: '.$_REQUEST['destination']);
?>
