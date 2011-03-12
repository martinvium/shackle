<?php
namespace VXML\Rule;

class OptionalTest extends TestCase 
{
	public function testEmptyValue()
	{
		$rule = new Optional(new Equal('empty', array('equal' => 'test')));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testNonEmptyInvalidValue()
	{
		$rule = new Optional(new Equal('firstname', array('equal' => 'test')));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testNonEmptyValidValue()
	{
		$rule = new Optional(new Equal('firstname', array('equal' => 'Jørgen')));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
}