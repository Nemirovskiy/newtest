<?
/**
*	
*/
class Test extends Page
{
	public static $theme=[]; 	// Тема
	public static $num=''; 	 	// номер вопроса
	public static $quest=''; 	// Текст вопроса
	public static $answers=[]; 	// ответы

    protected function prepareBody($testTheme)
    {
        // переместить запрос к БД в класс test
        $theme = $this->title;
        $theme = ['text'=>$theme];
        $num = 5;
        $quest = 'test';
        $answers = [
            ['order'=>'1','value'=>1,'text'=>'несколько ручеек дороге'],
            ['order'=>'1','value'=>1,'text'=>'путь ручеек языком дороге'],
            ['order'=>'1','value'=>1,'text'=>'языком ручеек дороге'],
            ['order'=>'2','value'=>2,'text'=>'семантика путь взобравшись']];
        //$template = Controller::getTemplate();
        include VIEW_DIR_TEST.'test.php';
    }
}