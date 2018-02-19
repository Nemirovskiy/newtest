<?
/**
*	адрес site.com?t=test
*	контроллер определяет действия пользователя (адрес, прием формы)
*	выберает модель и передает действия ей - получает от модели данные
*	выбирает нужно представление и передает данные от модели в представление
*	запускает вывод представления пользователю
*/
class Controller
{
	// метод определения страницы по параметру в адресной строке
	public static function initPageName()
	{
		return strip_tags($_GET['t']);
	}
	// метод выбора шаблона страницы
	public static function getTemplate()
	 {
	 	$name = !empty($_GET['t']) ? strip_tags($_GET['t']) : 'index';
	 	if(is_file(VIEW_DIR_PAGE.$name.'.php'))
	 		return VIEW_DIR_PAGE.$name.'.php';
	 	else return VIEW_DIR_TEST.'test.php';
	 }
	 // метод определяет по адресу страница или тест
	public static function urlDetecter()
	 {
	    // забираем параметр из строки адреса, если пусто - главная
	 	$name = !empty($_GET['t']) ? strip_tags($_GET['t']) : 'index';
         // если есть файл шаблна - это статичная стрница
	 	if(is_file(VIEW_DIR_PAGE.$name.'.php'))
	 		return ['Page',$name];
	 	// иначе - это тест
	 	else return ['Test',$name];
	 }

	//
	static function render()
	{
		# code...
	}

}