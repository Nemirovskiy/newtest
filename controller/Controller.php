<?
// запускаем сессию
session_start();
// подключаем настройки
require_once 'controller/settings.php';
// подключаем автозагрузку классов
require_once 'controller/autoload.php';


// метод определения адрес - шаблон или тема теста
$url = Controller::urlDetecter();
print_r($url);
// создаем объект страницу или тест
$page = new $url[0];

// передаем данные представлению
$page->renderHead();
$page->renderBody($url[1]);
