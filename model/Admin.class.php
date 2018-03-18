<?
/**
 * Класс Администратора
 *
 */

class Admin extends Page
{
    /**
     * @var string $regexpQuest - регулярное выражение для поиска вопроса
     * @var string $regexpAnswer - регулярное выражение для поиска ответа
     * @var string $regexpRight - регулярное выражение для поиска правильного ответа
     */
    protected static $regexpQuest = "#^[\s|\t]*[\#|№]+[\s|\t]*([0-9]*)(.*)$#u";
    protected static $regexpAnswer = "#^[\s|\t]*([a-zA-Zа-яА-ЯёЁ\s]+)\)(.+)#u";
    protected static $regexpRight = "#^[\s|\t]*([0-9]+)[\s\t]*([а-яА-Яa-zA-Z|,\s]{1,10})$#u";
    protected static $errors = '';
    /**
     * метод преобразования букв в порядковые номера
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
     * метод обработки текста в массив вопросов
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
            if (preg_match(self::$regexpQuest, $string, $q)) //чтение вопроса
            {
                $quests_num = (int)$q[1];
                // очищаем от лишних символов и убираем перевод строк
                $quests[$quests_num]['quest'] = str_replace(
                    ["\n","\r"],"",trim($q[2],"\x00..\x20 ;:.")
                );
                $quests[$quests_num]['number'] = $quests_num;
                $countQuest++;
            }
            elseif (preg_match(self::$regexpAnswer, $string, $a)) //чтение вариантов ответа
            {
                $order = self::literToNum($a[1]);
                $quests[$quests_num]['answers'][$order] = [
                    'order' => $order,
                    'right' => 0,
                    // очищаем от лишних символов и убираем перевод строк
                    'text' => str_replace(["\n","\r"],"",trim($a[2],"\x00..\x20 ;:."))
                ];
                if($prevQW !== $quests_num){
                    $prevQW = $quests_num;
                    $countAnswer++;
                }
            }
            if (preg_match(self::$regexpRight, $string, $a)) //чтение правильного ответа
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
     * метод проверки наличия полученного текста и подготовки текста
     * в массив для дальнейшей переработки
     * @return array|bool - массив необработанных строк (вопросы, ответы)
     */
    protected function prepareQuests(){
        if(!empty($_FILES['file']['tmp_name']) || $_FILES['file']['type'] === "text/plain"){
            $text = file_get_contents($_FILES['file']['tmp_name']);
            return explode("\n",strip_tags($text));
        }
        elseif(!empty($_POST['text'])){
            return explode("\n",strip_tags($_POST['text']));
        }
        return false;
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

    /**
     * метод показа загруженных тестов для добавления в базу
     */
    protected function previewAddTest($preTest){
        $_POST['code'];
        $_SESSION['tests'] = $tests = $this->convertQuests($preTest);
        echo "<pre>";
        //print_r($tests);
        echo "</pre>";
        $_SESSION['theme']['code'] = $theme['code'] = strip_tags($_POST['code'][0]);
        $_SESSION['theme']['name'] = $theme['name'] = strip_tags($_POST['name'][0]);
        $errors = self::$errors;
        include VIEW_DIR_ADMIN.'addtest_preview.php';
    }

    private function insertAddTest(){
        echo "<pre>";
        $prepareQuest = DBase::prepare("INSERT INTO `quest` ".
            "(`theme_code`, `quest_number`, `quest_text`) ".
            "VALUES (?,?,?)"
        );
        $prepareAnswer = DBase::prepare("INSERT INTO `answ` ".
            "(`quest_id`, `answ_order`, `answ_right`, `answ_text`) ".
            "VALUES (?,?,?,?)"
        );
        //$stringAnswPrepare = "INSERT INTO `answ` (`quest_id`, `answ_order`, `answ_right`, `answ_text`) VALUES (?,?,?,?)";
        //$stringQuestPrepare = "INSERT INTO `quest` (`theme_code`, `quest_number`, `quest_text`) VALUES (?,?,?)";

        try{
                foreach ($_SESSION['tests'] as $arr){
                    $code = $_SESSION['theme']['code'];
                    $num = $arr['number'];
                    /**
                     * Запись вопроса
                     * INSERT INTO `quest` (`theme_code`, `quest_number`, `quest_text`) VALUES
                     * ('feld', 2, 'ИМПУЛЬС, ВЫШЕДШИЙ ИЗ А/В УЗЛА, ВЫГЛЯДИТ НА ЭКГ КАК')
                     */
                    $ee = [$code, $num, "".addslashes($arr['quest']).""];
                    echo "<br>Q ";
                    print_r($ee);
                    var_dump($prepareQuest->execute($ee));
                    /**
                     * Запись ответа
                     * INSERT INTO `answ` (`quest_id`, `answ_order`, `answ_right`, `answ_text`) VALUES
                     * (1, 1, 0, 'Правильное чередование +зубцов Р, нормальных QRS с ЧСС 40-60 в 1 мин')
                     */
                    foreach($arr['answers'] as $key => $answer){
                        $ee = [$num, $key, $answer['right'],
                            "".addslashes($answer['text']).""];
                        echo "<br>A ";
                        print_r($ee);
                        var_dump($prepareAnswer->execute($ee));
                    }
                }
            }catch (Exception $e){
                echo "Ошибка записи в базу: ".$e;
            }

            echo "Ok!";
    }
    /**
     * метод подготовки отображения основной части станицы
     * @param string $template
     */
    protected function prepareBody($template)
	{
        $theme = Test::getThemeList();
        $preTest = $this->prepareQuests();
	    if($preTest){
            $this->previewAddTest($preTest);
        }
        else{
            include VIEW_DIR_ADMIN.$template.'.php';
        }
        if(isset($_POST['submit'])) {
	        $this->insertAddTest();
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