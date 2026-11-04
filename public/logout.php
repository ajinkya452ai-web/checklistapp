<?php
require dirname(__DIR__).'/config/bootstrap.php';
if ($_SERVER['REQUEST_METHOD']!=='POST') {http_response_code(405);exit;}
checkCsrf();$_SESSION=[];session_regenerate_id(true);session_destroy();redirect('/login.php');
