<?php
use VXML\Rule;

require_once 'TestCase.php';

class VXML_Rule_FailureTest extends VXML_Rule_TestCase 
{
	public function testFailure()
	{
		$rule = new Rule\Failure();
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
}