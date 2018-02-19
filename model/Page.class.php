<?
/**
*	
*/
class Page extends Model
{
	private $title='Тестирование'; // заголовок
	private $nav=[];	 // навигация
	private $name='';	 // имя

	public function getList()
	{
		$arr = parent::getList();
		array_unshift($arr,['name' => '','text'=>'Главная'/*,'active' => true*/ ]);
		$this->nav = $arr;
	}
	private function isActive($name)
	{
		$this->name = $name;
		$nav = $this->nav;
		$flag = false;
		foreach ($nav as $key => $value) {
			if($value['name'] == $name){
				$nav[$key]['active'] = true;
				$this->nav = $nav;
				$this->title .= ' - '.$value['text'];
				return;
			}
		}
	}
	public function renderHead()
	{
		$this->getList();
		$this->isActive(Controller::initPageName());
		$nav = $this->nav;
		$title = $this->title;
		include VIEW_DIR_INCLUDE.'head.php';
	}
	public function renderBody($template)
	{
		$template;// = Controller::getTemplate();
		include VIEW_DIR_PAGE.$template.'.php';
	}
}