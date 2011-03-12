<?php
namespace VXML\Rule;

use VXML\Event;

class CallbackTest extends TestCase
{
	/**
	 * @param Event $event
	 */
	public function callback($event)
	{
		 return ($event->getContext()->getPassedValue() == 'Jørgen');
	}
	
	public function testCallbackSuccess()
	{
		$rule = new Callback('firstname', array('callback' => array($this, 'callback')));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testCallbackFailure()
	{
		$rule = new Callback('lastname', array('callback' => array($this, 'callback')));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testPassingLambdaAsCallback()
	{
		$rule = new Callback('lastname', array('callback' => function() { return false; }));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testPassingRuleAsCallback()
	{
		$this->markTestSkipped();
		$cbRule = new Equal('salary', array('equal' => 25000));
		$rule = new Callback('salary', array('callback' => $cbRule));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testPassingInvalidCallback()
	{
		$rule = new Callback('salary', array('callback' => 123));
		$rule->execute($this->context, $this->response);
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testMissingCallbackOption()
	{
		$rule = new StringLength('firstname', array());
		$rule->execute($this->context, $this->response);
	}
}