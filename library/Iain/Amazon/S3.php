<?php

	/**
	 * 
	 * Enter description here ...
	 * @author Iain Cambridge
	 *
	 */

class Iain_Service_Amazon_S3 {

	protected static $connection = false;
	
	protected static function _getConnection(){
		
		if ( !is_a(self::$connection,'Zend_Service_Amazon_S3' ) ){
			$config = Zend_Registry::get("config");
			self::$connection = new Zend_Service_Amazon_S3();
		}
		
		return self::$connection;
	}

	public static function uploadFile( $filename , $fileLocation = false , array $fileOptions = array() ){
		
		$fileOptions = array_merge(array(Zend_Service_Amazon_S3::S3_ACL_HEADER => Zend_Service_Amazon_S3::S3_ACL_PUBLIC_READ,
							  		'Expires' => date('D, j M Y H:i:s', time() + (86400 * 365 * 10)) . ' GMT'), $fileOptions);
		$config = Zend_Registry::get("config");
		
		if ( empty($fileLocation) ){
			$fileLocation = $filename;
		}
		$s3 = self::_getConnection();
						
		$s3->putFile($fileLocation, $config->aws->bucket.'/'.$filename, $fileOptions );
							  				
	}
	
}