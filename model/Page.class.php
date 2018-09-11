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

    /**
     * получаем список страниц
     * @param string $menu
     * @return array|bool
     */
    public static function getList($menu = "menu")
    {
        if(self::$list === null){
            self::$list = DBase::getList($menu);
        }
        return self::$list;
    }
	// метод формирования меню
	protected function setMenu($menu = "menu")
	{
	    // получаем список страниц
		$pages = self::getList();
		$nav = [];
		foreach ($pages as $key => $value) {
		    if($value[$menu] > 0) {
		        $nav[$key] = $value;
		        if($value['code'] == $this->code){
                     $nav[$key]['active'] = true;
                }
                // если код текущей страницы index - ссылка будет без кода
                if($nav[$key]['code'] == 'index') $nav[$key]['code']='';
            }
		}
        $this->nav = $nav;
		return $nav;
	}
	// метод формирования второго меню
	protected function setSecondMenu()
	{
	    // получаем список страниц
		$pages = self::getList();
		$nav = [];
		foreach ($pages as $value) {
		    if($value['access'] <= User::getAccess() && $value['second_menu'] > 0) {
		        $key = $value['second_menu'];
		        $nav[$key] = $value;
		        if($value['code'] == $this->code){
                     $nav[$key]['active'] = true;
                }
                // если код текущей старницы index - ссылка будет без кода
                if($nav[$key]['code'] == 'index') $nav[$key]['code']='';
            }
		}
        //$this->nav = $nav;
        ksort($nav);
		return $nav;
	}
	// метод возвращения титла
	private function setTitle()
	{
	    // получаем список страниц
		$pages = self::getList();
		$code = $this->code;
		foreach ($pages as $item){
		    if($item['code'] == $code){
		        return $item['title'];
            }
        }
	}
	// метод возвращения заголовка
	protected function setHeader()
	{
	    // получаем список страниц
		$pages = self::getList();
		$code = $this->code;
		foreach ($pages as $item){
		    if($item['code'] == $code){
		        if($item['header'] === null)
		            return $item['title'];
		        else
		            return $item['header'];
            }
        }
	}

	protected function getButtonAccept(){
        if(isset($_POST['accept']))
            return $_SESSION['accept'] = true;
        elseif ($_SESSION['accept'])
            return true;
        else
            return false;
    }
    /**
     * метод формирования основного контента страницы
     */
    public function getContentPage(){
        $result['nav'] = $this->setMenu();
        $result['secondNav'] = $this->setSecondMenu(User::getAccess());
        $result['title'] = $this->setTitle();
        $result['header'] = $this->setHeader();
        $result['accept'] = $this->getButtonAccept();
        return $result;
    }
    /**
     * метод формирования 404 страницы
     */
    public function getContent404(){
        header("HTTP/1.0 404 Not Found");
        return $this->getContentPage();
    }
    /**
     * метод формирования 403 страницы
     */
    public function getContent403(){
        header("HTTP/1.0 403 Forbidden");
        return $this->getContentPage();
    }

}