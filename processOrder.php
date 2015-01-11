<?php
echo '<html>';
echo '<p>好了</p>';
echo '<p>name is '.$_POST['name'].'</p>';
echo '<p>Age is '.$_POST['age'].'</p>';
setcookie('session', '10');
var_export($_COOKIE);
echo "\n";
var_export($_REQUEST);
echo "\n";
echo '</html>';
//phpinfo();
?>
