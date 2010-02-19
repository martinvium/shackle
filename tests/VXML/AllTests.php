<?php
require_once 'PHPUnit/Framework.php';

set_include_path(get_include_path() . PATH_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '../library');
require_once 'VXML/Loader.php';
VXML\Loader::registerAutoload();

require_once 'ContextTest.php';
require_once 'EventTest.php';
require_once 'ResponseTest.php';

require_once 'Rule/AllTests.php';

class VXML_AllTests extends PHPUnit_Framework_TestSuite
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite();
		$suite->setName('VXML TestSuite');
		
		$suite->addTestSuite('VXML_ContextTest');
		$suite->addTestSuite('VXML_EventTest');
		$suite->addTestSuite('VXML_ResponseTest');
		
		$suite->addTest(VXML_Rule_AllTests::suite());
		
		return $suite;
	}
}