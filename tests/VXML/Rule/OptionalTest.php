<?php
use VXML\Rule;

require_once 'TestCase.php';

class VXML_Rule_OptionalTest extends VXML_Rule_TestCase 
{
	public function testEmptyValue()
	{
		$rule = new Rule\Optional(new Rule\Equal('empty', array('equal' => 'test')));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testNonEmptyInvalidValue()
	{
		$rule = new Rule\Optional(new Rule\Equal('firstname', array('equal' => 'test')));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testNonEmptyValidValue()
	{
		$rule = new Rule\Optional(new Rule\Equal('firstname', array('equal' => 'Jørgen')));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
}