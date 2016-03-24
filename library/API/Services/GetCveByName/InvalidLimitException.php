<?php
namespace API\Services\GetCveByName;

/**
 * GetAPI invalid limit exception class.
 *
 * @category   API
 * @package    \Services\Auth\InvalidLimitException
 * @subpackage Exception
 * @author     Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class InvalidLimitException extends \InvalidArgumentException {
    
    protected $message = 'A valid limit must be supplied';
    
}