<?php

	/**
	 * Concatention Model, to combine JavaScript and CSS 
	 * files. Instead of a single file per page build, this
	 * model will build concatention files.
	 * 
	 * @author Iain Cambridge
	 * @copyright Iain Cambridge all rights reserved 2011 (c)
	 * @license http://backie.org/copyright/bsd-license BSD License
	 */

class Iain_Optimize_Concat {
	
	/**
	 * 
	 * @var
	 */
	
	public static $rawFiles = array( 'scripts' => array(), 'styles' => array() );
	
	public static $concatFiles = array( 'scripts' => array(), 'styles' => array() );
	
	public static $builds = array( 'scripts' => array(), 'styles' => array() );
	
	
	public static function addFile($fileType,$controllers,$script){
		
		if ( !is_array($controllers) ){
			$controllers = array($controllers);
		}
		
		foreach($controllers as $controller ){
			if ( !isset(self::$rawFiles[$fileType][$controller]) ){
				self::$rawFiles[$fileType][$controller] = array();
			}
			if ( !is_array($script) ){
				self::$rawFiles[$fileType][$controller][] = $script;
			} else {
				self::$rawFiles[$fileType][$controller] = array_merge(self::$rawFiles[$fileType][$controller],$script);
			}
		}
		
	}
	
	protected static function process( $fileType ){
		$buildFiles = array();
		self::$builds = array();
		foreach ( self::$rawFiles[$fileType] as $controller => $controllerFiles ){
			
			foreach ( $controllerFiles as $file ){
				if ( !isset($buildFiles[$file]) || !is_array($buildFiles[$file]) ){
					$buildFiles[$file] = array();
				}
				$buildFiles[$file][] = $controller;
			}
			
		}
		
		foreach( $buildFiles as $file => $controllerArray ){
			
			sort($controllerArray);
			$key = implode("", $controllerArray);
			
			foreach ( $controllerArray as $controller ){
				if ( !isset(self::$concatFiles[$fileType][$controller]) || !is_array(self::$concatFiles[$fileType][$controller]) ){
					self::$concatFiles[$fileType][$controller] = array();
				}
				self::$concatFiles[$fileType][$controller][] = $key;	
			} 
			
			if ( !isset(self::$builds[$key]) || !is_array(self::$builds[$key]) ){
				self::$builds[$key] = array();	
			}
			self::$builds[$key][] = $file;
		}
		
		return self::$builds;
	}
	
	public static function getScripts(){

		return self::process('scripts');
		
	}
	
}