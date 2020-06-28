<?php

define('ROOT',dirname(__DIR__).DIRECTORY_SEPARATOR);
define('CORE',ROOT.'app'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR);
define('CONTROLLER',ROOT.'app'.DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR);
define('VIEW',ROOT.'app'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR);
define('MODEL',ROOT.'app'.DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR);
define('STYLESHEET',ROOT.'public'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR);
define('SCRIPT',ROOT.'public'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR);

define('TEMPLATE',ROOT.'public'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR);

$paths=[ROOT,CORE,CONTROLLER];

set_include_path(get_include_path().implode(PATH_SEPARATOR,$paths));
spl_autoload_register();
require CORE.'Application.php';
new Application();

?>