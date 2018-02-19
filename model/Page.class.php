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
	private $name='';	 // символьное имя
	private $list=[];	 // список всех страниц

	public function setList()
	{
	    // получаем список страниц из БД
		$arr = [
		    ['name' => '',		'menu'=> 1,'title'=>'Главная'],
            ['name' => 'ser',	'menu'=> 1,'title'=>'СанЭпидРежим'],
            ['name' => 'feld',	'menu'=> 1,'title'=>'Фельдшеры'],
            ['name' => 'smp',	'menu'=> 1,'title'=>'Скорая помощь'],
            ['name' => 'help',	'menu'=> 1,'title'=>'Инструкция']
        ];
        $this->list = $arr;
	}
	// метод формирования меню
	private function setMenu()
	{
	    // получаем список страниц
		$pages = $this->list;
        $name = $this->name;
		$nav = [];
		foreach ($pages as $key => $value) {
		    if($value['menu'] === 1) {
		        $nav[$key] = $value;
		        if($value['name'] == $name){
                    $nav[$key]['active'] = true;
                }
            }
		}
        $this->nav = $nav;
	}
	// метод возвращения заголовка
	private function setTitle()
	{
	    // получаем список страниц
		$pages = $this->list;
		$name = $this->name;
		//echo "<b>$name</b><br>";
		foreach ($pages as $item){
		   // echo $item['name'].' - '.$item['title'].'<br>';
		    if($item['name'] == $name){
                $this->title = $item['title'];
		        return;
            }
        }
	}
    protected function prepareHead($name)
	{
	    $this->name = $name;
	    $this->setList();
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
	public function renderHtml($name)
	{
	    $this->prepareHead($name);
        //include VIEW_DIR_INCLUDE.'head.php';
        $this->prepareBody($name);
        include VIEW_DIR_INCLUDE.'footer.php';
	}

}