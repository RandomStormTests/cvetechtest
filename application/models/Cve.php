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
     * @return array
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
    
}