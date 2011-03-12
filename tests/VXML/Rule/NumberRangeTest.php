<?php
use VXML\Rule;

class VXML_Rule_NumberRangeTest extends VXML_Rule_TestCase 
{
	public function testInRange()
	{
		$rule = new Rule\NumberRange('salary', array('min' => 10000, 'max' => 40000));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testNumberTooHigh()
	{
		$rule = new Rule\NumberRange('salary', array('min' => 25001, 'max' => 40000));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testNumberTooLow()
	{
		$rule = new Rule\NumberRange('salary', array('min' => 10000, 'max' => 24999));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testOnlyMinimumOptionInRange()
	{
		$rule = new Rule\NumberRange('salary', array('min' => 10000));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testOnlyMinimumOptionOutOfRange()
	{
		$rule = new Rule\NumberRange('salary', array('min' => 40000));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testOnlyMaximumOptionInRange()
	{
		$rule = new Rule\NumberRange('salary', array('max' => 40000));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testOnlyMaximumOptionOutOfRange()
	{
		$rule = new Rule\NumberRange('salary', array('max' => 10000));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testMissingBothMinAndMaxOptions()
	{
		$rule = new Rule\NumberRange('salary', array());
		$rule->execute($this->context, $this->response);
	}
}