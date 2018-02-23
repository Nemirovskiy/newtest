<?
$test = file($_FILES['file']['tmp_name']);
$i=0;
$quests =[];
// функция преобразования букв в порядковые номера
function literToNum($liter=''){
    if(is_numeric($liter)) return $liter;
    $cirilic = explode(' ',"а б в г д е ж з и к л м н о");
    $search  = array_merge($cirilic,range("a","n"));
    $replace = range(1,count($cirilic));
    $replace = array_merge($replace,$replace);
    $strlower = mb_strtolower($liter, mb_detect_encoding($liter));
    return str_replace($search, $replace, trim($strlower));
}
$quests_num = '';
foreach($test as $v){
    if (preg_match("#[\s|\t]*[\#|№]+[\s|\t]*([0-9]*)(.*)#u", $v, $q)) //чтение вопроса
    {
        $quests_num = (int)$q[1];
        $quests_text = trim(trim($q[2],'. : ; 	'),'. : ; 	');
        $quests[$quests_num]['quest'] = $quests_text;
        $quests[$quests_num]['number'] = $quests_num;
    }
    elseif (preg_match("#([а-яА-ЯёЁ\s]+)\)(.*)#u", $v, $a)) //чтение вариантов ответа
    {
        $order = literToNum($a[1]);
        $text = trim(trim($a[2]," . : ; ")," . : ; ");
        $quests[$quests_num]['answers'][$order]= ['order' => $order, 'right'=>0, 'text'=>$text];
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

foreach ($quests as $arr){
    $theme = $arr['theme'];
    $code = 'Theme';
    $num = $arr['number'];
    $quest = $arr['quest'];
    $answers = $arr['answers'];
    include VIEW_DIR_ADMIN.'test_preview.php';
}
?>
<div class='cntr'>
<h1>Администратор</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <input type="submit" value="отправить">
    </form>
</div>