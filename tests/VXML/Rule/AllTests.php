<?php
require_once 'PHPUnit/Framework.php';

require_once 'CallbackTest.php';
require_once 'EqualTest.php';
require_once 'FailureTest.php';
require_once 'ImportTest.php';
require_once 'InvertTest.php';
require_once 'IteratorTest.php';
require_once 'ListenerTest.php';
require_once 'NumberRangeTest.php';
require_once 'OptionalTest.php';
require_once 'RegexTest.php';
require_once 'SetTest.php';
require_once 'SilentTest.php';
require_once 'StringLengthTest.php';
require_once 'ValidTest.php';
require_once 'WhitelistTest.php';

require_once 'Person/AllTests.php';

class VXML_Rule_AllTests extends PHPUnit_Framework_TestSuite
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite();
		$suite->setName('VXML Rule TestSuite');
		
		$suite->addTestSuite('VXML_Rule_CallbackTest');
		$suite->addTestSuite('VXML_Rule_EqualTest');
		$suite->addTestSuite('VXML_Rule_FailureTest');
		$suite->addTestSuite('VXML_Rule_ImportTest');
		$suite->addTestSuite('VXML_Rule_InvertTest');
		$suite->addTestSuite('VXML_Rule_IteratorTest');
		$suite->addTestSuite('VXML_Rule_ListenerTest');
		$suite->addTestSuite('VXML_Rule_NumberRangeTest');
		$suite->addTestSuite('VXML_Rule_OptionalTest');
		$suite->addTestSuite('VXML_Rule_RegexTest');
		$suite->addTestSuite('VXML_Rule_SetTest');
		$suite->addTestSuite('VXML_Rule_SilentTest');
		$suite->addTestSuite('VXML_Rule_StringLengthTest');
		$suite->addTestSuite('VXML_Rule_ValidTest');
		$suite->addTestSuite('VXML_Rule_WhitelistTest');
		
		$suite->addTest(VXML_Rule_Person_AllTests::suite());
		
		return $suite;
	}
}