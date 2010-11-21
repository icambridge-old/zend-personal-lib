<?php

class Zend_View_Helper_PageTitle extends Zend_View_Helper_Abstract {

	protected $_string = '';
	
	public function pageTitle($pageTitle = null){
		
		if ( !is_null($pageTitle) ){
			$this->_string = trim($pageTitle);
		}
		return $this;
	}

	public function __toString(){

        return $this->_string;
    }
}

?>