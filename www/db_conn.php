<?php
$envHost = getenv('db_host');
if(!$envHost || strlen($envHost) == 0){
    define("SERVER_HOST", "153.92.7.101");
    define("DB_NAME", "u787130504_oceans");
    define("DB_USERNAME", "u787130504_brian");
    define("DB_PASSWORD", ";+B8CKe&");
}else{
    define("SERVER_HOST", $envHost);
    define("DB_NAME", "u787130504_oceans");
    define("DB_USERNAME", "root");
    define("DB_PASSWORD", "root");
}
