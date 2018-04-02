<?
/**
* модель страницы
 * - заголовок
 * - символьное имя - адрес
 * - меню
 * - контент
 * + метод получения данных
 * + метод подготовки заголовка
 * + метод подготовки основного контента
 * + метод формирования страницы
*/
class Page extends Model
{
	protected $title='Тестирование - Главная'; // заголовок
	private $nav=[];	 // навигация
	protected $code='';	 // символьное имя - код страницы
    private static $list= null;	 // список всех страниц

    // метод создания экземпляра с указанным кодом
    public function __construct($code)
    {
        $this->code = $code;
    }

    // получаем список страниц из БД
    public static function getList($menu = "menu")
    {
        if(self::$list === null)
            self::$list = DBase::select("SELECT * FROM page ORDER BY $menu");
        return self::$list;
    }
	// метод формирования меню
	protected function setMenu($menu = "menu")
	{
	    // получаем список страниц
		$pages = self::getList();
		$code = $this->code;
		$nav = [];
		foreach ($pages as $key => $value) {
		    if($value[$menu] > 0) {
		        $nav[$key] = $value;
		        if($value['code'] == $code){
                     $nav[$key]['active'] = $flag= true;
                }
                // если код текущей старницы index - ссылка будет без кода
                if($nav[$key]['code'] == 'index') $nav[$key]['code']='';
            }
		}
        $this->nav = $nav;
		return $nav;
	}
	// метод возвращения заголовка
	private function setTitle()
	{
	    // получаем список страниц
		$pages = self::getList();
		$code = $this->code;
		foreach ($pages as $item){
		    if($item['code'] == $code){
                // $this->title = $item['title'];
		        return $item['title'];
            }
        }
	}

    /**
     * метод формирования основного контента страницы
     */
    public function getContentPage(){
        $result['nav'] = $this->setMenu();
        $result['title'] = $this->setTitle();
        return $result;
    }
    /**
     * метод формирования 404 страницы
     */
    public function getContent404(){
        header("HTTP/1.0 404 Not Found");
        return $this->getContentPage();
    }
    /***
     * метод подготовки контента
     */

    protected function getContent(){

        $result['head']  = $this->prepareHead();
        $result['content'] = $this->prepareBody();
        return $result;
    }
    /**
     * Функция подготовки основной части страницы
     * @param string $template - название шаблона
     */
    protected function prepareBody($template)
	{
		    include VIEW_DIR_PAGE.$template.'.php';
	}
	public function renderHtml($code,$content)
	{
        include VIEW_DIR_INCLUDE.'head.php';
	    // проверяем на корректность адрес
	    //$valid = $this->validAdress($code);
	    // подготавливаем голову
        $this->prepareHead($code);
        // если адрес корректный - подготовим контент
        if($code == 404){
            $this->prepareBody($code);
        }
        // иначе - отобразим старницу ошибки
        else {
            include VIEW_DIR_ERORS.'404.php';
        }
        // подключим низ страницы
        include VIEW_DIR_INCLUDE.'footer.php';
	}

}