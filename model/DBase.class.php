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
}