<?php
use VXML\Rule;

class VXML_Rule_InvertTest extends VXML_Rule_TestCase 
{
	public function testValidValue()
	{
		$rule = new Rule\Invert(new Rule\Equal('firstname', array('equal' => 'Jørgen')));
		$this->assertFalse($rule->execute($this->context, $this->response));
		$this->assertEquals(0, count($this->response->getSuccessMessages()));
		$this->assertEquals(1, count($this->response->getFailureMessages()));
	}
	
	public function testInvalidValue()
	{
		$rule = new Rule\Invert(new Rule\Equal('firstname', array('equal' => 'test')));
		$this->assertTrue($rule->execute($this->context, $this->response));
		$this->assertEquals(1, count($this->response->getSuccessMessages()));
		$this->assertEquals(0, count($this->response->getFailureMessages()));
	}
}