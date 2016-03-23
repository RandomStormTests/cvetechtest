<?php
require_once("API/Services/Auth.php");
require_once("API/Services/Auth/InvalidPasswordException.php");
require_once("API/Services/Auth/InvalidUsernameException.php");
require_once("User.php");

/**
 * Unit tests for Auth class
 *
 * @category Test
 * @package  \API\Services\Auth
 * @author   Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class AuthTest extends PHPUnit_Framework_TestCase 
{
    
    /**
     * Authenticate object
     * 
     * @var \API\Services\Auth()
     */
    private $_oAuth = null;
    
    /**
     * Setup method
     * 
     * @return void
     */
    protected function setUp()
    {
        $this->_oAuth = new \API\Services\Auth();
    }
    
    /**
     * Tests Auth::setUser() when empty value supplied
     * 
     * @return void
     */
    public function testSetUser_invalid()
    {
        try {
            $this->_oAuth->setUser("");
        } catch (\API\Services\Auth\InvalidUsernameException $e) {
            $this->assertEquals( 
                \API\Services\Auth::INVALID_USERNAME_EMPTY,
                $e->getMessage()
            );
            return;
        }
        $this->fail("The expected exception was not thrown");
    }
    
    /**
     * Tests Auth::setPass() when empty value supplied
     * 
     * @return void
     */
    public function testSetPass_invalid()
    {
        try {
            $this->_oAuth->setPass("");
        } catch (\API\Services\Auth\InvalidPasswordException $e) {
            $this->assertEquals( 
                \API\Services\Auth::INVALID_PASSWORD_EMPTY,
                $e->getMessage()
            );
            return;
        }
        $this->fail("The expected exception was not thrown");
    }
    
    /**
     * Tests Auth::authenticate() for success
     * 
     * @return void
     */
    public function testAuthSucceed()
    {
        $stub = $this->getMock( '\API\Services\Auth', array('fetchUserByCredentials') );
        $stub->method('fetchUserByCredentials')->will(
            $this->returnValue(
                array(
                    'id' => 1,
                    'username' => 'me',
                    'password' => 'my'
                )
            )
        );
        $this->assertTrue( $stub->setUser('hello')->setPass('goodbye')->authenticate() );
    }
    
    /**
     * Tests Auth::authenticate() for failure
     * 
     * @return void
     */
    public function testAuthFail()
    {
        $stub = $this->getMock( '\API\Services\Auth', array('fetchUserByCredentials') );
        $stub->method('fetchUserByCredentials')->will( $this->returnValue( false ) );
        $this->assertFalse( $stub->setUser('hello')->setPass('goodbye')->authenticate() );
    }
    
}
