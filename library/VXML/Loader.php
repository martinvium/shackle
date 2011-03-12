<?php
namespace VXML;

/**
 * Autoloader for loading rules, without having to include them first
 */
class Loader
{
	/**
	 * Register autoloader, call this to have classes automatically included. 
	 * Required VXML to be in include path.
	 */
	static public function registerAutoload()
	{
		spl_autoload_register(array('VXML\Loader', 'autoload'));
	}
	
	/**
	 * Actual autoloader method
	 * 
	 * @param string $class
	 */
	static public function autoload($class)
	{
		if(class_exists($class, false) || interface_exists($class, false)) 
		{
			return;
		}
	
		$file = str_replace('\\', \DIRECTORY_SEPARATOR, $class) . '.php';
		require_once $file;
	}
}