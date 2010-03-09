<?php
use VXML\Rule;

require_once 'TestCase.php';

class VXML_Rule_ListenerTest extends VXML_Rule_TestCase 
{
	public function testValidEvent()
	{
		$rule = new Rule\Valid();
		$rule->addListener('valid', new Rule\Failure());
		$this->assertTrue($rule->execute($this->context, $this->response));
		$this->assertEquals(1, count($this->response->getFailureMessages()));
	}
	
	public function testFailureEvent()
	{
		$rule = new Rule\Failure();
		$rule->addListener('failure', new Rule\Valid());
		$this->assertFalse($rule->execute($this->context, $this->response));
		$this->assertEquals(1, count($this->response->getFailureMessages()));
		$this->assertEquals(1, count($this->response->getSuccessMessages()));
	}
	
	public function testBeforeEvent()
	{
		$rule = new Rule\Failure();
		$rule->addListener('before', new Rule\Valid());
		$this->assertFalse($rule->execute($this->context, $this->response));
		$this->assertEquals(1, count($this->response->getFailureMessages()));
		$this->assertEquals(1, count($this->response->getSuccessMessages()));
	}
	
	public function testAfterEvent()
	{
		$rule = new Rule\Failure();
		$rule->addListener('after', new Rule\Valid());
		$this->assertFalse($rule->execute($this->context, $this->response));
		$this->assertEquals(1, count($this->response->getFailureMessages()));
		$this->assertEquals(1, count($this->response->getSuccessMessages()));
	}
}