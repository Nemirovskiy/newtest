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
    protected static $regexAnsv = "#([а-яА-ЯёЁ\s]+)\)(.+)#u";
    protected static $regexRight = "#([0-9]+)[\s\t]*([[а-яА-Яa-zA-Z|,\s]{1,10})$#u";
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
         */
        $quests_num = '';
        $quests =[];
        $countQuest = 0;
        $countRight = 0;
        foreach($arrayStr as $string){
            if (preg_match(self::$regexQuest, $string, $q)) //чтение вопроса
            {
                $quests_num = (int)$q[1];
                // очищаем от лишних символов и убираем перевод строк
                $quests[$quests_num]['quest'] = str_replace(["\n","\r"],"",trim($q[2],"\x00..\x2F \x3A..\x3B"));
                $quests[$quests_num]['number'] = $quests_num;
            }
            elseif (preg_match(self::$regexAnsv, $string, $a)) //чтение вариантов ответа
            {
                $order = self::literToNum($a[1]);
                $text = trim($a[2],".:;\s\t ");
                $quests[$quests_num]['answers'][$order] = ['order' => $order, 'right'=>0,
                    // очищаем от лишних символов и убираем перевод строк
                    'text'=>str_replace(["\n","\r"],"",trim($a[2],"\x00..\x20 \x3A..\x3B"))];
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
                                //echo $a[1]. " - " .$right[$i]."<br>";
                                $quests[$a[1]]['answers'][$right[$i]]['right'] = 1;
                            }
                        }
                        else {
                            $quests[$a[1]]['answers'][$right]['right'] = 1;
                        }
                    }
                }
            }
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
        if(!empty($_FILES['file']['tmp_name']))
            $test = file($_FILES['file']['tmp_name']);
            // проверка кодировки и преобразование
            //mb_detect_order("CP1251,ASCI,UTF-7,UTF-8");
            //print_r($test);
            //$test2 = array_map('mb_convert_encoding',$test,
                    //array_fill(0,count($test)-1,'UTF-8')/*,
                    //array_fill(0,count($test)-1,"auto")*/);
            //echo "<hr>".$test[0].$text[1].$test[2]."<br>";
            //echo mb_detect_encoding($test[0].$text[1].$test[2])."<br>";
            //print_r($test2);
        if(!empty($_POST['text']))
            $test = explode("\n",$_POST['text']);
        return $test;
    }

    /**
     * @param string $template
     * @var array $arrayTests
     */
    protected function prepareBody($template)
	{
        /**
         * 1. Формирование массива вопросов - ответов
         *
         * @var array $arrayTests
         */
	    if(empty($this->prepareQuests())){
            include VIEW_DIR_ADMIN.$template.'.php';
        }
        else{
            // вывод на страницу результат
            $arrayTests = $this->convertQuests($this->prepareQuests());
            foreach ($arrayTests as $arr){
                $theme = $arr['theme'];
                $code = 'Theme';
                $num = $arr['number'];
                $quest = $arr['quest'];
                $answers = $arr['answers'];
                include VIEW_DIR_ADMIN.'test_preview.php';
            }
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