<?php
use VXML\Rule;

require_once 'TestCase.php';

class VXML_Rule_RegexTest extends VXML_Rule_TestCase 
{
	public function testEqual()
	{
		$rule = new Rule\Regex('firstname', array('pattern' => '/\p{L}+/x'));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testNotEqual()
	{
		$rule = new Rule\Regex('firstname', array('pattern' => '/\[0-9]+/x'));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testMissingPatternOptionError()
	{
		$rule = new Rule\Regex('firstname');
		$rule->execute($this->context, $this->response);
	}
}