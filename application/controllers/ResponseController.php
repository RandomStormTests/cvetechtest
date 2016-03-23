<?php
/*
 * Generalized response controller
 * 
 * This has been placed here rather than in the CveController in order to 
 * separate the concerns of requests and responses
 * Handles negotiation of Content-Type acceptable for the response
 * I suppose the argument could be made that I could've written a bespoke class to do this, however
 * then I would've had to write from first principles base code to handle status-headers and JSON 
 * rather than leveraging ZF to do that for me.  Horses for courses, I guess.
 * 
 * @category ZF
 * @package  ResponseController
 * @author   Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class ResponseController extends \Zend_Controller_Action
{

    /**
     * Result codes
     */
    const RESULT_SUCCESS = 1;
    const RESULT_FAILURE = 0;
    
    /**
     * Status codes
     * @see https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
     */
    const STATUS_SUCCESS = 200;
    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;
    const STATUS_PROCESSING_ERROR = 422;
    const STATUS_INTERNAL_SERVER_ERROR = 500;
    
    /**
     * Initialisation
     * 
     * @return void
     */
    public function init()
    {   
        /**
         * Preventing the standard page template html from being displayed
         */
        $this->_helper->_layout->disableLayout();

        /**
         * Preventing any output that isn't explicitly echoed/printed from the action
         */
        $this->_helper->_viewRenderer->setNoRender(true);
        //$this->getHelper('ViewRenderer')->setNoRender(true);
        
        /**
         * Setting context for JSON response action
         */
        $this->_helper->contextSwitch()
            ->addActionContext(
                'respondJson', 
                array('json') 
            )
            ->initContext();
    }
    
    /**
     * Main index action
     * 
     * @return void
     */
    public function indexAction()
    {
        if ( !empty( $this->getParam('status') ) ) {
            $this->getResponse()->setHttpResponseCode( $this->getParam('status') );
        }
        
        /**
         * Determine what the Accept Header requests and select the appropriate response format
         */
        //$accept = $this->getFrontController()->getRequest()->get
        
        if ( $this->_isJsonResponse() ) {
            $this->respondJsonAction();
        } else {
            $this->respondXmlAction();
        }
    }
    
    /**
     * Decides whether a JSON response is required based on the Accept header
     * 
     * @return boolean
     */
    private function _isJsonResponse()
    {
        $accept = \Zend_Controller_Request_Http::getHeader('Accept');
        $arrAccept = explode(",", $accept);
        if ( false !== array_search('application/json', $arrAccept) ) {
            return true;
        }
        return false;
    }
    
    /**
     * JSON response action
     * 
     * @return void
     */
    public function respondJsonAction()
    {   
        $this->getHelper('json')->sendJson( $this->getParam('response') );
        exit();
    }
    
    /**
     * XML response action
     * 
     * @return void
     */
    public function respondXmlAction()
    {
        
    }
    
}
