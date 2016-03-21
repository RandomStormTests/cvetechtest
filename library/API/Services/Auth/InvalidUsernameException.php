<?php
namespace API\Services\Auth;

/**
 * API invalid username authentication exception class.
 *
 * @category   API
 * @package    \Services\Auth\InvalidUserException
 * @subpackage Exception
 * 
 * @author      Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class InvalidUsernameException extends \InvalidArgumentException {
    
    protected $_message = 'Invalid username';
    
}