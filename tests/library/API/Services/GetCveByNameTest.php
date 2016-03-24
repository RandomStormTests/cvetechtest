<?php
require_once("Cve.php");
require_once("ResponseController.php");
require_once("API/Services/GetCveByName.php");
require_once("API/Services/GetCveByName/InvalidLimitException.php");
require_once("API/Services/GetCveByName/InvalidOffsetException.php");

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
    
    /**
     * Tests the response when no limit is supplied
     * 
     * @return void
     */
    public function testGetAllDataNoLimit()
    {
        /**
         * Stub only the GetCveByName::fetchCveByName() method
         */
        $stub = $this->getMock( '\API\Services\GetCveByName', array('fetchAllCve') );
        $stub->method('fetchAllCve')->will( $this->returnValue(false) );
        $this->assertEquals( 
            array(
                'response' => array(
                    'result' => ResponseController::RESULT_FAILURE,
                    'resultDescription' => ResponseController::RESULT_FAILURE_TEXT,
                    'reason' => "A valid limit must be supplied"
                ),
                'status' => ResponseController::STATUS_PROCESSING_ERROR,
            ),
            $stub->getAll(0, 25)
        );
    }
    
    /**
     * Tests the response when an invalid limit is supplied
     * 
     * @return void
     */
    public function testGetAllDataInvalidLimit()
    {
        /**
         * Stub only the GetCveByName::fetchCveByName() method
         */
        $stub = $this->getMock( '\API\Services\GetCveByName', array('fetchAllCve') );
        $stub->method('fetchAllCve')->will( $this->returnValue(false) );
        $this->assertEquals( 
            array(
                'response' => array(
                    'result' => ResponseController::RESULT_FAILURE,
                    'resultDescription' => ResponseController::RESULT_FAILURE_TEXT,
                    'reason' => "A valid limit must be supplied"
                ),
                'status' => ResponseController::STATUS_PROCESSING_ERROR,
            ),
            $stub->getAll('abc', 25)
        );
    }
    
    /**
     * Tests the response when no offset is supplied
     * 
     * @return void
     */
    public function testGetAllDataNoOffset()
    {
        /**
         * Stub only the GetCveByName::fetchCveByName() method
         */
        $stub = $this->getMock( '\API\Services\GetCveByName', array('fetchAllCve') );
        $stub->method('fetchAllCve')->will( $this->returnValue(false) );
        $this->assertEquals( 
            array(
                'response' => array(
                    'result' => ResponseController::RESULT_FAILURE,
                    'resultDescription' => ResponseController::RESULT_FAILURE_TEXT,
                    'reason' => "A valid offset must be supplied"
                ),
                'status' => ResponseController::STATUS_PROCESSING_ERROR,
            ),
            $stub->getAll(25, "")
        );
    }
    
    /**
     * Tests the response when no offset is supplied
     * 
     * @return void
     */
    public function testGetAllDataInvalidOffset()
    {
        /**
         * Stub only the GetCveByName::fetchCveByName() method
         */
        $stub = $this->getMock( '\API\Services\GetCveByName', array('fetchAllCve') );
        $stub->method('fetchAllCve')->will( $this->returnValue(false) );
        $this->assertEquals( 
            array(
                'response' => array(
                    'result' => ResponseController::RESULT_FAILURE,
                    'resultDescription' => ResponseController::RESULT_FAILURE_TEXT,
                    'reason' => "A valid offset must be supplied"
                ),
                'status' => ResponseController::STATUS_PROCESSING_ERROR,
            ),
            $stub->getAll(25, 'abc')
        );
    }
    
    /**
     * Tests fetching a specific record by CVE-name from CVE table
     * This is a naughty test, because it assumes the legacy CVE names will remain into the future
     * 
     * @return void
     */
    public function testGetData()
    {
        $oFactory = new \API\Services\GetCveByName();
        $arrData = $oFactory->fetchCveByName('CVE-1999-0004');
        $this->assertNotEmpty($arrData);
        $this->assertEquals( 8, count($arrData) ); // Number of columns
    }
    
    /**
     * Tests fetching a set of records from CVE table limited to a number of rows and offset
     * This test has some implicit assumptions, however the results being tested are sufficiently generalized
     * to compensate
     * This test assumes that there are at least 25 records in the CVE table
     * 
     * @return void
     */
    public function testGetAllData()
    {
        $oFactory = new \API\Services\GetCveByName();
        $arrData = $oFactory->setLimit(25)->setOffset(0)->fetchAllCve(25, 0);
        $this->assertNotEmpty($arrData);
        $this->assertEquals( 25, count($arrData) ); // Number of rows
    }
    
}
