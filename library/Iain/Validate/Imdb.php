<?php 

	/**
	 * A IMDb url validator. Created to have a nicer 
	 * and more elegant way of handling the different 
	 * error message and check that the IMDb url
	 * actually exists.
	 * 
	 * @author Iain Cambridge
	 * @license http://backie.org/copyright/bpl/ BPL
     * @version 0.2
     * @todo Improve!!
     */

class Iain_Validate_Imdb extends Iain_Validate_Page {
	
	
	public function __construct($pattern = false){
		
		// Hardcode the pattern since a twitter account can only be there.
		parent::__construct("~http://(www\.)?imdb.com/name/([a-z]{2}[0-9]+)~isU");
		$this->_messageTemplates = array( parent::NOT_MATCH => "%value% is not a valid IMDb URL.",
										 parent::ERROROUS => "Shit went bad! Error report has been sent.",
										 parent::INVALID => "Shit went bad! Error report been sent.",
										 parent::INVALID_PAGE => "%value% is not a valid IMDb page");
	}
	
	
}