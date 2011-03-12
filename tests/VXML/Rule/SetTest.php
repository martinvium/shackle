<?php
namespace VXML\Rule;

use VXML\Context;

class SetTest extends TestCase 
{
	public function testDefaultTargetIsRelative()
	{
		$set = new Set();
		$this->assertEquals(Context::RELATIVE, $set->getRelativeTarget());
	}
	
	public function testCustomTarget()
	{
		$set = new Set('firstname');
		$this->assertEquals('firstname', $set->getRelativeTarget());
	}
	
	public function testEmptyRuleset()
	{
		$set = new Set();
		$this->assertTrue($set->execute($this->context, $this->response));
	}
	
	public function testMinimumDefaultsToMax()
	{
		$set = new Set();
		$set->add(new Equal('firstname', array('equal' => 'Jørgen')));
		$set->add(new Equal('lastname', array('equal' => 'Fisk')));
		$this->assertFalse($set->execute($this->context, $this->response));
	}
	
	public function testMinimumOption()
	{
		$set = new Set(Context::RELATIVE, array('min' => 2));
		$set->add(new Equal('firstname', array('equal' => 'Jørgen')));
		$set->add(new Equal('lastname', array('equal' => 'Fisk')));
		$set->add(new Equal('email', array('equal' => 'jorgen@example.com')));
		$this->assertTrue($set->execute($this->context, $this->response));
	}
	
	public function testMaximumOption()
	{
		$set = new Set(Context::RELATIVE, array('max' => 1));
		$set->add(new Equal('firstname', array('equal' => 'Jørgen')));
		$set->add(new Equal('email', array('equal' => 'jorgen@example.com')));
		$this->assertFalse($set->execute($this->context, $this->response));
	}
	
	public function testNestedSets()
	{
		$set = new Set();
		$set->add(new Equal('firstname', array('equal' => 'Jørgen')));
		
		$nested = new Set();
		$nested->add(new Equal('email', array('equal' => 'jorgen@example.com')));
		$set->add($nested);
		
		$this->assertTrue($set->execute($this->context, $this->response));
	}
	
	public function testNestedSetsFailure()
	{
		$set = new Set();
		$set->add(new Equal('firstname', array('equal' => 'Jørgen')));
		
		$nested = new Set();
		$nested->add(new Equal('email', array('equal' => 'wrong@example.com')));
		$set->add($nested);
		
		$this->assertFalse($set->execute($this->context, $this->response));
	}
}