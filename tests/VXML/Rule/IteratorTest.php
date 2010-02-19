<?php
use VXML\Rule;

require_once 'TestCase.php';

class VXML_Rule_IteratorTest extends VXML_Rule_TestCase 
{
	public function testIteratableTarget()
	{
		$rule = new Rule\Iterator(new Rule\Regex('friends', array('pattern' => '/@/')));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testIterableTargetsFailure()
	{
		$rule = new Rule\Iterator(new Rule\Regex('blocked', array('pattern' => '/@/')));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSingleTarget()
	{
		$rule = new Rule\Iterator(new Rule\Regex('email', array('pattern' => '/@/')));
		$rule->execute($this->context, $this->response);
	}
	
	public function testMinimumOptionSuccess()
	{
		$rule = new Rule\Iterator(new Rule\Regex('blocked', array('pattern' => '/@/')), array('min' => 1));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testMinimumOptionTooFew()
	{
		$rule = new Rule\Iterator(new Rule\Regex('blocked', array('pattern' => '/@/')), array('min' => 2));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testMaximumOptionSuccess()
	{
		$rule = new Rule\Iterator(new Rule\Regex('blocked', array('pattern' => '/@/')), array('min' => 1, 'max' => 1));
//		$this->assertEquals(array(), array_map(function ($msg) {return $msg['type']; }, $this->response->getAllMessages()));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testMaximumOptionFailure()
	{
		$rule = new Rule\Iterator(new Rule\Regex('friends', array('pattern' => '/@/')), array('max' => 1));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
}