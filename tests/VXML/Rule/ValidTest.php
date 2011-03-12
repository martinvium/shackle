<?php
namespace VXML\Rule;

class ValidTest extends TestCase 
{
	public function testValid()
	{
		$rule = new Valid();
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
}