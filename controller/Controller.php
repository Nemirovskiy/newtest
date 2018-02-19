<?
session_start();

require_once 'controller/autoload.php';
require_once 'controller/settings.php';

// метод получения названий страниц
// метод получения тем тестов


//echo Controller::getTemplate();

$page = new Page;
$page->renderHead();
$page->renderBody();

//$page->renderHead() = Controller::initPageName();
// вызываем метод определения какую страницу отображать
// 