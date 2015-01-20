<?php
  function login($username, $password)
  // check username and password with db
  // if yes, return true
  // else return false
  {
    // connect to db
    $handle = db_connect();
    if (!$handle)
      return 0;

    $result = $handle->query("select * from writers
                              where username='$username' and
                                password = SHA1('$password')");
    if (!$result)
    {
      return 0;
    }
    if ($result->num_rows>0)
    {
      return 1;
    }
    else 
    {
      return 0;
    }
  }

  function check_auth_user()
  // see if somebody is logged in and notify them if not
  {
    global $_SESSION;
    if (isset($_SESSION['auth_user']))
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  


  function login_form()
  {
    ?>
    <form action='login.php' method='POST'>
    <table border=0>
    <tr>
      <td>Username</td>
      <td><input size='16' name='username'></td>
    </tr>
    <tr>
      <td>Password</td>
      <td><input size='16' type='password' name='password'></td>
    </tr>
    </table>
    <input type='submit' value='Log in'>
    </form>
    <?php
  }

  function check_permission($username, $story)
  // check user has permission to act on this story 
  {
    // connect to db
    $handle = db_connect();
    if (!$handle)
      return 0;

    if(!$_SESSION['auth_user'])
      return 0;

    $result = $handle->query("select * from writer_permissions wp, stories s
                              where wp.writer = '{$_SESSION['auth_user']}' and
                                  wp.page = s.page and
                                  s.id = $story
                              ");
    if (!$result)
    {
      return 0;
    }
    if ($result->num_rows>0)
    {
      return 1;
    }
    else 
    {
      return 0;
    }
  }
?>
