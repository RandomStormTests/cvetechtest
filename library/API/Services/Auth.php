<?php
namespace API\Services;

//require_once('Services/Auth/InvalidPasswordException.php');
//require_once('Services/Auth/InvalidUsernameException.php');

/**
 * Web services authentication class
 * 
 * Generalized rather than inside framework in order to provide common and centralized functionality across all 
 * potential future web services
 *
 * @category    API
 * @package     API\Services
 * @subpackage  Auth
 * @author      Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class Auth
{
    
    const INVALID_USERNAME_EMPTY = "Username is empty";
    const INVALID_PASSWORD_EMPTY = "Password is empty";
    
    /**
     * Username
     *
     * @var string
     */
    protected $_username;

    /**
     * Password
     *
     * @var string
     */
    protected $_password;

    /**
     * Sets the user and pass strings.
     *
     * @param string $p_user Username [Optional]
     * @param string $p_pass Password [Optional]
     */
    public function __construct($p_user=null, $p_pass=null)
    {
        if ( !empty($p_user) ) {
            $this->setUser($p_user);
        }
        if ( !empty($p_pass) ) {
            $this->setPass($p_pass);
        }
    }

    /**
     * Sets the username property
     *
     * @param string $p_user Username
     * 
     * @return \API\Services\Auth
     * 
     * @throws Auth\InvalidUsernameException
     */
    public function setUser($p_user)
    {
        if ( empty($p_user) ) {
            throw new Auth\InvalidUsernameException(self::INVALID_USERNAME_EMPTY);
        }
        $this->_username = $p_user;
        return $this;
    }

    /**
     * Sets the password property
     *
     * @param string $p_pass Password
     * 
     * @return \API\Services\Auth
     * 
     * @throws Auth\InvalidPasswordException
     */
    public function setPass($p_pass)
    {
        if ( empty($p_pass) ) {
            throw new Auth\InvalidPasswordException(self::INVALID_PASSWORD_EMPTY);
        }
        $this->_password = $p_pass;
        return $this;
    }

    /**
     * Authenticates username and password
     *
     * @return boolean
     */
    public function authenticate()
    {
        if ( empty($this->_username) ) {
            throw new Auth\InvalidUsernameException(self::INVALID_USERNAME_EMPTY);
        }
        if ( empty($this->_password) ) {
            throw new Auth\InvalidPasswordException(self::INVALID_PASSWORD_EMPTY);
        }
        
        
        if ( false !== ( $arrData = $this->fetchUserByCredentials() ) ) {
            return true;
        }
        return false;
    }
    
    /**
     * Fetches user by supplied credentials
     * 
     * @param string $p_user Username
     * @param string $p_pass Password
     * 
     * @return array
     */
    public function fetchUserByCredentials()
    {
        $oUser = new \User();
        return $oUser->fetchUserByCredentials($this->_username, $this->_password);
    }
}