<?php
namespace VXML\Rule;

class SilentTest extends TestCase 
{
	public function testValidValue()
	{
		$rule = new Silent(new Equal('firstname', array('equal' => 'Jørgen')));
		$this->assertTrue($rule->execute($this->context, $this->response));
		$this->assertEquals(2, count($this->response->getSuccessMessages()));
		$this->assertEquals(0, count($this->response->getDebugMessages()));
	}
	
	public function testInvalidValue()
	{
		$rule = new Silent(new Equal('firstname', array('equal' => 'test')));
		$this->assertTrue($rule->execute($this->context, $this->response));
		$this->assertEquals(0, count($this->response->getFailureMessages()));
		$this->assertEquals(1, count($this->response->getDebugMessages()));
	}
}