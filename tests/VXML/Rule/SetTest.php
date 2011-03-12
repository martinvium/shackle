<?php
use VXML\Rule;

class VXML_Rule_SetTest extends VXML_Rule_TestCase 
{
	public function testDefaultTargetIsRelative()
	{
		$set = new Rule\Set();
		$this->assertEquals(VXML\Context::RELATIVE, $set->getRelativeTarget());
	}
	
	public function testCustomTarget()
	{
		$set = new Rule\Set('firstname');
		$this->assertEquals('firstname', $set->getRelativeTarget());
	}
	
	public function testEmptyRuleset()
	{
		$set = new Rule\Set();
		$this->assertTrue($set->execute($this->context, $this->response));
	}
	
	public function testMinimumDefaultsToMax()
	{
		$set = new Rule\Set();
		$set->add(new Rule\Equal('firstname', array('equal' => 'Jørgen')));
		$set->add(new Rule\Equal('lastname', array('equal' => 'Fisk')));
		$this->assertFalse($set->execute($this->context, $this->response));
	}
	
	public function testMinimumOption()
	{
		$set = new Rule\Set(VXML\Context::RELATIVE, array('min' => 2));
		$set->add(new Rule\Equal('firstname', array('equal' => 'Jørgen')));
		$set->add(new Rule\Equal('lastname', array('equal' => 'Fisk')));
		$set->add(new Rule\Equal('email', array('equal' => 'jorgen@example.com')));
		$this->assertTrue($set->execute($this->context, $this->response));
	}
	
	public function testMaximumOption()
	{
		$set = new Rule\Set(VXML\Context::RELATIVE, array('max' => 1));
		$set->add(new Rule\Equal('firstname', array('equal' => 'Jørgen')));
		$set->add(new Rule\Equal('email', array('equal' => 'jorgen@example.com')));
		$this->assertFalse($set->execute($this->context, $this->response));
	}
	
	public function testNestedSets()
	{
		$set = new Rule\Set();
		$set->add(new Rule\Equal('firstname', array('equal' => 'Jørgen')));
		
		$nested = new Rule\Set();
		$nested->add(new Rule\Equal('email', array('equal' => 'jorgen@example.com')));
		$set->add($nested);
		
		$this->assertTrue($set->execute($this->context, $this->response));
	}
	
	public function testNestedSetsFailure()
	{
		$set = new Rule\Set();
		$set->add(new Rule\Equal('firstname', array('equal' => 'Jørgen')));
		
		$nested = new Rule\Set();
		$nested->add(new Rule\Equal('email', array('equal' => 'wrong@example.com')));
		$set->add($nested);
		
		$this->assertFalse($set->execute($this->context, $this->response));
	}
}