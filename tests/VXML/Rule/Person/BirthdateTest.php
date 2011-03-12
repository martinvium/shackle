<?php
namespace VXML\Rule\Person;

use VXML\Rule\TestCase;

/**
 * @todo add support for single string target, maybe just check on 1 vs 3 targets?
 * 	 we probably should add an option for it, so we don't pass 1 target by accident.
 */
class BirthdateTest extends TestCase 
{
	public function testValidValue()
	{
		$targets = array('birthdate_year', 'birthdate_month', 'birthdate_day');
		$rule = new Birthdate($targets);
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testInvalidDay()
	{
		$targets = array('birthdate_year', 'birthdate_month', 'salary');
		$rule = new Birthdate($targets);
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testMissingTarget()
	{
		$targets = array('birthdate_year', 'birthdate_month');
		$rule = new Birthdate($targets);
		$rule->execute($this->context, $this->response);
	}
}