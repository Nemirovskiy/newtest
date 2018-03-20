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

    // метод определяет по адресу страница или тест
    public static function urlDetecter()
    {
        // забираем параметр из строки адреса, если пусто - главная
        $name = !empty($_GET['t']) ? strip_tags($_GET['t']) : 'index';
        // если есть файл шаблна - это статичная страница
        if(is_file(VIEW_DIR_PAGE.$name.'.php'))
            return ['Page',$name];
        // или административная
        elseif(is_file(VIEW_DIR_ADMIN.$name.'.php'))
            return ['Admin',$name];
        // иначе - это тест
        else return ['Test',$name];
    }

    //
    static function render()
    {
        # code...
    }

}