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

    public static function checkAnswer($theme){
        /**
         * $_SESSION['log'][$theme][]  - массив отвеченных вопросов в теме
         * $_SESSION['right'][$theme][] - массив правильных ответов в теме
         */
        //$answer = $_POST[$theme];
        $right = $_SESSION["current"]["right"];
        // помечаем отвеченный вариант ответа
        foreach ($_POST[$theme] as $item){
            // проверка входящего на число
            $number = (int)$item;
            if($number > 0){
                $_SESSION["current"]["answers"][$item - 1]['check'] = true;
                $answer[] = $number;
            }
            else{
                Message::setError(MessageError::errorPostData);
                return true;
            }
        }
        // записываем в сессию номер отвеченного вопроса 
        $_SESSION['log'][$theme][] = $_SESSION['current']['number'];
        // собственно проверка правильности ответа
        if(implode(",",$answer) === implode(",",$right)){
            $_SESSION['right'][$theme][] = $_SESSION['current']['number'];
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
        if($infinity || empty($_SESSION['log'][$theme]))
            $number = rand(1,self::getThemeList()[$theme]['count']);
        elseif(count($_SESSION['log'][$theme]) < self::getThemeList()[$theme]['count']){
            do{
                $number = rand(1,self::getThemeList()[$theme]['count']);
            }while(in_array($number,$_SESSION['log'][$theme]));
        }
        else
            $number = false;
        return $number;
    }

    /**
     * метод сброса статистики
     * записывает в переменную test Класса сообщений
     * текст из константы класса сообщений теста
     * @param $code string - код темы
     */
    public static function cleanStat($code){
        $_SESSION['log'][$code] = [];
        $_SESSION['right'][$code] = [];
        Message::setTest(MessageTest::clean);
    }

    /**
     * метод формирования контента для
     * темы без тестов
     * @return array
     */
    public function getContentNotest(){
        return [];
    }

    /**
     * метод формирования контента
     * теста указанной темы
     * @return array
     */
    public function getContentTest(){
        $_SESSION['current']['code'] = $code = $this->code;
        $test = DBase::getTest($code,$this->generateNumberQuest());
        $test['code'] = $code;
        $test['stat'] = $this->getStat();
        $_SESSION['current'] = $test;
        return $test;
    }
    private function getStat(){
        $code = $this->code;
        $result['choice'] = $count = count($_SESSION['log'][$code]);
        $result['all'] = $all = Test::$list[$code]['count'];
        $result['right'] = $right = count($_SESSION['right'][$code]);
        $result['ratioR'] = ($count > 0) ? round($right / $count * 100) : 0 ;
        $result['ratioC'] = ($all > 0) ? round($count / $all * 100) : 0 ;
        return $result;
    }
    public function getContentWrong(){
        $result = $_SESSION['current'];
        $result['wrong'] = true;
        $result['stat'] = $this->getStat();
        return $result;
    }

    public function getContentResult(){
        $code = $this->code;
        $result['count'] = $all = count($_SESSION['log'][$code]);
        $result['right'] = $right = count($_SESSION['right'][$code]);
        $result['ratio'] = ($right > 0) ? round($right / $all * 100) : 0 ;
        $result['stat'] = $this->getStat();
        return $result;
    }

    /**
     * метод установки заголовка страницы теста
     * @return string
     */
    function setHeader()
    {
        // получаем список страниц
        $code = $this->code;
        $tests = Test::getThemeList();
        return MessageTest::header.$tests[$code]['text'];
    }

    /**
     * Метод подготовки основной части страницы
     *
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
            $content['log'] = $_SESSION['log'];
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
     * структура хранения вопросов в сессии
     *
     * запись при ответе:
     * $_SESSION['log'][$theme][]  - массив отвеченных вопросов в теме
     * $_SESSION['right'][$theme][] - массив правильных ответов в теме
     * $_SESSION['current'][
     *       'theme'=> текущая тема
     *       'quest'=> [массив вопроса]
     * добавить при ответе:
     *       'used'=>  [массив выбранных вариантов]
     * ]
     *
     * $_COOKIE['log']
     */


    public static function getThemeList(){
        if(self::$list === null){
            self::$list = DBase::getThemeList();
        }
        return self::$list;
    }
}