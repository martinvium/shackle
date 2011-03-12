<?php
use VXML\Rule;

class VXML_Rule_ValidTest extends VXML_Rule_TestCase 
{
	public function testValid()
	{
		$rule = new Rule\Valid();
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
}