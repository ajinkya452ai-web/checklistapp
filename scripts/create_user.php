<?php
// Run only from CLI: php scripts/create_user.php username
if(PHP_SAPI!=='cli')exit('CLI only');
require dirname(__DIR__).'/config/bootstrap.php';
$username=$argv[1]??'';
if(!preg_match('/^[A-Za-z0-9_-]{3,80}$/D',$username))exit("Usage: php scripts/create_user.php username\n");
fwrite(STDOUT,"Password (input visible; use private terminal): ");
$password=rtrim((string)fgets(STDIN),"\r\n");
if(strlen($password)<12)exit("Password must be at least 12 characters.\n");
$q=db()->prepare('INSERT INTO users(username,password_hash) VALUES(?,?)');
$q->execute([$username,password_hash($password,PASSWORD_DEFAULT)]);
echo "User created.\n";
