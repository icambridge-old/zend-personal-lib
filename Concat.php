<?php 

/**
 * Helper to concatrait css and javascript
 * files into one file, compress and upload
 * to s3.
 * 
 * @author Iain Cambridge
 * 
 * @version 0.1
 * 
 * @todo Improve!
 */

class Zend_View_Helper_Concat extends Zend_View_Helper_Abstract {
	
	public static $files = array(
								 'css' => array(),
								 'js'  => array(),
							);
							
	CONST UNCOMPILED_DIR  = '/uncompiled_scripts/';							
	CONST COMPILED_DIR  = '/compiled_scripts/';
							
							
	/**
	 * To add file to css or javascript arrays,
	 * to be processed later. Does a simple check 
	 * to ensure the file is readable before
	 * adding it to the array.
	 * 
	 * @param array|string $file
	 * @param string $filetype
	 * 
	 * @since 0.1
	 */
	public static function addFile($files,$fileType = 'css') {
		
		// To avoid redoing the file check
		if ( !is_array($files) ){
			$files = array($files);
		}

		foreach($files as $file){	
			$file = ROOT_DIR.'/application'. self::UNCOMPILED_DIR.$fileType.'/'.$file;		
			if ( is_readable($file) ){
				self::$files[$fileType][] = $file;
			}			
		}
		
	}
	
	/**
	 * Handles the concentration of the css and
	 * javascript files then compresses and
	 * uploads them to an Amazon S3 bucket.
	 * 
	 * @since 0.1
	 * 
	 * @return array
	 */
	
	public function concat(){
		
   		self::addFile('default.css','css');
		$outputFiles = array();
		$config = Zend_Registry::get('config');
		foreach(array_keys(self::$files) as $fileType){
			
			if ( !empty(self::$files[$fileType]) ){
				// This should work out that each css filename will be unquie.
				// Slim chance it won't.
				$compileFilename = ROOT_DIR.'/application/'.self::COMPILED_DIR.$fileType.'/'
								   .hash('md5',implode(',',self::$files[$fileType]) ).'.'.$fileType;
								   
				$outputFiles[$fileType] = 'scripts/'.$fileType.'/'.basename($compileFilename);
				if ( is_readable($compileFilename) ){
					// No need to redo what we already have.
					continue;
				}
				// Temp file
				$fileContents = '';
				foreach( self::$files[$fileType] as $file ){
					// I have a feeling which is most likely incorrect that I may need a new line for CSS files.
					$fileContents .= file_get_contents($file).PHP_EOL;
				}
				if ( empty($fileContents) ){
					continue;
				}
				// Write to file. Using zlib for the writing of the file as amazon
				// s3 doesn't send compress the data for you when you send the  
				// compress header.
				$fileResource = gzopen($compileFilename,'w9');				
				gzwrite($fileResource,$fileContents);
				gzclose($fileResource);
				
				// Upload file to s3.
				$s3 = new Zend_Service_Amazon_S3();
				
				$outputFiles[$fileType] = 'scripts/'.$fileType.'/'.basename($compileFilename);
				
				$s3->putFile($compileFilename, $config->aws->bucket.'/'.$outputFiles[$fileType], 
							  array(Zend_Service_Amazon_S3::S3_ACL_HEADER => Zend_Service_Amazon_S3::S3_ACL_PUBLIC_READ,
							  		'Content-Encoding' => 'gzip') );
							  				
			}
			
		}
		return $outputFiles;		
	}
	
}

?>