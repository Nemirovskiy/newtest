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
        $number = 2; // будем получать из метода генерации номера
        $random = false; // будем получать из метода получения пользовательских настроек
        $arr = $this->getTest($testTheme,$number,$random);
        echo "<pre>";
//        print_r($arr);
        echo "</pre>";
        $theme = $arr['theme'];
        $theme = $arr['quest'];
        $code = $testTheme;
        $num = $arr['number'];
        $quest = $arr['quest'];
        $answers = $arr['answers'];
        include VIEW_DIR_TEST.'test.php';
    }
    protected function getTest($theme,$number,$rnd = false){
        // метод получения вопроса и ответа из указанной темы и указанного номера вопроса
        $query = "SELECT * from quest INNER JOIN theme ON (quest.theme_code = theme.theme_code)
                  INNER JOIN answ ON (quest.quest_id = answ.quest_id) WHERE theme.theme_code = ? AND quest_number = ?";
        $tests = DBase::select($query,[$theme,$number]);
        echo "<pre>";
//        print_r($tests);
        echo "</pre>";
        $result =[];
        foreach ($tests as $key => $item){
            if(!isset($result['number'])) $result['number'] = $item['quest_number'];
            if(!isset($result['quest'])) $result['quest'] = $item['quest_text'];
            if(!isset($result['theme'])) $result['quest'] = $item['theme_text'];
            $result['answers'][] = [
                'order'=> $item['answ_order'],
                'right' => $item['answ_right'],
                'text' => $item['answ_text']
            ];
        }
        if($rnd) shuffle($result['answers']);
        return $result;
    }
}