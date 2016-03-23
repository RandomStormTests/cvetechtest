<?php
/*
 * Main controller for cve web service requests
 * 
 * @category ZF
 * @package  CveController
 * @author   Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class CveController extends \Zend_Controller_Action
{
    
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
        if ( $this->_authenticate() ) {
            $arrParam = array_merge(
                $this->getAllParams(),
                array(
                    'response' => array(
                        'result' => 1,
                        'resultDescription' => 'Success',
                        'data' => array(
                            'one' => 1,
                            'two' => 2,
                            'three' => 23,
                        )
                    ),
                    'status' => ResponseController::STATUS_FORBIDDEN,
                )
            );
            $this->_forward( 'index', 'response', null, $arrParam );
        }
        
//        if ( 'cve' == $arrParam['service'] ) {
//            
//        }
            
    }
    
    /**
     * Authenticating username/password for access to the service
     * 
     * @return boolean
     */
    private function _authenticate()
    {
        $isSuccess = true;
        try {
            $oAuth = new API\Services\Auth( $this->getParam('user'), $this->getParam('pass') );
            $oAuth->authenticate();
        } catch (\API\Services\Auth\InvalidUsernameException $e) {
            $isSuccess = false;
        } catch (\API\Services\Auth\InvalidPasswordException $e) {
            $isSuccess = false;
        }
        if (!$isSuccess) {
            $arrParam = array(
                'status' => \ResponseController::STATUS_UNAUTHORIZED,
                'response' => array(
                    'result' => 0,
                    'resultDescription' => 'Failure',
                    'reason' => $e->getMessage(),
                )
            );
            $this->_forward( 'index', 'response', null, $arrParam );
        }
        return $isSuccess;
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
