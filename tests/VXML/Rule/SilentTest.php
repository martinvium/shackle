<?php
use VXML\Rule;

require_once 'TestCase.php';

class VXML_Rule_SilentTest extends VXML_Rule_TestCase 
{
	public function testValidValue()
	{
		$rule = new Rule\Silent(new Rule\Equal('firstname', array('equal' => 'Jørgen')));
		$this->assertTrue($rule->execute($this->context, $this->response));
		$this->assertEquals(2, count($this->response->getSuccessMessages()));
		$this->assertEquals(0, count($this->response->getDebugMessages()));
	}
	
	public function testInvalidValue()
	{
		$rule = new Rule\Silent(new Rule\Equal('firstname', array('equal' => 'test')));
		$this->assertTrue($rule->execute($this->context, $this->response));
		$this->assertEquals(0, count($this->response->getFailureMessages()));
		$this->assertEquals(1, count($this->response->getDebugMessages()));
	}
}