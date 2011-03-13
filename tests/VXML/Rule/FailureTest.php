<?php
namespace VXML\Rule;

class FailureTest extends TestCase 
{
    public function testFailure()
    {
        $rule = new Failure();
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
}