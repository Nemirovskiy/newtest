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
	private $code='';	 // символьное имя - код страницы
    private static $list= null;	 // список всех страниц

    private static function getList()
    {
        if(self::$list === null){
            // получаем список страниц из БД
            $arr = [
                // code - символьный код страницы
                // menu - отображение в меню
                // title - заголовок страницы
                ['code' => 'index',	'menu'=> 1,'title'=>'Главная'],
                ['code' => 'ser',	'menu'=> 1,'title'=>'СанЭпидРежим'],
                ['code' => 'feld',	'menu'=> 1,'title'=>'Фельдшеры'],
                ['code' => 'smp',	'menu'=> 1,'title'=>'Скорая помощь'],
                ['code' => 'help',	'menu'=> 1,'title'=>'Инструкция']
            ];
            self::$list = $arr;
        }
        return self::$list;
    }
	// метод формирования меню
	private function setMenu()
	{
	    // получаем список страниц
		$pages = self::getList();
		$code = $this->code;
		$nav = [];
		$flag = false;
		foreach ($pages as $key => $value) {
		    if($value['menu'] === 1) {
		        $nav[$key] = $value;
		        if($value['code'] == $code){
                     $nav[$key]['active'] = $flag= true;
                }
                // если код текущей старницы index - ссылка будет без кода 
                if($nav[$key]['code'] == 'index') $nav[$key]['code']='';
            }
		}
        $this->nav = $nav;
	}
	// метод возвращения заголовка
	private function setTitle()
	{
	    // получаем список страниц
		$pages = self::getList();
		$code = $this->code;
		foreach ($pages as $item){
		    if($item['code'] == $code){
                $this->title = $item['title'];
		        return;
            }
        }
	}
	private function validAdress($code){
        foreach (self::getList() as $page){
            if($code == $page['code'] || $code == 'index'){
                return true;
            }
        }
        header("HTTP/1.0 404 Not Found");
        $this->title = "Нет такой страницы";
        return false;
    }
    protected function prepareHead($code)
	{
        $this->code = $code;
        $this->setMenu();
        $this->setTitle();
        $nav = $this->nav;
        $title = $this->title;
        include VIEW_DIR_INCLUDE.'head.php';
	}
    protected function prepareBody($template)
	{
		    include VIEW_DIR_PAGE.$template.'.php';
	}
	public function renderHtml($code)
	{
	    // проверяем на корректность адрес
	    $valid = $this->validAdress($code);
	    // подготавливаем голову
        $this->prepareHead($code);
        // если адрес корректный - подготовим контент
        if($valid){
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