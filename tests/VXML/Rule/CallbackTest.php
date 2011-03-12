<?php
use VXML\Rule;

class VXML_Rule_CallbackTest extends VXML_Rule_TestCase 
{
	/**
	 * @param VXML\Event $event
	 */
	public function callback($event)
	{
		 return ($event->getContext()->getPassedValue() == 'Jørgen');
	}
	
	public function testCallbackSuccess()
	{
		$rule = new Rule\Callback('firstname', array('callback' => array($this, 'callback')));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testCallbackFailure()
	{
		$rule = new Rule\Callback('lastname', array('callback' => array($this, 'callback')));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testPassingLambdaAsCallback()
	{
		$rule = new Rule\Callback('lastname', array('callback' => function() { return false; }));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testPassingRuleAsCallback()
	{
		$this->markTestSkipped();
		$cbRule = new Rule\Equal('salary', array('equal' => 25000));
		$rule = new Rule\Callback('salary', array('callback' => $cbRule));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testPassingInvalidCallback()
	{
		$rule = new Rule\Callback('salary', array('callback' => 123));
		$rule->execute($this->context, $this->response);
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testMissingCallbackOption()
	{
		$rule = new Rule\StringLength('firstname', array());
		$rule->execute($this->context, $this->response);
	}
}