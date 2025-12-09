<?php
  ob_start(); // output buffering is turned on
// Включена буферизация вывода независимо от значения в php.ini

session_start(); // turn on sessions

// Assign file paths to PHP constants
// __FILE__ returns the current path to this file
// dirname() returns the path to the parent directory

define("PRIVATE_PATH", dirname(__FILE__));        
// путь к папке private,
// определяется через место расположения текущего файла initialize.php,
// т.к. срабатывает при вызове
// require_once('../../private/initialize.php'); 
//
// echo PRIVATE_PATH; 
// E:\OSpanel\5.4\domains\globe-bank\private

define("PROJECT_PATH", dirname(PRIVATE_PATH));    
// путь к корню проекта
// определяет папку выше по отношению к файлу initialize.php
//
// echo PROJECT_PATH; 
// E:\OSpanel\5.4\domains\globe-bank

define("PUBLIC_PATH", PROJECT_PATH . '/public');  
// /public
// определяет папку public, вложенную в корень проекта

define("SHARED_PATH", PRIVATE_PATH . '/shared');  
// /private/shared
// определяет папку shared, вложенную в папку private


// Assign the root URL to a PHP constant
// * Do not need to include the domain
// * Use same document root as webserver
// * Can set a hardcoded value:

// 1. жестко закодировано WWW_ROOT на локальной машине
// define("WWW_ROOT", '/~kevinskoglund/globe_bank/public');

// или 2. жестко закодировано WWW_ROOT на машине разработки
// define("WWW_ROOT", '');

// или 3. динамически вычисляемое WWW_ROOT
// * Can dynamically find everything in URL up to "/public"
$public_end = strpos($_SERVER['SCRIPT_NAME'], '/public') + 7;
$doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
define("WWW_ROOT", $doc_root);

// echo WWW_ROOT; 
//   /public

require_once('functions.php');
require_once('database.php');
require_once('query_functions.php');
require_once('validation_functions.php');

// вызвана функция из database.php
// и установлено соединение с БД
$db = db_connect();
// сразу инициализируем массив для ошибок
$errors = [];

?>
