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
    protected static $regexpRight = "#^[\s|\t]*([0-9]+)[\s\t]*([а-яА-Яa-zA-Z,\s]+)$#u";
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
     * @return array|bool $quests - возвращаемый массив вопросов
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
        if($countQuest < 1){
            $this->errors .= "Вопросы в тексте не найдены<br>";
            return false;
        }
        elseif($countQuest !== $countAnswer){
            $this->errors .= "Количество вопросов не соответствует ответам "
                ."(на каждый вопрос должен быть хотябы один ответ)<br>";
            return false;
        }
        if($countAnswer !== $countRight){
            $this->errors .= "Количество правильных ответов не соответствует ".
                "количеству ответов (должен быть хотя бы один верный ответ)<br>";
            return false;
        }
        if(count($diffAnswer)>0){
            $this->errors .= "Указанный верный ответ в вопросе ".
                implode($diffAnswer," ") ." не соответствует ".
                "количеству ответов (должен быть не больше, чем вариантов ответа)<br>";
            return false;
        }
        $this->message .= "Будет добавленно $countQuest вопросов.<br>";
        return $quests;
    }

    /**
     * метод проверки наличия полученного текста и подготовки текста
     * в массив для дальнейшей переработки
     * @return array|bool - массив необработанных строк (вопросы, ответы)
     */
    protected function prepareQuests(){
        $text = '';
        if(!empty($_FILES['file']['tmp_name']) || $_FILES['file']['type'] === "text/plain"){
            $text = file_get_contents($_FILES['file']['tmp_name']);
        }
        elseif(!empty($_POST['text'])){
            $text = $_POST['text'];
        }
        // уберем теги и добавим неразрывный пробел перед цифрой
        $text = preg_replace("#([\S|\T])[\s|\t](\d)#","$1&nbsp;$2",strip_tags($text));
        $text = ($text === '') ? false : explode("\n",$text);
        return $text;
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
    public function getContentAddTestBD(){
        /**
         * @var int $lastQuest -
         *
         */
        $code = $_SESSION['theme']['code'];
        $count = 0;
        try{
            if(isset(Test::getThemeList()[$code])){
                if(!DBase::delAllFromTheme($code)){
                   // $this->errors .= "Ошибка очистки темы $code";
                    Message::setError(MessageError::errorAddCleanTheme .$code);
                    return false;
                }
            }
            else{
                $theme = [
                    'code'=>$code,
                    'name'=>$_SESSION['theme']['name'],
                    'count'=> 0
                ];
                if(DBase::insertTheme($theme)){
                    //$this->message .= "Добавлена тема ".$theme['code']." - ". $theme['name']."<br>";
                    Message::setMessage(MessageInfo::infoCreateThemeTheme ." - ". $theme['name']);
                }else{
                    //$this->errors .= "Ошибка добавления темы ".$theme['code']." - ". $theme['name']."<br>";
                    Message::setError(MessageError::errorAddNewTheme .$theme['code']." - ". $theme['name']);
                    return false;
                }
            }
            foreach ($_SESSION['addTests'] as $tests){
                $num = $tests['number'];
                $value = [$code, $tests['number'], $tests['quest']];
                // вызываем добавление вопроса
                // возвращается ID вопроса или false
                $addQuest = DBase::insertAddQuest($value);
                // если успешная запись вопроса увеличить счетчик вопросов на 1
                if($addQuest)
                    $count++;
                else{
                    //$this->errors .= "Ошибка записи вопроса $num<br>";
                    Message::setError(MessageError::errorAddQuest .$num);
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
                        //$this->errors .= "Ошибка записи ответа $key на вопрос $num<br>";
                        Message::setError(MessageError::errorAddAnswer1. $key . MessageError::errorAddAnswer2 .$num);
                        return false;
                    }
                }
            }
            if(!DBase::updateThemeCount($code,$count)){
                //$this->errors .= "Ошибка обновления темы<br>";
                Message::setError(MessageError::errorAddUpdateTheme);
                return false;
            }
        }catch (Exception $e){
            echo "Ошибка записи в базу: ".$e;
        }
        if(empty($this->errors)){
            //$this->message .= "Добавлено вопросов $count<br>";
            Message::setMessage(MessageInfo::infoAddQuests . $count);
            unset($_SESSION['addTests']);
            return ['theme' => Test::getThemeList()];
        }
        return false;
    }

    /**
     * метод проверки наличия темы для добавления вопросов
     * @return bool
     */
    public static function checkAddTheme(){
        $newCode = strip_tags($_POST['newCode']);
        // если выбрано добавление темы
        if(strip_tags($_POST['addCode']) === 'new'){
            if(empty($newCode) && empty($_POST['newName'])){
                Message::setError(MessageError::errorAddThemeNotCode);
                //$this->errors .= "Не указана новая тема для добавления тестов.<br>";
                return false;
            }
            elseif(array_key_exists($newCode, Test::getThemeList() )){
                Message::setError(MessageError::errorAddThemeCodeExist);
                //$this->errors .= "Нельзя указать для новой темы код существующей.<br>";
                return false;
            }
            elseif( 1 ){
                foreach (Page::getList() as $page){
                    if($page['code'] == $newCode){
                        Message::setError(MessageError::errorAddThemeCodeExist);
                        //$this->errors .= "Нельзя указать для новой темы код существующей.<br>";
                        return false;
                    }
                }
            }else{
                $theme['code'] = $_SESSION['theme']['code'] = $newCode;
                $theme['name'] = $_SESSION['theme']['name'] = strip_tags($_POST['newName']);
            }
        }else{
            $theme['code'] = $_SESSION['theme']['code'] = strip_tags($_POST['addCode']);
            $theme['name'] = $_SESSION['theme']['name'] = Test::getThemeList()[$theme['code']]['text'];
        }
        return true;
    }
    /**
     * метод показа загруженных тестов для добавления в базу
     */
    /*protected function previewAddTest($preTest){
        $template = 'addtest';
        if($this->checkAddTheme()){
            $_SESSION['addTests'] = $this->convertQuests($preTest);
            foreach ($_SESSION as $key=>$item){
                $$key = $item;
            }
            $template .= '_preview';
        }

        $errors = trim($this->errors,"<br>");
        $message = trim($this->message,"<br>");
        include VIEW_DIR_ADMIN.$template.'.php';
    }*/
    public function getContentAddtest(){
        $theme = Test::getThemeList();
        return ['theme'=>$theme];
    }

    public function getContentPreviewAddTest(){
        $preTest = $this->prepareQuests();
        $tests = $this->convertQuests($preTest);
        if($tests){
            $_SESSION['addTests'] = $tests;
            $result = $_SESSION;
            $result['count'] = count($tests);
            return $result;
        }
        //$this->errors .= "Нет тестов для добавления<br>";
        Message::setError(MessageError::errorAddNotQuests);
        return false;
    }
//    public function getContentAddTestBD(){
//        echo "<pre>";
//        print_r($_SESSION);
//        var_dump();
//        echo "</pre>";
//        if($this->insertAddTest()){
//
//        }
//        return [];
//    }
    public function getContentAdmin(){
        return [];
    }
      /**
     * метод подготовки отображения основной части станицы
     * @param string $template
     */
    protected function prepareBody($template)
    {
        // список ситуационных страниц
        $pages = [
          'previewAddTest'=>'addtest_preview',
          'addTest'=>'addtest',
          'addTestBD'=>'addtest'
        ];
        $case = $this->separation();
        if($case === false) $case = $this->code;
        // страница отображения
        // - если есть в ситуационных страницах
        // - если нет - то по коду текущей страницы
        $page = isset($pages[$case]) ? $pages[$case] : $this->code;
        echo "<pre>";
        //print_r($case);
        echo "</pre>";
        $execute = 'build'.ucfirst($case);
        $content = $this->$execute();
        // загружаем сообщения и ошибки
        $errors = trim($this->errors,"<br>");
        $message = trim($this->message,"<br>");
        // если контент вернул ложь
        // - значит ошибка,
        // отобразить текущую страницу
        if($content === false || empty($content)){
            $execute = 'build'.ucfirst($this->code);
            $content = $this->$execute();
            $page = $this->code;
        }
        foreach ($content as $key=>$item){
            $$key = $item;
        }
        //print_r($content);
        echo "</pre>";
        include VIEW_DIR_ADMIN.$page.'.php';
        /*
        $theme = Test::getThemeList();
        $preTest = $this->prepareQuests();

        if($preTest && $template === "addtest"){
            $this->previewAddTest($preTest);
        }
        elseif (isset($_POST['code'][0])){
            $code = strip_tags($_POST['code'][0]);
            $page = new Test();
            $tests = $page->getTest($code);
            include VIEW_DIR_ADMIN."test_list.php";
        }
        elseif(isset($_POST['submit'])) {
            $this->insertAddTest();
            $errors = trim($this->errors,"<br>");
            $message = trim($this->message,"<br>");
            include VIEW_DIR_ADMIN.$template.'.php';
        }
        else
            {
            $errors = trim($this->errors,"<br>");
            $message = trim($this->message,"<br>");
            include VIEW_DIR_ADMIN.$template.'.php';
        }*/
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