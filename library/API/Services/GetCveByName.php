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
