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
        if($count < 1){
            // вопросов в теме ещё нет
            return 'notest';
        }
        elseif(isset($_POST[$theme])){
            // есть ответ
            if(Test::checkAnswer(self::$code)){
                // ответ верный
                Message::setTest(MessageTest::right);
            }else{
                // ответ ошибочный
                Message::setTest(MessageTest::wrong);
                return 'wrong';
            }
        }
        elseif (isset($_POST['reset'])){
            Test::cleanStat(self::$code);
        }
        if(count($_SESSION['log'][$theme]) == $count){
            // отвечены все вопросы
            // показать результат
            return 'result';
        }
        // показать новый вопрос
        return 'test';
    }
    public function render()
    {
        $class = self::$class;
        // создаем экзепляр класса с указанием кода
        $page = new $class(self::$code);
        // получаем действие
        $action = $this->getAction();
        $execute = 'getContent'.ucfirst($action);
        // выполняем действие - получаем контент
        $head = $page->getContentPage();
        $content = $page->$execute();
        $content = array_merge($content,$head);
        $content['message'] = Message::get();
        // выбираем шаблон
        $template = [VIEW_DIR_TEST.$action.'.php',VIEW_DIR_TEST.'footer.php'];
        $this->getView($template,$content);
    }

}