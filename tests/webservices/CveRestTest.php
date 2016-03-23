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
     * @param array $p_params URL query parameters
     * 
     * @return string
     */
    private function _executeApi(array $p_params)
    {
        $p_params = array_merge(
            $p_params,
            array(
                'XDEBUG_SESSION_START' => 'netbeans-xdebug'
            )
        );
        $url = \Zend_Registry::get('Config')->url->cve . "?" . http_build_query($p_params);
        $arrHeader = array( 
            'Accept: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
        $response = curl_exec($ch);
        return $response;
    }
    
    /**
     * Testing cve with invalid username
     * 
     * @return void
     */
    public function testInvalidUsername()
    {
        $response = $this->_executeApi(
            array(
                'pass' => 'me',
                
            )
        );
        $this->assertEquals(
            array(
                'result' => 0,
                'resultDescription' => 'Failure',
                'reason' => \API\Services\Auth::INVALID_USERNAME_EMPTY,
            ),
            json_decode((string)$response, true)
        );
        
    }
    
    /**
     * Testing cve with invalid username
     * 
     * @return void
     */
    public function testInvalidPassword()
    {
        $response = $this->_executeApi(
            array(
                'user' => 'me',
                
            )
        );
        $this->assertEquals(
            array(
                'result' => 0,
                'resultDescription' => 'Failure',
                'reason' => \API\Services\Auth::INVALID_PASSWORD_EMPTY
            ),
            json_decode((string)$response, true)
        );
        
    }
    
}
