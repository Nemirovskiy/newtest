<?php
/**
 * Created by PhpStorm.
 * User: Николай
 * Date: 30.03.2018
 * Time: 18:12
 */

class Message
{
    protected static $textError = '';
    protected static $textMessage = '';
    protected static $textTest = '';

    static public function setError($text){
        if(empty(self::$textError))
            self::$textError .= $text;
        else
            self::$textError .= "<br>".$text;
    }
    static public function setMessage($text){
        if(empty(self::$textMessage))
            self::$textMessage .= $text;
        else
            self::$textMessage .= "<br>".$text;
    }
    static public function setTest($text){
        self::$textTest = $text;
    }

    static public function get(){

        return [
            'errors' => self::$textError,
            'info' => self::$textMessage,
            'test' => self::$textTest
        ];
    }

}