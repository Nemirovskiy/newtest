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
		include 'view/view__head.php';
	}
	public function renderBody()
	{
		$theme = ['text'=>'семантика'];
		$num = 5;
		$quest = 'test';
		$answers = [
			['order'=>'1','value'=>1,'text'=>'несколько ручеек языком дороге'],
			['order'=>'2','value'=>2,'text'=>'семантика путь взобравшись']];
		$template = Controller::getTemplate();
		include $template;
	}
}