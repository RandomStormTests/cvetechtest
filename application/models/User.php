<?php
/**
 * `user` table model
 * 
 * @category ZF
 * @package  Models/User
 * @author   Quentin Jendrzewski <qljsystems@hotmail.co.uk>
 */
class User extends \Zend_Db_Table_Abstract
{
    
    /**
     * Table being modelled
     * 
     * @var string
     */
    protected $_name = 'users';
    
    
    /**
     * Fetches a user record by the supplied credentials
     * 
     * @param string $p_username Username
     * @param string $p_password Password
     * 
     * @return array|boolean Array of record data or FALSE if nothing found
     */
    public function fetchUserByCredentials($p_username, $p_password)
    {
        $select = $this
            ->select()
            ->where(
                implode( 
                    "AND",
                    array(
                        "`username` = :username",
                        "`password` = :password"
                    )
                )
            )
            ->bind(
                array(
                    ":username" => $p_username,
                    ":password" => $p_password
                )
            );
        $arrData = $this->fetchAll()->toArray();
        if ( empty($arrData) ) {
             return false;
        }
        return $arrData[0];
    }
    
}