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
        /**
         * раскомментировать для добавления сообщений
         * через класс сообщений
         * $content['message'] = Message::get();
         */
        //print_r($content);
        // выбираем шаблон
        $template = $this->getTemplate($action);
        $this->getView($template,$content);
        //$page->renderHead(self::$code);
        //$page->renderBody();
        //include VIEW_DIR_ERORS.'404.php';
        //$page->renderHtml(self::$code);
        /*
        $this->prepareHead();
        $this->prepareBody();
        $this->prepareFooter();
        */
    }

    /***
     * метод выбора шаблона
     */
    public function getTemplate(){
        // если нет страницы (404) - шаблон ошибки,
        // иначе - шаблон страницы по коду
        return (self::$code == 404) ? VIEW_DIR_ERORS.'404.php' : VIEW_DIR_PAGE.self::$code.'.php';
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