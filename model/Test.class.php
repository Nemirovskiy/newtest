<?
/**
*	
*/
class Test extends Page
{
	public static  $list    = null; 	// Список тем
	public static  $theme   = []  ; 	// Тема
	public static  $num     = ''  ; 	// номер вопроса
	public static  $quest   = ''  ; 	// Текст вопроса
	public static  $answers = []  ; 	// ответы

    private function checkAnswer($theme){
        /**
         * $_SESSION['used'][$theme][]  - массив отвеченных вопросов в теме
         * $_SESSION['right'][$theme][] - массив правильных ответов в теме
         */
            $answer = $_POST[$theme];
            $right = $_SESSION["curent"]["right"];
            foreach ($answer as $item){
                $_SESSION["curent"]["answers"][$item - 1]['check'] = true;
            }
            $_SESSION['used'][$theme][] = $_SESSION['curent']['number'];
            if(implode(",",$answer) === implode(",",$right)){
                $_SESSION['right'][$theme][] = $_SESSION['curent']['number'];
                return true;
            }
            else
                return false;
    }

    /**
     * метод генерации номера вопроса без совпадения
     * @param bool $infinity
     * @return int
     */
    private function generateNumberQuest($infinity = false){
        $theme = $this->code;
        if($infinity || empty($_SESSION['used'][$theme]))
            $number = rand(1,self::getThemeList()[$theme]['count']);
        elseif(count($_SESSION['used'][$theme]) < self::getThemeList()[$theme]['count']){
            do{
                $number = rand(1,self::getThemeList()[$theme]['count']);
            }while(in_array($number,$_SESSION['used'][$theme]));
        }
        else
            $number = false;
        return $number;
    }

    /**
     * @return string метод оформления
     *
     */
    private function separation(){
        /**
         *
         */
        // код подготовки выражений для проверки
        $theme = $this->code;
        $count = self::getThemeList()[$theme]['count'];
        $answer = !empty($_POST[$theme]);

        if($count < 1){
            // вопросов в теме ещё нет
            return 'notest';
        }
        elseif($answer){
            // если есть ответ
            if($this->checkAnswer($theme)){
                // ответ верный
                // показать, что ответ верный
                $this->message = 'right';
            }
            else{
                // ответ не верный
                // показать ответ
                $this->message = 'Не верно!';
                return 'wrong';
            }
        }
        if(count($_SESSION['used'][$theme]) == $count){
            // отвечены все вопросы / ответ верный и вопросов больше нет
            // показать статистику
            return 'result';
        }
        // показать новый вопрос
        return 'test';
    }
    private function buildTest(){
        $_SESSION['curent']['code'] = $code = $this->code;
        $test = $this->getTest($code,$this->generateNumberQuest());
        $_SESSION['curent'] = $test;
        $test['code'] = $code;
        $test['right'] = '';
        $test['message'] = $this->message;
        $test['errors'] = $this->errors;
        return $test;
    }
    private function buildWrong(){
        $result = $_SESSION['curent'];
        $result['message'] = $this->message;
        $result['errors'] = $this->errors;
        return $result;
    }

    private function buildResult(){
        $code = $this->code;
        //$result = $_SESSION;
        //$_SESSION["curent"]["code"] = $code = $testTheme;
        $result['used'] = $u = count($_SESSION['used'][$code]);
        $result['right'] = $r = count($_SESSION['right'][$code]);
        $result['ratio'] = ($r>0) ? round($r / $u * 100) : 0 ;
        //$result['answers'] = $_SESSION["curent"]["answers"];
        $result['message'] = $this->message;
        $result['errors'] = $this->errors;
        return $result;
    }

    /**
     * Метод подготовки основной части страницы
     * @param string $testTheme - тема теста
     */
    protected function prepareBody($testTheme)
    {
        $template = $this->separation();
        $execute = 'build'.ucfirst($template);
        $content = $this->$execute();
        foreach ($content as $key=>$item){
            $$key = $item;
        }
        //$_SESSION["curent"] = [];
        include VIEW_DIR_TEST.$template.'.php';
        echo "<pre>";
        //echo json_encode($content);
        print_r($content);
        echo "</pre>";

        include VIEW_DIR_TEST."footer.php";
    }

    /**
     * Метод получения вопроса и ответа из указанной темы и указанного номера вопроса
     * @param $theme
     * @param $number
     * @param bool $rnd
     * @return array|bool
     */
    public function getTest($theme,$number = false,$rnd = false){
        if($number === false)
            $where = " > 0";
        else
            $where = " = ?";
        $query = "SELECT * from quest INNER JOIN theme ON ".
            "(quest.theme_code = theme.theme_code)".
            "INNER JOIN answ ON (quest.quest_id = answ.quest_id) ".
            "WHERE theme.theme_code = ? AND quest_number" . $where;
        if($number === false)
            $tests = DBase::select($query,[$theme]);
        else
            $tests = DBase::select($query,[$theme,$number]);
        if(empty($tests)) return false;
        $result =[];
        foreach ($tests as $key => $item){
            if(!isset($result['number'])) $result[$item['quest_number']]['number'] = $item['quest_number'];
            if(!isset($result['quest'])) $result[$item['quest_number']]['quest'] = $item['quest_text'];
            if(!isset($result['theme'])) $result[$item['quest_number']]['theme'] = $item['theme_text'];
            $result[$item['quest_number']]['answers'][] = [
                'order'=> $item['answ_order'],
                'right' => $item['answ_right'],
                'text' => $item['answ_text']
            ];

            if($item['answ_right'] == 1) $result[$item['quest_number']]['right'][] = $item['answ_order'];

        }
        if($number !== false){
            $result = $result[$item['quest_number']];
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