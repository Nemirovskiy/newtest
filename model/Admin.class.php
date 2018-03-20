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
    protected static $message = '';
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
         * @var array $quests      - возвращаемый массив вопросов
         * @var int   $quests_num  -  номер текущего вопроса
         * @var array $prevQ       - массав с предыдущими номерами вопросов, для подсчета правильных ответов
         * @var int   $countQuest  - количество загруженных вопросов
         * @var int   $countAnswer - количество ответов (мин 1 на вопрос)
         * @var int   $countRight  - количество правильных ответов (мин 1 на вопрос)
         * @var array $diffAnswer  - кол-во несоответствий верных ответов (верный ответ больше кол-ва ответов)
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
                ."(на каждый вопрос должен быть хотябы один ответ)<br>";
        }
        if($countAnswer !== $countRight){
            self::$errors .= "Количество правильных ответов не соответствует ".
                "количеству ответов (должен быть хотя бы один верный ответ)<br>";
        }
        if(count($diffAnswer)>0){
            self::$errors .= "Указанный верный ответ в вопросе ".
                implode($diffAnswer," ") ." не соответствует ".
                "количеству ответов (должен быть не больше, чем вариантов ответа)<br>";
        }
        self::$message .= "Будет добавленно $countQuest вопросов.<br>";
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
     * метод добавления загруженных тестов в базу
     *
     */
    private function insertAddTest(){
        /**
         * @var int $lastQuest -
         *
         */
        $code = $_SESSION['theme']['code'];
        $count = 0;
        try{
            if(isset(Test::getThemeList()[$code])){
                if(!DBase::delAllFromTheme($code)){
                    self::$errors .= "Ошибка очистки темы $code";
                    return false;
                }
            }
            else{
                echo "<br>добавляем тему $code<br>";
                $theme = [
                    'code'=>$code,
                    'name'=>$_SESSION['theme']['name'],
                    'count'=> 0
                ];
                if(DBase::insertTheme($theme)){
                    self::$message .= "Добавлена тема ".$theme['code']." - ". $theme['name']."<br>";
                }else{
                    self::$errors .= "Ошибка добавления темы ".$theme['code']." - ". $theme['name']."<br>";
                    return false;
                }
            }
            foreach ($_SESSION['tests'] as $tests){
                $num = $tests['number'];
                $value = [$code, $tests['number'], $tests['quest']];
                // вызываем добавление вопроса
                // возвращается ID вопроса или false
                $addQuest = DBase::insertAddQuest($value);
                // если успешная запись вопроса увеличить счетчик вопросов на 1
                if($addQuest)
                    $count++;
                else{
                    self::$errors .= "Ошибка записи вопроса $num<br>";
                    return false;
                }
                // перебор вариантов ответов
                foreach($tests['answers'] as $key => $answer){
                    $value = [
                        $addQuest,
                        $key,
                        $answer['right'],
                        $answer['text']
                    ];
                    if(!DBase::insertAddAnswer($value)){
                        self::$errors .= "Ошибка записи ответа $key на вопрос $num<br>";
                        return false;
                    }
                }
            }
            if(!DBase::updateThemeCount($code,$count)){
                self::$errors .= "Ошибка обновления темы<br>";
                return false;
            }
        }catch (Exception $e){
            echo "Ошибка записи в базу: ".$e;
        }
        if(empty(self::$errors))
            self::$message .= "Добавлено вопросов $count<br>";
        return $count;
    }

    /**
     * метод показа загруженных тестов для добавления в базу
     */
    protected function previewAddTest($preTest){
        if(empty($_POST['code'][0]) && empty($_POST['code'][1])){
            self::$errors .= "Не указана тема для добавления тестов.<br>";
        }else{
            $theme['code'] = $_SESSION['theme']['code'] = strip_tags($_POST['code'][0]);
            $theme['name'] = $_SESSION['theme']['name'] = strip_tags($_POST['name'][0]);
        }
        $tests = $_SESSION['tests'] = $this->convertQuests($preTest);
        $errors = trim(self::$errors,"<br>");
        $message = trim(self::$message,"<br>");
        include VIEW_DIR_ADMIN.'addtest_preview.php';
    }
    /**
     * метод подготовки отображения основной части станицы
     * @param string $template
     */
    protected function prepareBody($template)
    {
        $theme = Test::getThemeList();
        $preTest = $this->prepareQuests();

        if($preTest && $template === "addtest"){
            $this->previewAddTest($preTest);
        }
        elseif (isset($_POST['code'][0])){
            $code = strip_tags($_POST['code'][0]);
            $query = "SELECT * from quest INNER JOIN theme ON (quest.theme_code = theme.theme_code)
                  INNER JOIN answ ON (quest.quest_id = answ.quest_id) WHERE theme.theme_code = ? AND quest_number > 0";
            $tests = DBase::select($query,[$code]);
            print_r($tests);
            //$this->previewAddTest($tests);
        }
        elseif(isset($_POST['submit'])) {
            $this->insertAddTest();
            $errors = trim(self::$errors,"<br>");
            $message = trim(self::$message,"<br>");
            include VIEW_DIR_ADMIN.$template.'.php';
        }
        else
            {
            $errors = trim(self::$errors,"<br>");
            $message = trim(self::$message,"<br>");
            include VIEW_DIR_ADMIN.$template.'.php';
        }
       // echo " == $message ==";
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