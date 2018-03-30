<?php
/**
 * Created by PhpStorm.
 * User: Николай
 * Date: 28.03.2018
 * Time: 16:01
 */

class TestController extends PageController
{
    /**
     * метод определения действия
     * варианты
     * 1. - в теме нет тестов
     * 2. - есть ответ на вопрос
     * 2.1. - ответ верный
     * 2.2. - ответ не верный
     * 3. - сброс статистики
     * 4. - отвечены все попросы в теме
     * ---- нет действий - показать тест
     * @return string
     */
    protected function getAction(){
        // код подготовки выражений для проверки
        $theme = self::$code;
        $class = self::$class;
        $count = $class::getThemeList()[$theme]['count'];
        $answer = !empty($_POST[$theme]);
        $reset = !empty($_POST['reset']);

        if($count < 1){
            // вопросов в теме ещё нет
            return 'notest';
        }
        elseif($answer){
            return 'check';
        }
        elseif ($reset){
            return 'reset';
        }
        if(count($_SESSION['used'][$theme]) == $count){
            // отвечены все вопросы / ответ верный и вопросов больше нет
            // показать статистику
            return 'result';
        }
        // показать новый вопрос
        return 'test';
    }
    public function getTemplate()
    {
        echo " self::code - ".self::$code."<br>";
        $template = VIEW_DIR_TEST;
//        switch (self::$code){
//            case 'notest':
//                $template .= 'test.php';
//                break;
//            case 'notest':
//                $template .= 'test.php';
//                break;
//            default: $template .= 'test.php';
//        }
        if(self::$code !== 'notest' && self::$code !== 'wrong' && self::$code !== 'result')
            $template = VIEW_DIR_TEST.'test.php';
        else
            $template = VIEW_DIR_TEST.self::$code.'.php';
        return [$template,VIEW_DIR_TEST.'footer.php'];
    }


}