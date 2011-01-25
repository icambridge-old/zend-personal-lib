<?php 

	/**
	 * Simple class that extends Zend_Validate_Regex does
	 * a validation check on the regex pattern provided 
	 * and then does a http request to validate that the
	 * url doesn't belong to a 404 page. 
	 * 
	 * @author Iain Cambridge
	 * @license http://backie.org/copyright/bpl
	 * @copyright Iain Cambridge all rights reserved 2011.
	 * @todo improve!!
	 */

class Iain_Validate_Page extends Zend_Validate_Regex {
		
	const INVALID_PAGE = 'accPage';
	
	/**
	 * Whole point of the class, checks parent class to 
	 * see if valid regex then does the http request.
	 * @param string $value
	 */
	public function isValid($value){
		
		if ( parent::isValid($value) ){
			return false;
		}
		
		$client = new Zend_Http_Client($value);
		$response = $client->request();
		
		if ( $response->getStatus() == 404 ){
			$this->_error(self::INVALID_PAGE);
			return false;
		}
		
	}
	
}