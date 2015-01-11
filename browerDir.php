<?php
//$CUR_DIR = '.';
define(CUR_DIR, '.');
$dir = opendir(CUR_DIR);

echo '<ul>';
$base_url = "http://192.168.216.129";
while ($file = readdir($dir)) {
    echo "<li>";
    echo "<a href=${base_url}/$file>$file</a>";
    echo "</li>";
}
echo '</ur>';

closedir($dir);
?>
