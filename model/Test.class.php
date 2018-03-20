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

    private function checkAnswer($theme){
            $answer = $_POST[$theme];
            $right = $_SESSION["curent"]["right"];
            foreach ($answer as $item){
                $_SESSION["curent"]["answers"][$item - 1]['check'] = true;
            }

            echo "<br>отвечено: ".implode(",",$answer);
            echo "<br>надо: ".implode(",",$right);
            echo "<hr>";
            if(implode(",",$answer) === implode(",",$right))
                return true;
            else
                return false;
    }
    /**
     * Метод подготовки основгой части страницы
     * @param string $testTheme - тема теста
     */
    protected function prepareBody($testTheme)
    {
        $number = rand(1,self::getThemeList()[$testTheme]['count']);
        echo "Всего вопросов - ". self::getThemeList()[$testTheme]['count'] . " текущий - ".$number;
        $random = false; // будем получать из метода получения пользовательских настроек
        $test = $this->getTest($testTheme,$number,$random);

        if(empty($test)){
            include VIEW_DIR_TEST."notest.php";
        }
        elseif(isset($_POST[$testTheme])) {
            if ($this->checkAnswer($testTheme))
                echo "Верно!";
            else
                echo "Ошибка!";
            $theme = $_SESSION["curent"]["theme"];
            //$_SESSION["curent"]["code"] = $code = $testTheme;
            $num = $_SESSION["curent"]["number"];
            $quest = $_SESSION["curent"]["quest"];
            $answers = $_SESSION["curent"]["answers"];
            include VIEW_DIR_TEST."wrong.php";
        }
        else{
            $_SESSION["curent"] = $test;
            $theme = $test["theme"];
            $_SESSION["curent"]["code"] = $code = $testTheme;
            $num = $test["number"];
            $quest = $test["quest"];
            $answers = $test["answers"];
            include VIEW_DIR_TEST."test.php";
        }
        include VIEW_DIR_TEST."footer.php";

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

            if($item['answ_right'] == 1) $result['right'][] = $item['answ_order'];

        }
        if($rnd) shuffle($result['answers']);
        return $result;
    }

    /**
     * структура хранения вопросов в сессии
     *
     * запись при ответе:
     * $_SESSION['used'][$theme][]  - массив отвеченных вопросов в теме
     * $_SESSION['right'][$theme][] - массив правильных ответов в теме
     * $_SESSION['curent'][
     *       'theme'=> текущая тема
     *       'quest'=> [массив вопроса]
     * добавить при ответе:
     *       'used'=>  [массив выбранных вариантов]
     * ]
     *
     * $_COOKIE['used']
     */


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