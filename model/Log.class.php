<?php
/**
 * Класс для отработки логов работы
 */
class Log
{
    /**
     * разделитель
     * @var string
     */
    private static $indent = '  ';

    /**
     * метод рекурсиваного перебора массива
     * с возвратом форматированной строки
     * [ключ]: значение
     * @param $array - массив для разбора
     * @param int $tab - значение отступа
     * @return string - форматированная строка
     */
    private static function scanArray($array,$tab=0){
        if(is_array($array)){
            $result = "{".PHP_EOL;
            $tab++;
            foreach ($array as $key => $item){
                for($x=0; $x < $tab; $x++, $result .= self::$indent);
                $result .= "[$key]: ";
                if(is_array($item)){
                    if(self::valName($item) !== "GLOBALS")
                        $result .= self::scanArray($item,$tab);
                    else
                        $result .= "*recursive*".PHP_EOL;
                }else{
                    $result .= self::type($item,$tab).PHP_EOL;
                }
            }
            $tab--;
            for($x=0; $x < $tab; $x++, $result .= self::$indent);
            $result .= "}".PHP_EOL;
            return $result;
        }
    }

    /**
     * метод приведения типов данных кроме массивов
     * @param $data
     * @param $tab - отступ если есть
     * @return string
     */
    private static function type($data,$tab=0){
        if (is_object($data)){
            return "Object:".get_class($data). self::scanArray(get_object_vars($data),$tab);
        }elseif ($data === false){
            return "false";
        }elseif ($data === true){
            return "false";
        }else{
            return $data;
        }
    }
    /**
     * метод получения имени переменной
     * @param mixed $data     - входящая переменная имя которой будем искать
     * @return string - полученное значение переменной
     */
    private static function valName($data){
        if($data !== $GLOBALS){
            foreach ($GLOBALS as $key => $value){
                if($value === $data){
                    break;
                }
            }
            if($key === "GLOBALS")
                $key = '';
        }elseif($data === $GLOBALS)
            $key = "GLOBALS";
        return $key;
    }

    /**
     * метод подготовки входящего значения
     * @param mixed $data
     * @return string - сформированная строка
     */
    private static function prepare($data){
        $result = "[".self::valName($data)."]:";
        if(is_array($data)){
            $result .= self::scanArray($data);
        }else{
            $result .= self::type($data);
        }
        return $result;
    }

    /**
     * метод сохранения в файл
     * @param $data
     */
    public static function toFile($data){
        $result = date("H:i:s").PHP_EOL;
        $result .= self::prepare($data);
        file_put_contents(LOG_PATH.date("Y-m-d").".log",$result,FILE_APPEND);
    }

    /**
     * метод отображения на странице
     * @param $data
     */
    public static function display($data){
        echo "<pre>";
        echo self::prepare($data);
        echo "</pre>";
    }
}