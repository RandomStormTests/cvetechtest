<?php
namespace API\Services;

/**
 * Description of GetCveByName
 *
 * @category API
 * @package  API\Services\Service\GetCveByName
 * @author Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class GetCveByName 
{
    
    /**
     * Limit of CVE records to retrieve
     * 
     * @var integer
     */
    private $_limit = null;
    
    /**
     * Offset of CVE records to retrieve
     * 
     * @var integer
     */
    private $_offset = null;
    
    /**
     * Sets limit
     * 
     * @param integer $p_limit Limit setting
     * 
     * @return API\Services\GetCveByName
     * 
     * @throws \API\Services\GetCveByName\InvalidLimitException
     */
    public function setLimit($p_limit)
    {
        $this->_validateLimit($p_limit);
        $this->_limit = $p_limit;
        return $this; 
    }
    
    /**
     * Sets offset
     * 
     * @param integer $p_offset Offset setting
     * 
     * @return \API\Services\GetCveByName
     * 
     * @throws \API\Services\GetCveByName\InvalidOffsetException
     */
    public function setOffset($p_offset)
    {
        $this->_validateOffset($p_offset);
        $this->_offset = $p_offset;
        return $this;
    }
        
    
    /**
     * Fetches a CVE record by CVE name
     * 
     * @param string $p_name CVE name
     * 
     * @return array Record
     */
    public function fetchCveByName($p_name)
    {
        $oCve = new \Cve();
        return $oCve->fetchByName($p_name);
    }
    
    /**
     * Fetches a limited number of CVE records from the supplied offset
     * 
     * @return array
     * 
     * @throws \API\Services\GetCveByName\InvalidLimitException
     * @throws \API\Services\GetCveByName\InvalidOffsetException
     * 
     */
    public function fetchAllCve()
    {
        $this->_validateLimit($this->_limit)->_validateOffset($this->_offset);
        $oCve = new \Cve();
        return $oCve->fetchAllCve($this->_limit, $this->_offset);
    }
    
    /**
     * Validates the limit
     * 
     * @param integer $p_limit Limit
     * 
     * @return \API\Services\GetCveByName
     * 
     * @throws \API\Services\GetCveByName\InvalidLimitException
     */
    private function _validateLimit($p_limit)
    {
        if ( empty($p_limit) ) {
            throw new \API\Services\GetCveByName\InvalidLimitException();
        } elseif ( is_integer($p_limit) ) {
            /** Doing nothing */
        } elseif ( is_string($p_limit) AND ctype_digit($p_limit) ) {
            /** Doing nothing */
        } else {
            throw new \API\Services\GetCveByName\InvalidLimitException();
        }
        return $this;
    }
    
    /**
     * Validates the offset
     * 
     * @param integer $p_offset Offset
     * 
     * @return \API\Services\GetCveByName
     * 
     * @throws \API\Services\GetCveByName\InvalidOffsetException
     */
    private function _validateOffset($p_offset)
    {
        if (0 === $p_offset OR "0" === $p_offset) {
            /** Doing nothing */
        } elseif ( empty($p_offset) ) {
            throw new \API\Services\GetCveByName\InvalidOffsetException();
        } elseif ( is_integer($p_offset) ) {
            /** Doing nothing */
        } elseif ( is_string($p_offset) AND ctype_digit($p_offset) ) {
            /** Doing nothing */
        } else {
            throw new \API\Services\GetCveByName\InvalidOffsetException();
        }
        return $this;
    }
    
    private function _validateYear($p_year)
    {
        
    }
    
    /**
     * Gets CVE response data
     * 
     * @param integer $p_limit  Limit
     * @param integer $p_offset Offset
     * @param integer $p_year   Year [Optional]
     * 
     * @return array Response data
     */
    public function getAll($p_limit, $p_offset, $p_year)
    {
        
        $isError = false;
        
        try {
            $this->setLimit($p_limit)->setOffset($p_offset);
            if ( false === ( $arrData = $this->fetchAllCve() ) ) {
                return array(
                    'response' => array(
                        'result' => \ResponseController::RESULT_FAILURE,
                        'resultDescription' => \ResponseController::RESULT_FAILURE_TEXT,
                        'reason' => "CVE not found"
                    ),
                    'status' => \ResponseController::STATUS_SUCCESS,
                );
            } else {
                return array(
                    'response' => array(
                        'result' => \ResponseController::RESULT_SUCCESS,
                        'resultDescription' => \ResponseController::RESULT_SUCCESS_TEXT,
                        'data' => $arrData
                    ),
                    'status' => \ResponseController::STATUS_SUCCESS,
                );
            }
        } catch (\API\Services\GetCveByName\InvalidLimitException $e) {
            $isError = true;
        } catch (\API\Services\GetCveByName\InvalidOffsetException $e) {
            $isError = true;
        }
        if ($isError) {
            return array(
                'response' => array(
                    'result' => \ResponseController::RESULT_FAILURE,
                    'resultDescription' => \ResponseController::RESULT_FAILURE_TEXT,
                    'reason' => $e->getMessage(),
                ),
                'status' => \ResponseController::STATUS_PROCESSING_ERROR,
            );
        }
            
    }
    
    /**
     * Gets CVE response data for the supplied name
     * 
     * @param string $p_name CVE name
     * 
     * @return array Response data
     */
    public function get($p_name)
    {
        if (  false === ( $arrData = $this->fetchCveByName($p_name) )  ) {
            return array(
                'response' => array(
                    'result' => \ResponseController::RESULT_FAILURE,
                    'resultDescription' => \ResponseController::RESULT_FAILURE_TEXT,
                    'reason' => "CVE with name '{$p_name}' not found"
                ),
                'status' => \ResponseController::STATUS_SUCCESS,
            );
        } else {
            return array(
                'response' => array(
                    'result' => \ResponseController::RESULT_SUCCESS,
                    'resultDescription' => \ResponseController::RESULT_SUCCESS_TEXT,
                    'data' => $arrData
                ),
                'status' => \ResponseController::STATUS_SUCCESS,
            );
        }
    }
    
    
            
    
}
