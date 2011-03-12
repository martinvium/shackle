<?php
namespace VXML\Rule;

class InvertTest extends TestCase 
{
	public function testValidValue()
	{
		$rule = new Invert(new Equal('firstname', array('equal' => 'Jørgen')));
		$this->assertFalse($rule->execute($this->context, $this->response));
		$this->assertEquals(0, count($this->response->getSuccessMessages()));
		$this->assertEquals(1, count($this->response->getFailureMessages()));
	}
	
	public function testInvalidValue()
	{
		$rule = new Invert(new Equal('firstname', array('equal' => 'test')));
		$this->assertTrue($rule->execute($this->context, $this->response));
		$this->assertEquals(1, count($this->response->getSuccessMessages()));
		$this->assertEquals(0, count($this->response->getFailureMessages()));
	}
}