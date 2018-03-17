<?
/**
 * Класс Администратора
 *
 */

class Admin extends Page
{
    /**
     * @var string $regexQuest - регулярное выражение для поиска вопроса
     * @var string $regexAnsv - регулярное выражение для поиска ответа
     * @var string $regexRight - регулярное выражение для поиска правильного ответа
     */
    protected static $regexQuest = "#^[\s|\t]*[\#|№]+[\s|\t]*([0-9]*)(.*)$#u";
    protected static $regexAnsv = "#^[\s|\t]*([a-zA-Zа-яА-ЯёЁ\s]+)\)(.+)#u";
    protected static $regexRight = "#^[\s|\t]*([0-9]+)[\s\t]*([а-яА-Яa-zA-Z|,\s]{1,10})$#u";
    protected static $errors = '';
    /**
     * функция преобразования букв в порядковые номера
     * @param string $liter - входящий символ или строка
     * @return string  - преобразованый символ или строка
     */
    protected static function literToNum($liter=''){
        if(is_numeric($liter)) return $liter;
        $cirilic = explode(' ',"а б в г д е ж з и к л м н о");
        $search  = array_merge($cirilic,range("a","n"));
        // для замены готовим двойной массив из цифр
        $replace = range(1,count($cirilic));
        $replace = array_merge($replace,$replace);
        $strlower = mb_strtolower($liter, mb_detect_encoding($liter));
        return str_replace($search, $replace, trim($strlower));
    }

    /**
     * Функция обработки текста в массив вопросов
     * @param array $arrayStr входящий массив строк
     * @return array $quests - возвращаемый массив вопросов
     *
     */
    protected function convertQuests($arrayStr){
        /**
         * @var array $quests - возвращаемый массив вопросов
         * @var int $quests_num -  номер текущего вопроса
         * @var array $prevQ - массав с предыдущими номерами вопросов, для подсчета правильных ответов
         * @var int $countQuest - количество загруженных вопросов
         * @var int $countAnswer - количество ответов (мин 1 на вопрос)
         * @var int $countRight - количество правильных ответов (мин 1 на вопрос)
         * @var array $diffAnswer - кол-во несоответствий верных ответов (верный ответ больше кол-ва ответов)
         *
         */

        $quests_num = '';
        $quests =[];
        $countQuest = 0;
        $countRight = 0;
        $countAnswer = 0;
        $prevQ = [];
        $prevQW = 0;
        $diffAnswer = [];
        foreach($arrayStr as $string){
            if (preg_match(self::$regexQuest, $string, $q)) //чтение вопроса
            {
                $quests_num = (int)$q[1];
                // очищаем от лишних символов и убираем перевод строк
                $quests[$quests_num]['quest'] = str_replace(
                    ["\n","\r"],"",trim($q[2],"\x00..\x2F \x3A..\x3B")
                );
                $quests[$quests_num]['number'] = $quests_num;
                $countQuest++;
            }
            elseif (preg_match(self::$regexAnsv, $string, $a)) //чтение вариантов ответа
            {
                $order = self::literToNum($a[1]);
                //$text = trim($a[2],".:;\s\t ");
                $quests[$quests_num]['answers'][$order] = [
                    'order' => $order,
                    'right' => 0,
                    // очищаем от лишних символов и убираем перевод строк
                    'text' => str_replace(["\n","\r"],"",trim($a[2],"\x00..\x20 \x3A..\x3B"))
                ];
                if($prevQW !== $quests_num){
                    $prevQW = $quests_num;
                    $countAnswer++;
                }
            }
            if (preg_match(self::$regexRight, $string, $a)) //чтение правильного ответа
            {
                // если ответы написаны через пробел или запятую
                $str = preg_split("#[,\s]*#u", $a[2]);
                foreach ($str as $item){
                    if($item) {
                        $right = self::literToNum($item);
                        // если ответы написаны слитно
                        if(strlen($right) >1) {
                            for($i = 0;$i<strlen($right);$i++){
                                if(!isset($quests[$a[1]]['answers'][$right[$i]])) $diffAnswer++;
                                $quests[$a[1]]['answers'][$right[$i]]['right'] = 1;
                                if(!in_array($a[1],$prevQ)){
                                    $prevQ[] = $a[1];
                                    $countRight++;
                                }
                            }
                        }
                        else {
                            if(!isset($quests[$a[1]]['answers'][$right])){
                                $diffAnswer[] = $a[1];
                            }
                            else{
                                $quests[$a[1]]['answers'][$right]['right'] = 1;
                            }
                            if(!in_array($a[1],$prevQ)){
                                $prevQ[] = $a[1];
                                $countRight++;
                            }
                        }
                    }
                }
            }
        }
        /**
         * проверка подсчета кол-ва вопросов - ответов - верных ответов
         */
        if($countQuest !== $countAnswer){
            self::$errors .= "Количество вопросов не соответствует ответам "
                ."(на каждый вопрос должен быть хотябы один ответ)";
        }
        if($countAnswer !== $countRight){
            if(strlen(self::$errors)>0) self::$errors .="<br>";
            self::$errors .= "Количество правильных ответов не соответствует ".
                "количеству ответов (должен быть хотя бы один верный ответ)";
        }
        if(count($diffAnswer)>0){
            if(strlen(self::$errors)>0) self::$errors .="<br>";
            self::$errors .= "Указанный верный ответ в вопросе ".
                implode($diffAnswer," ") ." не соответствует ".
                "количеству ответов (должен быть не больше, чем вариантов ответа)";
        }
        return $quests;
    }

    /**
     * Функция подготовки полученного текста
     * в массив для дальнейшей переработки
     * @return array $test - массив необработанных строк (вопросы, ответы)
     */
    protected function prepareQuests(){
        $test = null;
        if(!empty($_FILES['file']['tmp_name']) || $_FILES['file']['type'] == "text/plain"){
            $test = file($_FILES['file']['tmp_name']);

        }


        if(!empty($_POST['text']))
            $test = explode("\n",$_POST['text']);
        return $test;
    }

    /**
     * @param string $template
     * @var array $arrayTests
     */
    protected function prepareHead($code){
        parent::prepareHead($code);
        $nav = $this->setMenu("admin_menu");
        include VIEW_DIR_INCLUDE.'nav.php';
    }
    protected function prepareBody($template)
	{
        /**
         * 1. Формирование массива вопросов - ответов
         *
         * @var array $arrayTests
         */
        $test = new Test();
        $theme = $test->getThemeList();
        //print_r($theme);
	    if(empty($this->prepareQuests())){
            include VIEW_DIR_ADMIN.$template.'.php';
        }
        else{
            // вывод на страницу результат
            $tests = $this->convertQuests(
                $this->prepareQuests()
            );
            $_SESSION['tests'] = $tests;
            echo "<pre>";
            //print_r($tests);
            echo "</pre>";
            $codeArr = explode("@",$_POST['code']);
            $_SESSION['theme']['code'] = $theme['code'] = empty($_POST['newCode'])?$codeArr[0]:$_POST['newCode'];
            $_SESSION['theme']['name'] = $theme['name'] = empty($_POST['newName'])?$codeArr[1]:$_POST['newName'];
            $errors = self::$errors;
//            foreach ($arrayTests as $arr){
//                $theme = $arr['theme'];
//                $code = 'Theme';
//                $num = $arr['number'];
//                $quest = $arr['quest'];
//                $answers = $arr['answers'];
                include VIEW_DIR_ADMIN.'addtest_preview.php';
//            }
        }

        if(isset($_POST['submit'])) {
            foreach ($_SESSION['tests'] as $arr){
                $code = 'Theme';
                $num = $arr['number'];
                /**
                 * Запись вопроса
                 * INSERT INTO `quest` (`theme_code`, `quest_number`, `quest_text`) VALUES
                 * ('feld', 2, 'ИМПУЛЬС, ВЫШЕДШИЙ ИЗ А/В УЗЛА, ВЫГЛЯДИТ НА ЭКГ КАК')
                 */
                $stringQuest[] = [
                    $code,
                    $num,
                    "'".addslashes($arr['quest'])."'"
                ];
                /**
                 * Запись ответа
                 * INSERT INTO `answ` (`quest_id`, `answ_order`, `answ_right`, `answ_text`) VALUES
                 * (1, 1, 0, 'Правильное чередование +зубцов Р, нормальных QRS с ЧСС 40-60 в 1 мин')
                 */
                foreach($arr['answers'] as $key => $answer){
                   $stringAnsw[] = [
                       $num,
                       $key,
                       $answer['right'],
                       "'".addslashes($answer['text'])."'"
                   ];
                }
            }
            // возвращаемое значение - строка для записи в БД -  неподготовленные строки
            echo "<pre>";
            print_r($stringAnsw);
            print_r($stringQuest);
	        echo "<pre>";
        }
	}

//	public function renderHtml($code)
//	{
//	    // проверяем на корректность адрес
//	    $valid = $this->validAdress($code);
//	    // подготавливаем голову
//        $this->prepareHead($code);
//        // если адрес корректный - подготовим контент
//        if($valid){
//            $this->prepareBody($code);
//        }
//        // иначе - отобразим старницу ошибки
//        else {
//            include VIEW_DIR_ERORS.'404.php';
//        }
//        // подключим низ страницы
//        include VIEW_DIR_INCLUDE.'footer.php';
//	}
}