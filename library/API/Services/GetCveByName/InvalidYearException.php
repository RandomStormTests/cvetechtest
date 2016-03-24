<?php
namespace API\Services\GetCveByName;

/**
 * GetAPI invalid year exception class.
 *
 * @category   API
 * @package    \Services\Auth\InvalidYearException
 * @subpackage Exception
 * @author     Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class InvalidYearException extends \InvalidArgumentException {
    
    protected $message = 'If a year is supplied, it must be valid';
    
}