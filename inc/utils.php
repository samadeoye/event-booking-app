<?php
session_start();

$httpHost = $_SERVER['HTTP_HOST'];
$httpFolderPath = '';
$isProductionServer = true;
$isLocal = false;

if (in_array($httpHost, ['localhost', '127.0.0.1']))
{
    //LOCAL
    $httpFolderPath = '/kpakpando_events_booking';
    $httpHost = 'http://'.$httpHost;
    $isProductionServer = false;
    $isLocal = true;
}
else
{
    //PRODUCTION
    $httpHost = 'https://'.$httpHost;
}
define('DEF_ROOT_PATH', $httpFolderPath);
define('DEF_FULL_ROOT_PATH', $httpHost.$httpFolderPath);
define('DEF_DOC_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/'. $httpFolderPath . '/');
define('DEF_IS_PRODUCTION', $isProductionServer);
define('DEF_IS_LOCAL', $isLocal);

if (DEF_IS_LOCAL)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require_once DEF_DOC_ROOT.'vendor/autoload.php';
require_once DEF_DOC_ROOT.'inc/functions.php';
require_once DEF_DOC_ROOT.'inc/constants.php';
require_once DEF_DOC_ROOT.'inc/connect.php';
require_once DEF_DOC_ROOT.'inc/dropdowns.php';

$arAdditionalCSS = $arAdditionalJs = $arAdditionalJsScripts = $arAdditionalJsOnLoad = [];
$arUser = [];
$userId = '';
if (isset($_SESSION['admin']))
{
    $arUser = $_SESSION['admin'];
    $userId = $arUser['id'];
}