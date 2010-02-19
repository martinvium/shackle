<?php
require_once 'PHPUnit/Framework.php';

require_once 'BirthdateTest.php';

class VXML_Rule_Person_AllTests extends PHPUnit_Framework_TestSuite
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite();
		$suite->setName('VXML Rule Person TestSuite');
		
		$suite->addTestSuite('VXML_Rule_Person_BirthdateTest');
		
		return $suite;
	}
}