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
    public static $user = null;
    static protected $code = 'index';
    static protected $class = 'Page';

    public function start(){
        // метод определения адрес - шаблон или тема теста
        $code = Controller::urlDetector();
        $class = $code.'Controller';
//print_r($url);
// создаем объект страницу или тест
        $page = new $class;
        $page->render();
        //Controller::dump($_SERVER);
// передаем данные представлению
    }

    /**
     * метод определяет по адресу страница или тест
     * @return string
     */
    public static function urlDetector()
    {
        $inputUrl = $_GET['t'];
        preg_match("/^[a-z\/]+$/",$inputUrl,$code);
        $list = Page::getList();
        if(empty($inputUrl)){
            self::$code  = "index";
        }elseif(empty($list[$code[0]])){
            self::$code  = 404;
        }else{
            self::$code  = $list[$code[0]]['code'];
            self::$class = ucfirst($list[$code[0]]['type']);
        }
        if(!empty($_POST['login'])){
            $user = new User();
            $user->createUser();
            $user->authUser();
        }
        self::isAccess();
        return self::$class;
    }

    /***
     * метод определения действия
     */
    protected function getAction(){}
    /**
     * метод формирования страницы
     * точка входа
     */
    public function render(){}

    /**
     * метод проверки доступа
     */
    public static function isAccess(){
        $page = Page::getList()[self::$code];
        if($page['access'] <= User::getAccess()){
            return true;
        }
        else{
            self::$code = 403;
            self::$class = 'Page';
            return true;
        }
    }

    /**
     * метод обработки ошибок сервера
     * генерирует страницу ошибки 500
     * передает сообщение об ошибке
     * если разрешено, записывает сообщение в лог
     */
    public static function errorServer($message = ''){
        self::$code = 500;
        self::$class = 'Page';
        if(SERVER_ERROR_TO_LOG_FILE)
            Log::toFile(MessageError::get()['errors'].": ".$message);
        $page = new PageController();
        $page->render();
        die();
    }

    /**
     * метод выборки из массива по ключу
     * @param array $array массив из которого выбрать
     * @param string $key ключ для выборки
     * @return array
     */
    public static function keyArray($array=[],$key=''){
        foreach ($array as $item){
            $result[$item[$key]] = $item;
        }
        return $result;
    }

    public static function dump($item){
        if(empty($_POST['ajax'])){
            echo "<pre>";
            print_r($item);
            echo "</pre>";
        }else{
//            ob_start();
//            print_r($item);
//            $str = ob_get_contents();
//            ob_end_clean();
//            echo json_encode($item);
        }

    }
}