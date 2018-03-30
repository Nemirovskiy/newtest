<?php
/**
 * Created by PhpStorm.
 * User: Николай
 * Date: 28.03.2018
 * Time: 16:00
 */

class AdminController extends PageController
{
    protected function getAction(){
        /**
         *
         */
        // код подготовки для проверки

        // проверяем есть ли пост?
        if(!empty($_POST)){
            // если есть указанная тема
            // проверим есть ли текст/файл
            if(!empty($_POST['addCode'])){
                if(Admin::checkAddTheme() && (!empty($_FILES['file']['tmp_name']) || !empty($_POST['text']))){
                    // покажем загруженные тесты и спросим добавить?
                    // отобразить список тестов
                    return 'previewAddTest';
                }else{
                    Message::setError(MessageError::errorAddNotText);
                    //$this->errors .= "Не указан текст для добавления тестов.<br>";
                    return false;
                }
            }
            // если нет файла - покажем ошибку
            if (!empty($_SESSION['addTests']) && isset($_POST['submit'])){
                // нажата кнопка добавить
                // добавим в БД
                if(empty($_SESSION['addTests'])){
                    Message::setError(MessageError::errorAddNotText);
                    //$this->errors .= "Нет текста для добавления тестов.<br>";
                    return false;
                }
                return 'addTestBD';
            }
        }
        elseif(0){
            //
        }
        // если условия не подошли - укажем код текущей страницы
        return self::$code;
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
        $template = VIEW_DIR_ADMIN.$action.'.php';
        $this->getView($template,$content);
    }

}