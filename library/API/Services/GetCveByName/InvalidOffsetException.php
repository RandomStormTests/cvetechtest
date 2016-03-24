<?php
namespace API\Services\GetCveByName;

/**
 * GetAPI invalid offset exception class.
 *
 * @category   API
 * @package    \Services\Auth\InvalidOffsetException
 * @subpackage Exception 
 * @author     Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class InvalidOffsetException extends \InvalidArgumentException {
    
    protected $message = 'A valid offset must be supplied';
    
}