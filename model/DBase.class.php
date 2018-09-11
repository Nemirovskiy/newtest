<?php
/**
 * User: Николай
 * Date: 19.02.2018
 * Time: 19:57
 */

class DBase
{
    private static $db;
    private static $connect = null;
    private function __construct(){}

    /**
     *
     */


    private static function baseConnect(){
        if(self::$connect === null){
            self::$connect = new PDO("mysql:host=localhost;dbname=".BD_NAME,BD_LOGIN,BD_PASS);
        }
        return self::$connect;
    }

    protected static function db($query,$param=[]){
    private static function query($query,$param=[]){
        $result = self::baseConnect()->prepare($query);
        return $result->execute($param);
    }
        if($result){
            return $result->fetchAll();
        }
        return false;
    }
//    public static function prepare($query){
//        return self::baseConnect()->prepare($query);
//    }
//    public static function lastAddId(){
//        return self::baseConnect()->lastInsertId();
//    }

    private static function insert($query,$param=[]){
        $result = self::query($query,$param);
        if($result)
            return self::baseConnect()->lastInsertId();
        return false;
    }
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
        if(empty($tests)) return false;
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
    public static function getList($menu = "menu"){
            $list = DBase::select("SELECT * FROM page ORDER BY $menu");
            $list = Controller::keyArray($list,'code');
        return $list;
    }
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
     * @param $theme
     * @return bool
     * INSERT INTO `theme` (`theme_code`, `theme_text`) VALUES ('eee', 'ddd', '111')
     */
    public static function insertTheme($theme){
        $query = "INSERT INTO `theme` (`theme_code`, `theme_text`) ".
            "VALUES (?, ?)";
        $result = self::baseConnect()->prepare($query);
        if($result->execute([$theme['code'],$theme['name']]))
            return true;
        else
            return false;
            "VALUES (:code, :name)";
        return self::insert($query,$theme);
    }

    /**
     * @param array $param
     * @return bool|string
     * Запись вопроса
     * INSERT INTO `quest` (`theme_code`, `quest_number`, `quest_text`) VALUES
     * ('feld', 2, 'ИМПУЛЬС, ВЫШЕДШИЙ ИЗ А/В УЗЛА, ВЫГЛЯДИТ НА ЭКГ КАК')
     *
     */
    public static function insertAddQuest($param=[]){
        $query = "INSERT INTO `quest` (`theme_code`, `quest_number`, `quest_text`) ".
            "VALUES (?,?,?)";
        $result = self::baseConnect()->prepare($query);
        if($result->execute($param))
            return self::baseConnect()->lastInsertId();
        else
            return false;
        return self::insert($query,$param,true);
    }
    /**
     * @param array $param
     * @return bool|string
     *
     * Запись ответа
     * INSERT INTO `answ` (`quest_id`, `answ_order`, `answ_right`, `answ_text`) VALUES
     * (1, 1, 0, 'Правильное чередование +зубцов Р, нормальных QRS с ЧСС 40-60 в 1 мин')
     */
    public static function insertAddAnswer($param=[]){
        $query = "INSERT INTO `answ` (`quest_id`, `answ_order`, `answ_right`, `answ_text`) ".
            "VALUES (?,?,?,?)";
        $result = self::baseConnect()->prepare($query);
        if($result->execute($param))
            return self::baseConnect()->lastInsertId();
        else
            return false;
        return self::insert($query,$param,true);
    }

    /**
     *
     */
    public static function createUser($login,$pass,$email=''){
        $query = "INSERT INTO `user` (`login`,`pass`) VALUES (?,?)";
        $result = self::baseConnect()->prepare($query);
        if($result->execute([$login,$pass]))
            return true;
        else
            return false;
        return self::insert($query,[$login,$pass,$email]);
    }

    public static function getPass($login){
        $query = "SELECT `pass` FROM `user` WHERE login = ?";
        $res = self::baseConnect()->prepare($query);
        if($res->execute([$login]))
            return $res->fetch()['pass'];
        else
            return false;
    public static function getUser($login){
        $query = "SELECT * FROM `user` WHERE login = :login OR email = :login ";
        $result = self::select($query,['login'=>$login]);
        if($result)
            return $result;
        return false;
    }
}