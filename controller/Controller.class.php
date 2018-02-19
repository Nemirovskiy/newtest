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
	 	// сделать и вынести в конфиг константы для папки представления
	 	if(is_file('view/view_'.$name.'.php'))
	 		return 'view/view_'.$name.'.php';
	 	else return 'view/view_test.php';
	 } 

	//
	static function render()
	{
		# code...
	}

}