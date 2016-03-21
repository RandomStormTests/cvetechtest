<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Bootstraps autoloader
     * 
     * @return void
     * 
     * @link http://robertbasic.com/blog/using-the-new-autoloaders-from-zend-framework-1-12
     */
    protected function _initAutoloader()
    {
        Zend_Loader_AutoloaderFactory::factory(
            array(                
                'Zend_Loader_StandardAutoloader' => array(
                    'prefixes' => array(
                        'API\Services' => 'API/Services',
                    ),
                    'fallback_autoloader' => true
                ),
            )   
        );
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->setFallbackAutoloader(true);
        return $autoloader;
    }
    
    /**
     * Initialising REST routing for controllers
     * 
     * @return void
     */
    protected function _initRestRoute()
    {
        $front = Zend_Controller_Front::getInstance();
        $route = new Zend_Rest_Route($front);
        $front->getRouter()->addRoute( 'default', $route );
    }
    
}

