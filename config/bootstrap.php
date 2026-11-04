<?php
declare(strict_types=1);
// The web root must point at public/, not this directory.
function loadEnv(string $file): void {
    if (!is_file($file)) return;
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line=trim($line);
        if ($line==='' || $line[0]==='#' || strpos($line,'=')===false) continue;
        [$key,$value]=explode('=',$line,2);
        $key=trim($key);
        if (!preg_match('/^[A-Z][A-Z0-9_]*$/',$key) || getenv($key)!==false) continue;
        putenv($key.'='.trim($value," \t\n\r\0\x0B\"'"));
    }
}
loadEnv(dirname(__DIR__).'/.env');
function cfg(string $key,string $default=''): string { $v=getenv($key); return $v===false?$default:$v; }
date_default_timezone_set(cfg('APP_TIMEZONE','Asia/Kolkata'));
ini_set('display_errors','0');
ini_set('log_errors','1');
if (PHP_SAPI!=='cli') {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Content-Security-Policy: default-src \'self\'; style-src \'self\' https://cdn.jsdelivr.net; script-src \'self\' https://cdn.jsdelivr.net; connect-src \'self\'; img-src \'self\' data:; object-src \'none\'; base-uri \'self\'; frame-ancestors \'none\'; form-action \'self\'');
    session_name('monthly_session');
    session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>cfg('SESSION_SECURE','true')==='true','httponly'=>true,'samesite'=>'Lax']);
    session_start();
}
function db(): PDO {
    static $pdo=null;
    if ($pdo===null) {
        $dsn='mysql:host='.cfg('DB_HOST','127.0.0.1').';port='.cfg('DB_PORT','3306').';dbname='.cfg('DB_NAME','monthly_checklist').';charset=utf8mb4';
        $pdo=new PDO($dsn,cfg('DB_USER'),cfg('DB_PASSWORD'),[
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES=>false
        ]);
    }
    return $pdo;
}
function e($value): string {return htmlspecialchars((string)$value,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function csrf(): string {if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function checkCsrf(): void {if(!hash_equals(csrf(),(string)($_POST['csrf']??''))) {http_response_code(403);exit('Invalid security token. Reload and retry.');}}
function redirect(string $url): void {header('Location: '.$url, true,303);exit;}
function auth(): int {if(empty($_SESSION['user_id']))redirect('/login.php');return (int)$_SESSION['user_id'];}
function flash(string $message): void {$_SESSION['flash']=$message;}
function validMonth(string $month): bool {return preg_match('/^\d{4}-(0[1-9]|1[0-2])$/D',$month)===1 && (int)substr($month,0,4)>=2000 && (int)substr($month,0,4)<=2100;}
function taskText(string $text): string {$text=trim($text);if($text==='' || mb_strlen($text)>180)throw new InvalidArgumentException('Task must contain 1–180 characters.');return $text;}
