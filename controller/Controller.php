<?
// запускаем сессию
session_start();
// подключаем настройки
require_once 'controller/settings.php';
// подключаем автозагрузку классов
require_once 'controller/autoload.php';


// метод определения адрес - шаблон или тема теста
$code = Controller::urlDetector();
$class = $code.'Controller';
//print_r($url);
// создаем объект страницу или тест
$page = new $class;
$page->render();
// передаем данные представлению
/**
 * 0. Определяем класс страницы
 * $url = контроллер-определение класса
 * 1. Создаем объект согласно адресу
 * $page = new Object
 * 2. Проверяем есть ли событие (нажатие на кнопку)
 * контроллер-проверка события
 * передаем событие в экземпляр класса страницы
 * $case = $page->isCase()
 * 3. Обрабатываем событие
 * $result = $page->$case()
 * 3. Формируем массив контента
 *
 * $content = $page->getContent()
 *
 *
 */

//if(empty($_POST['ajax'])){
//    $page->renderHtml($url[1]);
//}else{
//    $page->renderJson($url[1]);
//}
?>

