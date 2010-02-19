<?php
use VXML\Rule;

require_once 'TestCase.php';

class VXML_Rule_EqualTest extends VXML_Rule_TestCase 
{
	public function testEqual()
	{
		$rule = new Rule\Equal('firstname', array('equal' => 'Jørgen'));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testNotEqual()
	{
		$rule = new Rule\Equal('firstname', array('equal' => 'Jørgenx'));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testMissingEqualOptionError()
	{
		$rule = new Rule\Equal('firstname');
		$rule->execute($this->context, $this->response);
	}
}