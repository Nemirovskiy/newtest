<?
/**
*	
*/
class Model
{
	//protected function getList(){}
	public function renderHead(){}
    public function renderBody(){}
    protected static $db;
    protected static $connect = null;

    private static function baseConnect(){
        if(self::$connect === null){
            self::$connect = new PDO("mysql:host=localhost;dbname=".BD_NAME,BD_LOGIN,BD_PASS);
        }
        return self::$connect;
    }

    public static function db($query,$param=[]){
        $result = self::baseConnect()->prepare($query);
        $result->execute($param);
        return $result;
    }

}