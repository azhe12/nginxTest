<?php

function db_connect()
{
   $handle = new mysqli('localhost', 'content', 'password', 'content'); 
   if (!$handle)
   {
     return false;
   }
   return $handle;
}

function get_writer_record($username)
{
  $handle = db_connect();
  $query = "select * from writers where username = '$username'";
  $result = $handle->query($query);
  return($result->fetch_assoc());
}

function get_story_record($story)
{
  $handle = db_connect();
  $query = "select * from stories where id = '$story'";
  $result = $handle->query($query);
  return($result->fetch_assoc());
}

function query_select($name, $query, $default='')
{
  $handle = db_connect();

  $result = $handle->query($query);

  if (!$result)
  {
    return('');
  }

  $select  = "<select name='$name'>";
  $select .= '<option value=""';
  if($default == '') $select .= ' selected ';
  $select .= '>-- Choose --</option>';

  for ($i=0; $i < $result->num_rows; $i++) 
  {
    $option = $result->fetch_array();
    $select .= "<option value='{$option[0]}'";
    if ($option[0] == $default) 
    {
      $select .= ' selected';
    }
    $select .=  ">[{$option[0]}] {$option[1]}</option>";
  }
  $select .= "</select>\n";

  return($select);
}

?>
