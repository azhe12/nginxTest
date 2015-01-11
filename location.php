<?php
//header("HTTP/1.1 301 Moved Permanently");
if (!function_exists('getallheaders')) 
{ 
    function getallheaders() 
    { 
           $headers = ''; 
       foreach ($_SERVER as $name => $value) 
       { 
           if (substr($name, 0, 5) == 'HTTP_') 
           { 
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
           } 
       } 
       return $headers; 
    } 
} 

echo "Show request header:\n";
var_dump(getallheaders());

header("HTTP/1.1 301");
header("Location: http://www.baidu.com");
?>
