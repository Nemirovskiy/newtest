<?
/***************
 */
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
/****
 */
// функция преобразования букв в порядковые номера
/*function literToNum($liter=''){
    if(is_numeric($liter)) return $liter;
    $cirilic = explode(' ',"а б в г д е ж з и к л м н о");
    $search  = array_merge($cirilic,range("a","n"));
    // для замены готовим двойной массив из цифр
    $replace = range(1,count($cirilic));
    $replace = array_merge($replace,$replace);
    $strlower = mb_strtolower($liter, mb_detect_encoding($liter));
    return str_replace($search, $replace, trim($strlower));
}

// обработка текста в массив вопросов
$quests_num = ''; // номер текущего вопроса
$i=0;
$quests =[];
$countQuest = 0;
$countRight = 0;
foreach($test as $v){
    if (preg_match("#^[\s|\t]*[\#|№]+[\s|\t]*([0-9]*)(.*)$#u", $v, $q)) //чтение вопроса
    {
        $quests_num = (int)$q[1];
        // очищаем от лишних символов и убираем перевод строк
        $quests[$quests_num]['quest'] = str_replace(["\n","\r"],"",trim($q[2],"\x00..\x2F \x3A..\x3B"));
        $quests[$quests_num]['number'] = $quests_num;
    }
    elseif (preg_match("#([а-яА-ЯёЁ\s]+)\)(.+)#u", $v, $a)) //чтение вариантов ответа
    {
        $order = literToNum($a[1]);
        $text = trim($a[2],".:;\s\t ");
        //$text = str_replace(["\n","\r"],"",trim($a[2],"\x00..\x20 \x3A..\x3B"));
        $quests[$quests_num]['answers'][$order]= ['order' => $order, 'right'=>0,
            // очищаем от лишних символов и убираем перевод строк
            'text'=>str_replace(["\n","\r"],"",trim($a[2],"\x00..\x20 \x3A..\x3B"))];
    }
    if (preg_match("#([0-9]+)[\s\t]*([[а-яА-Яa-zA-Z|,\s]{1,10})$#u", $v, $a)) //чтение правильного ответа
    {
        // если ответы написаны через пробел или запятую
        $str = preg_split("#[,\s]*#u", $a[2]);
        foreach ($str as $item){
            if($item) {
                $right = literToNum($item);
                // если ответы написаны слитно
                if(strlen($right) >1) {
                    for($i = 0;$i<strlen($right);$i++){
                        echo $a[1]. " - " .$right[$i]."<br>";
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

// вывод на страницу результат
foreach ($quests as $arr){
    $theme = $arr['theme'];
    $code = 'Theme';
    $num = $arr['number'];
    $quest = $arr['quest'];
    $answers = $arr['answers'];
    include VIEW_DIR_ADMIN.'test_preview.php';
}

//$stringQuest = "INSERT INTO `quest` (`theme_code`, `quest_number`, `quest_text`) VALUES";
//$stringAnsw = "INSERT INTO `answ` (`quest_id`, `answ_order`, `answ_right`, `answ_text`) VALUES ";
foreach ($quests as $arr){
    $code = 'Theme';
    $num = $arr['number'];
    ////////////
    /// INSERT INTO `answ` (`quest_id`, `answ_order`, `answ_right`, `answ_text`) VALUES
    /// (1, 1, 0, 'Правильное чередование +зубцов Р, нормальных QRS с ЧСС 40-60 в 1 мин'),
    ///

    foreach($arr['answers'] as $key => $answer){
        $stringAnsw[] = [$num,$key,$answer['right'],"'".addslashes($answer['text'])."'"];
    }

    ////////////
    /// INSERT INTO `quest` (`theme_code`, `quest_number`, `quest_text`) VALUES
    /// ('feld', 2, 'ИМПУЛЬС, ВЫШЕДШИЙ ИЗ А/В УЗЛА, ВЫГЛЯДИТ НА ЭКГ КАК'),
    ///
    $stringQuest[] = [$code,$num,"'".addslashes($arr['quest'])."'"];
}
// возвращаемое значение - строка для записи в БД -  неподготовленные строки
echo "<pre>";
print_r($stringAnsw);
print_r($stringQuest);
//echo rtrim($stringAnsw,", ")."<hr>";
//echo rtrim($stringQuest,", ");
echo "</pre>";

*/
?>
<div class='cntr'>
<h1>Администратор</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <textarea name="text"></textarea>
        <input type="submit" value="отправить">
    </form>
</div>