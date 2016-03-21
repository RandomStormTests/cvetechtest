<?php

class IndexController extends Zend_Controller_Action
{

    /**
     * Initialisation
     * 
     * @return
     */
    public function init()
    {
        /* Initialize action controller here */
    }

    /**
     * Index action
     * 
     * @return void
     */
    public function indexAction()
    {
        $this->_forward('index', 'cve');
    }
    
    /**
     * cve route action
     * 
     * @return void
     */
    public function cveAction()
    {
        $arrParam = array_merge(
            array(
                'service' => 'cve',
            ),
            $this->getAllParams()
        );
        $this->_forward('index', 'cve', null, $arrParam);
    }


}

