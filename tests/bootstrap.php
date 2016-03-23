<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));

// Ensure library/ is on include_path
set_include_path(
    implode(
        PATH_SEPARATOR, 
        array(
            realpath(APPLICATION_PATH . '/../library'),
            realpath(APPLICATION_PATH . '/controllers'),
            realpath(APPLICATION_PATH . '/models'),
            get_include_path(),
        )
    )
);

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

/**
 * Storing application.ini settings for unit-test methods
 */
require_once('Zend/Config/Ini.php');
require_once('Zend/Registry.php');
Zend_Registry::set(
    'Config', 
    new Zend_Config_Ini( APPLICATION_PATH . '/configs/application.ini', 'testing' )
);