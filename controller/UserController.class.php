<?php
/**
 * Created by PhpStorm.
 * User: Николай
 * Date: 07.04.2018
 * Time: 15:42
 */

class UserController extends Controller
{
    static $auth = null;
    public function authUser(){
        if(self::$auth === null){
            User::getAccess();
        }
    }
}