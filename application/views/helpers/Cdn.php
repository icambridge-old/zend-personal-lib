<?php

/**
 * Simple helper to provide the current CDN hostname,
 * designed to allow for a robust system where it can
 * change if the need arises, such as site becomes massive
 * and I want use a different domain to allow for better
 * proformance.
 * 
 * @author iain.cambridge
 */

class Zend_View_Helper_Cdn extends Zend_View_Helper_Abstract {
		
	public function cdn($filename = ''){

		$config = Zend_Registry::get('config');
		$cdn = 'http://'.$config->aws->hostname.'/'.$filename;
		return $cdn;
	}
	
}

?>