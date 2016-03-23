<?php
require_once('Zend/Registry.php');
require_once("API/Services/Auth.php");
require_once("API/Services/Auth/InvalidPasswordException.php");
require_once("API/Services/Auth/InvalidUsernameException.php");
require_once("ResponseController.php");

/*
 * Unit tests for Cve REST web service
 * 
 * @category Test
 * @package  \API\Services\Auth
 * @author   Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */

class CveRestTest extends PHPUnit_Framework_TestCase 
{
    
    /**
     * Pre-testing setup
     *
     * @return void
     */
    static function setUpBeforeClass()
    {
        // setup Application environment
        putenv('APPLICATION_ENV=testing');

        parent::setUpBeforeClass();
    }
    
    /**
     * Test fixtures setup 
     * 
     * @return void
     */
    protected function setUp()
    {
        
    }
    
    /**
     * Executes the API
     * 
     * @param string $p_url Endpoint URL
     * 
     * @return string
     */
    private function _executeApi($p_url)
    {
        $arrHeader = array( 
            'Accept: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $p_url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
        $response = curl_exec($ch);
        return $response;
    }
    
    /**
     * Testing cve with a successful result
     * This is a naughty test, because it assumes the legacy CVE records will remain unchanged and will retain 
     * their same position in the data as new data is augmented to the source data CSV
     * 
     * @return void
     */
    public function testGetCveByNumberSuccess()
    {
        $url = \Zend_Registry::get('Config')->url->cve . "/CVE-1999-0004?XDEBUG_SESSION_START=netbeans-xdebug";
        $response = $this->_executeApi($url);
        $this->assertEquals(
            array(
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
            ),  
            json_decode((string)$response, true)
        );
        
    }
    
}
