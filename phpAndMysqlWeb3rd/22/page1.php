<?php
  session_start();
  
  $_SESSION['sess_var'] = "Hello world!";

  echo 'The content of $_SESSION[\'sess_var\'] is '
        .$_SESSION['sess_var'].'<br />';
?>
<a href="page2.php">Next page</a> 
