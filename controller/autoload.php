<?
spl_autoload_register('autoloadClass');
function autoloadClass($className)
{
	$found = false;
	$dirs = [
		CLASS_DIR_CONTROLLER,
		CLASS_DIR_MODEL
	];
	foreach ($dirs as $dir) {
		$fileName = $dir.$className.'.class.php';
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