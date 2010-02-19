<?php
use VXML\Rule;

/**
 * @todo this wont include relative, so i added this "hack"
 */
require_once 'Rule/TestCase.php';

/**
 * @todo add support for single string target, maybe just check on 1 vs 3 targets?
 * 	 we probably should add an option for it, so we don't pass 1 target by accident.
 */
class VXML_Rule_Person_BirthdateTest extends VXML_Rule_TestCase 
{
	public function testValidValue()
	{
		$targets = array('birthdate_year', 'birthdate_month', 'birthdate_day');
		$rule = new Rule\Person\Birthdate($targets);
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testInvalidDay()
	{
		$targets = array('birthdate_year', 'birthdate_month', 'salary');
		$rule = new Rule\Person\Birthdate($targets);
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testMissingTarget()
	{
		$targets = array('birthdate_year', 'birthdate_month');
		$rule = new Rule\Person\Birthdate($targets);
		$rule->execute($this->context, $this->response);
	}
}