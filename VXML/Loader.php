<?php
namespace VXML;

class Loader
{
	static public function autoload($class)
	{
		if(class_exists($class, false) || interface_exists($class, false)) 
		{
			return;
		}
	
		$file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
		include $file;
	}
	
	static public function registerAutoload()
	{
		spl_autoload_register(array('VXML\Loader', 'autoload'));
	}
}