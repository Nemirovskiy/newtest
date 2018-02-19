<?
spl_autoload_register('autoloadClass');
function autoloadClass($className)
{
	$found = false;
	$dirs = [
		'controller',
		'model'
	];
	foreach ($dirs as $dir) {
		$fileName = $dir.'/'.$className.'.class.php';
		if(is_file($fileName)){
			require_once($fileName);
			$found = true;
		}
	}
	if(!$found){
		throw new Exception('Ошибка загрузки класса '.$className);
	}
	return true;
}