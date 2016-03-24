<?php
namespace API\Services\Auth;

/**
 * API invalid password authentication exception class.
 *
 * @category   API
 * @package    \Services\Auth\InvalidPasswordException
 * @subpackage Exception
 * 
 * @author      Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class InvalidPasswordException extends \InvalidArgumentException {
    
    protected $message = 'Invalid password';
    
}