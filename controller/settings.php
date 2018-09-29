<?
// константы путей
define('ROOT_DIR', __DIR__.'/../');
/// пути представления
define('VIEW_DIR_PAGE', ROOT_DIR.'view/page/');
define('VIEW_DIR_ADMIN', ROOT_DIR.'view/admin/');
define('VIEW_DIR_TEST', ROOT_DIR.'view/test/');
define('VIEW_DIR_INCLUDE', ROOT_DIR.'view/include/');
define('VIEW_DIR_ERORS', ROOT_DIR.'view/errors/');
/// пути классов
define('CLASS_DIR_CONTROLLER', ROOT_DIR.'controller/');
define('CLASS_DIR_MODEL', ROOT_DIR.'model/');
// настройка для логов
define('LOG_PATH', ROOT_DIR.'log/');
// обработка ошибок сервера
define('SERVER_ERROR_TO_LOG_FILE', true);
define('SERVER_ERROR_TO_MESSAGE', true);

// настройка доступа к БД
define('DB_NAME', 'feldtest');
define('DB_LOGIN', 'root');
define('DB_PASS', '');
