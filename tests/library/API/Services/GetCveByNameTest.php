<?php
require_once("Cve.php");
require_once("ResponseController.php");
require_once("API/Services/GetCveByName.php");

/*
 * Unit tests for GetCveByName class
 * 
 * @category Test
 * @package  \API\Services\GetCveByName
 * @author   Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class GetCveByNameTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Tests the response when the CVE number is not found
     * 
     * @return void
     */
    public function testGetDataNotFound()
    {
        /**
         * Stub only the GetCveByName::fetchCveByName() method
         */
        $stub = $this->getMock( '\API\Services\GetCveByName', array('fetchCveByName') );
        $stub->method('fetchCveByName')->will( $this->returnValue(false) );
        $this->assertEquals( 
            array(
                'response' => array(
                    'result' => ResponseController::RESULT_FAILURE,
                    'resultDescription' => ResponseController::RESULT_FAILURE_TEXT,
                    'reason' => "CVE with name 'noname' not found"
                ),
                'status' => ResponseController::STATUS_SUCCESS,
            ),
            $stub->get("noname")
        );
    }
    
    /**
     * Tests the response when the CVE number is found
     * 
     * @return void
     */
    public function testGetDataFound()
    {
        /**
         * Stub only the GetCveByName::fetchCveByName() method
         */
        $stub = $this->getMock( '\API\Services\GetCveByName', array('fetchCveByName') );
        $stub->method('fetchCveByName')->will( 
            $this->returnValue(
                array(
                    'ID' => 4,
                    'Name' => 'hasname',
                    'Status' => 'Candidate',
                    'Description' => 'something',
                    'References' => 'refs',
                    'Phase' => 'some phase',
                    'Votes' => 'some votes',
                    'Comments' => 'some comments'
                )
            ) 
        );
        $this->assertEquals( 
            array(
                'response' => array(
                    'result' => ResponseController::RESULT_SUCCESS,
                    'resultDescription' => ResponseController::RESULT_SUCCESS_TEXT,
                    'data' => array(
                        'ID' => 4,
                        'Name' => 'hasname',
                        'Status' => 'Candidate',
                        'Description' => 'something',
                        'References' => 'refs',
                        'Phase' => 'some phase',
                        'Votes' => 'some votes',
                        'Comments' => 'some comments'
                    )
                ),
                'status' => ResponseController::STATUS_SUCCESS,
            ),
            $stub->get("hasname")
        );
    }
    
}
