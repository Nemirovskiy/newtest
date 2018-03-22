<?php
/**
 * User: Николай
 * Date: 19.02.2018
 * Time: 19:57
 */

class DBase
{
    protected static $db;
    protected static $connect = null;
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
        $result = self::baseConnect()->prepare($query);
        $result->execute($param);
        return $result;
    }
    public static function select($query,$param=[]){
        $result = self::db($query,$param);
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
        $result = self::baseConnect()->prepare($query);
        //if($result->execute([$theme]))
            return $result->execute([$theme,$theme]);
        //else
        //    return false;
    }
    public static function updateThemeCount($theme,$count=0){
        $query = "UPDATE theme SET theme.theme_count = ? WHERE theme.theme_code = ?;";
        $result = self::baseConnect()->prepare($query);
       // if()
            return $result->execute([$count,$theme]);
//        else
//            return false;
    }

    /**
     * @param $theme
     * @return bool
     * INSERT INTO `theme` (`theme_code`, `theme_text`, `theme_count`) VALUES ('eee', 'ddd', '111')
     */
    public static function insertTheme($theme){
        $query = "INSERT INTO `theme` (`theme_code`, `theme_text`, `theme_count`) ".
            "VALUES (?, ?, ?)";
        $result = self::baseConnect()->prepare($query);
        if($result->execute([$theme['code'],$theme['name'],$theme['count']]))
            return true;
        else
            return false;
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
    }
}