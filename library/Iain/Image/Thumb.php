<?php

	/**
	 * An attempt at creating an easiler and more
	 * elegant way of handling the create of multiple
	 * thumbnails pictures for a single picture.
	 *  
	 * @author Iain Cambridge
	 * @license http://backie.org/copyright/bpl/ BPL
	 * @version 0.2
	 * @uses gd
	 * @todo Improve!
	 */

class Iain_Image_Thumb {
	
	/**
	 * The simple interface for which I will use
	 * for creating thumbnails 
	 * 
	 * @param string $filename the filename of the orginal image that will be thumbnailed.
	 * @param array $sizes The varying sizes that the thumbnails will be created, each item is an array for a single image
	 *
	 * @return array	
	 */
	public static function create($filename, $sizes = array()){
		
		// Quick sanity checks
		if ( !is_readable($filename) ){
			throw new Exception($filename.' is not readable by '.__CLASS__);
		}
		
		if ( !is_array($sizes) || empty($sizes) ){
			throw new Exception('No thumbnail sizes provided for '.$filename);	
		}
		
		// The orginal image Resource
		if ( preg_match('/\.(jpg|jpeg)$/i', $filename) === 1 ){
			$imageSrc = ImageCreateFromJpeg($filename);
			$createCallback = 'ImageJPEG';
		} elseif (preg_match('/\.(png)$/i', $filename) === 1) {
			$imageSrc = ImageCreateFromPNG($filename);
			$createCallback = 'ImagePNG';
		} else {
			$imageSrc = ImageCreateFromGIF($filename);
			$createCallback = 'ImageGIF';
		}
		
        if ( !$imageSrc ){
            die('Failed');
        }
        
        // Resample
        $actualWidth = ImageSx($imageSrc);
        $actualHeight = ImageSy($imageSrc);
        
        $outputArray = array();
        
		// "The Loop" :-p
		foreach ( $sizes as $imageSpecs ){
		
			// Get sizes
			$dimensions = self::_getDimensions($actualWidth, $actualHeight,
								$imageSpecs['width'], $imageSpecs['height']);
								
			if ( $dimensions === false ){
				// The thumbnail would have been larger or 
				// equal to the orginal image.
				continue;
			}					
			
			// Create actual thumbnail image.					
			$newSrc = ImageCreateTrueColor($dimensions['width'],$dimensions['height']);
        	ImageCopyResampled($newSrc,$imageSrc,0,0,0,0,
        						$dimensions['width'],$dimensions['height'],
        						$actualWidth,$actualHeight);
        						
        	// Write to file and then free up space.					
        	$createCallback($newSrc,$imageSpecs['filename']);
        	imagedestroy($newSrc);				
			
        	$outputArray[] = array(
        						'filename' => $imageSpecs['filename'],
        						'width' => $dimensions['width'],
        						'height' => $dimensions['height'],
        						'filesize' => filesize($imageSpecs['filename']),
        						);
        	
		}
		
		return $outputArray;
	}
	
	/**
	 * Instead of automattically creating a thumbnail of a
	 * specfic size, I am going to have this method to find
	 * out the optimum size of the thumbnail based on the 
	 * sizes being the maxmium size for each dimension.
	 * 
	 * @param int $actualWidth
	 * @param int $actualHeight
	 * @param int $newWidth
	 * @param int $newHeight
	 * 
	 * @return array
	 */
	
	protected static function _getDimensions($actualWidth,$actualHeight,
											 $newWidth,$newHeight){
		
		// The the ratios for the image sizes									 	
		$heightRatio = $actualWidth / $newWidth;
		$widthRatio = $actualHeight / $newHeight;
		
		$thumbHeight = $actualHeight / $heightRatio; 
		$thumbWidth = $actualWidth / $widthRatio;
		
		// See which type of image is best.
		// Go witdh by default.
		
		if ( $newHeight >= $actualHeight && $newWidth >= $actualWidth  ){
			// Thumbnail is larger than actual.
			$imageSize = false;			
		} elseif ( $thumbHeight < $newHeight ){			    	
			$imageSize = array( 'width' => $newWidth, 'height' => intval($thumbHeight) );			
		} else {
			$imageSize = array( 'width' => intval($thumbWidth) , 'height' => $newHeight );
		}
		
		return $imageSize;
		
	}
}