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
    public function render()
    {
        $class = self::$class;
        // создаем экзепляр класса с указанием кода
        $page = new $class(self::$code);
        // получаем действие
        $action = $this->getAction();
        $action = 'getContent'.ucfirst($action);
        // выполняем действие - получаем контент
        $head = $page->getContentPage();
        $content = $page->$action();
        $content = array_merge($content,$head);
        $content['message'] = Message::get();
        // выбираем шаблон
        $template = (self::$code == 404) ? VIEW_DIR_ERORS.'404.php' : VIEW_DIR_PAGE.self::$code.'.php';
        $this->getView($template,$content);
    }
    /**
     * метод формирования заголовка и верхней части страницы
     */
    protected function prepareHead(){

    }
    protected function getAction()
    {
        if(self::$code == 404){
            return '404';
        }else{
            return self::$class;
        }
    }
    /***
     *
     */
    protected function getView($template,$content = []){
        ob_start();
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
        ob_end_flush();
    }
}