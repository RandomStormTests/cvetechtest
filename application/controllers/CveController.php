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
        if ( false === ( $arrParam = $this->_predeterminedResponses() )  ) {
            $oCveByName = new \API\Services\GetCveByName();
            if ( !empty( $this->getParam('cvenumber') ) ) {
                $arrParam = $oCveByName->get( $this->getParam('cvenumber') );
            } else {
                $arrParam = $oCveByName->getAll( 
                    $this->getParam('limit'), 
                    $this->getParam('offset'),
                    $this->getParam('year')
                );
            }   
        }   
        $this->forward( 'index', 'response', null, $arrParam );
    }
    
    /**
     * Returns predetermined responses based on cveNumber
     * 
     * @return array | boolean Response array or FALSE if none
     */
    private function _predeterminedResponses()
    {
        if ( 'unittest' == $this->getParam('cvenumber') ) {
            return array(
                'response' => array(
                    'result' => \ResponseController::RESULT_SUCCESS,
                    'resultDescription' => \ResponseController::RESULT_SUCCESS_TEXT,
                    'data' => array(
                        'ID' => '4',
                        'Name' => 'CVE-1999-0004',
                        'Status' => 'Candidate',
                        'Description' => 'MIME buffer overflow in email clients, e.g. Solaris mailtool and Outlook.',
                        'References' => 'CERT:CA-98.10.mime_buffer_overflows   |   XF:outlook-long-name   |   SUN:00175   |   MS:MS98-008   |   URL:http://www.microsoft.com/technet/security/bulletin/ms98-008.asp',
                        'Phase' => 'Modified (19990621-01)',
                        'Votes' => '   ACCEPT(8) Baker, Cole, Collins, Dik, Landfield, Magdych, Northcutt, Wall  |     MODIFY(1) Frech  |     NOOP(1) Christey  |     REVIEWING(1) Shostack',
                        'Comments' => ' Frech> Extremely minor, but I believe e-mail is the correct term. (If you reject  |     this suggestion, I will not be devastated.) :-)  |   Christey> This issue seems to have been rediscovered in  |     BUGTRAQ:20000515 Eudora Pro & Outlook Overflow - too long filenames again  |     http://marc.theaimsgroup.com/?l=bugtraq&m=95842482413076&w=2  |       |     Also see  |     BUGTRAQ:19990320 Eudora Attachment Buffer Overflow  |     http://marc.theaimsgroup.com/?l=bugtraq&m=92195396912110&w=2  |   Christey>   |     CVE-2000-0415 may be a later rediscovery of this problem  |     for Outlook.  |   Dik> Sun bug 4163471,  |   Christey> ADDREF BID:125  |   Christey> BUGTRAQ:19980730 Long Filenames & Lotus Products  |     URL:http://marc.theaimsgroup.com/?l=bugtraq&m=90221104526201&w=2',
                    ),
                )
            );
        }
        return false;
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
