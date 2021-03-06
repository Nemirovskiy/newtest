<?
/**
*	
*/
class User
{
    protected $isLogin = false;
    public static $access = 1;

    protected function login(){

    }
    function __construct()
    {
        if(empty($_POST['login']) && empty($_POST['pass'])){

        }
    }

    public static function getAccess(){
        if(empty($_SESSION['user']['access']))
            return self::$access;
        else
            return $_SESSION['user']['access'];
    }

    public static function prepareAuth(){
        $login = strip_tags($_POST['login']);
        $pass = strip_tags($_POST['pass']);
        return [$login,$pass];
    }

    public function setPass(){
        $pass = '';
        $id = 1;

    }

    /**
     * метод добавления нового пользователя
     */
    public function createUser(){
        if(!empty($_POST['passNew']) && $_POST['passNew'] === $_POST['passRet']){
            $login = strip_tags($_POST['login']);
            $pass = password_hash(strip_tags($_POST['passNew']), PASSWORD_DEFAULT);
            if(DBase::createUser($login,$pass)){
                echo "Запись нового пользователья успешная";
                return true;
            }else{
                echo "Ошибка записи";
                return false;
            }
        }
    }

    private function verifyUser($login,$password){
        $user = DBase::getUser($login);
        if($user)
            if(password_verify($password,$user['pass']))
                return $user;
        return false;
    }
    /**
     * метод авторизации пользователя
     * @return bool
     */
    public function authUser(){
        if(empty($_SESSION['user'])){
            $login = strip_tags($_POST['login']);
            $pass = strip_tags($_POST['pass']);
            $user = $this->verifyUser($login,$pass);
            if($user){
                Message::setMessage('успех авторизации');
                $_SESSION['user'] = [
                    'login'  => $user['login'],
                    'email'  => $user['email'],
                    'access' => $user['access']
                ];
                return true;
            }
            Message::setError('Не верный логин или пароль!');
            return false;
        }
        return true;
    }
}