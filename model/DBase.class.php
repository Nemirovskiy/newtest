<?php
/**
 * Класс для работы с базой данных
 */

class DBase
{
    private static $connect = null;
    private function __construct(){}

    /**
     * внутренний метод для подключения к БД
     * @return null|PDO
     */
    private static function baseConnect(){
        if(self::$connect === null){
            try{
                self::$connect = new PDO("mysql:host=localhost;dbname=".DB_NAME,DB_LOGIN,DB_PASS);
            }catch (Exception $e){
                Message::setError(MessageError::errorDB);
                Controller::errorServer($e->getMessage());
            }
        }
        return self::$connect;
    }

    /**
     * внутренний метод для формирования запроса к БД
     * @param $query
     * @param array $param
     * @return bool
     */
    private static function query($query,$param=[]){
        try{
            $statement = self::baseConnect()->prepare($query);
            $result =  $statement->execute($param);
        }catch (Exception $e){
            Message::setError(MessageError::errorDB);
            Controller::errorServer($e->getMessage());
        }
        return $result;
    }

    /**
     * внутренний метод для формирования запроса на получение списка из БД
     * @param $query
     * @param array $param
     * @return array|bool
     */
    private static function select($query,$param = []){
        try{
            $result = self::baseConnect()->prepare($query);
            $result->execute($param);
            if($result){
                return $result->fetchAll();
            }
        }catch (Exception $e){
            Message::setError(MessageError::errorDB);
            Controller::errorServer($e->getMessage());
        }
        return false;
    }

    /**
     * внутренний метод для формирования вставки в БД
     * @param $query
     * @param array $param
     * @return bool|integer = ложь или id записи
     */
    private static function insert($query,$param=[]){
        $result = self::query($query,$param);
        if($result)
            return self::baseConnect()->lastInsertId();
        return false;
    }

    /**
     * Метод получения вопроса и ответа из указанной темы
     * и указанного номера вопроса или всех вопросов
     * @param $theme
     * @param $number
     * @param bool $rnd
     * @return array|bool
     */
    public static function getTest($theme,$number = false,$rnd = false){
        if($number === false)
            $where = " > 0";
        else
            $where = " = ?";
        $query = "SELECT * from quest INNER JOIN theme ON ".
            "(quest.theme_code = theme.theme_code)".
            "INNER JOIN answ ON (quest.quest_id = answ.quest_id) ".
            "WHERE theme.theme_code = ? AND quest_number" . $where;
        if($number === false)
            $tests = DBase::select($query,[$theme]);
        else
            $tests = DBase::select($query,[$theme,$number]);

        if(empty($tests)) {
            Message::setError(MessageError::errorDB);
            Controller::errorServer('empty $tests');
        }
        $result =[];
        foreach ($tests as $item){
            if(!isset($result['number'])) $result[$item['quest_number']]['number'] = $item['quest_number'];
            if(!isset($result['quest'])) $result[$item['quest_number']]['quest'] = $item['quest_text'];
            if(!isset($result['theme'])) $result[$item['quest_number']]['theme'] = $item['theme_text'];
            $result[$item['quest_number']]['answers'][] = [
                'order'=> $item['answ_order'],
                'right' => $item['answ_right'],
                'text' => $item['answ_text']
            ];

            if($item['answ_right'] == 1) $result[$item['quest_number']]['right'][] = $item['answ_order'];

        }
        if($number !== false){
            $result = $result[$item['quest_number']];
        }
        if($rnd) shuffle($result['answers']);
        return $result;
    }
    /**
     * метод получения списка страниц из БД
     * @param string $menu
     * @return array|bool
     */
    public static function getList($menu = "menu"){
            $list = DBase::select("SELECT * FROM page ORDER BY $menu");
            $list = Controller::keyArray($list,'code');
        return $list;
    }

    /**
     * метод получения списка тем тестирования
     * @return mixed
     */
    public static function getThemeList(){
        $arr = DBase::select("SELECT * FROM theme ");
        foreach ($arr as $item){
            $list[$item['theme_code']]['code']  = $item['theme_code'];
            $list[$item['theme_code']]['text']  = $item['theme_text'];
            $list[$item['theme_code']]['count'] = self::getThemeCount($item['theme_code']);
        }
        return $list;
    }

    /**
     * метод получения кол-ва вопрсов в теме
     * @param $theme
     * @return mixed
     */
    public static function getThemeCount($theme){
        $query = "SELECT COUNT(*) count FROM `quest` WHERE  theme_code = ?";
        $result = self::select($query,[$theme])[0];
        return $result['count'];
    }
    /**
     * метод удаления тестов (вопросов и ответов) указанной темы
     * @param string $theme
     * @param int $count
     * @return bool
     */
    public static function delAllFromTheme($theme){
        $query = "DELETE quest,answ FROM quest INNER JOIN answ ".
            "WHERE quest.quest_id = answ.quest_id AND quest.theme_code = ?; ".
            "UPDATE theme SET theme.theme_count = 0 WHERE theme.theme_code = ?;";
        return self::query($query,[$theme,$theme]);
    }

    /**
     * метод записи в БД темы тестирования
     * @param $theme array ['code','name']
     * @return bool
     */
    public static function insertTheme($theme){
        $query = "INSERT INTO `theme` (`theme_code`, `theme_text`) ".
            "VALUES (:code, :name)";
        return self::insert($query,$theme);
    }

    /**
     * Запись вопроса
     * @param array $param = ['code','number','text']
     * @return bool|string
     */
    public static function insertAddQuest($param=[]){
        $query = "INSERT INTO `quest` (`theme_code`, `quest_number`, `quest_text`) ".
            "VALUES (?,?,?)";
        return self::insert($query,$param);
    }
    /**
     * Запись ответа
     * @param array $param = ['quest','order','right','text']
     * @return bool|string
     */
    public static function insertAddAnswer($param=[]){
        $query = "INSERT INTO `answ` (`quest_id`, `answ_order`, `answ_right`, `answ_text`) ".
            "VALUES (?,?,?,?)";
        return self::insert($query,$param);
    }

    /**
     * метод записи пользователя
     * @param string $login - логин
     * @param string $pass  - хеш пароля
     * @param string $email - почта
     * @return bool|string
     */
    public static function createUser($login,$pass,$email=''){
        $query = "INSERT INTO `user` (`login`,`pass`,`email`) VALUES (?,?,?)";
        return self::insert($query,[$login,$pass,$email]);
    }

    /**
     * метод получения информации о пользователе
     * @param $login string логин или почта
     * @return array|bool  массив данных пользователя или ложь
     */
    public static function getUser($login){
        $query = "SELECT * FROM `user` WHERE login = :login OR email = :login ";
        $result = self::select($query,['login'=>$login]);
        if($result)
            return $result[0];
        return false;
    }
}