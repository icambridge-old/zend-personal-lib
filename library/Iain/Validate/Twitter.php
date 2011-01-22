<?php 

	/**
	 * A twitter url validator. Created to have a nicer 
	 * and more elegant way of handling the different 
	 * error message and check that the twitter account
	 * actually exists.
	 * 
	 * @author Iain Cambridge
	 * @license http://backie.org/copyright/bpl/ BPL
     * @version 0.2
     * @todo Improve!!
     */

class Iain_Validate_Twitter extends Zend_Validate_Regex {
	
	
	public function __construct($pattern = false){
		
		// Hardcode the pattern since a twitter account can only be there.
		parent::__construct("~http://(www\.)?twitter.com/([a-zA-Z0-9\_\-]+)~isU");
		$this->_messageTemplates = array( parent::NOT_MATCH => "%value% is not a valid twitter URL.",
										 parent::ERROROUS => "Shit went bad! Error report has been sent.",
										 parent::INVALID => "Shit went bad! Error report been sent.");
	}
	
	public function isValid($value){
		
		// TODO add check to validate twitter account exists.
		return parent::isValid($value);
	}
	
}