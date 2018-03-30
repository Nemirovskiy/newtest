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
    static protected $code = 'index';
    static protected $class = 'Page';

    // метод определяет по адресу страница или тест
    public static function urlDetector()
    {
        // забираем параметр из строки адреса, если пусто - главная
        $url = explode("/",$_GET['t']);
        $name = !empty($url[0]) ? strip_tags($url[0]) : 'index';
        self::$code = $name;
        if(self::validAdress()) {
            if (is_file(VIEW_DIR_PAGE . $name . '.php'))
                self::$class = 'Page';
            // или административная
            elseif (is_file(VIEW_DIR_ADMIN . $name . '.php'))
                self::$class = 'Admin';
            // иначе - это тест
            else
                self::$class = 'Test';
        }else{
            self::$class = 'Page';
            self::$code = '404';
            // если есть файл шаблна - это статичная страница
        }
        return self::$class;

    }
    /**
     * метод проверки зарегистрированных страниц
     */
    protected static function validAdress(){
        foreach (Page::getList() as $page){
            $code = self::$code;
            //echo $page['code']."<br>";
            if($code == $page['code'] || $code == 'index'){
                return true;
            }
        }
        return false;
    }

    /***
     * метод определения ситуации
     */
    protected function separate(){

    }
    /**
     * метод формирования страницы
     * точка входа
     */
    public function render(){}
    /**
     * метод формирования заголовка и верхней части страницы
     */
    protected function prepareHead(){}
    /**
     * метод формирования основного контента страницы
     */
    protected function prepareBody(){}
    /**
     * метод формирования подвала страницы
     */
    protected function prepareFooter(){}

}