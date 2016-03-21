<?php
/*
 * Main REST controller for cve web service
 * 
 * @category API
 * @package  \CveController
 * @author   Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class CveController extends \Zend_Controller_Action
{
    
    /**
     * Initialisation
     * 
     * @return void
     */
    public function init()
    {
        $options = $this->getInvokeArg('bootstrap')->getOption('resources');
        
        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch->addActionContext('index', array('xml','json'))->initContext(); 
        
        //$this->_helper->viewRenderer->setNeverRender(); 
        $this->view->success = "true";
        $this->view->version = "1.0";
    }
    
    /**
     * Default (index) action method
     * 
     * This can act as the main dispatcher for all REST services.  This centralizes behaviour, making testing
     * and debugging and error trapping easier.  Common behaviour can go in here and be actioned across all
     * cve request
     * 
     * @return void
     */
    public function indexAction()
    {
        $this->_authenticate();
        $this->view->value = "hello";
        $arrParam = $this->getAllParams();
        if ( 'cve' == $arrParam['service'] ) {
            
        }
    }
    
    /**
     * Authenticating username/password for access to the service
     * 
     * @return boolean
     */
    private function _authenticate()
    {
        $oAuth = new API\Services\Auth( $this->getParam('user'), $this->getParam('pass') );
        $oAuth->authenticate();
    }
    
    /**
     * GET action method
     * 
     * @return void
     */
    public function getAction()
    {
        $this->_forward('index');
    }
    
    /**
     * POST action method
     * 
     * @return void
     */
    public function postAction()
    {
        $this->_forward('index');
    }
    
}
