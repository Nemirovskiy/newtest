<?
/**
 *
 */
class Test extends Page
{
    public static  $list    = null; 	// Список тем
    public static  $num     = ''  ; 	// номер вопроса
    public static  $quest   = ''  ; 	// Текст вопроса
    public static  $answers = []  ; 	// ответы
    public static  $wrong = false ; 	// ошибочный ответ

    public function checkAnswer(){
        /**
         * $_SESSION['used'][$theme][]  - массив отвеченных вопросов в теме
         * $_SESSION['right'][$theme][] - массив правильных ответов в теме
         */
        //echo "\nF:".__FUNCTION__;

        $theme = $this->code;//echo "\nt:".$theme;
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

    private function buildReset(){

        return [];
    }
    public function getContentNotest(){
        return [];
    }
    public function getContentCheck(){
        if($this->checkAnswer()){
            // ответ верный
            // показать, что ответ верный
//                    $result = $this->getContentTest();
//                    $result['message'] = 'Верно!';
            return 'test';
        }
        else{
            // ответ не верный
            // показать ответ
            //self::$wrong = true;
            //$result = $this->getContentWrong();
            return 'wrong';
        }
        echo __METHOD__."<br>";
        return [];
    }

    public function getContentTest(){
        // получим данные о страницы от родителя
        $head = parent::getContentPage();
        $_SESSION['curent']['code'] = $code = $this->code;
        $test = $this->getTest($code,$this->generateNumberQuest());
        $_SESSION['curent'] = $test;
        $test['code'] = $code;
        $test['right'] = '';
        $test['message'] = $this->message;
        $test['errors'] = $this->errors;
        return array_merge($test,$head);
    }
    public function getContentWrong(){
        $result = $_SESSION['curent'];
        $result['wrong'] = 'true';
        $result['message'] = $this->message;
        $result['errors'] = $this->errors;
        $result['user'] = $this->errors;
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

    public function renderJson($code){/*
        $this->code = $code;
        $this->validAdress($code);
//        $content = $this->getTest($code,$_POST['number']);
        $template = $this->separation();
        if($template === 'ajax'){

        }else{
            $execute = 'build'.ucfirst($template);
            $content = $this->$execute();
            $content['used'] = $_SESSION['used'];
        }
        $content['message'] = $this->message;
        $content['errors'] = $this->errors;
        $content['code'] = $this->code;
        echo json_encode($content);
    */}
    protected function prepareBody($testTheme)
    {/*
        $template = $this->separation();
        $execute = 'build'.ucfirst($template);
        $content = $this->$execute();
        $code = $this->code;
        foreach ($content as $key=>$item){
            $$key = $item;
        }
        //$_SESSION["curent"] = [];
        $message = $this->message;
        $errors = $this->errors;
        include VIEW_DIR_TEST.$template.'.php';
        echo "<pre>";
        //echo json_encode($content);
        //print_r($content);
        echo "</pre>";

        include VIEW_DIR_TEST."footer.php";
    */}

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