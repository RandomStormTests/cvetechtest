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
    const RESULT_SUCCESS_TEXT = "Success";
    const RESULT_FAILURE = 0;
    const RESULT_FAILURE_TEXT = "Failure";
    
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
         * Defaults to JSON response
         */
        if ( $this->_isXmlResponse() ) {
            $this->xmlAction();
        } else {
            $this->jsonAction();
        }
    }
    
    /**
     * Decides whether an XML response is required based on the Accept header
     * 
     * @return boolean
     */
    private function _isXmlResponse()
    {
        $accept = \Zend_Controller_Request_Http::getHeader('Accept');
        $arrAccept = explode(",", $accept);
        if ( false !== array_search('application/xml', $arrAccept) ) {
            return true;
        }
        return false;
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
    public function jsonAction()
    {   
        $this->getHelper('json')->sendJson( $this->getParam('response') );
        exit();
    }
    
    /**
     * XML response action
     * There are as yet no known helpers to create and dispatch XMLs from ZF
     * 
     * Decided against using context switching due to:
     *  1. potential complexity of response array
     *  2. requirement to inject parameter format/xml
     * 
     * @return void
     */
    public function xmlAction()
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><response></response>');
        $this->_convertArrayToXml( $this->getParam('response'), $xml );
        echo $xml->asXML();
    }
    
    /**
     * Converts multi-dim array to XML
     * 
     * @link http://stackoverflow.com/questions/1397036/how-to-convert-array-to-simplexml
     * 
     * @param array            $p_arrData Array to convert
     * @param SimpleXMLElement $p_xmlData XML element to expand [By Reference]
     * 
     * @return string XML
     */
    private function _convertArrayToXml($p_arrData, &$p_xmlData)
    {
        foreach( $p_arrData as $key => $value ) {
            if( is_array($value) ) {
                if( is_numeric($key) ){
                    $key = 'item'; //dealing with <0/>..<n/> issues
                }
                $subnode = $p_xmlData->addChild($key);
                $this->_convertArrayToXml($value, $subnode);
            } else {
                $p_xmlData->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }
    
        
}