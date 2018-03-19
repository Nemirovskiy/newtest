<?
/**
*	
*/
class Test extends Page
{
	public static $list    = null; 	// Список тем
	public static $theme   = []  ; 	// Тема
	public static $num     = ''  ; 	// номер вопроса
	public static $quest   = ''  ; 	// Текст вопроса
	public static $answers = []  ; 	// ответы

    /**
     * Метод подготовки основгой части страницы
     * @param string $testTheme - тема теста
     */
    protected function prepareBody($testTheme)
    {
        $count = DBase::select("SELECT COUNT(*) as count FROM quest WHERE theme_code = ?",[$testTheme]);

        $number = rand(1,$count[0]['count']); // будем получать из метода генерации номера
        echo "Всего вопросов - ". $count[0]['count'] . " текущий - ".$number;
        $random = false; // будем получать из метода получения пользовательских настроек
        $arr = $this->getTest($testTheme,$number,$random);
        echo "<pre>";
        print_r(Test::getThemeList());
        var_dump(isset(Test::getThemeList()[$testTheme]));
        var_dump(array_key_exists($testTheme,Test::getThemeList()));
        //print_r($arr);
        echo "</pre>";
        if(empty($arr)){
            include VIEW_DIR_TEST.'notest.php';
        }
        else{
            $theme = $arr['theme'];
            $code = $testTheme;
            $num = $arr['number'];
            $quest = $arr['quest'];
            $answers = $arr['answers'];
            include VIEW_DIR_TEST.'test.php';
        }
        include VIEW_DIR_TEST.'footer.php';
    }

    /**
     * Метод получения вопроса и ответа из указанной темы и указанного номера вопроса
     * @param $theme
     * @param $number
     * @param bool $rnd
     * @return array|bool
     */
    protected function getTest($theme,$number,$rnd = false){
        $query = "SELECT * from quest INNER JOIN theme ON (quest.theme_code = theme.theme_code)
                  INNER JOIN answ ON (quest.quest_id = answ.quest_id) WHERE theme.theme_code = ? AND quest_number = ?";
        $tests = DBase::select($query,[$theme,$number]);
        if(empty($tests)) return false;
        $result =[];
        foreach ($tests as $key => $item){
            if(!isset($result['number'])) $result['number'] = $item['quest_number'];
            if(!isset($result['quest'])) $result['quest'] = $item['quest_text'];
            if(!isset($result['theme'])) $result['theme'] = $item['theme_text'];
            $result['answers'][] = [
                'order'=> $item['answ_order'],
                'right' => $item['answ_right'],
                'text' => $item['answ_text']
            ];
        }
        if($rnd) shuffle($result['answers']);
        return $result;
    }

    public static function getThemeList(){
        if(self::$list === null){
            $arr = DBase::select("SELECT * FROM theme ");
            foreach ($arr as $item){
                $list[$item['theme_code']]['code']  = $item['theme_code'];
                $list[$item['theme_code']]['text']  = $item['theme_text'];
                $list[$item['theme_code']]['count'] = $item['theme_count'];
            }
            return self::$list = $list;
        }
        else
            return self::$list;
    }
}