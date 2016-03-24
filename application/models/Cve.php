<?php
/**
 * `cve` table model
 * 
 * @category ZF
 * @package  Models/Cve
 * @author   Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class Cve extends \Zend_Db_Table_Abstract
{
    
    /**
     * Table being modelled
     * 
     * @var string
     */
    protected $_name = 'cve';
    
    /**
     * Returns the record for the CVE entry matching the supplied CVE name
     * 
     * @param string $p_name CVE name
     * 
     * @return array|boolean Array of record results or FALSE if nothing found
     */
    public function fetchByName($p_name)
    {
       $select = $this
            ->select()
            ->where( "`Name` = :name" )
            ->bind(
                array(
                    ":name" => $p_name,
                )
            );
        $arrData = $this->fetchAll($select)->toArray();
        if ( empty($arrData) ) {
             return false;
        }
        return $arrData[0];
    }
    
    /**
     * Fetches all CVE records for offset with a limit of returned rows
     * 
     * @param integer $p_limit  Limit
     * @param integer $p_offset Offset
     * @param integer $p_year   Year [Optional]
     * 
     * @return array|boolean Array of record results or FALSE if nothing found
     */
    public function fetchAllCve($p_limit, $p_offset, $p_year=null)
    {
        $where = "ID IS NOT NULL";
        if ( !empty($p_year) ) {
            $where = "`Year` = {$p_year}";
        }
        $select = $this
            ->select()
            ->where($where)
            ->order('ID')
            ->limit($p_limit, $p_offset);
        $arrData = $this->fetchAll($select)->toArray();
        if ( empty($arrData) ) {
             return false;
        }
        return $arrData;
    }
    
}