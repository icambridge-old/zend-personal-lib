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
	
	public static $scripts = array();
	
	public static $styles = array();
	
	public static $pageScripts = array();
	
	public static $pageStyles = array();
	
	public static $builds = array();
	
	
	public static function addScript($controllers,$script){
		
		if ( !is_array($controllers) ){
			$controllers = array($controllers);
		}
		
		foreach($controllers as $controller ){
			if ( !isset(self::$scripts[$controller]) ){
				self::$scripts[$controller] = array();
			}
			if ( !is_array($script) ){
				self::$scripts[$controller][] = $script;
			} else {
				self::$scripts[$controller] = array_merge(self::$scripts[$controller],$script);
			}
		}
		
	}
	
	protected static function process( $files ){
		$buildFiles = array();
		$builds = array();
		foreach ( $files as $controller => $controllerFiles ){
			
			foreach ( $controllerFiles as $file ){
				if ( !isset($buildFiles[$file]) || !is_array($buildFiles[$file]) ){
					$buildFiles[$file] = array();
				}
				$buildFiles[$file][] = $controller;
			}
			
		}
		$pageScripts = array();
		foreach( $buildFiles as $file => $controllerArray ){
			
			sort($controllerArray);
			$key = implode("", $controllerArray);
			
			foreach ( $controllerArray as $controller ){
				if ( !isset($pageScripts[$controller]) || !is_array($pageScripts[$controller]) ){
					$pageScripts[$controller] = array();
				}
				$pageScripts[$controller][] = $key;	
			} 
			
			if ( !isset($builds[$key]) || !is_array($builds[$key]) ){
				$builds[$key] = array();	
			}
			$builds[$key][] = $file;
		}
		
		return $builds;
	}
	
	public static function getScripts(){

		return self::process(self::$scripts);
		
	}
	
}