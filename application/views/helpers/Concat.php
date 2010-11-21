<?php 

/**
 * Helper to concatrait css and javascript
 * files into one file, compress and upload
 * to s3.
 * 
 * @author Iain Cambridge
 * 
 * @version 0.2
 * 
 * @todo Improve!
 */

class Zend_View_Helper_Concat extends Zend_View_Helper_Abstract {
	
							
	CONST UNCOMPILED_DIR  = '/uncompiled_scripts/';							
	CONST COMPILED_DIR  = '/compiled_scripts/';
							
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
		
		$files = Zend_Registry::get('concat');
		$outputFiles = array();
		$config = Zend_Registry::get('config');
		
		
		foreach(array_keys($files) as $fileType){
			
			if ( !empty($files[$fileType]) ){
				
				// Only have valid files
				$validFiles = array();
				foreach( $files[$fileType] as $i => $filename ){
					$file = ROOT_DIR.'/application'. self::UNCOMPILED_DIR.$fileType.'/'.$filename;		
					if ( is_readable($file) ){
						$validFiles[] = $file;
					}
				}
				
				// This should work out that each css filename will be unquie.
				// Slim chance it won't.
				$compileFilename = ROOT_DIR.'/application/'.self::COMPILED_DIR.$fileType.'/'
								   .hash('md5',implode(',',$validFiles) ).'.'.$fileType;
								   
				$outputFiles[$fileType] = 'scripts/'.$fileType.'/'.basename($compileFilename);
				if ( is_readable($compileFilename) ){
					// No need to redo what we already have.
					continue;
				}
				// Temp file
				$fileContents = '';
				foreach( $validFiles as $file ){
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
							  		'Content-Encoding' => 'gzip',
							  		'Expires' => date('D, j M Y H:i:s', time() + (86400 * 365 * 10)) . ' GMT') );
							  				
			}
			
		}
		return $outputFiles;		
	}
	
}

?>