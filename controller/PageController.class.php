<?php
/**
 * Created by PhpStorm.
 * User: Николай
 * Date: 28.03.2018
 * Time: 16:01
 */

class PageController extends Controller
{

    /**
     * метод формирования страницы
     * точка входа
     *
     */
    private static $errors = [404,403,500];
    public function render()
    {
        $class = self::$class;
        // создаем экзепляр класса с указанием кода
        $page = new $class(self::$code);
        // получаем действие
        $action = $this->getAction();
        $action = 'getContent'.ucfirst($action);
        // выполняем действие - получаем контент
        if(self::$code != 500){
            $head = $page->getContentPage();
            $content = $page->$action();
            $content = array_merge($content,$head);
            $content['message'] = Message::get();
        }else{
            $content = [
                "nav" => [],
                "secondNav" => [],
                "title" => MessageError::errorServer,
                "header" => MessageError::errorServer
            ];
            if(!SERVER_ERROR_TO_MESSAGE)
                $content['message'] = Message::get();
        }
        // выбираем шаблон
        $template = (in_array(self::$code,self::$errors)) ? VIEW_DIR_ERORS.self::$code.'.php' : VIEW_DIR_PAGE.self::$code.'.php';
        $this->getView($template,$content);
    }

    protected function getAction()
    {
        if(in_array(self::$code,self::$errors)){
            return self::$code;
        }else{
            return self::$class;
        }
    }

    protected function getView($template,$content = []){
        ob_start();
        if(empty($_POST['ajax'])){
            foreach ($content as $key=>$item){
                $$key = $item;
            }
            include VIEW_DIR_INCLUDE.'head.php';
            if(is_array($template)){
                foreach ($template as $item){
                    include $item;
                }
            }else
                include $template;
            include VIEW_DIR_INCLUDE.'footer.php';
        }
        else
            echo json_encode($content);
        ob_end_flush();
    }

    /**
     * метод отображении ошибки сервера,
     * должен работать без обращения к БД
     */
    public function render500()
    {
        $content = [
            "nav" => [],
            "secondNav" => [],
            "title" => MessageError::errorServer,
            "header" => MessageError::errorServer
        ];
        if(SERVER_ERROR_TO_MESSAGE)
            $content['message'] = Message::get();
        $template = VIEW_DIR_ERORS.self::$code.'.php' ;
        $this->getView($template,$content);
    }
}